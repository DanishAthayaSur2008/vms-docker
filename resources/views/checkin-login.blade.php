<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Visitor Login | Sengked.com</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

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
          href="/assets/css/global.css">

    <!-- Device Preview Frame -->
    <link rel="stylesheet"
          href="/assets/css/user/device-frame.css">

    <!-- Dark Mode CSS -->
    <link rel="stylesheet"
          href="/assets/css/user/darkmode-user.css">

    <!-- Check In CSS (dipakai ulang untuk styling form) -->
    <link rel="stylesheet"
          href="/assets/css/user/checkin.css">

    @include('partials.head-assets')

</head>

<body>

<div id="deviceFrame">

<div class="page-toolbar">

    <button class="theme-toggle-user" type="button" title="Ganti tema">
        <i class="theme-toggle-icon bi bi-moon-stars-fill"></i>
    </button>

</div>

<section class="checkin-page">

    <div class="container">

        <!-- Back -->

        <a href="{{ route('checkin') }}"
           class="back-button">

            <i class="bi bi-arrow-left"></i>

            Kembali

        </a>

        <!-- Header -->

        <div class="page-header">

            <h1>

                Visitor Login

            </h1>

            <p>

                Untuk visitor yang sudah terdaftar. Masuk dengan Nomor Telepon
                dan ID Visitor 5 digit, lalu isi data kunjungan Anda.

            </p>

        </div>

        <!-- Card -->

        <div class="checkin-card">

            <form id="loginForm"
                  action="{{ route('visitor.login.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  data-lookup-url="{{ route('checkin.lookup') }}"
                  autocomplete="off"
                  novalidate>

                @csrf

                {{-- ===== Notifikasi dari server (hasil validasi backend) ===== --}}

                @foreach (['blacklist', 'no_hp', 'visitor_id'] as $errorKey)
                    @error($errorKey)
                        <div class="alert alert-danger login-alert mb-3" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>
                            <div>{{ $message }}</div>
                        </div>
                    @enderror
                @endforeach

                @if ($errors->any() && ! $errors->hasAny(['blacklist', 'no_hp', 'visitor_id']))
                    <div class="alert alert-danger login-alert mb-3" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>
                        <div>
                            Data yang Anda kirim belum lengkap atau tidak valid.
                            Silakan periksa kembali lalu coba lagi.
                        </div>
                    </div>
                @endif

                <!-- =========================
                     Indikator langkah
                ========================== -->

                <ol class="login-stepper" aria-label="Langkah login">

                    <li class="login-stepper-item is-active" id="loginStepIndicator1" aria-current="step">

                        <span class="login-stepper-dot">
                            <span class="dot-num">1</span>
                            <i class="bi bi-check-lg dot-check"></i>
                        </span>

                        <span class="login-stepper-label">Verifikasi</span>

                    </li>

                    <li class="login-stepper-line" id="loginStepLine" aria-hidden="true"></li>

                    <li class="login-stepper-item" id="loginStepIndicator2">

                        <span class="login-stepper-dot">
                            <span class="dot-num">2</span>
                            <i class="bi bi-check-lg dot-check"></i>
                        </span>

                        <span class="login-stepper-label">Data Kunjungan</span>

                    </li>

                </ol>

                <!-- ==========================================
                     LANGKAH 1 : NOMOR TELEPON + ID VISITOR
                =========================================== -->

                <div class="login-step-panel" id="loginStep1">

                    <div class="alert alert-danger login-alert d-none mb-3"
                         role="alert"
                         id="loginVerifyError">

                        <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>

                        <div id="loginVerifyErrorText"></div>

                    </div>

                    <div class="field-group">

                        <label class="form-label" for="loginPhone">

                            Nomor Telepon

                        </label>

                        <input
                            type="tel"
                            class="form-control"
                            id="loginPhone"
                            name="no_hp"
                            placeholder="08xxxxxxxxxx"
                            maxlength="15"
                            inputmode="numeric"
                            value="{{ old('no_hp') }}"
                            required>

                        <small class="text-muted mt-1 d-block">

                            Gunakan nomor telepon yang Anda daftarkan saat registrasi.

                        </small>

                        <div class="invalid-feedback">

                            Nomor telepon tidak valid.

                        </div>

                    </div>

                    <div class="field-group">

                        <label class="form-label" for="loginVisitorId">

                            ID Visitor

                        </label>

                        <input
                            type="text"
                            class="form-control visitor-id-input"
                            id="loginVisitorId"
                            name="visitor_id"
                            inputmode="numeric"
                            pattern="[0-9]{5}"
                            maxlength="5"
                            placeholder="•••••"
                            aria-describedby="loginVisitorIdHelp"
                            value="{{ old('visitor_id') }}"
                            required>

                        <div class="id-meter" id="loginVisitorIdHelp">

                            <small class="text-muted">

                                Masukkan <strong>5 digit</strong> ID Visitor Anda (angka saja).

                            </small>

                            <small class="id-count" id="loginVisitorIdCount">0/5</small>

                        </div>

                        <div class="invalid-feedback">

                            ID Visitor harus terdiri dari tepat 5 digit angka.

                        </div>

                    </div>

                    <div class="alert alert-info login-alert mb-3">

                        <i class="bi bi-info-circle-fill me-2 mt-1"></i>

                        <div>

                            Belum punya ID Visitor? ID diberikan sistem setelah
                            registrasi pertama.
                            <a href="{{ route('checkin.register') }}" class="fw-semibold">Register di sini</a>.

                        </div>

                    </div>

                    <button
                        type="button"
                        class="btn btn-primary w-100"
                        id="loginNextButton">

                        <span class="btn-label">Lanjutkan</span>

                        <i class="bi bi-arrow-right ms-1"></i>

                    </button>

                </div>

                <!-- ==========================================
                     LANGKAH 2 : DATA KUNJUNGAN
                =========================================== -->

                <div class="login-step-panel d-none" id="loginStep2">

                    <div class="alert alert-danger login-alert d-none mb-3"
                         role="alert"
                         id="loginFormError">

                        <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>

                        <div id="loginFormErrorText"></div>

                    </div>

                    <div class="alert alert-success login-alert mb-3" id="foundDataAlert">

                        <i class="bi bi-person-check-fill me-2 mt-1"></i>

                        <div>

                            Verifikasi berhasil. Selamat datang kembali,
                            <strong><span id="foundName">-</span></strong>
                            (<span id="foundCompany">-</span>).
                            Lengkapi data kunjungan Anda di bawah ini.

                        </div>

                    </div>

                    <!-- =========================
                         Tujuan
                    ========================== -->

                    <div class="field-group">

                        <label class="form-label" for="loginPurpose">

                            Tujuan Kunjungan

                        </label>

                        <select
                            class="form-select"
                            id="loginPurpose"
                            name="tujuan"
                            required>

                            <option value="">

                                -- Pilih Tujuan --

                            </option>

                            <option value="Maintenance">

                                Maintenance

                            </option>

                            <option value="Masuk Perangkat">

                                Masuk Perangkat

                            </option>

                            <option value="Keluar Perangkat">

                                Keluar Perangkat

                            </option>

                            <option value="Visit">

                                Visit

                            </option>

                        </select>

                        <div class="invalid-feedback">

                            Silakan pilih tujuan kunjungan.

                        </div>

                    </div>

                    <!-- =========================
                         Lantai
                    ========================== -->

                    <div class="field-group">

                        <label class="form-label" for="loginLantai">

                            Lantai

                        </label>

                        <select
                            class="form-select"
                            id="loginLantai"
                            name="lantai"
                            required>

                            <option value="">

                                -- Pilih Lantai --

                            </option>

                            <option value="1">Lantai 1</option>
                            <option value="2">Lantai 2</option>
                            <option value="3">Lantai 3</option>

                        </select>

                        <div class="invalid-feedback">

                            Silakan pilih lantai.

                        </div>

                    </div>

                    <!-- =========================
                         Nomor Rak
                    ========================== -->

                    <div class="field-group">

                        <label class="form-label" for="loginRak">

                            Nomor Rak

                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="loginRak"
                            name="nomor_rak"
                            inputmode="numeric"
                            placeholder="Contoh: 12"
                            maxlength="6"
                            required>

                        <small class="text-muted mt-1 d-block">

                            Masukkan nomor rak (angka saja) yang akan dikunjungi di Data Center.

                        </small>

                        <div class="invalid-feedback">

                            Nomor rak wajib diisi dan harus berupa angka.

                        </div>

                    </div>

                    <!-- =========================
                         FOTO SELFIE
                    ========================== -->

                    <div class="field-group">

                        <label class="form-label">

                            Foto Selfie

                        </label>

                        <div class="photo-preview" id="loginPhotoPreview">

                            <video
                                id="loginCameraStream"
                                class="preview-image d-none"
                                autoplay
                                playsinline
                                muted></video>

                            <img
                                id="loginPreviewImage"
                                src=""
                                alt="Preview Selfie"
                                class="preview-image d-none">

                            <div id="loginPhotoPlaceholder">

                                <i class="bi bi-person-bounding-box"></i>

                                <p>Belum ada foto</p>

                            </div>

                        </div>

                        <canvas id="loginCameraCanvas" class="d-none"></canvas>

                        <div class="previous-photo-hint d-none" id="loginPreviousPhotoHint">

                            <img id="loginPreviousPhotoImg" src="" alt="Foto check-in sebelumnya">

                            <span>Foto check-in sebelumnya. Tetap ambil foto baru untuk hari ini.</span>

                        </div>

                        <input
                            type="file"
                            id="loginSelfieInput"
                            name="foto"
                            accept="image/*"
                            hidden>

                        <div class="d-flex gap-2 mt-3">

                            <button
                                type="button"
                                class="btn btn-outline-primary w-100"
                                id="loginCameraButton">

                                <i class="bi bi-camera-fill"></i>

                                Aktifkan Kamera

                            </button>

                            <button
                                type="button"
                                class="btn btn-primary w-100 d-none"
                                id="loginCaptureButton">

                                <i class="bi bi-camera"></i>

                                Jepret

                            </button>

                            <button
                                type="button"
                                class="btn btn-outline-secondary w-100 d-none"
                                id="loginRetakeButton">

                                <i class="bi bi-arrow-counterclockwise"></i>

                                Ambil Ulang

                            </button>

                        </div>

                        <p class="text-danger small mt-2 d-none" id="loginCameraError"></p>

                    </div>

                    <!-- =========================
                        INFORMASI
                    ========================== -->

                    <div class="alert alert-info login-alert">

                        <i class="bi bi-info-circle-fill me-2 mt-1"></i>

                        <div>

                            Nama, email, dan perusahaan diambil otomatis dari data
                            registrasi Anda. Foto selfie wajib diambil ulang
                            pada setiap kunjungan.

                        </div>

                    </div>

                    <!-- =========================
                        BUTTON
                    ========================== -->

                    <div class="row g-2">

                        <div class="col-6 col-md-3">

                            <button
                                type="button"
                                class="btn btn-outline-secondary w-100"
                                id="loginBackButton">

                                <i class="bi bi-arrow-left"></i>

                                Kembali

                            </button>

                        </div>

                        <div class="col-6 col-md-3">

                            <button
                                type="reset"
                                class="btn btn-outline-secondary w-100"
                                id="loginResetButton">

                                <i class="bi bi-arrow-clockwise"></i>

                                Reset

                            </button>

                        </div>

                        <div class="col-12 col-md-6">

                            <button
                                type="submit"
                                class="btn btn-primary w-100"
                                id="loginSubmitButton">

                                <i class="bi bi-box-arrow-in-right"></i>

                                <span>

                                    Login &amp; Check In

                                </span>

                            </button>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>

</section>

@include('partials.footer')


<!-- =========================
     MODAL KONFIRMASI
========================= -->

<div class="modal fade"
     id="loginConfirmModal"
     tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">

                    Konfirmasi Login

                </h5>

            </div>

            <div class="modal-body">

                Apakah seluruh data yang Anda isi sudah benar?

            </div>

            <div class="modal-footer">

                <button
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">

                    Batal

                </button>

                <button
                    class="btn btn-primary"
                    id="loginConfirmSubmit">

                    Ya, Check In

                </button>

            </div>

        </div>

    </div>

</div>

<!-- Dark Mode JS -->
<script src="/assets/js/darkmode.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<!-- Device Preview Frame -->
<script src="/assets/js/device-frame.js"></script>

<!-- Checkin Login JS -->
<script src="/assets/js/checkin-login.js"></script>

</div>

</body>

</html>
