<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class AiEstimationFailedException extends Exception
{
    public function __construct(
        string $message = 'AI estimation failed',
        public readonly ?array $context = null,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getContext(): ?array
    {
        return $this->context;
    }

    public function getLoggableContext(): array
    {
        if ($this->context === null) {
            return [];
        }

        // Remove sensitive data before logging
        $loggableContext = $this->context;
        unset($loggableContext['api_key']);
        unset($loggableContext['auth_token']);

        return $loggableContext;
    }
}
