<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Espace Partenaire | @yield('title', 'Dashboard')</title>

    <link rel="icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>

    <style>
        :root {
            --primary-color: #799659;
            --primary-dark: #5a7342;
        }

        .main-sidebar {
            background: linear-gradient(180deg, #2c3e50 0%, #1a252f 100%);
        }

        .brand-link {
            border-bottom: 1px solid rgba(255,255,255,0.1) !important;
        }

        .nav-sidebar .nav-link.active {
            background: var(--primary-color) !important;
            color: #fff !important;
        }

        .nav-sidebar .nav-link:hover {
            background: rgba(255,255,255,0.1);
        }

        .small-box {
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .small-box .inner h3 {
            font-weight: 700;
        }

        .content-wrapper {
            background: #f4f6f9;
        }

        .ovl-panel-title {
            background: #3f4f59;
            color: #ffffff;
            font-weight: 700;
            letter-spacing: 0.06em;
            padding: 10px 14px;
            border-radius: 6px 6px 0 0;
            text-transform: uppercase;
            font-size: 14px;
        }

        .ovl-panel-body {
            background: #ffffff;
            border: 1px solid rgba(0,0,0,0.07);
            border-top: 0;
            padding: 14px;
            border-radius: 0 0 6px 6px;
        }

        .ovl-kpi {
            border: 1px solid rgba(0,0,0,0.07);
            background: #ffffff;
            padding: 14px;
            height: 100%;
            border-radius: 6px;
        }

        .ovl-kpi .ovl-kpi-label {
            font-size: 11px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 6px;
        }

        .ovl-kpi .ovl-kpi-value {
            font-size: 26px;
            font-weight: 700;
            line-height: 1.1;
        }

        .ovl-donut-wrap {
            position: relative;
            height: 170px;
        }

        .ovl-list-compact {
            margin: 0;
            padding-left: 0;
            list-style: none;
        }

        .ovl-list-compact li {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            padding: 3px 0;
            border-bottom: 1px solid rgba(0,0,0,0.04);
            font-size: 13px;
        }

        .ovl-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 6px;
        }

        .user-panel .info a {
            color: #fff !important;
        }
    </style>

    @yield('styles')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('dashboard') }}" class="nav-link">Tableau de bord</a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link text-danger" href="{{ route('logout') }}">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #1a1a2e;">
        <a href="{{ route('dashboard') }}" class="brand-link text-center" style="background-color: #16213e; border-bottom: 1px solid #0f3460;">
            <span class="brand-text font-weight-bold text-white">
                <i class="fas fa-handshake mr-2"></i>Espace Partenaire
            </span>
        </a>

        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <div class="img-circle elevation-2 d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; background-color: #36b58f; color: #fff; font-weight: bold; font-size: 14px;">
                        {{ strtoupper(substr(session('client.nom', 'U'), 0, 1)) }}
                    </div>
                </div>
                <div class="info">
                    <a href="#" class="text-white">{{ session('client.nom') }} {{ session('client.prenoms') }}</a>
                    <small class="d-block" style="color: #8aa4af;">{{ session('boutique.nom') ?? 'Boutique' }}</small>
                </div>
            </div>

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent nav-compact" data-widget="treeview" role="menu" data-accordion="false">
                    
                    <li class="nav-header text-uppercase" style="font-size: 10px; letter-spacing: 1px; color: #8aa4af;">Tableau de bord</li>
                    
                    <li class="nav-item {{ request()->routeIs('dashboard') || request()->routeIs('dashboard.annee') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('dashboard') || request()->routeIs('dashboard.annee') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-pie" style="color: #36b58f;"></i>
                            <p>
                                Analytics
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') && !request()->routeIs('dashboard.annee') ? 'active' : '' }}">
                                    <i class="fas fa-calendar-day nav-icon" style="color: #17a2b8;"></i>
                                    <p>Ce mois</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('dashboard.annee') }}" class="nav-link {{ request()->routeIs('dashboard.annee') ? 'active' : '' }}">
                                    <i class="fas fa-calendar-alt nav-icon" style="color: #6f42c1;"></i>
                                    <p>Cette année</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-header text-uppercase" style="font-size: 10px; letter-spacing: 1px; color: #8aa4af;">Gestion</li>

                    <li class="nav-item {{ request()->routeIs('commandes.*') && !request()->routeIs('commandes.points-valides') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('commandes.*') && !request()->routeIs('commandes.points-valides') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-boxes" style="color: #fd7e14;"></i>
                            <p>
                                Mes Colis
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('commandes.index') }}" class="nav-link {{ request()->routeIs('commandes.index') && !request('statut') ? 'active' : '' }}">
                                    <i class="fas fa-list nav-icon" style="color: #6c757d;"></i>
                                    <p>Tous les colis</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('commandes.index', ['statut' => 'Livré']) }}" class="nav-link {{ request('statut') == 'Livré' ? 'active' : '' }}">
                                    <i class="fas fa-check-circle nav-icon" style="color: #28a745;"></i>
                                    <p>Livrés</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('commandes.index', ['statut' => 'Non Livré']) }}" class="nav-link {{ request('statut') == 'Non Livré' || request('statut') == 'Non livré' ? 'active' : '' }}">
                                    <i class="fas fa-times-circle nav-icon" style="color: #dc3545;"></i>
                                    <p>Non livrés</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('commandes.points-valides') }}" class="nav-link {{ request()->routeIs('commandes.points-valides') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-clipboard-check" style="color: #28a745;"></i>
                            <p>Points validés</p>
                        </a>
                    </li>

                    <li class="nav-header text-uppercase" style="font-size: 10px; letter-spacing: 1px; color: #8aa4af;">Compte</li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-user-circle" style="color: #6c757d;"></i>
                            <p>Mon Profil</p>
                        </a>
                    </li>

                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">@yield('page_title', 'Tableau de bord')</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
                            <li class="breadcrumb-item active">@yield('page_title', 'Tableau de bord')</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
                <div class="container-fluid">
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                </div>
            @endif

            @yield('content')
        </section>
    </div>

    <footer class="main-footer text-center">
        <strong>&copy; {{ date('Y') }} OVL Delivery</strong> - Espace Boutiques
    </footer>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

@yield('scripts')
</body>
</html>
