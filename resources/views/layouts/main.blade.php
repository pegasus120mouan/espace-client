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
            background: linear-gradient(180deg, #1a1a2e 0%, #16213e 100%);
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.15);
        }

        .brand-link {
            border-bottom: 1px solid rgba(255,255,255,0.08) !important;
            padding: 1rem !important;
        }

        .brand-link .brand-text {
            font-size: 1.1rem;
            letter-spacing: 0.5px;
        }

        .nav-sidebar .nav-link {
            border-radius: 8px;
            margin: 4px 12px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .nav-sidebar .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%) !important;
            color: #fff !important;
            box-shadow: 0 4px 15px rgba(121, 150, 89, 0.4);
        }

        .nav-sidebar .nav-link:hover:not(.active) {
            background: rgba(255,255,255,0.08);
            transform: translateX(5px);
        }

        .nav-sidebar .nav-icon {
            font-size: 1.1rem;
            width: 25px;
            text-align: center;
        }

        .nav-header {
            padding: 15px 25px 8px !important;
            margin-top: 10px;
            font-size: 11px !important;
            font-weight: 600;
            letter-spacing: 1.5px;
            color: #5a7a8a !important;
            border-top: 1px solid rgba(255,255,255,0.05);
        }

        .nav-header:first-child {
            border-top: none;
            margin-top: 0;
        }

        .user-panel {
            background: rgba(255,255,255,0.03);
            border-radius: 12px;
            margin: 15px 12px !important;
            padding: 15px !important;
            border: 1px solid rgba(255,255,255,0.05);
        }

        .user-panel .image > div {
            width: 45px !important;
            height: 45px !important;
            font-size: 16px !important;
            box-shadow: 0 4px 10px rgba(54, 181, 143, 0.3);
        }

        .user-panel .info a {
            font-weight: 600;
            font-size: 14px;
        }

        .user-panel .info small {
            font-size: 12px;
            opacity: 0.8;
        }

        .nav-treeview {
            background: rgba(0,0,0,0.15);
            border-radius: 8px;
            margin: 5px 12px;
            padding: 8px 0;
        }

        .nav-treeview .nav-link {
            padding: 10px 15px;
            margin: 2px 8px;
            font-size: 13px;
        }

        .sidebar-mini .main-sidebar .nav-link p {
            font-weight: 500;
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
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('commandes.index') }}" class="nav-link">Mes Colis</a>
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
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="{{ route('dashboard') }}" class="brand-link text-center">
            <span class="brand-text font-weight-bold text-white">
                <i class="fas fa-truck-fast mr-2" style="color: #36b58f;"></i>OVL <span style="color: #36b58f;">Partner</span>
            </span>
        </a>

        <div class="sidebar">
            <div class="user-panel d-flex align-items-center">
                <div class="image">
                    <div class="img-circle elevation-3 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #36b58f 0%, #2a9d7c 100%); color: #fff; font-weight: bold;">
                        {{ strtoupper(substr(session('client.nom', 'U'), 0, 1)) }}
                    </div>
                </div>
                <div class="info ml-2">
                    <a href="#" class="text-white d-block">{{ session('client.nom') }} {{ session('client.prenoms') }}</a>
                    <small class="d-block" style="color: #36b58f;"><i class="fas fa-store mr-1"></i>{{ session('boutique.nom') ?? 'Boutique' }}</small>
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

                    <li class="nav-item">
                        <a href="{{ route('reclamations.index') }}" class="nav-link {{ request()->routeIs('reclamations.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-exclamation-triangle" style="color: #ffc107;"></i>
                            <p>Mes Réclamations</p>
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
