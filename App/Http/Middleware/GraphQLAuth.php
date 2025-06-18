<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GraphQLAuth
{
  public function handle(Request $request, Closure $next)
  {
    if (Auth::guard('sanctum')->check()) {
      return $next($request);
    }

    return response()->json([
      'message' => 'غير مصرح',
    ], 401);
  }
}
