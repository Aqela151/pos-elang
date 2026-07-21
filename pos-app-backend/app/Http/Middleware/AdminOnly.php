<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (! $user || strtolower((string) ($user->role ?? '')) !== 'admin') {
            return response()->json([
                'message' => 'Forbidden'
            ], 403);
        }

        return $next($request);
    }
}
