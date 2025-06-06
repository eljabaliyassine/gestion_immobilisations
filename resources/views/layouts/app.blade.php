<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Gestion Immo') }} @hasSection('title') - @yield('title') @endif</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />


    <!-- Styles personnalisés -->
    <style>
        /* Variables CSS personnalisées */
        :root {
            --primary-color: #0a3d62;
            --secondary-color: #2d3748;
            --success-color: #28a745;
            --info-color: #17a2b8;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
        }

        /* Styles généraux */
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f5f8fa;
        }

        /* Navbar personnalisée */
        .navbar-custom {
            background-color: var(--primary-color) !important;
        }


        .navbar-brand {
            font-weight: 700;
            color: white !important;
        }

        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            transition: color 0.3s ease;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 0.25rem;
        }

        .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.1);
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.75%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* Dropdown menu styles */
        .dropdown-menu {
            border-radius: 0.375rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .dropdown-item {
            transition: background-color 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: var(--primary-color);
            color: white;
        }

        .dropdown-item.active {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* Footer styles */
        .footer {
            background-color: var(--light-color);
            border-top: 1px solid #dee2e6;
        }

        /* Alert styles personnalisés */
        .alert {
            border-radius: 0.375rem;
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        /* Main content spacing */
        main {
            min-height: calc(100vh - 160px);
        }

        /* Custom button styles */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #083045;
            border-color: #083045;
        }

        /* Card styles */
        .card {
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        /* Form styles */
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(10, 61, 98, 0.25);
        }

		@media (min-width: 992px) {
            .navbar-custom {
                width: fit-content;
            }
        }

		@media (min-width: 992px) {
			.navbar-expand-lg .navbar-nav {
				flex-direction: row;
				align-items: center;
				white-space: nowrap;
			}
		}

        /* Responsive adjustments */
        @media (max-width: 991.98px) {
            .navbar-nav {
                padding-top: 0.5rem;
            }

            .navbar-nav .nav-link {
                padding: 0.75rem 1rem;
                margin: 0.125rem 0;
                border-radius: 0.375rem;
            }

            .navbar-nav .dropdown-menu {
                position: static !important;
                transform: none !important;
                width: 100%;
                box-shadow: none;
                border: 1px solid rgba(255, 255, 255, 0.1);
                background-color: rgba(255, 255, 255, 0.05);
                margin-top: 0.5rem;
            }

            .navbar-nav .dropdown-item {
                color: rgba(255, 255, 255, 0.8);
                padding: 0.75rem 1.5rem;
            }

            .navbar-nav .dropdown-item:hover {
                background-color: rgba(255, 255, 255, 0.1);
                color: white;
            }

            /* Organisation des menus sur mobile */
            .navbar-collapse {
                margin-top: 1rem;
                padding-top: 1rem;
                border-top: 1px solid rgba(255, 255, 255, 0.1);
            }
        }

        /* Navigation spacing */
        .navbar-nav .nav-item {
            margin: 0 0.25rem;
        }

        .navbar-nav .nav-link {
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            transition: all 0.3s ease;
        }

        /* Dropdown positioning */
        .dropdown-menu {
            margin-top: 0.25rem;
        }

        .dropdown-menu-end {
            --bs-position: end;
        }

        /* Mobile brand text */
        @media (max-width: 575.98px) {
            .navbar-brand span {
                font-size: 0.9rem;
            }
        }
    </style>

    <!-- Additional Styles -->
    @stack('styles')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-dark shadow-sm navbar-custom">
            <div class="container-fluid">
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                    <i class="bi bi-building me-2"></i>
                    <span class="d-none d-sm-inline">{{ config('app.name', 'Gestion Immo') }}</span>
                    <span class="d-sm-none">GI</span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">{{ __('Accueil') }}</a>
                            </li>

                            {{-- Display only if a dossier is selected --}}
                            @if(Auth::user()->current_dossier_id)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarImmobilisationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-house me-1"></i>Immobilisations
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarImmobilisationsDropdown">
                                        <li><a class="dropdown-item" href="{{ route('immobilisations.index') }}">
                                            <i class="bi bi-list me-2"></i>Liste
                                        </a></li>
                                        <li><a class="dropdown-item" href="{{ route('immobilisations.create') }}">
                                            <i class="bi bi-plus-circle me-2"></i>Ajouter
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="{{ route('amortissements.plan') }}">
                                            <i class="bi bi-graph-down me-2"></i>Amortissements
                                        </a></li>
                                    </ul>
                                </li>

                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarContratsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-file-text me-1"></i>Contrats
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarContratsDropdown">
                                        <li><a class="dropdown-item" href="{{ route('contrats.index', ['type' => 'location']) }}">
                                            <i class="bi bi-key me-2"></i>Locations
                                        </a></li>
                                        <li><a class="dropdown-item" href="{{ route('contrats.index', ['type' => 'leasing']) }}">
                                            <i class="bi bi-credit-card me-2"></i>Crédit-Bail
                                        </a></li>
                                        <li><a class="dropdown-item" href="{{ route('contrats.index', ['type' => 'maintenance']) }}">
                                            <i class="bi bi-tools me-2"></i>Maintenances
                                        </a></li>
                                    </ul>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('inventaires.*') ? 'active' : '' }}" href="{{ route('inventaires.index') }}">
                                        <i class="bi bi-clipboard-check me-1"></i>Inventaires
                                    </a>
                                </li>

                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarParametresDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-gear me-1"></i>Paramètres
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarParametresDropdown">
                                        <li><a class="dropdown-item" href="{{ route('parametres.familles.index') }}">
                                            <i class="bi bi-collection me-2"></i>Familles
                                        </a></li>
                                        <li><a class="dropdown-item" href="{{ route('parametres.sites.index') }}">
                                            <i class="bi bi-geo-alt me-2"></i>Sites
                                        </a></li>
                                        <li><a class="dropdown-item" href="{{ route('parametres.services.index') }}">
                                            <i class="bi bi-diagram-3 me-2"></i>Services
                                        </a></li>
                                        <li><a class="dropdown-item" href="{{ route('parametres.fournisseurs.index') }}">
                                            <i class="bi bi-truck me-2"></i>Fournisseurs
                                        </a></li>
                                        <li><a class="dropdown-item" href="{{ route('parametres.prestataires.index') }}">
                                            <i class="bi bi-people me-2"></i>Prestataires
                                        </a></li>
                                        <li><a class="dropdown-item" href="{{ route('parametres.comptescompta.index') }}">
                                            <i class="bi bi-calculator me-2"></i>Comptes Comptables
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="{{ route('home') }}">
                                            <i class="bi bi-app me-2"></i>Application
                                        </a></li>
                                    </ul>
                                </li>

                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarExportsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-download me-1"></i>Exports
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarExportsDropdown">
                                        <li><a class="dropdown-item" href="{{ route('exports.index') }}">
                                            <i class="bi bi-file-excel me-2"></i>Immobilisations (Excel)
                                        </a></li>
                                        <li><a class="dropdown-item" href="{{ route('exports.index') }}">
                                            <i class="bi bi-file-excel me-2"></i>Amortissements (Excel)
                                        </a></li>
                                        <li><a class="dropdown-item" href="{{ route('exports.index') }}">
                                            <i class="bi bi-file-excel me-2"></i>Ecritures Comptables (Excel)
                                        </a></li>
                                    </ul>
                                </li>
                            @endif {{-- End check for selected dossier --}}

                            {{-- Administration Menu for SuperAdmin and AdminClient --}}
                            @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarAdminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-shield-check me-1"></i>Administration
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarAdminDropdown">
                                        @if(Auth::user()->isSuperAdmin())
                                            <li><a class="dropdown-item {{ request()->routeIs('societes.*') ? 'active' : '' }}" href="{{ route('societes.index') }}">
                                                <i class="bi bi-building me-2"></i>Sociétés
                                            </a></li>
                                            <li><a class="dropdown-item {{ request()->routeIs('clients.*') ? 'active' : '' }}" href="{{ route('clients.index') }}">
                                                <i class="bi bi-person-badge me-2"></i>Clients
                                            </a></li>
                                            <li><hr class="dropdown-divider"></li>
                                        @endif
                                        <li><a class="dropdown-item {{ request()->routeIs('dossiers.*') ? 'active' : '' }}" href="{{ route('dossiers.index') }}">
                                            <i class="bi bi-folder me-2"></i>Dossiers
                                        </a></li>
                                        <li><a class="dropdown-item {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                            <i class="bi bi-people me-2"></i>Utilisateurs
                                        </a></li>
                                    </ul>
                                </li>
                            @endif
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link px-3" href="{{ route('login') }}">
                                        <i class="bi bi-box-arrow-in-right me-1"></i>
                                        <span class="d-none d-lg-inline">{{ __('Connexion') }}</span>
                                    </a>
                                </li>
                            @endif
                        @else
                            {{-- Dossier Selector --}}
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle px-3" href="#" id="navbarDossierDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    @if(Auth::user()->currentDossier)
                                        <i class="bi bi-folder-check me-1"></i>
                                        <span class="d-none d-md-inline">{{ Str::limit(Auth::user()->currentDossier->nom, 15) }}</span>
                                        <span class="d-md-none">Dossier</span>
                                    @else
                                        <i class="bi bi-folder-x me-1"></i>
                                        <span class="d-none d-md-inline">{{ __('Sélectionner') }}</span>
                                        <span class="d-md-none">Dossier</span>
                                    @endif
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDossierDropdown">
                                    @forelse (Auth::user()->dossiers()->where('est_cloture', 0)->get() as $dossier)
                                        <li>
                                            <form action="{{ route('dossiers.storeSelection') }}" method="POST" class="d-inline w-100">
                                                @csrf
                                                <input type="hidden" name="dossier_id" value="{{ $dossier->id }}">
                                                <button type="submit" class="dropdown-item {{ Auth::user()->current_dossier_id == $dossier->id ? 'active' : '' }}">
                                                    <i class="bi bi-folder me-2"></i>{{ $dossier->nom }}
                                                </button>
                                            </form>
                                        </li>
                                    @empty
                                        <li><span class="dropdown-item-text">
                                            <i class="bi bi-exclamation-triangle me-2"></i>{{ __('Aucun dossier disponible') }}
                                        </span></li>
                                    @endforelse
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('dossiers.index') }}">
                                        <i class="bi bi-gear me-2"></i>{{ __('Gérer les Dossiers') }}
                                    </a></li>
                                </ul>
                            </li>

                            {{-- User Menu --}}
                            <li class="nav-item dropdown">
                                <a id="navbarUserDropdown" class="nav-link dropdown-toggle px-3" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bi bi-person-circle me-1"></i>
                                    <span class="d-none d-md-inline">{{ Str::limit(Auth::user()->name, 12) }}</span>
                                    <span class="d-md-none">Profil</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarUserDropdown">
                                    {{-- <li><a class="dropdown-item" href="{{ route('home', ['section' => 'profile']) }}"> --}}
                                    <li><a class="dropdown-item" href="{{ route('user.profile.edit') }}">
                                        <i class="bi bi-person me-2"></i>Profil
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i>{{ __('Déconnexion') }}
                                    </a></li>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                {{-- Display Session Messages --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i>{{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Display Validation Errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Erreurs de validation :</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>

            @yield(section: 'content')
        </main>

        <footer class="footer mt-auto py-4 bg-light border-top">
            <div class="container">
                <div class="row align-items-center text-center text-md-start">
                    <div class="col-12 col-md-6 mb-2 mb-md-0">
                        <span class="text-muted d-block d-md-inline">
                            <i class="bi bi-c-circle me-1"></i>{{ date('Y') }} {{ config('app.name', 'Gestion Immo') }}. Tous droits réservés.
                        </span>
                    </div>
                    <div class="col-12 col-md-6 text-center text-md-end">
                        <small class="text-muted d-block d-md-inline">
                            <i class="bi bi-code-slash me-1"></i>Version 1.0
                        </small>
                    </div>
                </div>
            </div>
        </footer>

    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!-- Additional Scripts -->
    @yield('scripts')
    @stack('scripts')
</body>
</html>
