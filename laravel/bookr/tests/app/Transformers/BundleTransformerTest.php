<?php

namespace Tests\Transformers;

use App\Transformers\BundleTransformer;
use Laravel\Lumen\Testing\DatabaseMigrations;

class BundleTransformerTest extends \Tests\TestCase
{
    use DatabaseMigrations;

    private $subject;

    public function setUp()
    {
        parent::setUp();

        $this->subject = new BundleTransformer();
    }

    public function testItCanBeInitialized()
    {
        $this->assertInstanceOf(
            BundleTransformer::class,
            $this->subject
        );
    }

    public function testItCanTransformABundle()
    {
        $bundle = factory(\App\Bundle::class)->create();

        $actual = $this->subject->transform($bundle);

        $this->assertEquals($bundle->id, $actual['id']);
        $this->assertEquals($bundle->title, $actual['title']);
        $this->assertEquals($bundle->description, $actual['description']);
        $this->assertEquals($bundle->created_at->toIso8601String(), $actual['created']);
        $this->assertEquals($bundle->updated_at->toIso8601String(), $actual['updated']);
    }

    public function testItCanTransformRelatedBooks()
    {
        $bundle = $this->bundleFactory();

        $data = $this->subject->includeBooks($bundle);

        $this->assertInstanceOf(
            \League\Fractal\Resource\Collection::class,
            $data
        );
        $this->assertInstanceOf(
            \App\Book::class,
            $data->getData()[0]
        );
        $this->assertCount(2, $data->getData());
    }
}