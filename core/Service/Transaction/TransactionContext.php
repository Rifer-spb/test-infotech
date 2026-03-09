<?php

namespace Core\Service\Transaction;

class TransactionContext
{
    private array $data = [];

    public function set(string $key, mixed $value): self
    {
        $this->data[$key] = $value;
        return $this;
    }

    public function get(string $key): mixed
    {
        return $this->data[$key] ?? null;
    }
}
