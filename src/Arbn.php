<?php

declare(strict_types=1);

namespace BusinessApi\AuIdentifiers;

final class Arbn
{
    public static function isValid(mixed $value): bool
    {
        return Acn::isValid($value);
    }

    public static function normalise(mixed $value): ?string
    {
        return Acn::normalise($value);
    }

    public static function format(mixed $value): ?string
    {
        return Acn::format($value);
    }
}
