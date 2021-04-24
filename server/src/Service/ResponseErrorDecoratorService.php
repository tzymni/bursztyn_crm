<?php


namespace App\Service;

/**
 * Class ResponseErrorDecoratorService
 * @package App\Service
 *
 * Helper service to format nice error response out of given status-code and message
 *                (to standardize/make consistent error response from the server)
 */
class ResponseErrorDecoratorService
{
    /**
     * Decorate error.
     *
     * @param int $status
     * @param $message
     * @return array[]
     */
    public function decorateError(int $status, $message): array
    {
        return [
            'error' => [
                'code' => $status,
                'message' => $message
            ]
        ];
    }
}