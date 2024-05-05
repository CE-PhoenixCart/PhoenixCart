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
 * Replacement for PHP's native {@link IntlDateFormatter} class.
 *
 * The only methods currently supported in this class are:
 *
 *  - {@link __construct}
 *  - {@link create}
 *  - {@link format}
 *  - {@link getCalendar}
 *  - {@link getDateType}
 *  - {@link getErrorCode}
 *  - {@link getErrorMessage}
 *  - {@link getLocale}
 *  - {@link getPattern}
 *  - {@link getTimeType}
 *  - {@link getTimeZoneId}
 *  - {@link isLenient}
 *  - {@link parse}
 *  - {@link setLenient}
 *  - {@link setPattern}
 *  - {@link setTimeZoneId}
 *  - {@link setTimeZone}
 *
 * @author Igor Wiedler <igor@wiedler.ch>
 * @author Bernhard Schussek <bschussek@gmail.com>
 *
 * @internal
 */
class IntlDateFormatter {

   /**
     * The error code from the last operation.
     *
     * @var int
     */
    protected $errorCode = IntlGlobals::U_ZERO_ERROR;

    /**
     * The error message from the last operation.
     *
     * @var string
     */
    protected $errorMessage = 'U_ZERO_ERROR';

    /* date/time format types */
    public const NONE = -1;
    public const FULL = 0;
    public const LONG = 1;
    public const MEDIUM = 2;
    public const SHORT = 3;

    /* calendar formats */
    public const TRADITIONAL = 0;
    public const GREGORIAN = 1;

    /**
     * Patterns used to format the date when no pattern is provided.
     */
    private $defaultDateFormats = [
        self::NONE => '',
        self::FULL => 'EEEE, MMMM d, y',
        self::LONG => 'MMMM d, y',
        self::MEDIUM => 'MMM d, y',
        self::SHORT => 'M/d/yy',
    ];

    /**
     * Patterns used to format the time when no pattern is provided.
     */
    private $defaultTimeFormats = [
        self::FULL => 'h:mm:ss a zzzz',
        self::LONG => 'h:mm:ss a z',
        self::MEDIUM => 'h:mm:ss a',
        self::SHORT => 'h:mm a',
    ];

    private $datetype;
    private $timetype;

    /**
     * @var string
     */
    private $pattern;

    /**
     * @var DateTimeZone
     */
    private $dateTimeZone;

    /**
     * @var bool
     */
    private $uninitializedTimeZoneId = false;

    /**
     * @var string
     */
    private $timeZoneId;

    /**
     * @param string|null                             $locale   The locale code. The only currently supported locale is "en" (or null using the default locale, i.e. "en")
     * @param int|null                                $datetype Type of date formatting, one of the format type constants
     * @param int|null                                $timetype Type of time formatting, one of the format type constants
     * @param IntlTimeZone|DateTimeZone|string|null $timezone Timezone identifier
     * @param int|null                                $calendar Calendar to use for formatting or parsing. The only currently
     *                                                          supported value is IntlDateFormatter::GREGORIAN (or null using the default calendar, i.e. "GREGORIAN")
     * @param string|null                             $pattern  Optional pattern to use when formatting
     *
     * @see https://php.net/intldateformatter.create
     * @see http://userguide.icu-project.org/formatparse/datetime
     */
    public function __construct(string $locale = null, int $datetype = null, int $timetype = null, $timezone = null, int $calendar = null, string $pattern = null) {
        if ('en' !== $locale && null !== $locale) {
            trigger_error("The [$locale] locale will be treated as en", E_USER_WARNING);
        }

        if (self::GREGORIAN !== $calendar && null !== $calendar) {
            trigger_error("The [$calendar] calendar will be treated as Gregorian", E_USER_WARNING);
        }

        $this->datetype = $datetype ?? self::FULL;
        $this->timetype = $timetype ?? self::FULL;

        if ('' === ($pattern ?? '')) {
            $pattern = $this->getDefaultPattern();
        }

        $this->setPattern($pattern);
        $this->setTimeZone($timezone);
    }

    /**
     * Static constructor.
     *
     * @param string|null                             $locale   The locale code. The only currently supported locale is "en" (or null using the default locale, i.e. "en")
     * @param int|null                                $datetype Type of date formatting, one of the format type constants
     * @param int|null                                $timetype Type of time formatting, one of the format type constants
     * @param IntlTimeZone|DateTimeZone|string|null $timezone Timezone identifier
     * @param int                                     $calendar Calendar to use for formatting or parsing; default is Gregorian
     *                                                          One of the calendar constants
     * @param string|null                             $pattern  Optional pattern to use when formatting
     *
     * @return static
     *
     * @see https://php.net/intldateformatter.create
     * @see http://userguide.icu-project.org/formatparse/datetime
     */
    public static function create(string $locale = null, int $datetype = null, int $timetype = null, $timezone = null, int $calendar = self::GREGORIAN, string $pattern = null) {
        return new static($locale, $datetype, $timetype, $timezone, $calendar, $pattern);
    }

