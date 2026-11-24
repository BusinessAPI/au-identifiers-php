<?php

declare(strict_types=1);

namespace BusinessApi\AuIdentifiers\Tests;

use BusinessApi\AuIdentifiers\Abn;
use BusinessApi\AuIdentifiers\Acn;
use BusinessApi\AuIdentifiers\Arbn;
use BusinessApi\AuIdentifiers\DirectorId;
use BusinessApi\AuIdentifiers\Tfn;
use BusinessApi\AuIdentifiers\Vin;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class VectorsTest extends TestCase
{
    private static function vectors(string $key): array
    {
        $all = json_decode(file_get_contents(__DIR__.'/vectors.json'), true);
        $out = [];
        foreach ($all[$key] as $case) {
            $out[$case['type'].' '.json_encode($case['value'])] = [$case];
        }

        return $out;
    }

    public static function validCases(): array
    {
        return self::vectors('valid');
    }

    public static function formatCases(): array
    {
        return self::vectors('format');
    }

    #[DataProvider('validCases')]
    public function testValid(array $case): void
    {
        $got = match ($case['type']) {
            'abn' => Abn::isValid($case['value']),
            'acn' => Acn::isValid($case['value']),
            'did' => DirectorId::isValid($case['value']),
            'tfn' => Tfn::isValid($case['value'], $case['individual'] ?? false),
            'vin' => Vin::isValid($case['value']),
        };
        $this->assertSame($case['valid'], $got);
    }

    #[DataProvider('formatCases')]
    public function testFormat(array $case): void
    {
        $got = match ($case['type']) {
            'abn' => Abn::format($case['value']),
            'acn' => Acn::format($case['value']),
            'vin' => Vin::normalise($case['value']),
        };
        $this->assertSame($case['expected'], $got);
    }

    public function testOtherInputs(): void
    {
        $this->assertTrue(Arbn::isValid('000 000 019'));
        $this->assertFalse(Arbn::isValid('000 000 018'));
        foreach ([null, 51824753556.0, true, ['51824753556'], -51824753556] as $v) {
            $this->assertFalse(Abn::isValid($v));
            $this->assertFalse(Tfn::isValid($v));
            $this->assertFalse(Vin::isValid($v));
        }
        $this->assertTrue(Abn::isValid(51824753556));
        $this->assertSame('123456782', Tfn::normalise(123456782));
        $this->assertFalse(Acn::isValid('٠٠٤٠٨٥٦١٦'));
    }
}
