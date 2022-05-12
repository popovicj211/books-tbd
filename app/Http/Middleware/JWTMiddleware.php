<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
//use PHPOpenSourceSaver\JWTAuth\JWTAuth;

use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class JWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {

        /* try {
                  $user = JWTAuth::parseToken()->authenticate();
              } catch (\Exception $e) {
                  if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                      return response()->json(['status' => 'Token is Invalid']);
                  }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                      return response()->json(['status' => 'Token is Expired']);
                  }else{
                      return response()->json(['status' => 'Authorization Token not found']);
                  }
              }*/

      try {

          JWTAuth::parseToken()->authenticate();

        } catch (TokenExpiredException $e) {

            return $this->unauthorized('Your token has expired. Please, login again.');
        } catch (TokenInvalidException $e) {

            return $this->unauthorized('Your token is invalid. Please, login again.');
        } catch (JWTException $e) {

            return $this->unauthorized('Please, attach a Bearer Token to your request');
        }

       return $next($request);

    }

    private function unauthorized($message = null)
    {
        return response()->json([
            'message' => $message ?? 'Unauthorized.'
        ], 401);
    }
}
