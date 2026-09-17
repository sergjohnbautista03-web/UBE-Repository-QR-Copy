<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckResearcherExpiry
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Kapag researcher at nag-expire na ang graduation year,
            // HINDI na i-lo-logout — graduated researcher siya.
            // Ang middleware ay mag-a-update lang ng flag para read-only na siya.
            if (
                $user->role === 'researcher' &&
                $user->graduation_year &&
                $user->graduation_year < (int) date('Y') &&
                $user->is_active
            ) {
                // Mark as graduated — hindi burahin, hindi i-deactivate
                // is_active = true pa rin, pero makakapag-browse lang
                // Ginagawa ito sa pamamagitan ng graduated_at timestamp
                if (! $user->graduated_at) {
                    $user->update(['graduated_at' => now()]);
                }
            }
        }

        return $next($request);
    }
}