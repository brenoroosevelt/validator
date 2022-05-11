<?php

declare(strict_types=1);

namespace App;

use DomainException;
use JsonSerializable;
use Psr\Http\Message\ResponseInterface;

final class ValidationException extends DomainException implements JsonSerializable
{
    /** @var Error[] */
    public readonly array $errors;

    public function __construct(string $message, Error ...$errors)
    {
        parent::__construct($message, 422);
        $this->errors = $errors;
    }

    public function jsonSerialize(): array
    {
        return [
            'status' => $this->getCode(),
            'message' => $this->getMessage(),
            'violations' => array_map(fn(Error $error) => $error->jsonSerialize(), $this->errors)
        ];
    }
}
