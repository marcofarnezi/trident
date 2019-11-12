<?php

namespace App\Http\Middleware;

use Closure;

class CheckUserIdMiddleware
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
        if (empty($request->header('User-Id'))) {
            return response()->json(['message' => "The request is wrong - miss User-Id header"], 404);
        }

        return $next($request);
    }

}
