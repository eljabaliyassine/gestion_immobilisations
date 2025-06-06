<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon;

class UserActionsController extends Controller
{
    /**
     * Télécharger les données utilisateur
     */
    public function downloadUserData(Request $request): Response
    {
        $user = $request->user();

        // Collecte des données utilisateur
        $userData = [
            'profile' => [
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
            'export_date' => Carbon::now()->format('Y-m-d H:i:s'),
            'export_type' => 'user_data_export'
        ];

        // Vous pouvez ajouter d'autres données selon vos besoins
        // Par exemple : commandes, posts, commentaires, etc.

        $jsonData = json_encode($userData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $fileName = 'user_data_' . $user->id . '_' . Carbon::now()->format('Y_m_d_H_i_s') . '.json';

        return response($jsonData, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    /**
     * Afficher l'historique des connexions
     */
    public function loginHistory(Request $request): View
    {
        // Pour cette fonctionnalité, vous devrez d'abord créer un système de log des connexions
        // Voici un exemple basique - vous devrez adapter selon vos besoins

        $loginHistory = collect([
            [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'login_at' => Carbon::now()->subHours(2),
                'status' => 'success'
            ],
            [
                'ip' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'login_at' => Carbon::now()->subDays(1),
                'status' => 'success'
            ],
            [
                'ip' => '192.168.1.101',
                'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X)',
                'login_at' => Carbon::now()->subDays(3),
                'status' => 'failed'
            ]
        ]);

        return view('user.login-history', [
            'loginHistory' => $loginHistory,
            'user' => $request->user()
        ]);
    }

    /**
     * Centre d'aide
     */
    public function helpCenter(): View
    {
        $helpSections = [
            [
                'title' => 'Gestion du profil',
                'icon' => 'fas fa-user-cog',
                'items' => [
                    'Comment modifier mes informations personnelles ?',
                    'Comment changer mon mot de passe ?',
                    'Comment supprimer mon compte ?'
                ]
            ],
            [
                'title' => 'Sécurité',
                'icon' => 'fas fa-shield-alt',
                'items' => [
                    'Comment sécuriser mon compte ?',
                    'Que faire si mon compte est compromis ?',
                    'Comment activer l\'authentification à deux facteurs ?'
                ]
            ],
            [
                'title' => 'Données personnelles',
                'icon' => 'fas fa-database',
                'items' => [
                    'Comment télécharger mes données ?',
                    'Comment supprimer mes données ?',
                    'Politique de confidentialité'
                ]
            ]
        ];

        return view('user.help-center', [
            'helpSections' => $helpSections
        ]);
    }
}