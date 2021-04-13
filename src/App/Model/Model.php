<?php

namespace App\Model;

interface Model
{
    public function fromArray(array $data): self;
    public function toArray(): array;
}
