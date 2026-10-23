<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Check In | Sengked.com</title>

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

    <!-- Check In CSS -->
    <link rel="stylesheet"
          href="/assets/css/user/checkin.css">

    <!-- Checkin Select CSS -->
    <link rel="stylesheet"
          href="/assets/css/user/checkin-select.css">

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

            <a href="{{ route('home') }}"
               class="back-button">

                <i class="bi bi-arrow-left"></i>

                Kembali

            </a>

            <!-- Header -->

            <div class="page-header">

                <h1>

                    Visitor Check In

                </h1>

                <p>

                    Pilih salah satu opsi di bawah ini untuk
                    melanjutkan proses check-in.

                </p>

            </div>

            <!-- Pilihan -->

            <div class="row g-4 checkin-select-row">

                <!-- REGISTER -->

                <div class="col-md-6">

                    <div class="checkin-option-card">

                        <div class="checkin-option-icon">
                            <i class="bi bi-person-plus-fill"></i>
                        </div>

                        <h3>Register</h3>

                        <p class="checkin-option-desc">

                            Untuk visitor yang <strong>baru pertama kali</strong>
                            berkunjung ke Data Center. Anda perlu mengisi
                            seluruh data secara lengkap: nama, email,
                            perusahaan, tujuan kunjungan, lantai, nomor rak,
                            hingga foto selfie. Setelah berhasil, Anda akan
                            mendapatkan <strong>ID Visitor 5 digit</strong>
                            untuk kunjungan berikutnya.

                        </p>

                        <a href="{{ route('checkin.register') }}"
                           class="btn btn-primary w-100">

                            <i class="bi bi-person-plus-fill"></i>
                            Register

                        </a>

                    </div>

                </div>

                <!-- LOGIN -->

                <div class="col-md-6">

                    <div class="checkin-option-card">

                        <div class="checkin-option-icon checkin-option-icon-alt">
                            <i class="bi bi-box-arrow-in-right"></i>
                        </div>

                        <h3>Login</h3>

                        <p class="checkin-option-desc">

                            Untuk visitor yang <strong>sudah pernah check-in</strong>
                            sebelumnya. Masuk dengan <strong>nomor telepon</strong>
                            dan <strong>ID Visitor 5 digit</strong>, lalu isi
                            tujuan kunjungan, lantai, nomor rak, dan foto
                            selfie baru.

                        </p>

                        <a href="{{ route('checkin.login') }}"
                           class="btn btn-outline-primary w-100">

                            <i class="bi bi-box-arrow-in-right"></i>
                            Login

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </section>

@include('partials.footer')


</div>

<!-- Dark Mode JS -->
<script src="/assets/js/darkmode.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<!-- Device Preview Frame -->
<script src="/assets/js/device-frame.js"></script>

</body>

</html>
