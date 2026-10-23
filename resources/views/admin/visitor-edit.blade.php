<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Visitor | Sengked.com</title>

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

    <!-- Detail CSS (dipakai ulang, styling mirip halaman detail) -->

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

                    Edit Visitor

                </h3>

                <p>

                    Perbarui data pengunjung.

                </p>

            </div>

            <button class="theme-toggle" type="button" title="Ganti tema">
                <i class="theme-toggle-icon bi bi-moon-stars-fill"></i>
            </button>

        </div>

        @if ($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach ($errors->all() as $error)

                <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

        @endif

        <div class="card shadow-sm border-0">

            <div class="card-header bg-white">

                <h5 class="mb-0">

                    Form Edit Visitor

                </h5>

            </div>

            <div class="card-body">

                <form action="{{ route('admin.visitor.update', $visitor->id) }}"
                      method="POST"
                      enctype="multipart/form-data">

                    @csrf
                    @method('PUT')

                    <div class="row g-4">

                        <div class="col-md-4 text-center">

                            <img
                                src="{{ $visitor->foto ? asset('storage/' . $visitor->foto) : asset('assets/img/default-user.png') }}"
                                class="visitor-photo mb-3"
                                alt="Visitor">

                            <div class="mb-3 text-start">

                                <label class="form-label">Ganti Foto (opsional)</label>

                                <input type="file"
                                       name="foto"
                                       accept="image/*"
                                       class="form-control">

                            </div>

                        </div>

                        <div class="col-md-8">

                            <div class="row g-3">

                                <div class="col-md-6">

                                    <label class="form-label">Nama</label>

                                    <input type="text"
                                           name="nama"
                                           value="{{ old('nama', $visitor->nama) }}"
                                           class="form-control"
                                           required>

                                </div>

                                <div class="col-md-6">

                                    <label class="form-label">Nomor HP</label>

                                    <input type="text"
                                           name="no_hp"
                                           value="{{ old('no_hp', $visitor->no_hp) }}"
                                           class="form-control"
                                           required>

                                </div>

                                <div class="col-md-6">

                                    <label class="form-label">Email</label>

                                    <input type="email"
                                           name="email"
                                           value="{{ old('email', $visitor->email) }}"
                                           class="form-control"
                                           required>

                                </div>

                                <div class="col-md-6">

                                    <label class="form-label">Perusahaan</label>

                                    <input type="text"
                                           name="perusahaan"
                                           value="{{ old('perusahaan', $visitor->perusahaan) }}"
                                           class="form-control"
                                           required>

                                </div>

                                <div class="col-12">

                                    <label class="form-label">Tujuan / Alasan Masuk</label>

                                    <textarea name="tujuan"
                                              class="form-control"
                                              rows="3"
                                              required>{{ old('tujuan', $visitor->tujuan) }}</textarea>

                                </div>

                            </div>

                        </div>

                    </div>

                    <hr class="my-4">

                    <div class="d-flex flex-wrap gap-2">

                        <button type="submit" class="btn btn-primary">

                            <i class="bi bi-save"></i>

                            Simpan Perubahan

                        </button>

                        <a href="{{ route('admin.visitor.detail', $visitor->id) }}"
                           class="btn btn-secondary">

                            <i class="bi bi-arrow-left"></i>

                            Batal

                        </a>

                    </div>

                </form>

            </div>

        </div>

    </main>

</div>

<!-- Bootstrap -->

<!-- Dark Mode JS -->

<script src="{{ asset('assets/js/darkmode.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>