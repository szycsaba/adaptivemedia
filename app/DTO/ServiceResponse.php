<?php

namespace App\DTO;

class ServiceResponse
{
    public function __construct(
        public bool $success,
        public ?string $message = null,
        public array $data = [],
        public int $status = 200
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'success' => $this->success,
            'message' => $this->message,
            'data' => empty($this->data) ? null : $this->data,
        ], fn($value) => $value !== null);
    }
}