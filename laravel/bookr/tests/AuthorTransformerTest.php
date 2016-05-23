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
}