    /**
     * Format the date/time value (timestamp) as a string.
     *
     * @param int|string|DateTimeInterface $timestamp The timestamp to format
     *
     * @return string|bool The formatted value or false if formatting failed
     *
     * @see https://php.net/intldateformatter.format
     *
     * @throws InvalidArgumentException If one of the formatting characters is not implemented
     */
    public function format($timestamp) {
        // intl allows timestamps to be passed as arrays - we don't
        if (is_array($timestamp)) {
            $message = ' Only Unix timestamps and DateTime objects are supported';

            throw new InvalidArgumentException(__METHOD__ . $message);
        }

        if (is_string($timestamp) && $dt = DateTime::createFromFormat('U', $timestamp)) {
            $timestamp = $dt;
        }

        // behave like the intl extension
        $argumentError = null;
        if (!is_int($timestamp) && !$timestamp instanceof DateTimeInterface) {
            $argumentError = sprintf("datefmt_format: string '%s' is not numeric, which would be required for it to be a valid date", $timestamp);
        }

        if (null !== $argumentError) {
            IntlGlobals::setError(IntlGlobals::U_ILLEGAL_ARGUMENT_ERROR, $argumentError);
            $this->errorCode = IntlGlobals::getErrorCode();
            $this->errorMessage = IntlGlobals::getErrorMessage();

            return false;
        }

        if ($timestamp instanceof DateTimeInterface) {
            $timestamp = $timestamp->format('U');
        }

        $transformer = new FullTransformer($this->getPattern(), $this->getTimeZoneId());
        $formatted = $transformer->format($this->createDateTime($timestamp));

        // behave like the intl extension
        IntlGlobals::setError(IntlGlobals::U_ZERO_ERROR);
        $this->errorCode = IntlGlobals::getErrorCode();
        $this->errorMessage = IntlGlobals::getErrorMessage();

        return $formatted;
    }

    /**
     * Returns the formatter's calendar.
     *
     * @return int The calendar being used by the formatter. Currently always returns
     *             IntlDateFormatter::GREGORIAN.
     *
     * @see https://php.net/intldateformatter.getcalendar
     */
    public function getCalendar() {
        return self::GREGORIAN;
    }

    /**
     * Returns the formatter's datetype.
     *
     * @return int The current value of the formatter
     *
     * @see https://php.net/intldateformatter.getdatetype
     */
    public function getDateType() {
        return $this->datetype;
    }

    /**
     * Returns formatter's last error code. Always returns the U_ZERO_ERROR class constant value.
     *
     * @return int The error code from last formatter call
     *
     * @see https://php.net/intldateformatter.geterrorcode
     */
    public function getErrorCode() {
        return $this->errorCode;
    }

    /**
     * Returns formatter's last error message. Always returns the U_ZERO_ERROR_MESSAGE class constant value.
     *
     * @return string The error message from last formatter call
     *
     * @see https://php.net/intldateformatter.geterrormessage
     */
    public function getErrorMessage() {
        return $this->errorMessage;
    }

    /**
     * Returns the formatter's locale.
     *
     * @param int $type Not supported. The locale name type to return (Locale::VALID_LOCALE or Locale::ACTUAL_LOCALE)
     *
     * @return string The locale used to create the formatter. Currently always
     *                returns "en".
     *
     * @see https://php.net/intldateformatter.getlocale
     */
    public function getLocale(int $ignored = null) {
        return 'en';
    }

    /**
     * Returns the formatter's pattern.
     *
     * @return string The pattern string used by the formatter
     *
     * @see https://php.net/intldateformatter.getpattern
     */
    public function getPattern() {
        return $this->pattern;
    }

    /**
     * Returns the formatter's time type.
     *
     * @return int The time type used by the formatter
     *
     * @see https://php.net/intldateformatter.gettimetype
     */
    public function getTimeType() {
        return $this->timetype;
    }

    /**
     * Returns the formatter's timezone identifier.
     *
     * @return string The timezone identifier used by the formatter
     *
     * @see https://php.net/intldateformatter.gettimezoneid
     */
    public function getTimeZoneId() {
        return $this->uninitializedTimeZoneId
             ? date_default_timezone_get()
             : $this->timeZoneId;
    }

    /**
     * Returns whether the formatter is lenient.
     *
     * @return bool Currently always returns false
     *
     * @see https://php.net/intldateformatter.islenient
     */
    public function isLenient() {
        return false;
    }

    /**
     * Parse string to a timestamp value.
     *
     * @param string   $value    String to convert to a time value
     * @param int|null $position Not supported. Position at which to start the parsing in $value (zero-based)
     *                           If no error occurs before $value is consumed, $parse_pos will
     *                           contain -1 otherwise it will contain the position at which parsing
     *                           ended. If $parse_pos > strlen($value), the parse fails immediately.
     *
     * @return int|false Parsed value as a timestamp
     *
     * @see https://php.net/intldateformatter.parse
     *
     * @throws InvalidArgumentException When $position different than null, behavior not implemented
     */
    public function parse(string $value, int &$position = null) {
        // We don't calculate the position when parsing the value
        if (null !== $position) {
            throw new InvalidArgumentException(__METHOD__ . ' position');
        }

        $dateTime = $this->createDateTime(0);
        $transformer = new FullTransformer($this->getPattern(), $this->getTimeZoneId());

        $timestamp = $transformer->parse($dateTime, $value);

        // behave like the intl extension. FullTransformer::parse() set the proper error
        $this->errorCode = IntlGlobals::getErrorCode();
        $this->errorMessage = IntlGlobals::getErrorMessage();

        return $timestamp;
    }

