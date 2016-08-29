<?php

use PHPUnit\Framework\TestCase;

class GetUsersTest extends TestCase
{
    private $client;

    public function setUp()
    {
        $dotenv = new Dotenv\Dotenv(__DIR__ . '/../');
        $dotenv->load();

        $this->client = new \GuzzleHttp\Client([
            'base_uri' => getenv('TEST_BASE_URI')
        ]);
    }

    public function testGetUsers()
    {
        $response = $this->client->get('/users');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetUserWithId1()
    {
        $response = $this->client->get('/users/1');
        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('address', $data);
        $this->assertArrayHasKey('picture', $data);
    }

    public function testCreateUser()
    {
        $response = $this->client->post('/users', [
            'multipart' => [
                [
                    'name' => 'name',
                    'contents' => 'nah'
                ],
                [
                    'name' => 'address',
                    'contents' => 'calle 123'
                ],
                [
                    'name' => 'picture',
                    'contents' => fopen('./tests/cara.jpg', 'r')
                ],
            ],
        ]);
        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('address', $data);
        $this->assertArrayHasKey('picture', $data);

        $this->assertEquals($data['name'], 'nah');
        $this->assertEquals($data['address'], 'calle 123');
    }

    public function testUpdateUser()
    {
        $response = $this->client->get('/users/1');
        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('address', $data);
        $this->assertArrayHasKey('picture', $data);

        $response = $this->client->post('/users/' . $data['id'], [
            'multipart' => [
                [
                    'name' => 'name',
                    'contents' => 'Updated name'
                ],
                [
                    'name' => 'address',
                    'contents' => 'Updated address'
                ],
                [
                    'name' => 'picture',
                    'contents' => fopen('./tests/cara.jpg', 'r')
                ],
            ],
        ]);
        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('address', $data);
        $this->assertArrayHasKey('picture', $data);

        $this->assertEquals($data['name'], 'Updated name');
        $this->assertEquals($data['address'], 'Updated address');
    }
}