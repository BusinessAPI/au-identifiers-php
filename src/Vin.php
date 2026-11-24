<?php

declare(strict_types=1);

namespace BusinessApi\AuIdentifiers;

final class Vin
{
    public static function isValid(mixed $value): bool
    {
        return self::normalise($value) !== null;
    }

    public static function normalise(mixed $value): ?string
    {
        if (!is_string($value)) {
            return null;
        }

        $vin = strtoupper(trim($value, " \t\n\r\v\f"));

        return preg_match('/^[A-HJ-NPR-Z0-9]{17}$/', $vin) === 1 ? $vin : null;
    }
}
