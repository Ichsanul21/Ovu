<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUnlocked
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->pin_code && ! $request->session()->get('unlocked')) {
            if ($request->expectsJson()) {
                abort(403, 'Terkunci.');
            }

            return redirect()->route('pin.show');
        }

        return $next($request);
    }
}
