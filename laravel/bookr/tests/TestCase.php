<?php

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class TestCase extends Laravel\Lumen\Testing\TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    public function seeHasHeader($header)
    {
        $this->assertTrue(
            $this->response->headers->has($header),
            "Response should have the header '{$header}' but does not."
        );

        return $this;
    }

    public function seeHeaderWithRegExp($header, $regexp)
    {
        $this
            ->seeHasHeader($header)
            ->assertRegExp(
                $regexp,
                $this->response->headers->get($header)
            );

        return $this;
    }

    public function bookFactory($count = 1)
    {
        $author = factory(\App\Author::class)->create();
        $books = factory(\App\Book::class, $count)->make();

        if ($count === 1) {
            $books->author()->associate($author);
            $books->save();
        } else {
            foreach ($books as $book) {
                $book->author()->associate($author);
                $book->save();
            }
        }

        return $books;
    }
}
