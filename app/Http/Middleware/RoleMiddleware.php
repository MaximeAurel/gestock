<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Non connecté
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Veuillez vous connecter');
        }

        $user = Auth::user();

        // Aucun rôle lié
        if (!$user->role) {
            abort(403, 'Aucun rôle attribué à cet utilisateur');
        }

        // Normalisation (important)
        $userRole = strtolower(trim($user->role->nom));
        $allowedRoles = array_map(fn($role) => strtolower(trim($role)), $roles);

        // Vérification
        if (!in_array($userRole, $allowedRoles)) {
            abort(403, 'Accès refusé');
        }

        return $next($request);
    }
}
