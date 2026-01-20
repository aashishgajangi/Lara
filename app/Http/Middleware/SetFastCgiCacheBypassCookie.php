<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cookie;

class SetFastCgiCacheBypassCookie
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (Auth::check() || $request->session()->has('cart')) {
            // User is logged in or has a cart, tell Nginx to bypass cache
            // We set a raw cookie that Nginx can check easily
            if (!$request->cookie('nginx_bypass')) {
                 $response->withCookie(Cookie::make('nginx_bypass', '1', 60 * 24 * 30, null, null, false, false));
            }
        } else {
            // Guest without cart, remove bypass cookie if it exists
             if ($request->cookie('nginx_bypass')) {
                $response->withCookie(Cookie::forget('nginx_bypass'));
            }
        }

        return $response;
    }
}
