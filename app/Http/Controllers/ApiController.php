<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Psr\Http\Message\ServerRequestInterface;
use stdClass;
use Symfony\Component\HttpFoundation\AcceptHeader;

abstract class ApiController extends BaseController
{
    /**
     * @var \Psr\Http\Message\ServerRequestInterfacet
     */
    protected $request;

    /**
     * @var float
     */
    protected $version;

    /**
     * @var stdClass|array
     */
    protected $parsedBody;

    /**
     * @var array
     */
    protected $queryParams;

    /**
     * @var string
     */
    protected $defaultAcceptHeader = 'application/vnd.novus+json';

    /**
     * Class constructor.
     *
     * @param ServerRequestInterface $request
     *
     * @throws ApiException
     */
    public function __construct(ServerRequestInterface $request)
    {
        $acceptHeader = AcceptHeader::fromString($request->getHeaderLine('accept'));

        switch (config('app.request_type')) {
            case 'array':
                $data = json_decode(json_encode($request->getParsedBody()), true) ?? [];
                break;
            case 'object':
            default:
                $jsonBody = $request->getBody()->__toString() ?: json_encode($request->getParsedBody()); // Support for unit tests + keeping json objects
                $data = json_decode($jsonBody) ?? new stdClass();
                break;
        }

        $this->request = $request->withParsedBody($data);
        $this->parsedBody = $this->request->getParsedBody();
        $this->queryParams = $this->request->getQueryParams();
    }

    /**
     * Return the request object.
     *
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    protected function getRequest(): \Psr\Http\Message\ServerRequestInterface
    {
        return $this->request;
    }

    /**
     * Return the version of the API requested.
     *
     * @return float
     */
    protected function getVersion(): float
    {
        return (float) $this->version;
    }

    /**
     * Return a value from the parsed body.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    protected function getBodyParam(string $key, $default = null)
    {
        if (empty($this->parsedBody)) {
            return $default;
        }

        switch (config('app.request_type')) {
            case 'array':
                return array_key_exists($key, $this->parsedBody) ? $this->parsedBody[$key] : $default;
            case 'object':
            default:
                return property_exists($this->parsedBody, $key) ? $this->parsedBody->{$key} : $default;
        }
    }

    /**
     * Return all the body params.
     *
     * @return stdClass|array
     */
    protected function getBodyParams()
    {
        return $this->parsedBody;
    }

    /**
     * Set a new request body.
     *
     * @return \Novus\Platform\Core\Http\Controllers\ApiController
     */
    protected function setBody($body)
    {
        $this->parsedBody = $body;

        return $this;
    }

    /**
     * Return a value from the query.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    protected function getQueryParam(string $key, $default = null)
    {
        return isset($this->queryParams[$key]) ? $this->queryParams[$key] : $default;
    }

    /**
     * Return all the query params.
     *
     * @return stdClass|array
     */
    protected function getQueryParams()
    {
        return $this->queryParams;
    }

    /**
     * Return the response in the format that was requested.
     *
     * @param mixed $data
     * @param int   $status
     * @param array $headers
     * @param int   $options
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function response($data = null, $status = 200, $headers = [], $options = 0)
    {
        return response()->json($data, $status, $headers, $options);
    }

    /**
     * Return the accept header.
     *
     * @return string
     */
    protected function getAcceptHeader(): string
    {
        return config('app.accept_header') ?: $this->defaultAcceptHeader;
    }
}
