<?php

namespace App\Http\Middleware;

use Closure;
//use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use App\Http\Controllers\AuthController;
use Auth;

class JwtMiddleware
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next) {
		// return $next($request);
		// try {
		// 	$user = Auth::parseToken()->authenticate();
		// } catch (Exception $e) {
		// 	if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
		// 		return response()->json(['message' => 'Token is Invalid'], 401);
		// 	} else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
		// 		return response()->json(['message' => 'Token is Expired'], 419);
		// 	} else {
		// 		return response()->json(['message' => 'Authorization Token not found'], 401);
		// 	}
		// }
		
		return $next($request);
	}
}
