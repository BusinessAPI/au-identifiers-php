<?php

declare(strict_types=1);

namespace BusinessApi\AuIdentifiers;

final class Digits
{
    public static function of(mixed $value, array $lengths): ?string
    {
        if (is_int($value) && $value >= 0) {
            $value = (string) $value;
        }
        if (!is_string($value)) {
            return null;
        }

        $digits = preg_replace('/[ \t\n\r\v\f-]/', '', $value);

        return in_array(strlen($digits), $lengths, true) && ctype_digit($digits) ? $digits : null;
    }
}
