<?php

declare(strict_types=1);

namespace BusinessApi\AuIdentifiers;

// Format only. The check digit method is only given to ATO-registered software providers.
final class DirectorId
{
    public static function isValid(mixed $value): bool
    {
        return self::normalise($value) !== null;
    }

    public static function normalise(mixed $value): ?string
    {
        $id = Digits::of($value, [15]);

        return $id !== null && str_starts_with($id, '036') ? $id : null;
    }
}
