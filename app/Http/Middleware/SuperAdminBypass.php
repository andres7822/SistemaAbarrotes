<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class SuperAdminBypass
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();

        //Condición PRO: usuario ID=1 O rol ID=1
        $isSuperById = $user->id == 1;

        // Obtenemos roles del usuario via Spatie
        $role = $user->roles->first();  // El primer rol asignado

        $isSuperByRoleId = ($role && $role->id == 1);

        //Si cumple cualquiera -> bypass total
        if ($isSuperById || $isSuperByRoleId) {

            // Sobreescribir middlewares de Spatie para que siempre permitan
            app()->singleton(\Spatie\Permission\Middlewares\PermissionMiddleware::class, function () {
                return new class {
                    public function handle($request, Closure $next, $permission, $guard = null)
                    {
                        return $next($request);
                    }
                };
            });

            app()->singleton(\Spatie\Permission\Middlewares\RoleMiddleware::class, function () {
                return new class {
                    public function handle($request, Closure $next, $role, $guard = null)
                    {
                        return $next($request);
                    }
                };
            });
        }

        return $next($request);
    }
}
