<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;

class JsonResponseMiddleware
{

    /**
     * Set default json response options
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        /**
         * @var JsonResponse $response
         */
        $response = $next($request);

        if ($response instanceof JsonResponse) {
            $response->setEncodingOptions(JSON_NUMERIC_CHECK);
        }

        return $response;
    }
}
