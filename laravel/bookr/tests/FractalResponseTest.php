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
}