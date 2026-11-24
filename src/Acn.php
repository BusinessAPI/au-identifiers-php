<?php

declare(strict_types=1);

namespace BusinessApi\AuIdentifiers;

final class Acn
{
    private const WEIGHTS = [8, 7, 6, 5, 4, 3, 2, 1];

    public static function isValid(mixed $value): bool
    {
        return self::normalise($value) !== null;
    }

    public static function normalise(mixed $value): ?string
    {
        $acn = Digits::of($value, [9]);
        if ($acn === null) {
            return null;
        }

        $sum = 0;
        foreach (self::WEIGHTS as $i => $weight) {
            $sum += (int) $acn[$i] * $weight;
        }

        return (int) $acn[8] === (10 - $sum % 10) % 10 ? $acn : null;
    }

    public static function format(mixed $value): ?string
    {
        $acn = self::normalise($value);

        return $acn === null ? null : substr($acn, 0, 3).' '.substr($acn, 3, 3).' '.substr($acn, 6);
    }
}
