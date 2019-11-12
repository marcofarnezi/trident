<?php

namespace App\Http\Middleware;

use Closure;

class CheckWishListIdMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (empty($request->header('Wish-Id'))) {
            return response()->json(['message' => "The request is wrong - miss Wish-Id header"], 404);
        }

        return $next($request);
    }

}
