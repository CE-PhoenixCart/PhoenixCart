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
 * Base class for hour transformers.
 *
 * @author Eriksen Costa <eriksen.costa@infranology.com.br>
 *
 * @internal
 */
abstract class HourTransformer extends Transformer {

    /**
     * Returns a normalized hour value suitable for the hour transformer type.
     *
     * @param int         $hour   The hour value
     * @param string|null $marker An optional AM/PM marker
     *
     * @return int The normalized hour value
     */
    abstract public function normalizeHour(int $hour, ?string $marker = null): int;

    /**
     * {@inheritdoc}
     */
    public function extractDateOptions(string $matched, int $length): array {
        return [
            'hour' => (int) $matched,
            'hourInstance' => $this,
        ];
    }

}
