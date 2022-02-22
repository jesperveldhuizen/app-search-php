<?php

declare(strict_types=1);
/**
 * This file is part of the Elastic App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elastic\AppSearch\Client\Endpoint;

/**
 * Implementation of the UpdateCuration endpoint.
 */
class UpdateCuration extends \Elastic\OpenApi\Codegen\Endpoint\AbstractEndpoint
{
    // phpcs:disable
    /**
     * @var string
     */
    protected $method = 'PUT';

    /**
     * @var string
     */
    protected $uri = '/engines/{engine_name}/curations/{curation_id}';

    protected $routeParams = ['engine_name', 'curation_id'];

    protected $paramWhitelist = ['queries', 'promoted', 'hidden'];
    // phpcs:enable
}