<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AccessForShop
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->route('shop')->id !== Auth::user()->id) {
            return \response()->json([
                'msg' => 'You are not permitted',
            ], JsonResponse::HTTP_METHOD_NOT_ALLOWED);
        }
        return $next($request);
    }
}
