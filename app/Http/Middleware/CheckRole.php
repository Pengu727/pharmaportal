<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles  Dynamic list of roles passed from the route
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Get the authenticated user's role from any guard
        $userRole = null;
        
        // Check all available guards
        $guards = ['web', 'admin'];
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $userRole = Auth::guard($guard)->user()->role ?? null;
                if ($userRole) break;
            }
        }
        
        // Fallback: Check request->user() (default guard)
        if (!$userRole && $request->user()) {
            $userRole = $request->user()->role ?? null;
        }
        
        // Final fallback: API testing with header
        if (!$userRole) {
            $userRole = $request->header('X-User-Role');
        }

        // 2. Reject if no role context is provided
        if (!$userRole) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Missing authentication context.'
            ], 401);
        }

        // 3. Compare user role against the route's allowed roles array
        if (!in_array($userRole, $roles)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden. You do not have the required permissions to access this resource.'
            ], 403);
        }

        return $next($request);
    }
}