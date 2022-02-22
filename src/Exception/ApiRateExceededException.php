<?php

declare(strict_types=1);

/**
 * This file is part of the Elastic App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elastic\AppSearch\Client\Exception;

use Elastic\OpenApi\Codegen\Exception\ApiException;
use Elastic\OpenApi\Codegen\Exception\ClientException;

/**
 * Exception thrown when the API Rate limit have been exceded.
 */
class ApiRateExceededException extends ApiException implements ClientException
{
    /**
     * @var int
     */
    private $limit;

    /**
     * @var int
     */
    private $retryAfter;

    /**
     * Exception constructor.
     *
     * @param mixed $message
     * @param int   $limit
     * @param int   $retryAfter
     * @param mixed $code
     * @param mixed $previous
     */
    public function __construct($message = null, $limit = null, $retryAfter = null, $code = null, $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->limit = $limit;
        $this->retryAfter = $retryAfter;
    }

    /**
     * Return the max number of call allowed over a period.
     *
     * @return string
     */
    public function getApiRateLimit(): string
    {
        return $this->limit;
    }

    /**
     * Indicate the ttl before trying again to use the API.
     *
     * @return string
     */
    public function getRetryAfter(): string
    {
        return $this->retryAfter;
    }
}
