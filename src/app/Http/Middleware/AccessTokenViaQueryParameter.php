<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AccessTokenViaQueryParameter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('bearerToken')) {
            $request->headers->set('Authorization', sprintf('%s %s', 'Bearer', $request->get('bearerToken')));
        }

        return $next($request);
    }
}
