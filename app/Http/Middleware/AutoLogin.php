<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AutoLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = User::whereName('Test User')->first();

        if ($user) {
            Auth::login($user);
        } else {
            return \response()->json(['error' => 'User not found. Did you run migrations?']);
        }

        return $next($request);
    }
}
