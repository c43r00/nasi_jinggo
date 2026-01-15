<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->role;

        // Check if user's role matches any of the allowed roles
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // Redirect based on user's actual role
        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}