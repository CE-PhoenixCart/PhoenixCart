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
 * Parser and formatter for month format.
 *
 * @author Igor Wiedler <igor@wiedler.ch>
 *
 * @internal
 */
class MonthTransformer extends Transformer {

  protected static $months = [
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December',
    ];

    /**
     * Short months names (first 3 letters).
     */
    protected static $shortMonths = [];

    /**
     * Flipped $months array, $name => $index.
     */
    protected static $flippedMonths = [];

    /**
     * Flipped $shortMonths array, $name => $index.
     */
    protected static $flippedShortMonths = [];

    public function __construct() {
        if (0 === \count(self::$shortMonths)) {
            self::$shortMonths = array_map(function ($month) {
                return substr($month, 0, 3);
            }, self::$months);

            self::$flippedMonths = array_flip(self::$months);
            self::$flippedShortMonths = array_flip(self::$shortMonths);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function format(\DateTime $dateTime, int $length): string {
        $matchLengthMap = [
            1 => 'n',
            2 => 'm',
            3 => 'M',
            4 => 'F',
        ];

        if (isset($matchLengthMap[$length])) {
            return $dateTime->format($matchLengthMap[$length]);
        }

        if (5 === $length) {
            return substr($dateTime->format('M'), 0, 1);
        }

        return $this->padLeft($dateTime->format('m'), $length);
    }

    /**
     * {@inheritdoc}
     */
    public function getReverseMatchingRegExp(int $length): string {
        switch ($length) {
            case 1:
                $regExp = '\d{1,2}';
                break;
            case 3:
                $regExp = implode('|', self::$shortMonths);
                break;
            case 4:
                $regExp = implode('|', self::$months);
                break;
            case 5:
                $regExp = '[JFMASOND]';
                break;
            default:
                $regExp = '\d{1,'.$length.'}';
                break;
        }

        return $regExp;
    }

    /**
     * {@inheritdoc}
     */
    public function extractDateOptions(string $matched, int $length): array {
        if (is_numeric($matched)) {
            $matched = (int) $matched;
        } elseif (3 === $length) {
            $matched = self::$flippedShortMonths[$matched] + 1;
        } elseif (4 === $length) {
            $matched = self::$flippedMonths[$matched] + 1;
        } elseif (5 === $length) {
            // IntlDateFormatter::parse() always returns false for MMMMM or LLLLL
            $matched = false;
        }

        return [
            'month' => $matched,
        ];
    }
}
