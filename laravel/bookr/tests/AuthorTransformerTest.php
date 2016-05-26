<?php

use App\Author;
use App\Transformers\AuthorTransformer;
use League\Fractal\TransformerAbstract;
use Laravel\Lumen\Testing\DatabaseMigrations;

class AuthorTransformerTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $this->subject = new AuthorTransformer();
    }

    public function testItCanBeInitialized()
    {
        $this->assertInstanceOf(AuthorTransformer::class, $this->subject);
    }

    public function testItCanTransformAnAuthor()
    {
        $author = factory(\App\Author::class)->create();

        $actual = $this->subject->transform($author);

        $this->assertEquals($author->id, $actual['id']);
        $this->assertEquals($author->name, $actual['name']);
        $this->assertEquals($author->biography, $actual['biography']);
        $this->assertEquals($author->gender, $actual['gender']);
        $this->assertEquals(
            $author->created_at->toIso8601String(),
            $actual['created']
        );
        $this->assertEquals(
            $author->updated_at->toIso8601String(),
            $actual['updated']
        );
    }

    public function testItCanTransformRelatedBooks()
    {
        $book = $this->bookFactory();
        $author = $book->author;

        $data = $this->subject->includeBooks($author);
        $this->assertInstanceOf(\League\Fractal\Resource\Collection::class, $data);
    }
}