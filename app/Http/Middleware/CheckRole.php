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
        $userRole = null;

        // Check admin guard first (separate auth model)
        if (in_array('admin', $roles) && Auth::guard('admin')->check()) {
            // Admin model has a getRoleAttribute() accessor returning 'admin'
            $userRole = 'admin';
        }

        // Check web guard for regular users (owner, seller, client)
        if (!$userRole && Auth::guard('web')->check()) {
            $userRole = Auth::guard('web')->user()->role ?? null;
        }

        // Fallback: Check default request user
        if (!$userRole && $request->user()) {
            $userRole = $request->user()->role ?? null;
        }

        // Final fallback: API testing header
        if (!$userRole) {
            $userRole = $request->header('X-User-Role');
        }

        // Reject if no role context
        if (!$userRole) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized. Missing authentication context.'
                ], 401);
            }
            // Redirect web requests to appropriate login page
            if (in_array('admin', $roles)) {
                return redirect()->route('admin.login');
            }
            return redirect()->route('client.login');
        }

        // Compare user role against the route's allowed roles
        if (!in_array($userRole, $roles)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Forbidden. You do not have the required permissions.'
                ], 403);
            }
            abort(403, 'Forbidden. You do not have the required permissions.');
        }

        return $next($request);
    }
}