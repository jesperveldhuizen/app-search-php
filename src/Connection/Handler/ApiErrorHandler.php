<?php

declare(strict_types=1);

/**
 * This file is part of the Elastic App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elastic\AppSearch\Client\Connection\Handler;

use Elastic\AppSearch\Client\Exception\ApiRateExceededException;
use Elastic\OpenApi\Codegen\Exception\ApiException;
use Elastic\OpenApi\Codegen\Exception\AuthenticationException;
use Elastic\OpenApi\Codegen\Exception\BadRequestException;
use Elastic\OpenApi\Codegen\Exception\NotFoundException;
use GuzzleHttp\Ring\Core;

/**
 * This handler manage server side errors and throw comprehensive exceptions to the user.
 */
class ApiErrorHandler
{
    /**
     * @var callable
     */
    private $handler;

    /**
     * Constructor.
     *
     * @param callable $handler original handler
     */
    public function __construct(callable $handler)
    {
        $this->handler = $handler;
    }

    /**
     * Proxy the response and throw an exception if a http error is detected.
     *
     * @param array $request request
     */
    public function __invoke($request)
    {
        $handler = $this->handler;

        return Core::proxy($handler($request), function ($response) use ($request) {
            if ($response['status'] >= 400) {
                $exception = new ApiException($this->getErrorMessage($response));
                switch ($response['status']) {
                    case 401:
                    case 403:
                        $exception = new AuthenticationException($exception->getMessage());

                        break;
                    case 404:
                        $exception = new NotFoundException($exception->getMessage());

                        break;
                    case 429:
                        $exception = $this->getApiRateExceededException($exception->getMessage(), $response);

                        break;
                    case 400:
                        $exception = new BadRequestException($exception->getMessage());

                        break;
                }

                throw $exception;
            }

            return $response;
        });
    }

    /**
     * Process the error message from the response body.
     *
     * @param array $response
     *
     * @return string
     */
    private function getErrorMessage($response): string
    {
        $message = $response['reason'] ?? 'Unexpected error.';

        if (!empty($response['body']['errors'])) {
            $message = $response['body']['errors'];
        } elseif (!empty($response['body']['error'])) {
            $message = $response['body']['error'];
        }

        return is_array($message) ? implode(' ', $message) : $message;
    }

    /**
     * Build an ApiRateExceededException from the response.
     *
     * @param string $message
     * @param array  $response
     *
     * @return \Elastic\AppSearch\Client\Exception\ApiRateExceededException
     */
    private function getApiRateExceededException($message, $response): \Elastic\AppSearch\Client\Exception\ApiRateExceededException
    {
        $limit = null;
        $retryAfter = null;

        if (Core::hasHeader($response, RateLimitLoggingHandler::RATE_LIMIT_LIMIT_HEADER_NAME)) {
            $limit = Core::firstHeader($response, RateLimitLoggingHandler::RATE_LIMIT_LIMIT_HEADER_NAME);
        }

        if (Core::hasHeader($response, RateLimitLoggingHandler::RETRY_AFTER_HEADER_NAME)) {
            $retryAfter = Core::firstHeader($response, RateLimitLoggingHandler::RETRY_AFTER_HEADER_NAME);
        }

        return new ApiRateExceededException($message, $limit, $retryAfter);
    }
}
