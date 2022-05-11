<?php

declare(strict_types=1);

namespace Tests\Rules;

use App\Rules\Integer;
use PHPUnit\Framework\TestCase;

class IntegerTest extends TestCase
{
    public function validDataProvider(): array
    {
        return [
            [1],
            [-10],
            ['1'],
            ['-9'],
            ['0'],
            ['0.0']
        ];
    }

    public function invalidDataProvider(): array
    {
        return [
            ['-9.3'],
            ['1+'],
            [null],
            [''],
            ['.9'],
            [.5],
            [1/3],
            [true],
            [false],
            [[]],
        ];
    }

    /** @dataProvider validDataProvider */
    public function testValidInteger(mixed $input): void
    {
        $rule = new Integer('invalid integer');
        $result = $rule->isValid($input);
        $this->assertTrue($result->isOk());
    }

    /** @dataProvider invalidDataProvider */
    public function testInvalidInteger(mixed $input): void
    {
        $rule = new Integer('invalid integer');
        $result = $rule->isValid($input);
        $this->assertFalse($result->isOk());
        $this->assertEquals('invalid integer', $result->message);
    }
}
