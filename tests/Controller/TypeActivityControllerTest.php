<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TypeActivityControllerTest extends webTestCase
{
    private $client;
    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testGetTest(): void
    {
        $this->client->request('GET', '/api/typeActivity/test');

        $this->assertResponseIsSuccessful();
        $this->assertJsonStringEqualsJsonString(
            '{"data":"test"}',
            $this->client->getResponse()->getContent()
        );
    }

    public function testCreateTypeActivity(): void
    {
        $this->client->request('POST', '/api/typeActivity/new', [], [], [], json_encode(['name'=>'test new type activity']));
        $this->assertSame(201,$this->client->getResponse()->getStatusCode());
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertSame("test new type activity", $responseData['name']);
    }
}