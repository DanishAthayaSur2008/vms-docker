<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Profile Admin | Sengked.com</title>

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

    <!-- Profile CSS -->

    <link rel="stylesheet"
          href="{{ asset('assets/css/admin/profile.css') }}">

    @include('partials.head-assets')

</head>

<body data-profile-error="{{ $errors->hasAny(['name','email','no_hp']) ? 'true' : 'false' }}"
      data-password-error="{{ $errors->hasAny(['current_password','password']) ? 'true' : 'false' }}">

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

                <a href="{{ route('admin.profile') }}"
                   class="active">

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

            Profile Admin

        </h3>

        <p>

            Informasi akun administrator.

        </p>

    </div>

    <button class="theme-toggle" type="button" title="Ganti tema">
        <i class="theme-toggle-icon bi bi-moon-stars-fill"></i>
    </button>

</div>

<div class="row">

    @if(session('success'))

    <div class="col-12">

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    </div>

    @endif

    <div class="col-lg-4">

        <div class="card shadow-sm border-0">

            <div class="card-body text-center">

                <div class="mb-3" style="font-size: 6rem; color: #f0932b; line-height: 1;">

                    <i class="bi bi-person-circle"></i>

                </div>

                <h4>

                    {{ $user->name }}

                </h4>

                <p class="text-muted">

                    System Administrator

                </p>

            </div>

        </div>

    </div>

    <div class="col-lg-8">

        <div class="card shadow-sm border-0">

            <div class="card-header bg-white">

                <h5 class="mb-0">

                    Informasi Akun

                </h5>

            </div>

            <div class="card-body">
                <div class="row g-4">

    <div class="col-md-6">

        <strong>Nama Lengkap</strong>

        <p>{{ $user->name }}</p>

    </div>

    <div class="col-md-6">

        <strong>Email</strong>

        <p>{{ $user->email }}</p>

    </div>

    <div class="col-md-6">

        <strong>Role</strong>

        <p>System Administrator</p>

    </div>

    <div class="col-md-6">

        <strong>Nomor HP</strong>

        <p>{{ $user->no_hp ?: '-' }}</p>

    </div>

    <div class="col-md-6">

        <strong>Bergabung Sejak</strong>

        <p>{{ $user->created_at->format('d F Y') }}</p>

    </div>

    <div class="col-md-6">

        <strong>Status</strong>

        <span class="badge bg-success">

            Aktif

        </span>

    </div>

</div>

<hr class="my-4">

<div class="d-flex gap-3 flex-wrap">

    <button

        class="btn btn-primary"

        id="editProfile"
        type="button"
        data-bs-toggle="modal"
        data-bs-target="#editProfileModal">

        <i class="bi bi-pencil-square"></i>

        Edit Profile

    </button>

    <button

        class="btn btn-warning"

        id="changePassword"
        type="button"
        data-bs-toggle="modal"
        data-bs-target="#changePasswordModal">

        <i class="bi bi-key-fill"></i>

        Ubah Password

    </button>

</div>
            </div>

        </div>

    </div>

</div>

<!-- ===================== EDIT PROFILE MODAL ===================== -->

<div class="modal fade" id="editProfileModal" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <form method="POST" action="{{ route('admin.profile.update') }}">

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">Edit Profile</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    @if($errors->any() && $errors->has('name'))
                    <div class="alert alert-danger">{{ $errors->first('name') }}</div>
                    @endif

                    @if($errors->any() && $errors->has('email'))
                    <div class="alert alert-danger">{{ $errors->first('email') }}</div>
                    @endif

                    <div class="mb-3">

                        <label class="form-label">Nama Lengkap</label>

                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">Email</label>

                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">Nomor HP</label>

                        <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $user->no_hp) }}">

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>

                    <button type="submit" class="btn btn-primary">Simpan</button>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- ===================== CHANGE PASSWORD MODAL ===================== -->

<div class="modal fade" id="changePasswordModal" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <form method="POST" action="{{ route('admin.profile.password') }}">

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">Ubah Password</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    @if($errors->any() && $errors->has('current_password'))
                    <div class="alert alert-danger">{{ $errors->first('current_password') }}</div>
                    @endif

                    @if($errors->any() && $errors->has('password'))
                    <div class="alert alert-danger">{{ $errors->first('password') }}</div>
                    @endif

                    <div class="mb-3">

                        <label class="form-label">Password Saat Ini</label>

                        <input type="password" name="current_password" class="form-control" required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">Password Baru</label>

                        <input type="password" name="password" class="form-control" minlength="8" required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">Konfirmasi Password Baru</label>

                        <input type="password" name="password_confirmation" class="form-control" minlength="8" required>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>

                    <button type="submit" class="btn btn-warning">Ubah Password</button>

                </div>

            </form>

        </div>

    </div>

</div>

    </main>

</div>

<!-- Bootstrap -->

<!-- Dark Mode JS -->

<script src="{{ asset('assets/js/darkmode.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<!-- Profile JS -->

<script>
window.profilePageErrors = {
    profile: document.body.dataset.profileError === 'true',
    password: document.body.dataset.passwordError === 'true'
};
</script>

<script src="{{ asset('assets/js/profile.js') }}"></script>

</body>

</html>