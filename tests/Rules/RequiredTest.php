<?php

declare(strict_types=1);

namespace Tests\Rules;

use App\Rules\Required;
use PHPUnit\Framework\TestCase;

class RequiredTest extends TestCase
{
    public function testRequiredIsOk(): void
    {
        $rule = new Required('field required');
        $rule->setField('field1');
        $result = $rule->isValid('abc', ['field1' => 'a']);
        $this->assertTrue($result->isOk());
        $this->assertEquals('field1', $rule->getField());
    }

    public function testRequiredError(): void
    {
        $rule = new Required('This field is required');
        $rule->setField('field1');
        $result = $rule->isValid('abc', ['field2' => 'a']);
        $this->assertFalse($result->isOk());
        $this->assertEquals('This field is required', $result->message);
        $this->assertEquals('field1', $rule->getField());
    }
}
