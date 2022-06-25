<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

/**
 * @internal
 * @coversNothing
 */
class ItemsTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    public function testListItems()
    {
        $response = $this->makeRequest('GET', '/api/items');

        $this->assertResponseIsSuccessful();

        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/api/contexts/Item',
            '@id' => '/api/items',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 10,
        ]);

        $this->assertCount(10, $response->toArray()['hydra:member']);
    }

    public function testItemAdd()
    {
        $this->makeRequest('POST', '/api/items', [
            'json' => [
                'name' => 'Ice Cream',
            ],
        ]);

        $this->assertResponseStatusCodeSame(201);

        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            'name' => 'Ice Cream',
        ]);
    }

    public function testItemChange()
    {
        $this->makeRequest('PUT', '/api/items/1', [
            'json' => [
                'name' => 'Pizza',
            ],
        ]);

        $this->assertResponseIsSuccessful();

        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@id' => '/api/items/1',
            'name' => 'Pizza',
        ]);
    }

    public function testItemDelete()
    {
        $this->makeRequest('DELETE', '/api/items/2');

        $this->assertResponseStatusCodeSame(204);
    }

    public function testItemPropertyAdd()
    {
        $this->makeRequest('POST', '/api/properties', [
            'json' => [
                'value' => 'Attractive',
                'item' => '/api/items/1',
            ],
        ]);

        $this->assertResponseStatusCodeSame(201);

        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            'value' => 'Attractive',
        ]);
    }

    public function testItemPropertyAddInvalid()
    {
        $this->makeRequest('POST', '/api/properties', [
            'json' => [
                'value' => 'Unattractive',
                'item' => null,
            ],
        ]);

        $this->assertResponseStatusCodeSame(400);

        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            'hydra:title' => 'An error occurred',
        ]);
    }

    public function testRequestWithoutApiKey()
    {
        static::createClient()->request('GET', '/api/items');
        $this->assertResponseStatusCodeSame(403);
    }

    private function makeRequest(string $method, string $url, array $options = [])
    {
        if (!isset($options['headers'])) {
            $options['headers'] = [];
        }
        $options['headers']['X-API-KEY'] = $_ENV['X_API_KEY'];

        return static::createClient()->request($method, $url, $options);
    }
}
