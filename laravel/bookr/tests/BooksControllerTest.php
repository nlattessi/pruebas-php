<?php

use Carbon\Carbon;
use Laravel\Lumen\Testing\DatabaseMigrations;

class BooksControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::now('UTC'));
    }

    public function tearDown()
    {
        parent::tearDown();

        Carbon::setTestNow();
    }

    public function testIndexStatusCodeShouldBe200()
    {
        $this
            ->get('/books')
            ->seeStatusCode(200);
    }

    public function testIndexShouldReturnACollectionOfRecords()
    {
        $books = factory('App\Book', 2)->create();
        
        $this->get('/books');

        $content = json_decode($this->response->getContent(), true);
        $this->assertArrayHasKey('data', $content);

        foreach ($books as $book) {
            $this->seeJson([
                'id' => $book->id,
                'title' => $book->title,
                'description' => $book->description,
                'author' => $book->author,
                'created' => $book->created_at->toIso8601String(),
                'updated' => $book->updated_at->toIso8601String(),
            ]);
        }
    }

    public function testShowShouldReturnAValidBook()
    {
        $book = factory('App\Book')->create();
        
        $this
            ->get("/books/{$book->id}")
            ->seeStatusCode(200);

        // Get the content and assert the data key exists
        $content = json_decode($this->response->getContent(), true);
        $this->assertArrayHasKey('data', $content);
        $data = $content['data'];

        // Assert the Book Properties
        $this->assertEquals($book->id, $data['id']);
        $this->assertEquals($book->title, $data['title']);
        $this->assertEquals($book->description, $data['description']);
        $this->assertEquals($book->author, $data['author']);
        $this->assertEquals($book->created_at->toIso8601String(), $data['created']);
        $this->assertEquals($book->updated_at->toIso8601String(), $data['updated']);
    }

    public function testShowShouldFailWhenTheBookIdDoesNotExist()
    {
        $this
            ->get('/books/99999', ['Accept' => 'application/json'])
            ->seeStatusCode(404)
            ->seeJson([
                'error' => [
                    'message' => 'Not Found',
                    'status' => 404
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

    public function testStoreShouldSaveNewBookInTheDatabase()
    {
        $this
            ->post('/books', [
                'title' => 'The Invisible Man',
                'description' => 'An invisible man is trapped in the terror of his own creation',
                'author' => 'H. G. Wells'
            ]);

        $body = json_decode($this->response->getContent(), true);
        $this->assertArrayHasKey('data', $body);

        $data = $body['data'];
        $this->assertEquals('The Invisible Man', $data['title']);
        $this->assertEquals('An invisible man is trapped in the terror of his own creation', $data['description']);
        $this->assertEquals('H. G. Wells', $data['author']);
        $this->assertTrue($data['id'] > 0, 'Expected a positive integer, but did not see one.');

        $this->assertArrayHasKey('created', $data);
        $this->assertEquals(Carbon::now()->toIso8601String(), $data['created']);
        $this->assertArrayHasKey('updated', $data);
        $this->assertEquals(Carbon::now()->toIso8601String(), $data['updated']);

        $this->seeInDatabase('books', ['title' => 'The Invisible Man']);
    }

    public function testStoreShouldRespondWithA201AndLocationHeaderWhenSuccess()
    {
        $this
            ->post('/books', [
                'title' => 'The Invisible Man',
                'description' => 'An invisible man is trapped in the terror of his own creation',
                'author' => 'H. G. Wells'
            ]);

        $this
            ->seeStatusCode(201)
            ->seeHeaderWithRegExp('Location', '#/books/[\d]+$#');
    }

    public function testUpdateShouldOnlyChangeFillableFields()
    {
        $book = factory('App\Book')->create([
            'title' => 'The War of the Worlds',
            'description' => 'A science fiction masterpiece about Martians invading London.',
            'author' => 'H. G. Wells'
        ]);

        $this->notSeeInDatabase('books', [
            'title' => 'The War of the Worlds',
            'description' => 'The book is way better than the movie.',
            'author' => 'Wells, H. G.'
        ]);

        $this
            ->put("/books/{$book->id}", [
                'id' => 5,
                'title' => 'The War of the Worlds',
                'description' => 'The book is way better than the movie.',
                'author' => 'Wells, H. G.'
            ]);

        $this
            ->seeStatusCode(200)
            ->seeJson([
                'id' => 1,
                'title' => 'The War of the Worlds',
                'description' => 'The book is way better than the movie.',
                'author' => 'Wells, H. G.'
            ])
            ->seeInDatabase('books', [
                'title' => 'The War of the Worlds'
            ]);

        $body = json_decode($this->response->getContent(), true);
        $this->assertArrayHasKey('data', $body);

        $data = $body['data'];
        $this->assertArrayHasKey('created', $data);
        $this->assertEquals(Carbon::now()->toIso8601String(), $data['created']);
        $this->assertArrayHasKey('updated', $data);
        $this->assertEquals(Carbon::now()->toIso8601String(), $data['updated']);
    }

    public function testUpdateShouldFailWithAnInvalidId()
    {
        $this
            ->put('/books/99999')
            ->seeStatusCode(404)
            ->seeJsonEquals([
                'error' => [
                    'message' => 'Book not found'
                ]
            ]);
    }

    public function testUpdateShouldNotMatchAnInvalidRoute()
    {
        $this
            ->put('/books/this-is-invalid')
            ->seeStatusCode(404);
    }

    public function testDestroyShouldRemoveAValidBook()
    {
        $book = factory('App\Book')->create();

        $this
            ->delete("/books/{$book->id}")
            ->seeStatusCode(204)
            ->isEmpty();

        $this->notSeeInDatabase('books', ['id' => $book->id]);
    }

    public function testDestroyShouldReturnA404WithAnInvalidId()
    {
        $this
            ->delete('/books/99999')
            ->seeStatusCode(404)
            ->seeJsonEquals([
                'error' => [
                    'message' => 'Book not found'
                ]
            ]);
    }

    public function testDestroyShouldNotMatchAnInvalidRoute()
    {
        $this
            ->delete('/books/this-is-invalid')
            ->seeStatusCode(404);
    }
}
