<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - Inventory System</title>
    
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect width=%22100%22 height=%22100%22 fill=%22%23f39c12%22 stroke=%22%23222%22 stroke-width=%225%22/><text y=%22.9em%22 font-size=%2280%22 font-family=%22monospace%22 font-weight=%22bold%22 x=%2250%22 text-anchor=%22middle%22 fill=%22%23222%22>I</text></svg>">
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700|Roboto+Mono:400,700" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>

    <style>
        :root {
            --bg: #eff2f5;
            --surface: #ffffff;
            --surface-soft: #f8fafc;
            --ink: #1f2933;
            --ink-soft: #5b6874;
            --line: #d8dee4;
            --sidebar: #1d252c;
            --sidebar-soft: #27323b;
            --accent: #d88a14;
            --danger: #b4382a;
            --success: #2b8a57;
            --radius: 6px;
        }

        body {
            background-color: var(--bg);
            font-family: 'Open Sans', sans-serif;
            color: var(--ink);
            line-height: 1.45;
        }

        .sidebar {
            background: var(--sidebar);
            color: #fff;
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 16.66%;
            z-index: 1000;
            border-right: 1px solid #11181d;
        }

        .sidebar-header {
            padding: 24px 16px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            margin-bottom: 16px;
        }

        .brand-title {
            font-size: 19px;
            font-weight: 800;
            letter-spacing: 0.7px;
            color: #fff;
        }

        .brand-accent {
            color: var(--accent);
        }

        .brand-subtitle {
            font-size: 10px;
            color: #8f9aa3;
            margin-top: 6px;
            letter-spacing: 1.4px;
            font-weight: 700;
        }

        .menu-section {
            padding: 14px 20px 8px;
            font-size: 10px;
            color: #80909d;
            font-weight: 700;
            letter-spacing: 1.2px;
        }

        .sidebar a {
            color: #bac7d1;
            display: block;
            padding: 11px 20px;
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
            border-left: 3px solid transparent;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #182026;
            color: #fff;
            border-left-color: var(--accent);
        }

        .main-content {
            margin-left: 16.66%;
            padding: 26px;
            min-height: 100vh;
        }

        h2 {
            color: var(--ink);
            margin: 0 0 18px 0;
            font-size: 20px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            border-bottom: 2px solid var(--line);
            padding-bottom: 10px;
        }

        .panel {
            border: 1px solid var(--line);
            border-radius: var(--radius);
            background: var(--surface);
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05);
            margin-bottom: 18px;
        }

        .panel-heading,
        .panel-heading-industrial {
            background: var(--surface-soft);
            color: var(--ink);
            font-weight: 700;
            padding: 12px 16px;
            border-bottom: 1px solid var(--line);
            border-radius: var(--radius) var(--radius) 0 0;
        }

        .panel-body {
            padding: 16px;
        }

        .panel-industrial {
            border-top: 2px solid var(--accent);
        }

        .btn {
            border-radius: 4px;
            border: 1px solid transparent;
            font-weight: 700;
            letter-spacing: 0.15px;
            transition: filter 0.2s ease;
        }

        .btn:hover {
            filter: brightness(0.96);
        }

        .btn-primary {
            background: #2f6ea2;
            border-color: #2a5f8b;
        }

        .btn-success {
            background: var(--success);
            border-color: #25724a;
        }

        .btn-danger {
            background: var(--danger);
            border-color: #9d3024;
        }

        .btn-warning {
            background: var(--accent);
            border-color: #bf7810;
            color: #fff;
        }

        .form-control {
            border-radius: 4px;
            height: 36px;
            border: 1px solid var(--line);
            box-shadow: none;
        }

        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 2px rgba(216, 138, 20, 0.14);
        }

        .table > thead > tr > th {
            background: #f3f6f8;
            color: var(--ink);
            border-bottom: 1px solid var(--line);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.2px;
        }

        .table > tbody > tr > td {
            vertical-align: middle;
            border-top: 1px solid #e7edf2;
        }

        .table-dark-head > thead > tr > th {
            background: #22303a;
            color: #f4f7fa;
        }

        .page-actions {
            margin-bottom: 14px;
        }

        .metric-card {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .metric-icon {
            font-size: 34px;
            color: #33424e;
        }

        .metric-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #6d7a86;
            font-weight: 700;
        }

        .metric-value {
            font-size: 26px;
            font-weight: 700;
            color: #22303a;
            font-family: 'Roboto Mono', monospace;
            line-height: 1.15;
        }

        .modal-industrial .modal-content {
            border-radius: 4px;
            border: 1px solid #2a3741;
        }

        .modal-industrial .modal-header {
            background: #22303a;
            color: #fff;
            border-bottom: 1px solid #1b252c;
        }

        .modal-industrial .modal-header .close {
            color: #fff;
            opacity: 0.9;
        }

        .label {
            border-radius: 3px;
            font-weight: 700;
        }

        .badge {
            border-radius: 3px;
            background: #637281;
        }

        .text-muted {
            color: var(--ink-soft);
        }

        @media (max-width: 768px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                min-height: auto;
                padding-bottom: 16px;
            }

            .sidebar-header {
                padding: 14px;
                margin-bottom: 8px;
            }

            .menu-section {
                padding: 10px 14px 6px;
            }

            .sidebar a {
                display: inline-block;
                width: 48%;
                margin-bottom: 6px;
                font-size: 12px;
                border-radius: 4px;
                background: var(--sidebar-soft);
                margin-left: 1%;
                border-left: none;
            }

            .sidebar a[href*="logout"] {
                width: 98%;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 14px;
            }

            h2 {
                font-size: 16px;
                margin-bottom: 14px;
            }

            .col-md-3,
            .col-md-8,
            .col-md-4,
            .col-md-12 {
                width: 100%;
                margin-bottom: 12px;
            }
        }
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="row">

            <div class="col-md-2 sidebar">
                
                <div class="sidebar-header">
                    <div class="brand-title">
                        INVENTORY <span class="brand-accent">SYSTEM AGI</span>
                    </div>
                    <div class="brand-subtitle">
                        VERSION 1.0
                    </div>
                </div>

                @if(Auth::check() && Auth::user()->role == 'admin')
                <a href="{{ route('admin.dashboard') }}" class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">
                    <i class="glyphicon glyphicon-dashboard" style="margin-right: 10px;"></i> Dashboard
                </a>
                
                <a href="{{ route('admin.items.index') }}" class="{{ Request::is('admin/items*') ? 'active' : '' }}">
                    <i class="glyphicon glyphicon-th-list" style="margin-right: 10px;"></i> Master Barang
                </a>
                
                <a href="{{ route('admin.transactions.approval') }}" class="{{ Request::is('admin/approval*') ? 'active' : '' }}">
                    <i class="glyphicon glyphicon-transfer" style="margin-right: 10px;"></i> Transaksi
                    @if(isset($pendingCount) && $pendingCount > 0)
                    <span class="badge pull-right">{{ $pendingCount }}</span>
                    @endif
                </a>
                
                <a href="{{ route('admin.users.index') }}" class="{{ Request::is('admin/users*') ? 'active' : '' }}">
                    <i class="glyphicon glyphicon-user" style="margin-right: 10px;"></i> Manajemen User
                </a>

                <div class="menu-section">
                    LAPORAN & LOG
                </div>

                <a href="{{ route('admin.incoming.index') }}" class="{{ Request::is('admin/stock-in*') ? 'active' : '' }}">
                    <i class="glyphicon glyphicon-import" style="margin-right: 10px;"></i> Stock In
                </a>
                
                <a href="{{ route('admin.stockout.index') }}" class="{{ Request::is('admin/stock-out*') ? 'active' : '' }}">
                    <i class="glyphicon glyphicon-export" style="margin-right: 10px;"></i> Stock Out
                </a>
                
                <a href="{{ route('admin.reports.index') }}" class="{{ Request::is('admin/reports*') ? 'active' : '' }}">
                    <i class="glyphicon glyphicon-file" style="margin-right: 10px;"></i> Laporan PDF
                </a>
                @endif

                @if(Auth::check() && Auth::user()->role == 'user')
                <a href="{{ route('user.dashboard') }}" class="{{ Request::is('user/dashboard') ? 'active' : '' }}">
                    <i class="glyphicon glyphicon-dashboard" style="margin-right: 10px;"></i> Dashboard
                </a>
                <a href="{{ route('user.request.create') }}" class="{{ Request::is('user/request*') ? 'active' : '' }}">
                    <i class="glyphicon glyphicon-plus" style="margin-right: 10px;"></i> Pengajuan Barang
                </a>
                @endif

                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    style="margin-top: 30px; border-top: 1px solid #2c3b41; color: #dd4b39;">
                    <i class="glyphicon glyphicon-off" style="margin-right: 10px;"></i> Logout
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </div>

            <div class="col-md-10 main-content">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    @yield('scripts')
</body>
</html>
