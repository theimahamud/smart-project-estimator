<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class InvalidEstimationResponseException extends Exception
{
    public function __construct(
        string $message = 'Invalid estimation response from AI',
        public readonly ?array $response = null,
        public readonly ?string $validationError = null,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getResponse(): ?array
    {
        return $this->response;
    }

    public function getValidationError(): ?string
    {
        return $this->validationError;
    }

    public function getLoggableResponse(): array
    {
        if ($this->response === null) {
            return [];
        }

        // Truncate large responses for logging
        $loggableResponse = $this->response;
        if (isset($loggableResponse['raw_response']) && strlen($loggableResponse['raw_response']) > 1000) {
            $loggableResponse['raw_response'] = substr($loggableResponse['raw_response'], 0, 1000).'...';
        }

        return $loggableResponse;
    }
}
