<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Visitor Aktif | Sengked.com</title>

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

    <!-- Visitor Active CSS -->

    <link rel="stylesheet"
        href="{{ asset('assets/css/admin/visitor-active.css') }}">

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

                <a href="{{ route('admin.visitor.active') }}"
                   class="active">

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

    <main class="main-content">
        <!-- ===================== HEADER ===================== -->

<div class="topbar">

    <div>

        <h3>

            Visitor Aktif

        </h3>

        <p>

            Daftar visitor yang saat ini masih berada di dalam Data Center.

        </p>

    </div>

    <button class="theme-toggle" type="button" title="Ganti tema">
        <i class="theme-toggle-icon bi bi-moon-stars-fill"></i>
    </button>

</div>

<!-- ===================== ACTIVE TABLE ===================== -->

<div class="card shadow-sm border-0">

    <div class="card-header bg-white">

        <h5 class="mb-0">

            Daftar Visitor Aktif

        </h5>

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

                    <th>Durasi</th>

                    <th>Status</th>

                    <th>Aksi</th>

                </tr>

            </thead>

            <tbody id="activeVisitorTable">

                @foreach($visitors as $visitor)

                <tr>

                <td>
                {{ $loop->iteration }}
                </td>


                <td>
                {{ $visitor->nama }}
                </td>


                <td>
                <span class="fw-semibold" style="font-family:ui-monospace,Menlo,Consolas,monospace;letter-spacing:.08em;">{{ $visitor->visitor_id ?: '-' }}</span>
                </td>


                <td>
                {{ $visitor->perusahaan }}
                </td>


                <td>
                {{ $visitor->waktu_masuk->format('d M Y H:i') }}
                </td>


                <td>
                {{ $visitor->waktu_masuk->diffForHumans() }}
                </td>


                <td>

                <span class="badge bg-success">

                Aktif

                </span>

                </td>


                <td>

                <a
                    href="{{ route('admin.visitor.detail', $visitor->id) }}"
                    class="btn btn-primary btn-sm">

                <i class="bi bi-eye-fill"></i>

                </a>

                </td>


                </tr>


                @endforeach


                </tbody>

        </table>

    </div>

</div>
    </main>

</div>

<!-- Bootstrap -->

<!-- Dark Mode JS -->

<script src="{{ asset('assets/js/darkmode.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<!-- Visitor Active JS -->

<script src="{{ asset('assets/js/visitor-active.js') }}"></script>

</body>

</html>