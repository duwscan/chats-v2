<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class NotFoundException extends CustomException
{
    public function __construct(string $message = 'Resource not found.', array $data = [])
    {
        parent::__construct($message, Response::HTTP_NOT_FOUND, $data);
    }
}
