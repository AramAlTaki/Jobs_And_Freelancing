<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RefreshTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $refresh_request = Request::create('oauth/token', 'POST', [
            'grant_type' => 'refresh_token',
            'client_id' => env("CLIENT_ID"),
            'client_secret' => env("CLIENT_SECRET"),
            'scope' => '',
        ]);
        $result = app()->handle($request);
        $response = json_decode($result->getContent(), true);

        $request->headers->set('Authorization','Bearer ' . $response['access_token']);
        return $next($request);
    }
}
