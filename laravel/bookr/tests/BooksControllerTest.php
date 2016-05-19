<?php

class BooksControllerTest extends TestCase
{
    public function testIndexStatusCodeShouldBe200()
    {
        $response = $this->call('GET', '/books');

        $this->assertEquals(200, $response->status());
    }

    public function testIndexShouldReturnACollectionOfRecords()
    {
        $this
            ->json('GET', '/books')
            ->seeJson([
                'title' => 'War of the Worlds'
            ])
            ->seeJson([
                'title' => 'A Wrinkle in Time'
            ]);
    }

    public function testShowShouldReturnAValidBook()
    {
        $this
            ->json('GET', '/books/1')
            ->seeStatusCode(200)
            ->seeJson([
                'id' => 1,
                'title' => 'War of the Worlds',
                'description' => 'A science fiction masterpiece about Martians invading London',
                'author' => 'H. G. Wells'
            ]);

        $data = json_decode($this->response->getContent(), true);
        $this->assertArrayHasKey('created_at', $data);
        $this->assertArrayHasKey('updated_at', $data);
    }

    public function testShowShouldFailWhenTheBookIdDoesNotExist()
    {
        $this
            ->json('GET', '/books/99999')
            ->seeStatusCode(404)
            ->seeJson([
                'error' => [
                    'message' => 'Book not found'
                ],
            ]);
    }

    public function testShowRouteShouldNotMatchAnInvalidRoute()
    {
        $response = $this->call('GET', '/books/this-is-invalid');

        $this->assertNotRegExp(
            '/Book not found/',
            $this->response->getContent(),
            'BooksController@show route matching when it should not.'
        );
    }
}
