<?php

declare(strict_types=1);

/**
 * This file is part of the Elastic App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elastic\AppSearch\Client;

use Elastic\OpenApi\Codegen\Endpoint\Builder;

/**
 * Use this class to instantiate new client and all their dependencies.
 */
class ClientBuilder extends \Elastic\OpenApi\Codegen\AbstractClientBuilder
{
    /**
     * @var string
     */
    public const URI_PREFIX = '/api/as/v1/';

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $integration;

    /**
     * Instantiate a new client builder.
     *
     * @param string $hostIdentifier
     * @param string $apiKey
     *
     * @return \Elastic\AppSearch\Client\ClientBuilder
     */
    public static function create($apiEndpoint = null, $apiKey = null): self
    {
        return (new static())->setHost($apiEndpoint)->setApiKey($apiKey);
    }

    /**
     * Set the api key for the client.
     *
     * @param string $apiKey
     *
     * @return ClientBuilder
     */
    public function setApiKey($apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Set integration name & version for the client.
     *
     * @param string $integration
     *
     * @return ClientBuilder
     */
    public function setIntegration($integration): self
    {
        $this->integration = $integration;

        return $this;
    }

    /**
     * Set the api endpoint for the client.
     *
     * @param string $host
     *
     * @return ClientBuilder
     */
    public function setHost($host): self
    {
        $isValidEndpoint = false;
        $testedEndpoint = $host;

        if (filter_var($testedEndpoint, \FILTER_VALIDATE_URL)) {
            $isValidEndpoint = true;
        }

        if (!$isValidEndpoint) {
            $testedEndpoint = sprintf('https://%s', $testedEndpoint);
            $isValidEndpoint = false != filter_var($testedEndpoint, \FILTER_VALIDATE_URL);
        }

        if (!$isValidEndpoint) {
            $testedEndpoint = sprintf('%s.%s', $testedEndpoint, 'api.swiftype.com');
            $isValidEndpoint = false != filter_var($testedEndpoint, \FILTER_VALIDATE_URL);
        }

        if (!$isValidEndpoint) {
            throw new \Elastic\OpenApi\Codegen\Exception\UnexpectedValueException("Invalid API endpoint : $host");
        }

        return parent::setHost($testedEndpoint);
    }

    /**
     * Return the configured App Search client.
     *
     * @return \Elastic\AppSearch\Client\Client
     */
    public function build(): \Elastic\AppSearch\Client\Client
    {
        return new Client($this->getEndpointBuilder(), $this->getConnection());
    }

    /**
     * {@inheritdoc}
     */
    protected function getHandler(): callable
    {
        $handler = parent::getHandler();

        $handler = new Connection\Handler\RequestClientHeaderHandler($handler, $this->integration);
        $handler = new Connection\Handler\RequestAuthenticationHandler($handler, $this->apiKey);
        $handler = new \Elastic\OpenApi\Codegen\Connection\Handler\RequestUrlPrefixHandler($handler, self::URI_PREFIX);
        $handler = new Connection\Handler\ApiErrorHandler($handler);

        return new Connection\Handler\RateLimitLoggingHandler($handler, $this->getLogger());
    }

    /**
     * {@inheritdoc}
     */
    protected function getEndpointBuilder(): Builder
    {
        return new \Elastic\OpenApi\Codegen\Endpoint\Builder(__NAMESPACE__."\Endpoint");
    }
}
