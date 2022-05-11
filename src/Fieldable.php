<?php

declare(strict_types=1);

namespace App;

trait Fieldable
{
    protected ?string $field = null;

    public function setField(string $field): void
    {
        $this->field = $field;
    }

    public function getField(): ?string
    {
        return $this->field;
    }
}
