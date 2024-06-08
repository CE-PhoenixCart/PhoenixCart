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
 * Parser and formatter for quarter format.
 *
 * @author Igor Wiedler <igor@wiedler.ch>
 *
 * @internal
 */
class QuarterTransformer extends Transformer {

    /**
     * {@inheritdoc}
     */
    public function format(\DateTime $dateTime, int $length): string {
        $month = (int) $dateTime->format('n');
        $quarter = (int) floor(($month - 1) / 3) + 1;
        switch ($length) {
            case 1:
            case 2:
                return $this->padLeft($quarter, $length);
            case 3:
                return 'Q'.$quarter;
            case 4:
                $map = [1 => '1st quarter', 2 => '2nd quarter', 3 => '3rd quarter', 4 => '4th quarter'];

                return $map[$quarter];
            default:
                if (\defined('INTL_ICU_VERSION') && version_compare(\INTL_ICU_VERSION, '70.1', '<')) {
                    $map = [1 => '1st quarter', 2 => '2nd quarter', 3 => '3rd quarter', 4 => '4th quarter'];

                    return $map[$quarter];
                } else {
                    return $quarter;
                }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getReverseMatchingRegExp(int $length): string {
        switch ($length) {
            case 1:
            case 2:
                return '\d{'.$length.'}';
            case 3:
                return 'Q\d';
            default:
                return '(?:1st|2nd|3rd|4th) quarter';
        }
    }

}
