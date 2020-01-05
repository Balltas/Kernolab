<?php

namespace App\Responses;

class JsonResponse
{
    private $status;
    private $messages;

    public function __construct(array $messages, int $status)
    {
        $this->status = $status;
        $this->messages = $messages;
    }

    public function status(): int
    {
        return $this->status;
    }

    public function messages(): array
    {
        return $this->messages;
    }
}