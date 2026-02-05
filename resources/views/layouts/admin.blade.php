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
        /* 1. LAYOUT UTAMA */
        body {
            background-color: #ecf0f5; /* Abu muda */
            font-family: 'Open Sans', sans-serif;
            color: #333;
        }

        /* 2. SIDEBAR */
        .sidebar {
            background: #222d32; /* Hitam Kebiruan */
            color: #fff;
            min-height: 100vh;
            position: fixed;
            left: 0; top: 0; bottom: 0;
            width: 16.66%;
            z-index: 1000;
            padding-top: 0;
        }

        /* === PERUBAHAN DI SINI: HEADER SIDEBAR TRANSPARAN & ELEGAN === */
        .sidebar-header {
            background-color: transparent; /* Tidak ada kotak oranye lagi */
            padding: 25px 15px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1); /* Garis pemisah tipis */
            margin-bottom: 20px;
        }

        /* LINK MENU SIDEBAR */
        .sidebar a {
            color: #b8c7ce;
            display: block;
            padding: 12px 20px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            border-left: 3px solid transparent;
            transition: 0.3s;
        }

        .sidebar a:hover, .sidebar a.active {
            background: #1e282c;
            color: #fff;
            border-left: 3px solid #f39c12; /* Indikator Aktif */
        }

        /* 3. KONTEN UTAMA */
        .main-content {
            margin-left: 16.66%;
            padding: 30px;
            min-height: 100vh;
        }

        /* 4. HEADER HALAMAN (Judul Halaman Tetap Oranye) */
        h2 {
            background-color: #f39c12;
            color: #fff;
            padding: 15px 20px;
            margin: 0 0 25px 0;
            font-size: 18px;
            font-weight: 600;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-transform: uppercase;
            border: none;
        }
        
        h2 .btn {
            margin-top: -5px;
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.4);
            color: #fff;
        }
        h2 .btn:hover { background: #fff; color: #f39c12; }

        /* 5. PANEL & CARDS */
        .panel {
            border: none; border-radius: 5px;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .panel-heading, .panel-heading-industrial {
            background: #fff; color: #444;
            font-weight: 700; padding: 15px 20px;
            border-bottom: 1px solid #f4f4f4;
            border-radius: 5px 5px 0 0;
        }
        .panel-body { padding: 20px; }

        /* 6. TOMBOL & FORM */
        .btn { border-radius: 3px; border: none; font-weight: 600; padding: 6px 12px; }
        .btn-primary { background: #3c8dbc; }
        .btn-success { background: #00a65a; }
        .btn-danger { background: #dd4b39; }
        .btn-warning { background: #f39c12; color: #fff; }
        .form-control { border-radius: 3px; height: 34px; border: 1px solid #d2d6de; box-shadow: none; }
        .form-control:focus { border-color: #f39c12; }

        /* 7. WIDGET WARNA */
        .panel-industrial { border-top: 3px solid #d2d6de; }
        .panel-industrial:nth-child(1) { border-top-color: #00c0ef; }
        .panel-industrial:nth-child(2) { border-top-color: #dd4b39; }
        .panel-industrial:nth-child(3) { border-top-color: #00a65a; }
        .panel-industrial:nth-child(4) { border-top-color: #f39c12; }
        
        /* Utility */
        .badge { background: #dd4b39; border-radius: 4px; }
        .text-muted { font-size: 12px; }
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="row">

            <div class="col-md-2 sidebar">
                
                <div class="sidebar-header">
                    <div style="font-size: 20px; font-weight: 800; letter-spacing: 1px; color: #fff;">
                        INVENTORY <span style="color: #f39c12;">SYSTEM AGI</span>
                    </div>
                    <div style="font-size: 10px; color: #6c7b88; margin-top: 5px; letter-spacing: 2px; font-weight: 600;">
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

                <div style="padding: 15px 20px; font-size: 11px; color: #5b6e79; margin-top: 10px; font-weight: bold; letter-spacing: 1px;">
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