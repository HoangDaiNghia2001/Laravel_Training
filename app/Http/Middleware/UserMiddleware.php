<?php

namespace App\Http\Middleware;

use App\Constants\RoleConstants;
use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;

class UserMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        if (auth()->check()) {
            foreach (auth()->user()->roles as $role) {
                if ($role->name === RoleConstants::USER_ROLE) {
                    return $next($request);
                }
            }
            return response()->json(['message' => 'You not permission.'], Response::HTTP_FORBIDDEN);
        } else {
            return response()->json(['message' => 'Unauthorized.'], Response::HTTP_UNAUTHORIZED);
        }
    }
}
