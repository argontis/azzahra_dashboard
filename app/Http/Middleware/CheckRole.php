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
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $userLevel = Auth::user()->kry_level;
        
        // Admin or Pimpinan have full access
        if ($userLevel === 'Admin' || $userLevel === 'Pimpinan') {
            return $next($request);
        }
        
        // Check if user's level is in allowed roles
        if (in_array($userLevel, $roles)) {
            return $next($request);
        }
        
        abort(403, 'Unauthorized action.');
    }
}
