<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('title') - Inventory System</title>
    <link rel="icon"
        href="data:image/svg    +xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect width=%22100%22 height=%22100%22 fill=%22%23f39c12%22 stroke=%22%23222%22 stroke-width=%225%22/><text y=%22.9em%22 font-size=%2280%22 font-family=%22monospace%22 font-weight=%22bold%22 x=%2250%22 text-anchor=%22middle%22 fill=%22%23222%22>I</text></svg>">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700|Roboto+Mono:400,700" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>

    <style>
        /* === INDUSTRIAL THEME CSS (GLOBAL) === */
        body {
            background-color: #e8e8e8;
            /* Abu Beton */
            font-family: 'Roboto', sans-serif;
            color: #333;
        }

        /* RESET KOTAK (SEMUA JADI TAJAM) */
        .btn,
        .panel,
        .form-control,
        .well,
        .alert,
        .modal-content,
        .label,
        .badge {
            border-radius: 0 !important;
        }

        /* SIDEBAR STYLE */
        .sidebar {
            background: #222;
            /* Hitam Logam */
            color: #fff;
            min-height: 100vh;
            padding-top: 20px;
            border-right: 4px solid #f39c12;
            /* Kuning Safety */
            position: fixed;
            /* Biar sidebar diem pas discroll */
            left: 0;
            top: 0;
            bottom: 0;
            width: 16.66666667%;
            /* Setara col-md-2 */
            z-index: 1000;
        }

        .sidebar h3 {
            font-family: 'Roboto Mono', monospace;
            text-align: center;
            font-weight: bold;
            letter-spacing: 2px;
            margin-bottom: 30px;
            border-bottom: 2px dashed #555;
            padding-bottom: 15px;
        }

        .sidebar a {
            color: #ccc;
            display: block;
            padding: 15px 20px;
            text-decoration: none;
            text-transform: uppercase;
            font-weight: bold;
            border-bottom: 1px solid #333;
            transition: 0.3s;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #f39c12;
            color: #000;
        }

        /* MAIN CONTENT STYLE */
        .main-content {
            margin-left: 16.66666667%;
            /* Geser konten ke kanan biar ga ketutup sidebar */
            padding: 20px;
            min-height: 100vh;
        }

        /* PANEL INDUSTRIAL */
        .panel-industrial {
            border: 2px solid #444;
            box-shadow: 5px 5px 0px rgba(0, 0, 0, 0.2);
            background: #fff;
            margin-bottom: 20px;
        }

        .panel-heading-industrial {
            background: #444;
            color: #fff;
            padding: 10px 15px;
            font-family: 'Roboto Mono', monospace;
            text-transform: uppercase;
            font-weight: bold;
            border-bottom: 2px solid #000;
        }

        /* TYPOGRAPHY */
        h1,
        h2,
        h3,
        h4,
        th {
            font-family: 'Roboto Mono', monospace;
        }

        .data-number {
            font-family: 'Roboto Mono', monospace;
            font-weight: bold;
        }

        /* TOMBOL KEREN */
        .btn-industrial {
            border: 2px solid #000;
            font-weight: bold;
            text-transform: uppercase;
            box-shadow: 3px 3px 0px #000;
            transition: 0.2s;
        }

        .btn-industrial:active {
            transform: translate(2px, 2px);
            box-shadow: 1px 1px 0px #000;
        }
    </style>

</head>

<body>

    <div class="container-fluid">
        <div class="row">

            <div class="col-md-2 sidebar">
                <h3 class="text-center" style="font-family:'Roboto Mono'; border-bottom: 2px dashed #555; padding-bottom:15px;">
                    INVENTORY<br><span style="color:#f39c12; font-size:14px;">SYSTEM v1.0</span>
                </h3>

                @if(Auth::check() && Auth::user()->role == 'admin')
                <a href="{{ route('admin.dashboard') }}" class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('admin.items.index') }}" class="{{ Request::is('admin/items*') ? 'active' : '' }}">Master Barang</a>
                <a href="{{ route('admin.transactions.approval') }}" class="{{ Request::is('admin/approval*') ? 'active' : '' }}">
                    Transaksi
                    @if(isset($pendingCount) && $pendingCount > 0)
                    <span class="badge pull-right" style="background:red; font-family: 'Roboto Mono';">{{ $pendingCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.users.index') }}" class="{{ Request::is('admin/users*') ? 'active' : '' }}">Manajemen User</a>
                <a href="{{ route('admin.reports.index') }}" class="{{ Request::is('admin/reports*') ? 'active' : '' }}">Laporan</a>
                
                @endif

                @if(Auth::check() && Auth::user()->role == 'user')
                <a href="{{ route('user.dashboard') }}" class="{{ Request::is('user/dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('user.request.create') }}" class="{{ Request::is('user/request*') ? 'active' : '' }}">Pengajuan Barang</a>
                @endif

                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    style="margin-top: 50px; border-top: 2px solid #555;">Logout</a>

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