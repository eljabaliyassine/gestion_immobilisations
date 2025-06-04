<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Middleware applied via routes (auth, dossier.selected)
        // $this->middleware(["auth", "dossier.selected"]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(): View
    {
        $user = Auth::user();
        $dossier = $user->currentDossier; // Get the currently selected dossier

        // You can fetch dashboard-specific data here based on the selected dossier
        // $stats = [...];

        return view("home", compact("user", "dossier"));
    }
}
