<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckJwtBlacklist
{
    public function handle(Request $request, Closure $next)
    {
        $jwt = str_replace('Bearer ', '', $request->header('Authorization'));

        if (cache()->has("blacklisted_token:{$jwt}")) {
            return response()->json(['message' => 'Token is revoked.'], 401);
        }

        return $next($request);
    }
}