    /**
     * Set the leniency of the parser.
     *
     * Define if the parser is strict or lenient in interpreting inputs that do not match the pattern
     * exactly. Enabling lenient parsing allows the parser to accept otherwise flawed date or time
     * patterns, parsing as much as possible to obtain a value. Extra space, unrecognized tokens, or
     * invalid values ("February 30th") are not accepted.
     *
     * @param bool $lenient Sets whether the parser is lenient or not. Currently
     *                      only false (strict) is supported.
     *
     * @return bool true on success or false on failure
     *
     * @see https://php.net/intldateformatter.setlenient
     *
     * @throws InvalidArgumentException When $lenient is true
     */
    public function setLenient(bool $lenient) {
        if ($lenient) {
            throw new InvalidArgumentException('Only the strict parser is supported');
        }

        return true;
    }

    /**
     * Set the formatter's pattern.
     *
     * @param string|null $pattern A pattern string in conformance with the ICU IntlDateFormatter documentation
     *
     * @return bool true on success or false on failure
     *
     * @see https://php.net/intldateformatter.setpattern
     * @see http://userguide.icu-project.org/formatparse/datetime
     */
    public function setPattern(string $pattern = null){
        $this->pattern = (string) $pattern;

        return true;
    }

    /**
     * Set the formatter's timezone identifier.
     *
     * @param string|null $timeZoneId The time zone ID string of the time zone to use.
     *                                If NULL or the empty string, the default time zone for the
     *                                runtime is used.
     *
     * @return bool true on success or false on failure
     *
     * @see https://php.net/intldateformatter.settimezoneid
     */
    public function setTimeZoneId(string $timeZoneId = null) {
        if (null === $timeZoneId) {
            $timeZoneId = date_default_timezone_get();

            $this->uninitializedTimeZoneId = true;
        }

        // Backup original passed time zone
        $timeZone = $timeZoneId;

        // Get an Etc/GMT time zone that is accepted for DateTimeZone
        if ('GMT' !== $timeZoneId && Text::is_prefixed_by($timeZoneId, 'GMT')) {
            try {
                $timeZoneId = DateFormat\TimezoneTransformer::getEtcTimeZoneId($timeZoneId);
            } catch (InvalidArgumentException $e) {
                // Does nothing, will fallback to UTC
            }
        }

        try {
            $this->dateTimeZone = new DateTimeZone($timeZoneId);
            if ('GMT' !== $timeZoneId && $this->dateTimeZone->getName() !== $timeZoneId) {
                $timeZone = $this->getTimeZoneId();
            }
        } catch (Exception $e) {
            $timeZoneId = $timeZone = $this->getTimeZoneId();
            $this->dateTimeZone = new DateTimeZone($timeZoneId);
        }

        $this->timeZoneId = $timeZone;

        return true;
    }

    /**
     * This method was added in PHP 5.5 as replacement for `setTimeZoneId()`.
     *
     * @param IntlTimeZone|DateTimeZone|string|null $timeZone
     *
     * @return bool true on success or false on failure
     *
     * @see https://php.net/intldateformatter.settimezone
     */
    public function setTimeZone($timeZone) {
        if ($timeZone instanceof IntlTimeZone) {
            $timeZone = $timeZone->getID();
        }

        if ($timeZone instanceof DateTimeZone) {
            $timeZone = $timeZone->getName();

            // DateTimeZone returns the GMT offset timezones without the leading GMT, while our parsing requires it.
            if (!empty($timeZone) && ('+' === $timeZone[0] || '-' === $timeZone[0])) {
                $timeZone = 'GMT'.$timeZone;
            }
        }

        return $this->setTimeZoneId($timeZone);
    }

    /**
     * Create and returns a DateTime object with the specified timestamp and with the
     * current time zone.
     *
     * @return DateTime
     */
    protected function createDateTime(string $timestamp) {
        $dateTime = DateTime::createFromFormat('U', $timestamp);
        $dateTime->setTimezone($this->dateTimeZone);

        return $dateTime;
    }

    /**
     * Returns a pattern string based in the datetype and timetype values.
     *
     * @return string
     */
    protected function getDefaultPattern() {
        $pattern = '';
        if (self::NONE !== $this->datetype) {
            $pattern = $this->defaultDateFormats[$this->datetype];
        }
        if (self::NONE !== $this->timetype) {
            if (self::FULL === $this->datetype || self::LONG === $this->datetype) {
                $pattern .= " 'at' ";
            } elseif (self::NONE !== $this->datetype) {
                $pattern .= ', ';
            }
            $pattern .= $this->defaultTimeFormats[$this->timetype];
        }

        return $pattern;
    }
}
