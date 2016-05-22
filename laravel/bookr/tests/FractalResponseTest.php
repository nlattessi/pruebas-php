<?php

use \Mockery as m;
use League\Fractal\Manager;
use League\Fractal\Serializer\SerializerAbstract;
use App\Http\Response\FractalResponse;

class FractalResponseTest extends TestCase
{
    public function testItCanBeInitialized()
    {
        $manager = m::mock(Manager::class);
        $serializer = m::mock(SerializerAbstract::class);

        $manager
            ->shouldReceive('setSerializer')
            ->with($serializer)
            ->once()
            ->andReturn($manager);

        $fractal = new FractalResponse($manager, $serializer);
        $this->assertInstanceOf(FractalResponse::class, $fractal);
    }

    public function testItCanTransformAnItem()
    {
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

        $subject = new FractalResponse($manager, $serializer);
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

        $subject = new FractalResponse($manager, $serializer);
        $this->assertInternalType(
            'array',
            $subject->collection($data, $transformer)
        );
    }
}