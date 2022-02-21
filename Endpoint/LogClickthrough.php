<?php
/**
 * This file is part of the Elastic App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elastic\AppSearch\Client\Endpoint;

/**
 * Implementation of the LogClickthrough endpoint.
 *
 * @package Elastic\AppSearch\Client\Endpoint
 */
class LogClickthrough extends \Elastic\OpenApi\Codegen\Endpoint\AbstractEndpoint
{
    // phpcs:disable
    /**
     * @var string
     */
    protected $method = 'POST';

    /**
     * @var string
     */
    protected $uri = '/engines/{engine_name}/click';

    protected $routeParams = ['engine_name'];

    protected $paramWhitelist = ['query', 'document_id', 'request_id', 'tags'];
    // phpcs:enable
}
