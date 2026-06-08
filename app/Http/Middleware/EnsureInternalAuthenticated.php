<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureInternalAuthenticated
{
    public function handle(Request $request, Closure $next): Response|RedirectResponse
    {
        if (! $request->session()->has('user_role')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
