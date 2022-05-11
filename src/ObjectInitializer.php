<?php

declare(strict_types=1);

namespace App;

use DateTime;
use DateTimeImmutable;
use ReflectionClass;
use ReflectionNamedType;

trait ObjectInitializer
{
    public static function initialize(array $data): object
    {
        Validator::fromObjectOrClass(static::class)->validateOrFail($data);
        $args = [];
        $constructorParams = (new ReflectionClass(static::class))->getConstructor()?->getParameters() ?? [];
        foreach ($constructorParams as $param) {
            $name = $param->getName();
            $value = $data[$name] ?? null;
            if(null === $value && $param->getType()?->allowsNull()) {
                $args[$name] = null;
                continue;
            }

            $type = $param->getType() instanceof ReflectionNamedType ? $param->getType()->getName() : null;
            $args[$name] = match ($type) {
                DateTimeImmutable::class => new DateTimeImmutable($value),
                DateTime::class => new DateTime($value),
                'int'       => (int) $value,
                'string'    => (string) $value,
                'float'     => (float) $value,
                'boolean'   => (bool) $value,
                'array'     => (array) $value,
                default     => $value,
            };
        }

        return new static(...$args);
    }
}
