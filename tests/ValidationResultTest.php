<?php

declare(strict_types=1);

namespace Tests;

use App\ValidationResult;
use PHPUnit\Framework\TestCase;

class ValidationResultTest extends TestCase
{
    public function testOk(): void
    {
        $result = ValidationResult::valid();
        $this->assertTrue($result->isOk());
    }

    public function testError(): void
    {
        $result = ValidationResult::invalid('error message');
        $this->assertFalse($result->isOk());
        $this->assertEquals('error message', $result->message);
    }
}
