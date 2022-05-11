<?php

declare(strict_types=1);

namespace Tests;

use App\Error;
use App\ValidationException;
use PHPUnit\Framework\TestCase;
use Tests\Initializer\Class1;

class ObjectInitializerTest extends TestCase
{
    public function testInitSuccess(): void
    {
        $object = Class1::initialize(['name' => 'joe', 'value' => '1', 'date' => '2021-01-05']);
        $this->assertEquals('joe', $object->name);
        $this->assertEquals(1, $object->value);
        $this->assertEquals('05/01/2021', $object->date->format('d/m/Y'));
    }

    public function testInitSuccessWithNull(): void
    {
        $object = Class1::initialize(['name' => 'joe', 'value' => '1', 'date' => null]);
        $this->assertEquals('joe', $object->name);
        $this->assertEquals(1, $object->value);
        $this->assertEquals(null, $object->date);
    }

    public function testInitTypeError(): void
    {
        $exception = null;
        try {
            Class1::initialize(['name' => 'joe', 'value' => '1.5', 'date' => '2021-01-05']);
        } catch (ValidationException $e) {
            $exception = $e;
        }

        $this->assertInstanceOf(ValidationException::class, $exception);
        $this->assertEquals(new Error('invalid integer', 'value'), $exception->errors[0]);
    }
}
