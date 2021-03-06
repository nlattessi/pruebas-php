<?php

namespace Tests\App\Transformers;

use App\Transformers\RatingTransformer;
use Laravel\Lumen\Testing\DatabaseMigrations;

class RatingTransformerTest extends \Tests\TestCase
{
    use DatabaseMigrations;

    private $subject;

    public function setUp()
    {
        parent::setUp();

        $this->subject = new RatingTransformer();
    }

    public function testItCanBeInitialized()
    {
        $this->assertInstanceOf(RatingTransformer::class, $this->subject);
    }

    public function testItCanTransformARatingForAnAuthor()
    {
        $author = factory(\App\Author::class)->create();
        $rating = $author->ratings()->save(
            factory(\App\Rating::class)->make()
        );

        $actual = $this->subject->transform($rating);

        $this->assertEquals($rating->id, $actual['id']);
        $this->assertEquals($rating->value, $actual['value']);
        $this->assertEquals($rating->rateable_type, $actual['type']);
        $this->assertEquals($rating->created_at->toIso8601String(), $actual['created']);
        $this->assertEquals($rating->updated_at->toIso8601String(), $actual['updated']);
        $this->assertArrayHasKey('links', $actual);
        $links = $actual['links'];
        $this->assertCount(1, $links);
        $authorLink = $links[0];

        $this->assertArrayHasKey('rel', $authorLink);
        $this->assertEquals('author', $authorLink['rel']);
        $this->assertArrayHasKey('href', $authorLink);
        $this->assertEquals(
            route('authors.show', ['id' => $author->id]),
            $authorLink['href']
        );
    }

    /**
     * @test
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Rateable model type for Foo\Bar is not defined
     */
    public function testItThrowsAnExceptionWhenAModelIsNotDefined()
    {
        $rating = factory(\App\Rating::class)->create([
            'value' => 5,
            'rateable_type' => 'Foo\Bar',
            'rateable_id' => 1
        ]);

        $this->subject->transform($rating);
    }
}