<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Detail Visitor | Sengked.com</title>

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

    <!-- Detail CSS -->

    <link rel="stylesheet"
          href="{{ asset('assets/css/admin/detail-visitor.css') }}">

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

                    Detail Visitor

                </h3>

                <p>

                    Informasi lengkap mengenai visitor.

                </p>

            </div>

            <button class="theme-toggle" type="button" title="Ganti tema">
                <i class="theme-toggle-icon bi bi-moon-stars-fill"></i>
            </button>

        </div>

        @if (session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

        @endif

        <!-- ===================== FOTO + INFORMASI VISITOR ===================== -->

        <div class="row g-4">

            <div class="col-lg-4">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-body text-center">

                        <img
                            src="{{ $visitor->foto ? asset('storage/' . $visitor->foto) : asset('assets/img/default-user.png') }}"
                            class="visitor-photo"
                            alt="Visitor">

                        <h4 class="mt-3">

                            {{ $visitor->nama }}

                        </h4>

                        @if($visitor->status == 'Masuk')

                        <span class="badge bg-success">

                            Check In

                        </span>

                        @else

                        <span class="badge bg-secondary">

                            Sudah Check Out

                        </span>

                        @endif

                    </div>

                </div>

            </div>

            <div class="col-lg-8">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">

                            Informasi Visitor

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row g-4">

                            <div class="col-md-6">

                                <strong>ID Visitor</strong>

                                <p><span class="fw-semibold" style="font-family:ui-monospace,Menlo,Consolas,monospace;letter-spacing:.08em;">{{ $visitor->visitor_id ?: '-' }}</span></p>

                            </div>

                            <div class="col-md-6">

                                <strong>Email</strong>

                                <p>{{ $visitor->email ?: '-' }}</p>

                            </div>

                            <div class="col-md-6">

                                <strong>Nomor HP</strong>

                                <p>{{ $visitor->no_hp }}</p>

                            </div>

                            <div class="col-md-6">

                                <strong>Perusahaan</strong>

                                <p>{{ $visitor->perusahaan }}</p>

                            </div>

                            <div class="col-md-6">

                                <strong>Tujuan</strong>

                                <p>{{ $visitor->tujuan }}</p>

                            </div>

                            <div class="col-md-6">

                                <strong>Lantai</strong>

                                <p>{{ $visitor->lantai ? 'Lantai ' . $visitor->lantai : '-' }}</p>

                            </div>

                            <div class="col-md-6">

                                <strong>Nomor Rak</strong>

                                <p>{{ $visitor->nomor_rak ?: '-' }}</p>

                            </div>

                            <div class="col-md-6">

                                <strong>Check In</strong>

                                <p>
                                    {{ $visitor->waktu_masuk ? $visitor->waktu_masuk->format('d M Y - H:i') . ' WIB' : '-' }}
                                </p>

                            </div>

                            <div class="col-md-6">

                                <strong>Check Out</strong>

                                <p>
                                    {{ $visitor->waktu_keluar ? $visitor->waktu_keluar->format('d M Y - H:i') . ' WIB' : '-' }}
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- ===================== RIWAYAT VISITOR ===================== -->

        <div class="card shadow-sm border-0 mt-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">

                    <i class="bi bi-clock-history"></i>

                    Riwayat Visitor

                </h5>

            </div>

            <div class="card-body">

                <div class="alert alert-light border mb-0">

                    <p class="mb-2">

                        <i class="bi bi-box-arrow-in-right text-success"></i>

                        Check In :

                        {{ $visitor->waktu_masuk ? $visitor->waktu_masuk->format('d M Y - H:i') . ' WIB' : '-' }}

                    </p>

                    <p class="mb-0">

                        <i class="bi bi-box-arrow-right text-danger"></i>

                        Check Out :

                        {{ $visitor->waktu_keluar ? $visitor->waktu_keluar->format('d M Y - H:i') . ' WIB' : '-' }}

                    </p>

                </div>

            </div>

        </div>

        <!-- ===================== AKSI ADMINISTRATOR ===================== -->

        <div class="card shadow-sm border-0 mt-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">

                    Aksi Administrator

                </h5>

            </div>

            <div class="card-body">

                <div class="d-flex flex-wrap gap-2">

                    <a href="{{ route('admin.visitor.edit',$visitor->id) }}"
                       class="btn btn-warning">

                        <i class="bi bi-pencil-square"></i>

                        Edit

                    </a>

                    @if($visitor->status=="Masuk")

                    <form action="{{ route('admin.visitor.checkout', $visitor->id) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('Yakin ingin check out visitor ini?')">

                        @csrf
                        @method('PATCH')

                        <button type="submit" class="btn btn-primary">

                            <i class="bi bi-box-arrow-right"></i>

                            Check Out

                        </button>

                    </form>

                    @endif

                    @if($isBlacklisted)

                    <form action="{{ route('admin.visitor.unblacklist', $visitor->id) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('Hapus visitor ini dari blacklist? Visitor bisa check-in lagi.')">

                        @csrf

                        <button type="submit" class="btn btn-success">

                            <i class="bi bi-unlock-fill"></i>

                            Unblacklist

                        </button>

                    </form>

                    @endif

                    <form action="{{ route('admin.visitor.destroy', $visitor->id) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('Yakin ingin menghapus data visitor ini? Data tidak bisa dikembalikan.')">

                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger">

                            <i class="bi bi-trash"></i>

                            Hapus

                        </button>

                    </form>

                    <a href="{{ route('admin.visitor') }}"
                       class="btn btn-secondary">

                        <i class="bi bi-arrow-left"></i>

                        Kembali

                    </a>

                </div>

            </div>

        </div>

    </main>

</div>

<!-- Dark Mode JS -->

<script src="{{ asset('assets/js/darkmode.js') }}"></script>

<!-- Bootstrap -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<!-- Detail JS -->

<script src="{{ asset('assets/js/detail-visitor.js') }}"></script>

</body>

</html>