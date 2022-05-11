<?php

declare(strict_types=1);

namespace Tests;

use App\Error;
use App\Fieldable;
use App\Rules\AllowsNull;
use App\Rules\Integer;
use App\Rules\IsString;
use App\Rules\Required;
use App\Validation;
use App\ValidationException;
use App\ValidationResult;
use App\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    public function testValidatorEmptyIsOk(): void
    {
        $validator = new Validator();
        $errors = $validator->validate([]);
        $this->assertEmpty($errors);
    }

    public function testShouldAddRule(): void
    {
        $validator = new Validator();

        $rule1 = new class implements Validation {
            use Fieldable;

            public function isValid(mixed $input, array $context = []): ValidationResult
            {
                return ValidationResult::invalid('error1');
            }
        };

        $rule2 = new class implements Validation {
            use Fieldable;

            public function isValid(mixed $input, array $context = []): ValidationResult
            {
                return ValidationResult::invalid('error2');
            }
        };

        $validator->add($rule1, 'field1');
        $validator->add($rule2, 'field2');

        $errorField1 = $validator->rulesOf('field1');
        $errorField2 = $validator->rulesOf('field2');

        $this->assertSame($rule1, $errorField1[0]);
        $this->assertSame($rule2, $errorField2[0]);
        $this->assertCount(1, $errorField1);
        $this->assertCount(1, $errorField2);
    }

    public function testShouldAddRuleRequired(): void
    {
        $validator = new Validator();

        $validator->add(new Required('required field'), 'field1');
        $errorField1 = $validator->rulesOf('field1');

        $this->assertInstanceOf(Required::class, $errorField1[0]);
        $this->assertEquals('field1', $errorField1[0]->getField());
    }

    public function testShouldValidateRule(): void
    {
        $validator = new Validator();
        $validator->add(new class implements Validation {
            use Fieldable;

            public function isValid(mixed $input, array $context = []): ValidationResult
            {
                return ValidationResult::invalid('error1');
            }
        }, 'field1');

        $validator->add(new class implements Validation {
            use Fieldable;

            public function isValid(mixed $input, array $context = []): ValidationResult
            {
                return ValidationResult::invalid('error2');
            }
        }, 'field1');

        $errors = $validator->validate([]);
        $this->assertContains('field1', array_map(fn(Error $error) => $error->field, $errors));
        $this->assertContains('error1', array_map(fn(Error $error) => $error->error, $errors));
        $this->assertContains('error2', array_map(fn(Error $error) => $error->error, $errors));
    }

    public function testRulesOf(): void
    {
        $validator = new Validator();
        $validator->add(new Integer('invalid integer'), 'f1');
        $validator->add(new IsString('invalid integer'), 'f1');
        $validator->add(new Integer('invalid integer'), 'f2');

        $this->assertCount(2, $validator->rulesOf('f1'));
        $this->assertCount(1, $validator->rulesOf('f2'));
    }

    public function testIsNullable(): void
    {
        $validator = new Validator();
        $validator->add(new class implements Validation {
            use Fieldable;

            public function isValid(mixed $input, array $context = []): ValidationResult
            {
                return ValidationResult::invalid('error1');
            }
        }, 'field1');

        $validator->add(new AllowsNull(), 'field1');
        $this->assertTrue($validator->allowsNull('field1'));
    }

    public function testShouldAllowsNull(): void
    {
        $validator = new Validator();
        $validator->add(new class implements Validation {
            use Fieldable;

            public function isValid(mixed $input, array $context = []): ValidationResult
            {
                return ValidationResult::invalid('error1');
            }
        }, 'field1');

        $validator->add(new AllowsNull(), 'field1',);

        $errors = $validator->validate(['field1' => null]);
        $this->assertEmpty($errors);
    }

    public function testValidateOrFail(): void
    {
        $validator = new Validator();
        $validator->add(new class implements Validation {
            use Fieldable;

            public function isValid(mixed $input, array $context = []): ValidationResult
            {
                return ValidationResult::invalid('error1');
            }
        }, 'field1');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('input error msg');

        $validator->validateOrFail([], 'input error msg');
    }

    public function testCreateValidatorFromClass(): void
    {
        $validator = Validator::fromObjectOrClass(new class {
            #[DummyValidation(true, 'value1-ok')]
            #[DummyValidation(false, 'value1-nok')]
            private int $value1;

            #[DummyValidation(true, 'value2-ok')]
            #[DummyValidation(false, 'value2-nok1')]
            #[DummyValidation(false, 'value2-nok2')]
            private string $value2;
        });

        $errors = $validator->validate(['value1' => 'a', 'value2' => 'b', 'value3' => 'c']);

        $this->assertContains('value1', array_map(fn(Error $error) => $error->field, $errors));
        $this->assertContains('value2', array_map(fn(Error $error) => $error->field, $errors));
        $this->assertContains('value1-nok', array_map(fn(Error $error) => $error->error, $errors));
        $this->assertContains('value2-nok1', array_map(fn(Error $error) => $error->error, $errors));
        $this->assertContains('value2-nok2', array_map(fn(Error $error) => $error->error, $errors));
    }
}
