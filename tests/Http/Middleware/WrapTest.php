<?php

namespace Aregsar\Converter\Tests\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Aregsar\Converter\Http\Middleware\Wrap;
use Aregsar\Converter\Tests\BaseTestCase;

class WrapTest extends BaseTestCase
{
    // protected function tearDown(): void
    // {
    //     //Add cleanup code here before the parent tearDown
    //     \Mockery::close();
    //     parent::tearDown();
    // }

    /** @test */
    function it_adds_in_header_to_request_and_out_header_to_response()
    {
        $request = new Request();

        // $response = \Mockery::Mock(Response::class)
        //     ->shouldReceive('headers->set')
        //     ->with("out", "outgoing response")
        //     ->once()
        //     ->getMock();

        // $response = \Illuminate\Support\Facades\Response::shouldReceive('headers->set')
        //     ->with("out", "outgoing response")
        //     ->once()
        //     ->getMock();

        $response = new Response();

        $wrappedResponse = (new Wrap())->handle($request, function ($request)  use ($response) {

            //we want to check that the header was added before this closure is called
            //by the Wrap middleware handle method, so we need to check assert inside this closure not
            $this->assertEquals("incoming request", $request->headers->get("in"));


            //we pass the mock response to our closure so it can return it to the Wrap::handle method
            //that the closure is passed to, where the Wrap::handle method will add the out header to mock response
            //and then return the modified response which we set to $wrappedResponse
            return $response;
        });

        $this->assertEquals("outgoing response", $wrappedResponse->headers->get("out"));
    }
}
