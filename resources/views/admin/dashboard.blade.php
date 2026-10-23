<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Dashboard | Sengked.com</title>

    <!-- Google Font -->

    <link rel="preconnect"
          href="https://fonts.googleapis.com">

    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    <!-- Bootstrap -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- Bootstrap Icons -->

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- Global CSS -->

    <link rel="stylesheet"
      href="{{ asset('assets/css/global.css') }}">

    <!-- Dashboard CSS -->

    <link rel="stylesheet"
      href="{{ asset('assets/css/admin/dashboard.css') }}">

    @include('partials.head-assets')

</head>

<body>

<div class="dashboard-wrapper">

    <!-- ===================== SIDEBAR ===================== -->

    <aside class="sidebar">

        <div class="sidebar-logo">

            @include('partials.brand-logo')


        </div>

                <ul class="sidebar-menu">

            <li>

                <a href="{{ route('admin.dashboard') }}"
                   class="active">

                    <i class="bi bi-speedometer2"></i>

                    Dashboard

                </a>

            </li>

            <li>

                <a href="{{ route('admin.visitor') }}">

                    <i class="bi bi-people-fill"></i>

                    Data Visitor

                </a>

            </li>

            <li>

                <a href="{{ route('admin.visitor.active') }}">

                    <i class="bi bi-person-check-fill"></i>

                    Visitor Aktif

                </a>

            </li>
            <li>
                <a href="{{ route('admin.blacklist') }}">
                    <i class="bi bi-slash-circle-fill"></i>
                    Blacklist
                </a>
            </li>

            <li>

                <a href="{{ route('admin.laporan') }}">

                    <i class="bi bi-file-earmark-bar-graph-fill"></i>

                    Laporan

                </a>

            </li>

            <li>

                <a href="{{ route('admin.profile') }}">

                    <i class="bi bi-person-circle"></i>

                    Profile

                </a>

            </li>

            <li>

                <a href="{{ route('admin.setting') }}">

                    <i class="bi bi-gear-fill"></i>

                    Setting

                </a>

            </li>

            <li>

                <a href="{{ route('logout') }}">

                    <i class="bi bi-box-arrow-left"></i>

                    Logout

                </a>

            </li>

        </ul>

    </aside>

    <!-- ===================== CONTENT ===================== -->

    <main class="main-content" id="dashboardContent" data-poll-url="{{ route('admin.dashboard.data') }}">
        <!-- ===================== TOPBAR ===================== -->

<header class="topbar">

    <div>

        <h3>

            Dashboard

        </h3>

        <p>

            Selamat datang kembali, Administrator.

        </p>

    </div>

    <div class="d-flex align-items-center gap-3">

        <button class="theme-toggle" type="button" title="Ganti tema">
            <i class="theme-toggle-icon bi bi-moon-stars-fill"></i>
        </button>

        <div class="admin-profile">

            <i class="bi bi-person-circle"></i>

            <span>

                Admin

            </span>

        </div>

    </div>

</header>

<!-- ===================== STATISTIC ===================== -->

<div class="row g-4 mb-4">

    <!-- Total Visitor -->

    <div class="col-xl-3 col-md-6">

        <div class="dashboard-card">

            <div class="card-icon bg-primary">

                <i class="bi bi-people-fill"></i>

            </div>

            <div>

                <small>

                    Total Visitor

                </small>

                <h3 id="totalVisitor">

                    {{ $totalVisitor }}

                </h3>

            </div>

        </div>

    </div>

    <!-- Visitor Aktif -->

    <div class="col-xl-3 col-md-6">

        <div class="dashboard-card">

            <div class="card-icon bg-success">

                <i class="bi bi-person-check-fill"></i>

            </div>

            <div>

                <small>

                    Visitor Aktif

                </small>

                <h3 id="activeVisitor">

                    {{ $visitorAktif }}

                </h3>

            </div>

        </div>

    </div>

    <!-- Check In Hari Ini -->

    <div class="col-xl-3 col-md-6">

        <div class="dashboard-card">

            <div class="card-icon bg-warning">

                <i class="bi bi-box-arrow-in-right"></i>

            </div>

            <div>

                <small>

                    Check In Hari Ini

                </small>

                <h3 id="todayCheckin">

                     {{ $visitorHariIni }}

                </h3>

            </div>

        </div>

    </div>

    <!-- Check Out Hari Ini -->

    <div class="col-xl-3 col-md-6">

        <div class="dashboard-card">

            <div class="card-icon bg-danger">

                <i class="bi bi-box-arrow-left"></i>

            </div>

            <div>

                <small>

                    Check Out Hari Ini

                </small>

                <h3 id="todayCheckout">

                    {{ $visitorKeluarHariIni }}

                </h3>

            </div>

        </div>

    </div>

</div>

<!-- ===================== VISITOR TERBARU ===================== -->

<div class="card shadow-sm border-0 mt-4">

    <div class="card-header bg-white">

        <h5 class="mb-0">

            Visitor Terbaru

        </h5>

    </div>

    <div class="table-responsive">

        <table class="table table-hover align-middle mb-0">

            <thead class="table-light">

                <tr>

                    <th>Nama</th>

                    <th>Perusahaan</th>

                    <th>Tujuan</th>

                    <th>Check In</th>

                    <th>Status</th>

                </tr>

            </thead>

            <tbody id="visitorTerbaruTable">

                @forelse($visitorTerbaru as $visitor)

                <tr>

                    <td>{{ $visitor->nama }}</td>

                    <td>{{ $visitor->perusahaan }}</td>

                    <td>{{ $visitor->tujuan }}</td>

                    <td>
                        {{ $visitor->waktu_masuk ? $visitor->waktu_masuk->format('d M Y H:i') : '-' }}
                    </td>

                    <td>

                        @if($visitor->status == 'Masuk')

                        <span class="badge bg-success">

                            Check In

                        </span>

                        @else

                        <span class="badge bg-secondary">

                            Sudah Check Out

                        </span>

                        @endif

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="5" class="text-center text-muted py-4">

                        Belum ada data visitor.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>


<!-- Dark Mode JS -->

<script src="{{ asset('assets/js/darkmode.js') }}"></script>

<!-- Bootstrap JS -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<!-- Dashboard JS -->

<script src="{{ asset('assets/js/dashboard.js') }}"></script>

</body>

</html>