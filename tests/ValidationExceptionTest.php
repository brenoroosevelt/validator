<?php

declare(strict_types=1);

namespace Tests;

use App\Error;
use App\ValidationException;
use PHPUnit\Framework\TestCase;

class ValidationExceptionTest extends TestCase
{
    public function testExceptionValues(): void
    {
        $errors = [new Error('error 1', 'field1'), new Error('error 2', 'field1')];
        $exception = new ValidationException('input error', ...$errors);
        $this->assertEquals($errors, $exception->errors);
        $this->assertEquals('input error', $exception->getMessage());
        $this->assertEquals(422, $exception->getCode());
        $this->assertEquals([
            'status' => 422,
            'message' => 'input error',
            'violations' => [
                ['field' => 'field1', 'error' => 'error 1'],
                ['field' => 'field1', 'error' => 'error 2'],
            ]
        ], $exception->jsonSerialize());
    }
}
