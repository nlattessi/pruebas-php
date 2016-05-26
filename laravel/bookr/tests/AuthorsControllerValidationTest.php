<?php

use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseMigrations;

class AuthorsControllerValidationTest extends TestCase
{
    use DatabaseMigrations;

    public function testStoreMethodValidateRequiredFields()
    {
        $this->post('/authors', [], ['Accept' => 'applicaiton/json']);

        $data = $this->response->getData(true);

        $fields = ['name', 'gender', 'biography'];

        foreach ($fields as $field) {
            $this->assertArrayHasKey($field, $data);
            $this->assertEquals(["The {$field} field is required."], $data[$field]);
        }
    }
}