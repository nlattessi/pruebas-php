<?php

namespace Tests\Transformers;

use App\Author;
use App\Transformers\AuthorTransformer;
use League\Fractal\TransformerAbstract;
use Laravel\Lumen\Testing\DatabaseMigrations;

class AuthorTransformerTest extends \Tests\TestCase
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

    public function test_it_can_transform_an_author()
    {
        $author = factory(\App\Author::class)->create();

        $author->ratings()->save(
            factory(\App\Rating::class)->make(['value' => 5])
        );

        $author->ratings()->save(
            factory(\App\Rating::class)->make(['value' => 3])
        );

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

        // Rating
        $this->assertArrayHasKey('rating', $actual);
        $this->assertInternalType('array', $actual['rating']);
        $this->assertEquals(4, $actual['rating']['average']);
        $this->assertEquals(5, $actual['rating']['max']);
        $this->assertEquals(80, $actual['rating']['percent']);
        $this->assertEquals(2, $actual['rating']['count']);
    }

    public function testItCanTransformRelatedBooks()
    {
        $book = $this->bookFactory();
        $author = $book->author;

        $data = $this->subject->includeBooks($author);
        $this->assertInstanceOf(\League\Fractal\Resource\Collection::class, $data);
    }
}