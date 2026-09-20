<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureWife
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isWife()) {
            abort(403, 'Hanya pemilik akun yang bisa mengubah data.');
        }

        return $next($request);
    }
}
