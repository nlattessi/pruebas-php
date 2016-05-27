<?php

use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseMigrations;

class AuthorsControllerTest extends TestCase
{
    use DatabaseMigrations;

    // public function setUp()
    // {
    // }

    // public function tearDown()
    // {
    // }

    public function testIndexRespondsWith200StatusCode()
    {
        $this->get('/authors')->seeStatusCode(Response::HTTP_OK);
    }

    public function testIndexShouldReturnACollectionOfRecords()
    {
        $authors = factory(\App\Author::class, 2)->create();

        $this->get('/authors', ['Accept' => 'application/json']);

        $body = json_decode($this->response->getContent(), true);

        $this->assertArrayHasKey('data', $body);
        $this->assertCount(2, $body['data']);

        foreach ($authors as $author) {
            $this->seeJson([
                'id' => $author->id,
                'name' => $author->name,
                'biography' => $author->biography,
                'gender' => $author->gender,
                'created' => $author->created_at->toIso8601String(),
                'updated' => $author->updated_at->toIso8601String(),
            ]);
        }
    }

    public function testShowShouldReturnAValidAuthor()
    {
        $book = $this->bookFactory();
        $author = $book->author;

        $this->get("/authors/{$author->id}", ['Accept' => 'application/json']);
        $body = json_decode($this->response->getContent(), true);

        $this->assertArrayHasKey('data', $body);

        $this->seeJson([
            'id' => $author->id,
            'name' => $author->name,
            'biography' => $author->biography,
            'gender' => $author->gender,
            'created' => $author->created_at->toIso8601String(),
            'updated' => $author->updated_at->toIso8601String(),
        ]);
    }

    public function testShowShouldFailOnAnInvalidAuthor()
    {
        $this->get('/authors/1234', ['Accept' => 'application/json']);
        $this->seeStatusCode(Response::HTTP_NOT_FOUND);

        $this->seeJson([
            'message' => 'Not Found',
            'status' => Response::HTTP_NOT_FOUND
        ]);

        $body = json_decode($this->response->getContent(), true);
        $this->assertArrayHasKey('error', $body);
        $error = $body['error'];

        $this->assertEquals('Not Found', $error['message']);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $error['status']);
    }

    public function testShowOptionallyIncludesBooks()
    {
        $book = $this->bookFactory();
        $author = $book->author;

        $this->get(
            "/authors/{$author->id}?include=books",
            ['Accept' => 'application/json']
        );

        $body = json_decode($this->response->getContent(), true);

        $this->assertArrayHasKey('data', $body);
        $data = $body['data'];
        $this->assertArrayHasKey('books', $data);
        $this->assertArrayHasKey('data', $data['books']);
        $this->assertCount(1, $data['books']['data']);

        // See Author Data
        $this->seeJson([
            'id' => $author->id,
            'name' => $author->name
        ]);

        // Test included book data (the first record)
        $actual = $data['books']['data'][0];
        $this->assertEquals($book->id, $actual['id']);
        $this->assertEquals($book->title, $actual['title']);
        $this->assertEquals($book->description, $actual['description']);
        $this->assertEquals(
        $book->created_at->toIso8601String(),
        $actual['created']
        );
        $this->assertEquals(
        $book->updated_at->toIso8601String(),
        $actual['updated']
        );
    }

    public function testStoreCanCreateANewAuthor()
    {
        $postData = [
            'name' => 'Nahuel Lattessi',
            'gender' => 'male',
            'biography' => 'Science Fiction Writer // Programmer'
        ];

        $this->post('/authors', $postData, ['Accept' => 'application/json']);

        $this->seeStatusCode(Response::HTTP_CREATED);
        $data = $this->response->getData(true);
        $this->assertArrayHasKey('data', $data);
        $this->seeJson($postData);

        $this->seeInDatabase('authors', $postData);
    }

    public function testStoreReturnsAValidLocationHeader()
    {
        $postData = [
            'name' => 'H. G. Wells',
            'gender' => 'male',
            'biography' => 'Prolific Science-Fiction Writer'
        ];

        $this
            ->post('/authors', $postData, ['Accept' => 'application/json'])
            ->seeStatusCode(Response::HTTP_CREATED);

        $data = $this->response->getData(true);
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayHasKey('id', $data['data']);

        // Check the location header
        $id = $data['data']['id'];
        $this->seeHeaderWithRegExp('Location', "#/authors/{$id}$#");
    }

    public function testUpdateCanUpdateAnExistingAuthor()
    {
        $author = factory(\App\Author::class)->create();

        $requestData = [
            'name' => 'New Author Name',
            'gender' => $author->gender === 'male' ? 'female' : 'male',
            'biography' => 'An updated biography',
        ];

        $this
            ->put("/authors/{$author->id}",
                $requestData,
                ['Accept' => 'application/json']
            )
            ->seeStatusCode(Response::HTTP_OK)
            ->seeJson($requestData)
            ->seeInDatabase('authors', [
                'name' => 'New Author Name'
            ])
            ->notSeeInDatabase('authors', [
                'name' => $author->name
            ]);

            $this->assertArrayHasKey('data', $this->response->getData(true));
    }

    public function testDeleteCanRemoveAnAuthorAndHisOrHerBooks()
    {
        $author = factory(\App\Author::class)->create();

        $this
            ->delete("/authors/{$author->id}", [], ['Accept' => 'application/json'])
            ->seeStatusCode(204)
            ->notSeeInDatabase('authors', ['id' => $author->id])
            ->notSeeInDatabase('books', ['author_id' => $author->id]);
    }

    public function testDeletingAnInvalidAuthorShouldReturnA404()
    {
        $this
            ->delete('/authors/99999', [], ['Accept' => 'application/json'])
            ->seeStatusCode(404);
    }
}