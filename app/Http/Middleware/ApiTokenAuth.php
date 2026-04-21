<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenAuth
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $header = (string) $request->header('Authorization', '');

        if (! str_starts_with($header, 'Bearer ')) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $token = trim(substr($header, 7));
        if ($token === '') {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $hash = hash('sha256', $token);

        $user = User::query()->where('api_token_hash', $hash)->first();
        if (! $user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        Auth::setUser($user);
        $request->setUserResolver(fn () => $user);

        return $next($request);
    }
}

