<?php

use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseMigrations;

class BooksControllerValidationTest extends TestCase
{
    use DatabaseMigrations;

    public function testItValidatesRequiredFieldsWhenCreatingANewBook()
    {
        $this->post('/books', [], ['Accept' => 'application/json']);

        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $this->response->getStatusCode());

        $body = json_decode($this->response->getContent(), true);

        $this->assertArrayHasKey('title', $body);
        $this->assertArrayHasKey('description', $body);
        $this->assertArrayHasKey('author', $body);

        $this->assertEquals(["The title field is required."], $body['title']);
        $this->assertEquals(
            ["Please fill out the description."],
            $body['description']
        );
        $this->assertEquals(["The author field is required."], $body['author']);
    }

    public function testItValidatesRequiredFieldsWhenUpdatingANewBook()
    {
        $book = factory(\App\Book::class)->create();

        $this->put("/books/{$book->id}", [], ['Accept' => 'application/json']);

        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $this->response->getStatusCode());

        $body = json_decode($this->response->getContent(), true);

        $this->assertArrayHasKey('title', $body);
        $this->assertArrayHasKey('description', $body);
        $this->assertArrayHasKey('author', $body);

        $this->assertEquals(["The title field is required."], $body['title']);
        $this->assertEquals(
            ["Please fill out the description."],
            $body['description']
        );
        $this->assertEquals(["The author field is required."], $body['author']);
    }

    public function testTitleFailsCreateValidationWhenJustTooLong()
    {
        // Creating a book
        $book = factory(\App\Book::class)->make();
        $book->title = str_repeat('a', 256);

        $this->post('/books', [
            'title' => $book->title,
            'description' => $book->description,
            'author' => $book->author,
        ], ['Accept' => 'application/json']);

        $this
            ->seeStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->seeJson([
                'title' => ["The title may not be greater than 255 characters."]
            ])
            ->notSeeInDatabase('books', ['title' => $book->title]);
    }

    public function testTitleFailsUpdateValidationWhenJustTooLong()
    {
        // Updating a book
        $book = factory(\App\Book::class)->create();
        $book->title = str_repeat('a', 256);

        $this->put("/books/{$book->id}", [
            'title' => $book->title,
            'description' => $book->description,
            'author' => $book->author,
        ], ['Accept' => 'application/json']);

        $this
            ->seeStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->seeJson([
                'title' => ["The title may not be greater than 255 characters."]
            ])
            ->notSeeInDatabase('books', ['title' => $book->title]);
    }

    public function testTitlePassesCreateValidationWhenExactlyMax()
    {
        // Creating a new book
        $book = factory(\App\Book::class)->make();
        $book->title = str_repeat('a', 255);

        $this->post('/books', [
            'title' => $book->title,
            'description' => $book->description,
            'author' => $book->author,
        ], ['Accept' => 'application/json']);

        $this
            ->seeStatusCode(Response::HTTP_CREATED)
            ->seeInDatabase('books', ['title' => $book->title]);
    }

    public function testTitlePassesUpdateValidationWhenExactlyMax()
    {
        // Creating a new book
        $book = factory(\App\Book::class)->make();
        $book->title = str_repeat('a', 255);

        $this->post('/books', [
            'title' => $book->title,
            'description' => $book->description,
            'author' => $book->author,
        ], ['Accept' => 'application/json']);

        $this
            ->seeStatusCode(Response::HTTP_CREATED)
            ->seeInDatabase('books', ['title' => $book->title]);
    }
}