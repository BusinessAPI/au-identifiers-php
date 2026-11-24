<?php

declare(strict_types=1);

namespace BusinessApi\AuIdentifiers;

// 8-digit TFNs (organisations only) have their own weights. A 9-digit TFN with a leading
// zero passes on either the 9-digit check or the 8-digit check of the rest.
final class Tfn
{
    private const WEIGHTS_9 = [1, 4, 3, 7, 5, 8, 6, 9, 10];
    private const WEIGHTS_8 = [1, 4, 3, 7, 5, 8, 6, 10];

    public static function isValid(mixed $value, bool $individual = false): bool
    {
        return self::normalise($value, $individual) !== null;
    }

    public static function normalise(mixed $value, bool $individual = false): ?string
    {
        $tfn = Digits::of($value, $individual ? [9] : [8, 9]);
        if ($tfn === null) {
            return null;
        }

        if (strlen($tfn) === 9 && strlen(ltrim($tfn, '0')) >= 8 && self::sum($tfn, self::WEIGHTS_9) % 11 === 0) {
            return $tfn;
        }

        $core = ltrim($tfn, '0');
        $weights = match (strlen($core)) {
            9 => self::WEIGHTS_9,
            8 => self::WEIGHTS_8,
            default => null,
        };

        return $weights !== null && self::sum($core, $weights) % 11 === 0 ? $tfn : null;
    }

    private static function sum(string $digits, array $weights): int
    {
        $sum = 0;
        foreach ($weights as $i => $weight) {
            $sum += (int) $digits[$i] * $weight;
        }

        return $sum;
    }
}
