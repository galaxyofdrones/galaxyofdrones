<?php

namespace Koodilab\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfStarted
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param string|null              $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        /** @var \Koodilab\Models\User $user */
        $user = Auth::guard($guard)->user();

        if ($user && $user->isStarted()) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Bad Request.'], 400)
                : redirect()->route('home');
        }

        return $next($request);
    }
}
