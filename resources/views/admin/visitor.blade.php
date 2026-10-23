<!DOCTYPE html>
    <html lang="id">

    <head>

        <meta charset="UTF-8">

        <meta name="viewport"
            content="width=device-width, initial-scale=1.0">

        <title>Data Visitor | Sengked.com</title>

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

        <link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">

        <!-- Visitor CSS -->

        <link rel="stylesheet" href="{{ asset('assets/css/admin/visitor.css') }}">

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

                <a href="{{ route('admin.visitor') }}"
                   class="active">

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

        <main class="main-content">
          

            <!-- ===================== HEADER ===================== -->

        <div class="topbar">

            <div>

                <h3>

                    Data Visitor

                </h3>

                <p>

                    Kelola seluruh data pengunjung Data Center.

                </p>

            </div>

            <button class="theme-toggle" type="button" title="Ganti tema">
                <i class="theme-toggle-icon bi bi-moon-stars-fill"></i>
            </button>

        </div>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- ===================== FILTER ===================== -->

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <div class="row g-3">

                <div class="col-lg-4">

                    <input
                        type="text"
                        id="searchVisitor"
                        class="form-control"
                        placeholder="Cari nama visitor...">

                </div>

                <div class="col-lg-3">

                    <input
                        type="date"
                        id="filterDate"
                        class="form-control">

                </div>

                <div class="col-lg-3">

                    <select
                        id="filterStatus"
                        class="form-select">

                        <option value="">

                            Semua Status

                        </option>

                        <option>

                            Check In

                        </option>

                        <option>

                            Sudah Check Out

                        </option>

                        <option>

                            Blacklist

                        </option>

                    </select>

                </div>

                <div class="col-lg-2">

                    <button
                        class="btn btn-primary w-100"
                        id="searchButton">

                        <i class="bi bi-search"></i>

                        Cari

                    </button>

                </div>

            </div>

        </div>

    </div>
    <!-- ===================== TABLE ===================== -->

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white d-flex justify-content-between align-items-center">

            <h5 class="mb-0">

                Daftar Visitor

            </h5>

            <a
                href="{{ route('admin.laporan.export') }}"
                class="btn btn-success btn-sm"
                id="exportButton">

                <i class="bi bi-file-earmark-excel"></i>

                Export

            </a>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th>No</th>

                        <th>Nama</th>

                        <th>ID Visitor</th>

                        <th>Email</th>

                        <th>No. HP</th>

                        <th>Perusahaan</th>

                        <th>Tujuan</th>

                        <th>Lantai</th>

                        <th>Nomor Rak</th>

                        <th>Check In</th>

                        <th>Status</th>

                        <th class="text-center">Aksi</th>

                    </tr>

                </thead>

                <tbody id="visitorTable" data-poll-url="{{ route('admin.visitor.data') }}">

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
                    {{ $visitor->email }}
                </td>

                <td>
                    {{ $visitor->no_hp }}
                </td>

                <td>
                    {{ $visitor->perusahaan }}
                </td>

                <td>
                    {{ $visitor->tujuan }}
                </td>

                <td>
                    {{ $visitor->lantai ? 'Lantai ' . $visitor->lantai : '-' }}
                </td>

                <td>
                    {{ $visitor->nomor_rak ?: '-' }}
                </td>

                <td>
                    {{ $visitor->created_at->format('d M Y H:i') }}
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

                    @if(in_array($visitor->no_hp, $blacklistedNoHp))

                        <span class="badge bg-danger">

                            Blacklist

                        </span>

                    @endif

                </td>

                <td>

                    <div class="d-flex justify-content-center align-items-center flex-nowrap gap-2">

                    <a
                        href="{{ route('admin.visitor.detail', $visitor->id) }}"
                        class="btn btn-primary btn-sm">

                        <i class="bi bi-eye-fill"></i>

                    </a>

                    @if(!in_array($visitor->no_hp, $blacklistedNoHp))

                        <button
                            type="button"
                            class="btn btn-outline-danger btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#blacklistModal"
                            data-visitor-id="{{ $visitor->id }}"
                            data-visitor-nama="{{ $visitor->nama }}">

                            <i class="bi bi-slash-circle"></i>

                        </button>

                    @else

                        <button
                            type="button"
                            class="btn btn-outline-success btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#unblacklistModal"
                            data-visitor-id="{{ $visitor->id }}"
                            data-visitor-nama="{{ $visitor->nama }}"
                            title="Hapus dari blacklist">

                            <i class="bi bi-unlock-fill"></i>

                        </button>

                    @endif

                    </div>

                </td>

            </tr>

        @endforeach

    </tbody>

            </table>

        </div>

    </div>
    <!-- ===================== PAGINATION ===================== -->

    <div class="d-flex justify-content-between align-items-center mt-4">

        <small class="text-muted" id="visitorCountText">

            Menampilkan 1 - {{ $visitors->count() }} dari {{ $visitors->count() }} data visitor

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

    <!-- ===================== MODAL BLACKLIST ===================== -->

    <div class="modal fade" id="blacklistModal" tabindex="-1">

        <div class="modal-dialog">

            <form method="POST" id="blacklistForm">

                @csrf

                <div class="modal-content">

                    <div class="modal-header">

                        <h5 class="modal-title">

                            Tambahkan ke blacklist

                        </h5>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                        </button>

                    </div>

                    <div class="modal-body">

                        <p>

                            Anda akan memblokir
                            <strong id="blacklistNama"></strong>
                            agar tidak bisa check-in lagi.

                        </p>

                        <div class="mb-3">

                            <label class="form-label">

                                Alasan

                            </label>

                            <textarea
                                name="alasan"
                                class="form-control"
                                rows="3"
                                required></textarea>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                            Batal

                        </button>

                        <button
                            type="submit"
                            class="btn btn-danger">

                            Blacklist

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>
    <!-- ===================== MODAL UNBLACKLIST ===================== -->

    <div class="modal fade" id="unblacklistModal" tabindex="-1">

        <div class="modal-dialog">

            <form method="POST" id="unblacklistForm">

                @csrf

                <div class="modal-content">

                    <div class="modal-header">

                        <h5 class="modal-title">

                            Hapus dari blacklist

                        </h5>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                        </button>

                    </div>

                    <div class="modal-body">

                        <p>

                            Anda akan menghapus
                            <strong id="unblacklistNama"></strong>
                            dari daftar blacklist, sehingga visitor ini
                            bisa check-in lagi.

                        </p>

                    </div>

                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                            Batal

                        </button>

                        <button
                            type="submit"
                            class="btn btn-success">

                            Hapus dari Blacklist

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <!-- Dark Mode JS -->

    <script src="{{ asset('assets/js/darkmode.js') }}"></script>

    <!-- Bootstrap JS -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Visitor JS -->

    <script src="{{ asset('assets/js/visitor.js') }}"></script>

    <!-- Blacklist Modal JS -->

    <script>

        document.getElementById('blacklistModal').addEventListener('show.bs.modal', function (event) {

            const button = event.relatedTarget;
            const visitorId = button.getAttribute('data-visitor-id');
            const visitorNama = button.getAttribute('data-visitor-nama');

            document.getElementById('blacklistForm').action = "/admin/visitor/" + visitorId + "/blacklist";
            document.getElementById('blacklistNama').textContent = visitorNama;

        });

        document.getElementById('unblacklistModal').addEventListener('show.bs.modal', function (event) {

            const button = event.relatedTarget;
            const visitorId = button.getAttribute('data-visitor-id');
            const visitorNama = button.getAttribute('data-visitor-nama');

            document.getElementById('unblacklistForm').action = "/admin/visitor/" + visitorId + "/unblacklist";
            document.getElementById('unblacklistNama').textContent = visitorNama;

        });

    </script>

    </body>

    </html>