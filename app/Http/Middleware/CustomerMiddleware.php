<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class CustomerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $role_user = DB::table('roles')->where('id', '=', auth()->user()->role_id)->value('role_name');

        if ($role_user === 'ROLE_CUSTOMER') {
            return $next($request);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
