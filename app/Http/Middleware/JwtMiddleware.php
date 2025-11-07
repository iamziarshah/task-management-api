<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token is invalid',
                'error' => $e->getMessage(),
            ], 401);
        } catch (TokenExpiredException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token has expired',
                'error' => $e->getMessage(),
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'error' => $e->getMessage(),
            ], 401);
        }

        return $next($request);
    }
}
