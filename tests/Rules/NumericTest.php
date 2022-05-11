<?php

declare(strict_types=1);

namespace Tests\Rules;

use App\Rules\Numeric;
use PHPUnit\Framework\TestCase;

class NumericTest extends TestCase
{
    public function validDataProvider(): array
    {
        return [
            [1],
            [-10],
            ['1'],
            ['-9'],
            ['-9.111'],
            ['0'],
            ['0.0'],
            [.66],
            [1/3],
            [00100],
            ['-0'],
            ['-0.00']
        ];
    }

    public function invalidDataProvider(): array
    {
        return [
            ['1+'],
            [null],
            [''],
            [true],
            [false],
            [[]],
            [new \stdClass()]
        ];
    }

    /** @dataProvider validDataProvider */
    public function testValidNumeric(mixed $input): void
    {
        $rule = new Numeric('invalid number');
        $result = $rule->isValid($input);
        $this->assertTrue($result->isOk());
    }

    /** @dataProvider invalidDataProvider */
    public function testInvalidNumeric(mixed $input): void
    {
        $rule = new Numeric('invalid number');
        $result = $rule->isValid($input);
        $this->assertFalse($result->isOk());
        $this->assertEquals('invalid number', $result->message);
    }
}
