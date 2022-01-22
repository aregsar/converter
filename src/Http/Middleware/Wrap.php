<?php

namespace Aregsar\Converter\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Wrap
{
    public function handle(Request $request, Closure $next)
    {
        echo "\n from middelware \n";

        //inject header in request
        $request->headers->set("in", "incoming request");

        //trigger a call to the next middleware in the pipeline and get back the response from the next middlware.
        $response = $next($request);

        // add code to inject header in the response
        //$response->header("out", "outgoing response");
        $response->headers->set("out", "outgoing response");

        return $response;
    }
}
