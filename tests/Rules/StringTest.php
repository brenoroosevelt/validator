<?php

declare(strict_types=1);

namespace Tests\Rules;

use App\Rules\IsString;
use PHPUnit\Framework\TestCase;
use stdClass;

class StringTest extends TestCase
{
    public function validDataProvider(): array
    {
        return [
            ['abc'],
            ['']
        ];
    }

    public function invalidDataProvider(): array
    {
        return [
            [null],
            [1],
            [true],
            [false],
            [[]],
            [.5],
            [1/3],
            [new stdClass()]
        ];
    }

    /** @dataProvider validDataProvider */
    public function testValidString(mixed $input): void
    {
        $rule = new IsString('invalid string');
        $result = $rule->isValid($input);
        $this->assertTrue($result->isOk());
    }

    /** @dataProvider invalidDataProvider */
    public function testInvalidString(mixed $input): void
    {
        $rule = new IsString('invalid string');
        $result = $rule->isValid($input);
        $this->assertFalse($result->isOk());
        $this->assertEquals('invalid string', $result->message);
    }
}
