<?php

/**
 * This file is part of the Elastic App Search PHP Client package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elastic\AppSearch\Client\Tests\Integration;

/**
 * Integration test for the Query Suggestions API.
 *
 * @package Elastic\AppSearch\Client\Test\Integration
 */
class QuerySuggestionApiTest extends AbstractEngineTestCase
{
    /**
     * @var bool
     */
    protected static $importSampleDocs = true;

    /**
     * Run simple searches with optional pagination and check result returned.
     *
     * @param array $searchRequest The search request.
     *
     * @testWith ["cat", null, null]
     *           ["gru", null, 4]
     *           ["gru", ["title"], null]
     *           ["gru", ["title"], 1]
     */
    public function testQuerySuggestion($queryText, $fields, $size)
    {
        $client = $this->getDefaultClient();
        $engine = $this->getDefaultEngineName();

        $suggestions = $client->querySuggestion($engine, $queryText, $fields, $size);

        $this->assertNotEmpty($suggestions['meta']['request_id']);
        $this->assertNotEmpty($suggestions['results']['documents']);

        if (null !== $size) {
            $this->assertLessThanOrEqual($size, count($suggestions['results']['documents']));
        }

        array_walk($suggestions['results']['documents'], function ($suggestion) {
            $this->assertNotEmpty($suggestion['suggestion']);
        });
    }
}
