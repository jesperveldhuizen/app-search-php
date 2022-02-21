<?php
/**
 * This file is part of the Elastic App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elastic\AppSearch\Client\Endpoint;

/**
 * Implementation of the UpdateDocuments endpoint.
 *
 * @package Elastic\AppSearch\Client\Endpoint
 */
class UpdateDocuments extends \Elastic\OpenApi\Codegen\Endpoint\AbstractEndpoint
{
    // phpcs:disable
    /**
     * @var string
     */
    protected $method = 'PATCH';

    /**
     * @var string
     */
    protected $uri = '/engines/{engine_name}/documents';

    protected $routeParams = ['engine_name'];
    // phpcs:enable
}