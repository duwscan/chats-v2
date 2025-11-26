<?php

namespace App\Exceptions;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class CustomException extends RuntimeException
{
    public function __construct(
        string $message,
        protected int $status = Response::HTTP_BAD_REQUEST,
        protected array $data = [],
    ) {
        parent::__construct($message, $this->status);
    }

    public function status(): int
    {
        return $this->status;
    }

    public function data(): array
    {
        return $this->data;
    }
}
