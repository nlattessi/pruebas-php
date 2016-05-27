<?php

namespace Tests;

use App\Http\Response\FractalResponse;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Serializer\SerializerAbstract;
use Mockery as m;

class FractalResponseTest extends TestCase
{
    public function testItCanBeInitialized()
    {
        $manager = m::mock(Manager::class);
        $serializer = m::mock(SerializerAbstract::class);
        $request = m::mock(Request::class);

        $manager
            ->shouldReceive('setSerializer')
            ->with($serializer)
            ->once()
            ->andReturn($manager);

        $fractal = new FractalResponse($manager, $serializer, $request);
        $this->assertInstanceOf(FractalResponse::class, $fractal);
    }

    public function testItCanTransformAnItem()
    {
        // Request
        $request = m::mock(Request::class);

        // Transformer
        $transformer = m::mock('League\Fractal\TransformerAbstract');

        // Scope
        $scope = m::mock('League\Fractal\Scope');
        $scope
            ->shouldReceive('toArray')
            ->once()
            ->andReturn(['foo' => 'bar']);

        // Serializer
        $serializer = m::mock('League\Fractal\Serializer\SerializerAbstract');

        // Manager
        $manager = m::mock('League\Fractal\Manager');
        $manager
            ->shouldReceive('setSerializer')
            ->with($serializer)
            ->once();

        $manager
            ->shouldReceive('createData')
            ->once()
            ->andReturn($scope);

        $subject = new FractalResponse($manager, $serializer, $request);
        $this->assertInternalType(
            'array',
            $subject->item(['foo' => 'bar'], $transformer)
        );
    }

    public function testItCanTransformACollection()
    {
        $data = [
            ['foo' => 'bar'],
            ['fizz' => 'buzz']
        ];

        // Request
        $request = m::mock(Request::class);

        // Transformer
        $transformer = m::mock('League\Fractal\TransformerAbstract');

        // Scope
        $scope = m::mock('League\Fractal\Scope');
        $scope
            ->shouldReceive('toArray')
            ->once()
            ->andReturn(['foo' => 'bar']);

        // Serializer
        $serializer = m::mock('League\Fractal\Serializer\SerializerAbstract');

        // Manager
        $manager = m::mock('League\Fractal\Manager');
        $manager
            ->shouldReceive('setSerializer')
            ->with($serializer)
            ->once();

        $manager
            ->shouldReceive('createData')
            ->once()
            ->andReturn($scope);

        $subject = new FractalResponse($manager, $serializer, $request);
        $this->assertInternalType(
            'array',
            $subject->collection($data, $transformer)
        );
    }

    public function testItShouldParsePassedIncludesWhenPassed()
    {
        $serializer = m::mock(SerializerAbstract::class);

        $manager = m::mock(Manager::class);
        $manager->shouldReceive('setSerializer')->with($serializer);
        $manager
            ->shouldReceive('parseIncludes')
            ->with('books');

        $request = m::mock(Request::class);
        $request->shouldNotReceive('query');

        $subject = new FractalResponse($manager, $serializer, $request);
        $subject->parseIncludes('books');
    }

    public function testItShouldParseRequestQueryIncludesWithNoArguments()
    {
        $serializer = m::mock(SerializerAbstract::class);

        $manager = m::mock(Manager::class);
        $manager->shouldReceive('setSerializer')->with($serializer);
        $manager
            ->shouldReceive('parseIncludes')
            ->with('books');

        $request = m::mock(Request::class);
        $request
            ->shouldReceive('query')
            ->with('include', '')
            ->andReturn('books');

        $subject = new FractalResponse($manager, $serializer, $request);
        $subject->parseIncludes();
    }
}