<?php
/**
 * This file is part of the Elastic App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elastic\AppSearch\Client\Endpoint;

/**
 * Implementation of the DeleteCuration endpoint.
 *
 * @package Elastic\AppSearch\Client\Endpoint
 */
class DeleteCuration extends \Elastic\OpenApi\Codegen\Endpoint\AbstractEndpoint
{
    // phpcs:disable
    /**
     * @var string
     */
    protected $method = 'DELETE';

    /**
     * @var string
     */
    protected $uri = '/engines/{engine_name}/curations/{curation_id}';

    protected $routeParams = ['engine_name', 'curation_id'];
    // phpcs:enable
}
