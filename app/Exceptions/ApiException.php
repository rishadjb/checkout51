<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class ApiException extends Exception
{
    /**
     * @var array
     */
    private $jsonResponse = [];

    const CODE_BAD_INPUT = 'bad_input';
    const CODE_BAD_REQUEST = 'bad_request';
    const CODE_NOT_FOUND = 'not_found';
    const CODE_UNAUTHORIZED = 'access_denied';
    const CODE_SERVER_ERROR = 'server_error';

    /**
     * Construct the API exception.
     *
     * @param string       $idCode     The id code of the error
     * @param string|array $messages   An array of messages or a string with the error message
     * @param int          $statusCode The http status code
     */
    public function __construct(string $idCode, $messages, int $statusCode)
    {
        $this->jsonResponse = [
            'id'       => $idCode,
            'messages' => is_array($messages) ? $messages : [$messages],
        ];

        $this->code = (int) $statusCode;
    }

    /**
     * Return the json response array.
     *
     * @return array
     */
    public function getJsonResponse(): array
    {
        return $this->jsonResponse;
    }
}
