<?php

declare(strict_types=1);

namespace BusinessApi\AuIdentifiers;

final class Abn
{
    private const WEIGHTS = [10, 1, 3, 5, 7, 9, 11, 13, 15, 17, 19];

    public static function isValid(mixed $value): bool
    {
        return self::normalise($value) !== null;
    }

    public static function normalise(mixed $value): ?string
    {
        $abn = Digits::of($value, [11]);
        if ($abn === null) {
            return null;
        }

        // Subtract 1 from the first digit, then the weighted sum must be divisible by 89.
        $sum = ((int) $abn[0] - 1) * self::WEIGHTS[0];
        for ($i = 1; $i < 11; $i++) {
            $sum += (int) $abn[$i] * self::WEIGHTS[$i];
        }

        return $sum % 89 === 0 ? $abn : null;
    }

    public static function format(mixed $value): ?string
    {
        $abn = self::normalise($value);

        return $abn === null ? null
            : substr($abn, 0, 2).' '.substr($abn, 2, 3).' '.substr($abn, 5, 3).' '.substr($abn, 8);
    }
}
