<?php

namespace App\Http\Middleware;

use App\Services\MenuRoleService;
use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MenuPermission
{
    public function __construct(protected MenuRoleService $service)
    { }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $currentRouteName = $request->route()->getName();
            if (!$this->service->hasAccess(auth_role(), $currentRouteName)) {
                abort(403);
            }
        }
        return $next($request);
    }
}
