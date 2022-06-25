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
        $response = static::createClient()->request('GET', '/api/items');

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
        static::createClient()->request('POST', '/api/items', [
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
        static::createClient()->request('PUT', '/api/items/1', [
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
        static::createClient()->request('DELETE', '/api/items/2');

        $this->assertResponseStatusCodeSame(204);
    }

    public function testItemPropertyAdd()
    {
        static::createClient()->request('POST', '/api/properties', [
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
        static::createClient()->request('POST', '/api/properties', [
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
}
