<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserCanLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || $request->user()->can_login) {
            return $next($request);
        }

        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->withErrors([
            'email' => 'Your access is currently disabled. Please contact an administrator.',
        ]);
    }
}
