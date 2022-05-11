<?php

declare(strict_types=1);

namespace App;

use App\Rules\AllowsNull;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;

final class Validator
{
    public static string $BASE_CLASS = Validation::class;
    public static string $MSG = 'Input validation error';
    private const FILTER = ReflectionAttribute::IS_INSTANCEOF;

    /** @var Validation[] */
    private array $rules = [];

    public function add(Validation $rule, ?string $field = null): self
    {
        $this->rules[] = $rule;
        if (null !== $field) {
            $rule->setField($field);
        }

        return $this;
    }

    /** @throws ReflectionException */
    public static function fromObjectOrClass(object|string $objetOrClass): self
    {
        $instance = new self;
        foreach ((new ReflectionClass($objetOrClass))->getProperties() as $reflectionProperty) {
            foreach ($reflectionProperty->getAttributes(self::$BASE_CLASS, self::FILTER) as $attribute) {
                $instance->add($attribute->newInstance(), $reflectionProperty->getName());
            }
        }

        return $instance;
    }

    /** @return Error[] */
    public function validate(array $data): array
    {
        $errors = [];
        foreach ($this->rules as $rule) {
            $field = $rule->getField();
            $value = $data[$field] ?? null;
            if (null === $value && $this->allowsNull($field)) {
                continue;
            }

            $result = $rule->isValid($value, $data);
            if (!$result->isOk()) {
                $errors[] = new Error($result->message, $field);
            }
        }

        return $errors;
    }

    public function validateOrFail(array $data, ?string $message = null): void
    {
        $errors = $this->validate($data);
        if ([] !== $errors) {
            throw new ValidationException($message ?? self::$MSG, ...$errors);
        }
    }

    public function allowsNull(string $field): bool
    {
        foreach ($this->rulesOf($field) as $rule) {
            if ($rule instanceof AllowsNull) {
                return true;
            }
        }

        return false;
    }

    /** @return Validation[] */
    public function rulesOf(string $field): array
    {
        return array_values(
            array_filter($this->rules, fn(Validation $validation) => $validation->getField() === $field)
        );
    }

    /** @return Validation[] */
    public function rules(): array
    {
        return $this->rules;
    }
}
