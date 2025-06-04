<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class EnsureDossierIsSelected
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Allow access if user is not logged in (handled by auth middleware)
        if (!$user) {
            return $next($request);
        }

        // Si l'utilisateur a déjà un dossier sélectionné, on continue
        if ($user->current_dossier_id) {
            // Stocker l'ID du dossier en session pour référence facile
            Session::put("current_dossier_id", $user->current_dossier_id);
            return $next($request);
        }

        // Ces routes sont toujours accessibles même sans dossier sélectionné
        $allowedRoutes = [
            "dossiers.select",
            "dossiers.storeSelection",
            "dossiers.index",
            "dossiers.create",
            "dossiers.store",
            "dossiers.show",
            "dossiers.edit",
            "dossiers.update",
            "dossiers.destroy",
            "clients.index",
            "clients.create",
            "clients.store",
            "clients.show",
            "clients.edit",
            "clients.update",
            "clients.destroy",
            "societes.index",
            "societes.create",
            "societes.store",
            "societes.show",
            "societes.edit",
            "societes.update",
            "societes.destroy",
            "users.index",
            "users.create",
            "users.store",
            "users.show",
            "users.edit",
            "users.update",
            "users.destroy",
            "users.update_dossiers"
        ];

        // Si la route actuelle est dans la liste des routes autorisées, on continue
        if (in_array($request->route()->getName(), $allowedRoutes)) {
            return $next($request);
        }

        // Si l'utilisateur a accès à au moins un dossier, on le redirige vers la page de sélection
        if ($user->dossiers()->exists()) {
            return redirect()->route("dossiers.select")
                ->with("warning", "Veuillez sélectionner un dossier pour continuer.");
        }

        // Si l'utilisateur n'a accès à aucun dossier, on le déconnecte
        Auth::logout();
        return redirect("/")->with("error", "Vous n'avez accès à aucun dossier.");
    }
}
