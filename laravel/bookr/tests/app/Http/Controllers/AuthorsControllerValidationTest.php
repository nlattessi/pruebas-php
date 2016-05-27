<?php

namespace Tests\App\Http\Controllers;

use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseMigrations;

class AuthorsControllerValidationTest extends \Tests\TestCase
{
    use DatabaseMigrations;

    public function testValidationInvalidatesIncorrectGenderData()
    {
        foreach ($this->getValidationTestData() as $test) {
            $method = $test['method'];
            $test['data']['gender'] = 'unknown';
            $this->{$method}($test['url'], $test['data'], ['Accept' => 'application/json']);
            
            $this->seeStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);

            $data = $this->response->getData(true);
            $this->assertCount(1, $data);
            $this->assertArrayHasKey('gender', $data);
            $this->assertEquals(
                ["Gender format is invalid: must equal 'male' or 'female'"],
                $data['gender']
            );
        }
    }

    public function testValidationIsValidWhenNameIsJustLongEnough()
    {
        foreach ($this->getValidationTestData() as $test) {
            $method = $test['method'];
            $test['data']['name'] = str_repeat('a', 255);

            $this->{$method}($test['url'], $test['data'], ['Accept' => 'application/json']);
            
            $this->seeStatusCode($test['status']);
            $this->seeInDatabase('authors', $test['data']);
        }
    }

    public function testValidationInvalidatesNameWhenNameIsJustTooLong()
    {
        foreach ($this->getValidationTestData() as $test) {
            $method = $test['method'];
            $test['data']['name'] = str_repeat('a', 256);

            $this->{$method}($test['url'], $test['data'], ['Accept' => 'application/json']);
            
            $this->seeStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);

            $data = $this->response->getData(true);
            $this->assertCount(1, $data);
            $this->assertArrayHasKey('name', $data);
            $this->assertEquals(
                ["The name may not be greater than 255 characters."],
                $data['name']
            );
        }
    }

    public function testValidationValidatesRequiredFields()
    {
        $author = factory(\App\Author::class)->create();
        $tests = [
            ['method' => 'post', 'url' => '/authors'],
            ['method' => 'put', 'url' => "/authors/{$author->id}"],
        ];

        foreach ($tests as $test) {
            $method = $test['method'];
            $this->{$method}($test['url'], [], ['Accept' => 'application/json']);
            $this->seeStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);

            $data = $this->response->getData(true);

            $fields = ['name', 'gender', 'biography'];

            foreach ($fields as $field) {
                $this->assertArrayHasKey($field, $data);
                $this->assertEquals(["The {$field} field is required."], $data[$field]);
            }
        }
    }

    private function getValidationTestData()
    {
        $author = factory(\App\Author::class)->create();
        return [
            // Create
            [
                'method' => 'post',
                'url' => '/authors',
                'status' => Response::HTTP_CREATED,
                'data' => [
                    'name' => 'John Doe',
                    'gender' => 'male',
                    'biography' => 'An anonymous author'
                ]
            ],

            // Update
            [
                'method' => 'put',
                'url' => "/authors/{$author->id}",
                'status' => Response::HTTP_OK,
                'data' => [
                    'name' => $author->name,
                    'gender' => $author->gender,
                    'biography' => $author->biography
                ]
            ]
        ];
    }
}