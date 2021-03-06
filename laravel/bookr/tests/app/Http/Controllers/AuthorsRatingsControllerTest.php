<?php

namespace Tests\App\Http\Controllers;

use Laravel\Lumen\Testing\DatabaseMigrations;

class AuthorsRatingsControllerTest extends \Tests\TestCase
{
    use DatabaseMigrations;

    public function testStoreCanAddARatingToAnAuthor()
    {
        $author = factory(\App\Author::class)->create();

        $this->post(
            "/authors/{$author->id}/ratings",
            ['value' => 5],
            ['Accept' => 'application/json']
        );

        $this
            ->seeStatusCode(201)
            ->seeJson([
                'value' => 5
            ])
            ->seeJson([
                'rel' => 'author',
                'href' => route('authors.show', ['id' => $author->id])
            ]);

        $body = $this->response->getData(true);
        $this->assertArrayHasKey('data', $body);

        $data = $body['data'];
        $this->assertArrayHasKey('links', $data);
    }

    public function testStoreFailsWhenTheAuthorIsInvalid()
    {
        $this->post("/authors/1/ratings", [], ['Accept' => 'application/json']);
        $this->seeStatusCode(404);
    }

    public function testDestroyCanDeleteAnAuthorRating()
    {
        $author = factory(\App\Author::class)->create();
        $ratings = $author->ratings()->saveMany(
            factory(\App\Rating::class, 2)->make()
        );

        $this->assertCount(2, $ratings);

        $ratings->each(function (\App\Rating $rating) use ($author) {
            $this->seeInDatabase('ratings', [
                'rateable_id' => $author->id,
                'id' => $rating->id
            ]);
        });

        $ratingToDelete = $ratings->first();
        $this
            ->delete("/authors/{$author->id}/ratings/{$ratingToDelete->id}")
            ->seeStatusCode(204);

        $dbAuthor = \App\Author::find($author->id);
        $this->assertCount(1, $dbAuthor->ratings);
        $this->notSeeInDatabase(
            'ratings',
            ['id' => $ratingToDelete->id]
        );
    }

    public function test_destroy_should_not_delete_ratings_from_another_author()
    {
        $authors = factory(\App\Author::class, 2)->create();
        
        $authors->each(function (\App\Author $author) {
            $author->ratings()->saveMany(
                factory(\App\Rating::class, 2)->make()
            );
        });

        $firstAuthor = $authors->first();
        $rating = $authors
            ->last()
            ->ratings()
            ->first();

        $this->delete(
            "/authors/{$firstAuthor->id}/ratings/{$rating->id}",
            [],
            ['Accept' => 'application/json']
        )->seeStatusCode(404);
    }

    public function test_destroy_fails_when_the_author_is_invalid()
    {
        $this->delete('/authors/1/ratings/1', [], ['Accept' =>  'application/json'])->seeStatusCode(404);
    }
}