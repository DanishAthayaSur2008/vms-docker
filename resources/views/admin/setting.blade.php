<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Setting | Sengked.com</title>

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

    <!-- Setting CSS -->

    <link rel="stylesheet"
          href="{{ asset('assets/css/admin/setting.css') }}">

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

                <a href="{{ route('admin.setting') }}"
                   class="active">

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

            Pengaturan Sistem

        </h3>

        <p>

            Kelola konfigurasi aplikasi Visitor Management System.

        </p>

    </div>

    <button class="theme-toggle" type="button" title="Ganti tema">
        <i class="theme-toggle-icon bi bi-moon-stars-fill"></i>
    </button>

</div>

<div class="card shadow-sm border-0">

    <div class="card-header bg-white">

        <h5 class="mb-0">

            Pengaturan Umum

        </h5>

    </div>

    <div class="card-body">

        @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

        @endif

        @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

        @endif

        <form method="POST" action="{{ route('admin.setting.update') }}">

            @csrf

            <div class="mb-4">

                <label class="form-label">

                    Nama Perusahaan

                </label>

                <input

                    type="text"

                    name="company_name"

                    class="form-control"

                    id="companyName"

                    value="{{ old('company_name', $setting->company_name) }}"

                    placeholder="Masukkan nama perusahaan">

            </div>

            <div class="mb-4">

                <label class="form-label">

                    Email Administrator

                </label>

                <input

                    type="email"

                    name="admin_email"

                    class="form-control"

                    id="adminEmail"

                    value="{{ old('admin_email', $setting->admin_email) }}"

                    placeholder="admin@email.com">

            </div>

            <div class="mb-4">

                <label class="form-label">

                    Nomor Telepon

                </label>

                <input

                    type="text"

                    name="admin_phone"

                    class="form-control"

                    id="adminPhone"

                    value="{{ old('admin_phone', $setting->admin_phone) }}">

            </div>

            <div class="form-check form-switch mb-4">

                <input

                    class="form-check-input"

                    type="checkbox"

                    name="maintenance_mode"

                    id="maintenanceMode"

                    value="1"

                    {{ old('maintenance_mode', $setting->maintenance_mode) ? 'checked' : '' }}>

                <label

                    class="form-check-label"

                    for="maintenanceMode">

                    Aktifkan Maintenance Mode

                </label>

            </div>

            <button

                type="submit"

                class="btn btn-primary"

                id="saveSetting">

                <i class="bi bi-floppy-fill"></i>

                Simpan Pengaturan

            </button>

        </form>

    </div>

</div>

    </main>

</div>

<!-- Bootstrap -->

<!-- Dark Mode JS -->

<script src="{{ asset('assets/js/darkmode.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<!-- Setting JS -->

<script src="{{ asset('assets/js/setting.js') }}"></script>

</body>

</html>