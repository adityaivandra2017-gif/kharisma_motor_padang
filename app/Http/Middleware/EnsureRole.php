<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response|RedirectResponse
    {
        $userRole = $request->session()->get('user_role');

        if (! $userRole || ! in_array($userRole, $roles, true)) {
            return redirect()->route('login')->withErrors([
                'email' => 'Anda tidak memiliki akses ke halaman tersebut.',
            ]);
        }

        return $next($request);
    }
}
