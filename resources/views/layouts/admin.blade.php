<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kost Sejahtera') | Admin Panel</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        :root {
            --yellow:       #E6B800;
            --yellow-light: #FFF8DC;
            --yellow-bg:    #FFFBEB;
            --dark:         #111827;
            --dark-sub:     #374151;
            --gray:         #6B7280;
            --gray-light:   #E5E7EB;
            --bg:           #F3F4F6;
            --green:        #16A34A;
            --green-light:  #D1FAE5;
            --red:          #DC2626;
            --red-light:    #FEE2E2;
            --blue:         #2563EB;
            --blue-light:   #DBEAFE;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            background: var(--bg);
            color: var(--dark);
        }

        /* ── Navbar ──────────────────────────── */
        .top-navbar {
            background: #fff;
            border-bottom: 1px solid var(--gray-light);
            height: 64px;
            position: sticky;
            top: 0;
            z-index: 1030;
            padding: 0 32px;
        }
        .navbar-brand-text {
            font-weight: 900;
            font-size: 1.1rem;
            color: var(--dark);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .brand-icon {
            background: var(--yellow);
            border-radius: 8px;
            padding: 6px 9px;
            font-size: 1.2rem;
            line-height: 1;
        }
        .nav-link-custom {
            color: var(--gray);
            font-weight: 500;
            font-size: 0.9rem;
            padding: 8px 14px !important;
            border-radius: 8px;
            transition: all .15s;
            text-decoration: none;
        }
        .nav-link-custom:hover, .nav-link-custom.active {
            color: var(--dark);
            font-weight: 700;
            background: var(--bg);
        }
        .btn-logout {
            background: var(--yellow);
            color: var(--dark);
            border: none;
            border-radius: 24px;
            padding: 8px 20px;
            font-weight: 700;
            font-size: 0.875rem;
            cursor: pointer;
            transition: opacity .15s;
        }
        .btn-logout:hover { opacity: .85; }

        /* ── Cards ───────────────────────────── */
        .card-kost {
            background: #fff;
            border-radius: 14px;
            padding: 24px;
            box-shadow: 0 1px 6px rgba(0,0,0,.07);
            border: none;
        }
        .card-stat {
            border-radius: 14px;
            padding: 24px;
            border: none;
        }

        /* ── Badges ──────────────────────────── */
        .badge-available    { background: var(--green-light); color: var(--green); }
        .badge-occupied     { background: var(--red-light);   color: var(--red);   }
        .badge-maintenance  { background: #FEF3C7;            color: #92400E;      }
        .badge-paid         { background: var(--green-light); color: var(--green); }
        .badge-unpaid       { background: var(--red-light);   color: var(--red);   }
        .badge-overdue      { background: #FEE2E2;            color: #991B1B;      }
        .badge-active       { background: var(--green-light); color: var(--green); }
        .badge-alumni       { background: var(--bg);          color: var(--gray);  }
        .badge-kost {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        /* ── Buttons ─────────────────────────── */
        .btn-kost-primary {
            background: var(--dark);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 9px 18px;
            font-weight: 700;
            font-size: 0.875rem;
            transition: opacity .15s;
        }
        .btn-kost-primary:hover { opacity: .85; color: #fff; }
        .btn-kost-yellow {
            background: var(--yellow);
            color: var(--dark);
            border: none;
            border-radius: 8px;
            padding: 9px 18px;
            font-weight: 700;
            font-size: 0.875rem;
            transition: opacity .15s;
        }
        .btn-kost-yellow:hover { opacity: .85; color: var(--dark); }
        .btn-icon {
            border: none;
            border-radius: 7px;
            padding: 6px 9px;
            font-size: .85rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: opacity .15s;
        }
        .btn-icon:hover { opacity: .8; }

        /* ── Table ───────────────────────────── */
        .table-kost th {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: var(--gray);
            border-bottom: 2px solid var(--bg);
            padding: 10px 12px;
            background: #fff;
        }
        .table-kost td {
            padding: 14px 12px;
            border-bottom: 1px solid var(--bg);
            vertical-align: middle;
        }
        .table-kost tbody tr:hover { background: #FAFAFA; }

        /* ── Form ────────────────────────────── */
        .form-kost .form-label {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--gray);
            margin-bottom: 6px;
        }
        .form-kost .form-control, .form-kost .form-select {
            border: 1.5px solid var(--gray-light);
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 0.875rem;
            background: #F9FAFB;
            font-family: inherit;
        }
        .form-kost .form-control:focus, .form-kost .form-select:focus {
            border-color: var(--yellow);
            box-shadow: 0 0 0 3px rgba(230,184,0,.15);
        }

        /* ── Alert ───────────────────────────── */
        .alert-kost-due {
            background: var(--yellow-bg);
            border: 1.5px solid var(--yellow);
            border-radius: 12px;
            padding: 18px 24px;
        }

        /* ── Page header ─────────────────────── */
        .page-header h1 {
            font-size: 1.6rem;
            font-weight: 900;
            margin: 0;
        }
        .page-header p { color: var(--gray); margin: 4px 0 0; font-size: .9rem; }

        /* ── Room card ───────────────────────── */
        .room-card {
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 1px 6px rgba(0,0,0,.07);
            transition: transform .2s, box-shadow .2s;
        }
        .room-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,.1); }
        .room-card-img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            background: var(--bg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
        }

        /* ── Due alert row ───────────────────── */
        .due-overdue { color: var(--red); font-weight: 700; }
        .due-warning { color: #D97706; font-weight: 700; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg); }
        ::-webkit-scrollbar-thumb { background: var(--gray-light); border-radius: 3px; }
    </style>

    @stack('styles')
</head>
<body>

{{-- TOP NAVBAR --}}
<nav class="top-navbar d-flex align-items-center justify-content-between">
    <a href="{{ route('dashboard') }}" class="navbar-brand-text">
        <span class="brand-icon">🏠</span>
        KOST SEJAHTERA
    </a>
    <div class="d-flex align-items-center gap-1">
        <a href="{{ route('dashboard') }}" class="nav-link-custom {{ request()->routeIs('dashboard') ? 'active' : '' }}">Overview</a>
        <a href="{{ route('rooms.index') }}" class="nav-link-custom {{ request()->routeIs('rooms.*') ? 'active' : '' }}">Kamar</a>
        <a href="{{ route('tenants.index') }}" class="nav-link-custom {{ request()->routeIs('tenants.*') ? 'active' : '' }}">Penghuni</a>
        <a href="{{ route('rentals.index') }}" class="nav-link-custom {{ request()->routeIs('rentals.*') ? 'active' : '' }}">Sewa</a>
        <a href="{{ route('payments.index') }}" class="nav-link-custom {{ request()->routeIs('payments.*') ? 'active' : '' }}">Keuangan</a>
        <a href="{{ route('expenses.index') }}" class="nav-link-custom {{ request()->routeIs('expenses.*') ? 'active' : '' }}">Pengeluaran</a>
        <a href="{{ route('reports.index') }}" class="nav-link-custom {{ request()->routeIs('reports.*') ? 'active' : '' }}">Laporan</a>
    </div>
    <form action="{{ route('logout') }}" method="POST" class="mb-0">
        @csrf
        <button type="submit" class="btn-logout">↩ Logout</button>
    </form>
</nav>

{{-- FLASH MESSAGES --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mx-4 mt-3 mb-0" role="alert" style="border-radius:10px;border:none;background:#D1FAE5;color:#166534;">
    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show mx-4 mt-3 mb-0" role="alert" style="border-radius:10px;border:none;background:#FEE2E2;color:#991B1B;">
    <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- MAIN CONTENT --}}
<main>
    @yield('content')
</main>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

@stack('scripts')
</body>
</html>
