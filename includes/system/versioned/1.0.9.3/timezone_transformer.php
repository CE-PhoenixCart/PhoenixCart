<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please read:
 *
 * Copyright (c) 2004-present Fabien Potencier

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is furnished
to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
 */

/**
 * Parser and formatter for time zone format.
 *
 * @author Igor Wiedler <igor@wiedler.ch>
 *
 * @internal
 */
class TimezoneTransformer extends Transformer {

    /**
     * {@inheritdoc}
     *
     * @throws DomainException When time zone is different than UTC or GMT (Etc/GMT)
     */
    public function format(DateTime $dateTime, int $length): string {
        $timeZone = substr($dateTime->getTimezone()->getName(), 0, 3);

        if (!in_array($timeZone, ['Etc', 'UTC', 'GMT'])) {
            throw new DomainException('Time zone different than GMT or UTC is not supported as a formatting output.');
        }

        if ('Etc' === $timeZone) {
            // i.e. Etc/GMT+1, Etc/UTC, Etc/Zulu
            $timeZone = substr($dateTime->getTimezone()->getName(), 4);
        }

        // From ICU >= 59.1 GMT and UTC are no longer unified
        if (in_array($timeZone, ['UTC', 'UCT', 'Universal', 'Zulu'])) {
            // offset is not supported with UTC
            return $length > 3 ? 'Coordinated Universal Time' : 'UTC';
        }

        $offset = (int) $dateTime->format('O');

        // From ICU >= 4.8, the zero offset is no more used, example: GMT instead of GMT+00:00
        if (0 === $offset) {
            return $length > 3 ? 'Greenwich Mean Time' : 'GMT';
        }

        if ($length > 3) {
            return $dateTime->format('\G\M\TP');
        }

        return sprintf('GMT%s%d', $offset >= 0 ? '+' : '', $offset / 100);
    }

    /**
     * {@inheritdoc}
     */
    public function getReverseMatchingRegExp(int $length): string {
        return 'GMT[+-]\d{2}:?\d{2}';
    }

    /**
     * {@inheritdoc}
     */
    public function extractDateOptions(string $matched, int $length): array {
        return [
            'timezone' => self::getEtcTimeZoneId($matched),
        ];
    }

    /**
     * Get an Etc/GMT timezone identifier for the specified timezone.
     *
     * The PHP documentation for timezones states to not use the 'Other' time zones because them exists
     * "for backwards compatibility". However all Etc/GMT time zones are in the tz database 'etcetera' file,
     * which indicates they are not deprecated (neither are old names).
     *
     * Only GMT, Etc/Universal, Etc/Zulu, Etc/Greenwich, Etc/GMT-0, Etc/GMT+0 and Etc/GMT0 are old names and
     * are linked to Etc/GMT or Etc/UTC.
     *
     * @param string $formattedTimeZone A GMT timezone string (GMT-03:00, e.g.)
     *
     * @return string A timezone identifier
     *
     * @see https://php.net/timezones.others
     *
     * @throws DomainException   When the GMT time zone have minutes offset different than zero
     * @throws InvalidArgumentException When the value cannot be matched with pattern
     */
    public static function getEtcTimeZoneId(string $formattedTimeZone): string {
        if (preg_match('/GMT(?P<signal>[+-])(?P<hours>\d{2}):?(?P<minutes>\d{2})/', $formattedTimeZone, $matches)) {
            $hours = (int) $matches['hours'];
            $minutes = (int) $matches['minutes'];
            $signal = '-' === $matches['signal'] ? '+' : '-';

            if (0 < $minutes) {
                throw new DomainException(sprintf('It is not possible to use a GMT time zone with minutes offset different than zero (0). GMT time zone tried: "%s".', $formattedTimeZone));
            }

            return 'Etc/GMT'.(0 !== $hours ? $signal.$hours : '');
        }

        throw new InvalidArgumentException(sprintf('The GMT time zone "%s" does not match with the supported formats GMT[+-]HH:MM or GMT[+-]HHMM.', $formattedTimeZone));
    }

}
