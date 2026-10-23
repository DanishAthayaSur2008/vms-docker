<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Laporan | Sengked.com</title>

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

    <!-- Laporan CSS -->

    <link rel="stylesheet"
          href="{{ asset('assets/css/admin/laporan.css') }}">

    @include('partials.head-assets')

</head>

<body>

<div class="dashboard-wrapper">

    <!-- Sidebar -->

    <aside class="sidebar">

        <div class="sidebar-logo">

            @include('partials.brand-logo')


        </div>

                <ul class="sidebar-menu">

            <li>

                <a href="{{ route('admin.dashboard') }}">

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

                <a href="{{ route('admin.laporan') }}"
                   class="active">

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

    <main class="main-content">
        <!-- ===================== HEADER ===================== -->

<div class="topbar">

    <div>

        <h3>

            Laporan Visitor

        </h3>

        <p>

            Ringkasan seluruh aktivitas kunjungan Data Center.

        </p>

    </div>

    <button class="theme-toggle" type="button" title="Ganti tema">
        <i class="theme-toggle-icon bi bi-moon-stars-fill"></i>
    </button>

</div>

<!-- ===================== STATISTIC ===================== -->

<div class="row g-4 mb-4">

    <div class="col-lg-3 col-md-6">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <h6>Total Visitor</h6>

                <h2>

                    {{ $totalVisitor }}

                </h2>

                <small class="text-muted">

                    Seluruh data visitor

                </small>

            </div>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <h6>Hari Ini</h6>

                <h2>

                    {{ $visitorHariIni }}

                </h2>

                <small class="text-muted">

                    Visitor hari ini

                </small>

            </div>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <h6>Masih Aktif</h6>

                <h2>

                    {{ $visitorAktif }}

                </h2>

                <small class="text-muted">

                    Belum Check Out

                </small>

            </div>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <h6>Selesai</h6>

                <h2>

                    {{ $visitorSelesai }}

                </h2>

                <small class="text-muted">

                    Sudah Check Out

                </small>

            </div>

        </div>

    </div>

</div>

<!-- ===================== FILTER ===================== -->

<form method="GET" action="{{ route('admin.laporan') }}">

<div class="card shadow-sm border-0 mb-4">

    <div class="card-body">

        <div class="row g-3">

            <div class="col-lg-4">

                <label class="form-label">

                    Dari Tanggal

                </label>

                <input

                    type="date"

                    name="start_date"

                    class="form-control"

                    id="startDate"

                    value="{{ request('start_date') }}">

            </div>

            <div class="col-lg-4">

                <label class="form-label">

                    Sampai Tanggal

                </label>

                <input

                    type="date"

                    name="end_date"

                    class="form-control"

                    id="endDate"

                    value="{{ request('end_date') }}">

            </div>

            <div class="col-lg-4 d-flex align-items-end">

                <button

                    type="submit"

                    class="btn btn-primary w-100"

                    id="filterButton">

                    <i class="bi bi-search"></i>

                    Tampilkan Laporan

                </button>

            </div>

        </div>

    </div>

</div>

</form>
<!-- ===================== REPORT TABLE ===================== -->

<div class="card shadow-sm border-0">

    <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-3">

        <h5 class="mb-0">

            Data Laporan Visitor

        </h5>

        <div class="d-flex gap-2">

            <input
                type="text"
                class="form-control"
                id="searchReport"
                placeholder="Cari visitor..."
                style="width:220px;">

            <a
                href="{{ route('admin.laporan.export', request()->only(['start_date', 'end_date'])) }}"
                class="btn btn-success"
                id="exportExcel">

                <i class="bi bi-file-earmark-excel"></i>

                Excel

            </a>

            <button
                class="btn btn-danger"
                id="exportPdf">

                <i class="bi bi-file-earmark-pdf"></i>

                PDF

            </button>

        </div>

    </div>

    <div class="table-responsive">

        <table class="table table-hover align-middle mb-0">

            <thead class="table-light">

                <tr>

                    <th>No</th>

                    <th>Nama</th>

                    <th>ID Visitor</th>

                    <th>Perusahaan</th>

                    <th>Check In</th>

                    <th>Check Out</th>

                    <th>Status</th>

                </tr>

            </thead>

            <tbody id="reportTable">

                @forelse($visitors as $visitor)

                <tr>

                    <td>{{ $loop->iteration }}</td>

                    <td>{{ $visitor->nama }}</td>

                    <td><span class="fw-semibold" style="font-family:ui-monospace,Menlo,Consolas,monospace;letter-spacing:.08em;">{{ $visitor->visitor_id ?: '-' }}</span></td>

                    <td>{{ $visitor->perusahaan }}</td>

                    <td>
                        {{ $visitor->waktu_masuk ? $visitor->waktu_masuk->format('d M Y H:i') : '-' }}
                    </td>

                    <td>
                        {{ $visitor->waktu_keluar ? $visitor->waktu_keluar->format('d M Y H:i') : '-' }}
                    </td>

                    <td>

                        @if($visitor->status == 'Masuk')

                        <span class="badge bg-success">

                            Aktif

                        </span>

                        @else

                        <span class="badge bg-secondary">

                            Selesai

                        </span>

                        @endif

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="7" class="text-center text-muted py-4">

                        Belum ada data visitor.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>
<!-- ===================== PAGINATION ===================== -->

<div class="d-flex justify-content-between align-items-center mt-4">

    <small class="text-muted">

        Menampilkan 1 - 3 dari 3 data

    </small>

    <nav>

        <ul class="pagination mb-0">

            <li class="page-item disabled">

                <a class="page-link" href="#">

                    Previous

                </a>

            </li>

            <li class="page-item active">

                <a class="page-link" href="#">

                    1

                </a>

            </li>

            <li class="page-item disabled">

                <a class="page-link" href="#">

                    Next

                </a>

            </li>

        </ul>

    </nav>

</div>

    </main>

</div>

<!-- Dark Mode JS -->

<script src="{{ asset('assets/js/darkmode.js') }}"></script>

<!-- Bootstrap JS -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<!-- Report JS -->

<script src="{{ asset('assets/js/laporan.js') }}"></script>

</body>

</html>