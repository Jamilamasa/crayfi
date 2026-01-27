<?php

namespace Cray\Laravel\Support;

use Illuminate\Http\Client\Response;
use Cray\Laravel\Exceptions\CrayApiException;
use Cray\Laravel\Exceptions\CrayAuthenticationException;
use Cray\Laravel\Exceptions\CrayValidationException;

class ResponseNormalizer
{
    /**
     * Normalize the response and handle errors.
     *
     * @param Response $response
     * @return array
     * @throws CrayApiException
     * @throws CrayAuthenticationException
     * @throws CrayValidationException
     */
    public function normalize(Response $response): array
    {
        if ($response->successful()) {
            return $response->json() ?? [];
        }

        $status = $response->status();
        $data = $response->json();
        $message = $data['message'] ?? 'Unknown error occurred';

        if ($status === 401) {
            throw new CrayAuthenticationException($message, $status);
        }

        if ($status === 422 || $status === 400) {
            // Some APIs return 400 for validation errors or bad requests
            throw new CrayValidationException($message, $status, $data['errors'] ?? []);
        }

        if ($status >= 500) {
            throw new CrayApiException("Cray API Server Error: {$message}", $status);
        }

        throw new CrayApiException("Cray API Error: {$message}", $status);
    }
}
