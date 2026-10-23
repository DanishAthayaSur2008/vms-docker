<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Check Out | Sengked.com</title>

    <!-- Google Font -->
    <link rel="preconnect"
          href="https://fonts.googleapis.com">

    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    <!-- Bootstrap CSS -->
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

    <!-- Checkout CSS -->
    <link rel="stylesheet"
          href="/assets/css/user/checkout.css">

    @include('partials.head-assets')

</head>

<body>

<div id="deviceFrame">

<div class="page-toolbar">

    <!-- Tombol Dark / Light Mode -->
    <button class="theme-toggle-user" type="button" title="Ganti tema">
        <i class="theme-toggle-icon bi bi-moon-stars-fill"></i>
    </button>

</div>
    <!-- ===================== CHECK OUT ===================== -->

    <section class="checkout-page">

        <div class="container">

            <!-- Back Button -->

            <a href="{{ route('home') }}"
               class="back-button">

                <i class="bi bi-arrow-left"></i>

                Kembali

            </a>

            <!-- Page Header -->

            <div class="page-header">

                <h1>

                    Visitor Check Out

                </h1>

                <p>

                    Masukkan Nomor HP yang digunakan
                    saat melakukan Check In untuk mencari data visitor.

                </p>

            </div>

            <!-- Search Card -->

            <div class="checkout-card">

                <form id="searchForm" data-search-url="{{ route('checkout.search') }}">

                    <!-- Nomor HP -->

                    <div class="mb-4">

                        <label for="searchPhone"
                               class="form-label">

                            Nomor HP

                        </label>

                        <input
                            type="tel"
                            id="searchPhone"
                            name="search_phone"
                            class="form-control"
                            placeholder="08xxxxxxxxxx"
                            maxlength="15"
                            required>

                    </div>

                    <!-- Information -->

                    <div class="alert alert-info">

                        <i class="bi bi-info-circle-fill"></i>

                        Masukkan Nomor HP yang digunakan saat
                        Check In.

                    </div>

                    <!-- Search Button -->

                    <div class="d-grid">

                        <button
                            type="submit"
                            id="searchButton"
                            class="btn btn-primary btn-lg">

                            <i class="bi bi-search"></i>

                            <span>

                                Cari Visitor

                            </span>

                        </button>

                    </div>

                </form>

            </div>

            <!-- Alert Data Tidak Ditemukan -->

            <div
                id="notFoundAlert"
                class="alert alert-danger mt-4 d-none">

                <i class="bi bi-exclamation-circle-fill"></i>

                Visitor tidak ditemukan.
                Periksa kembali Nomor HP yang dimasukkan.

            </div>
                        <!-- ===================== VISITOR CARD ===================== -->

            <div
                id="visitorCard"
                class="visitor-card mt-4 d-none"
                data-checkout-url="{{ route('visitor.checkout') }}">

                <input type="hidden" id="visitorId">

                <div class="row">

                    <div class="col-lg-4 text-center">

                        <div class="visitor-photo">

                            <img
                                id="visitorPhoto"
                                src="{{ asset('assets/img/default-user.png') }}"
                                alt="Visitor Photo"
                                class="img-fluid rounded-circle">

                        </div>

                        <span class="badge bg-success mt-3">

                            ACTIVE

                        </span>

                    </div>

                    <div class="col-lg-8">

                        <h4 class="mb-4">

                            Data Visitor

                        </h4>

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="text-muted">

                                    Nama

                                </label>

                                <h6 id="visitorName">

                                    -

                                </h6>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="text-muted">

                                    Nomor HP

                                </label>

                                <h6 id="visitorPhone">

                                    -

                                </h6>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="text-muted">

                                    Email

                                </label>

                                <h6 id="visitorEmail">

                                    -

                                </h6>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="text-muted">

                                    Perusahaan

                                </label>

                                <h6 id="visitorCompany">

                                    -

                                </h6>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="text-muted">

                                    Tujuan

                                </label>

                                <h6 id="visitorPurpose">

                                    -

                                </h6>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="text-muted">

                                    Lantai

                                </label>

                                <h6 id="visitorLantai">

                                    -

                                </h6>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="text-muted">

                                    Nomor Rak

                                </label>

                                <h6 id="visitorRak">

                                    -

                                </h6>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="text-muted">

                                    Check In

                                </label>

                                <h6 id="visitorCheckIn">

                                    -

                                </h6>

                            </div>

                        </div>

                        <div class="d-grid">

                            <button
                                type="button"
                                id="checkoutButton"
                                class="btn btn-danger btn-lg"
                                data-bs-toggle="modal"
                                data-bs-target="#confirmCheckoutModal">

                                <i class="bi bi-box-arrow-right"></i>

                                Check Out

                            </button>

                        </div>

                    </div>

                </div>

            </div>

            <!-- ===================== CONFIRM MODAL ===================== -->

            <div
                class="modal fade"
                id="confirmCheckoutModal"
                tabindex="-1">

                <div class="modal-dialog modal-dialog-centered">

                    <div class="modal-content">

                        <div class="modal-header">

                            <h5 class="modal-title">

                                Konfirmasi Check Out

                            </h5>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">

                            </button>

                        </div>

                        <div class="modal-body">

                            Apakah Anda yakin ingin melakukan
                            <strong>Check Out</strong>
                            dari Data Center?

                        </div>

                        <div class="modal-footer">

                            <button
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">

                                Batal

                            </button>

                            <button
                                id="confirmCheckoutButton"
                                class="btn btn-danger">

                                Ya, Check Out

                            </button>

                        </div>

                    </div>

                </div>

            </div>
            <!-- ===================== SUCCESS MODAL ===================== -->

            <div
                class="modal fade"
                id="successModal"
                tabindex="-1"
                data-bs-backdrop="static"
                data-bs-keyboard="false">

                <div class="modal-dialog modal-dialog-centered">

                    <div class="modal-content">

                        <div class="modal-body text-center p-5">

                            <div class="success-icon mb-4">

                                <i class="bi bi-check-circle-fill"></i>

                            </div>

                            <h3 class="mb-3">

                                Check Out Berhasil

                            </h3>

                            <p class="text-muted mb-4">

                                Terima kasih telah melakukan
                                Check Out.

                                Semoga aktivitas Anda
                                berjalan dengan lancar.

                            </p>

                            <button
                                type="button"
                                id="backHomeButton"
                                data-home-url="{{ route('home') }}"
                                class="btn btn-primary">

                                <i class="bi bi-house-door-fill"></i>

                                Kembali ke Beranda

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

@include('partials.footer')


    <!-- Dark Mode JS -->

    <script src="{{ asset('assets/js/darkmode.js') }}"></script>

    <!-- Bootstrap JS -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Device Preview Frame (shared) -->

    <script src="{{ asset('assets/js/device-frame.js') }}"></script>

    <!-- Checkout JS -->

    <script src="{{ asset('assets/js/checkout.js') }}"></script>

</div>

</body>

</html>