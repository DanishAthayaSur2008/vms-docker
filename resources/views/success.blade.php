<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Check In Berhasil | Sengked.com</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <style>

        body{

            font-family:'Poppins',sans-serif;

            background:#f5f7fb;

        }

        .success-card{

            max-width:650px;

            border:none;

            border-radius:20px;

        }

        .success-icon{

            width:110px;

            height:110px;

            background:#198754;

            color:white;

            border-radius:50%;

            display:flex;
 
            justify-content:center;

            align-items:center;

            margin:auto;

            font-size:55px;
        }

        .visitor-id-card{
            margin:24px 0 8px;
            padding:22px 20px 20px;
            background:#FFF7ED;
            border:1px solid #FED7AA;
            border-radius:16px;
            animation:ui-fade-up var(--ui-slow, .36s) var(--ui-ease, ease) 120ms backwards;
        }

        .visitor-id-label{
            margin:0 0 14px;
            font-size:13px;
            font-weight:600;
            letter-spacing:.08em;
            text-transform:uppercase;
            color:#9A3412;
        }

        .visitor-id-digits{
            display:flex;
            justify-content:center;
            gap:10px;
            margin-bottom:16px;
        }

        .visitor-id-digit{
            display:flex;
            align-items:center;
            justify-content:center;
            width:52px;
            height:64px;
            font-family:ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
            font-size:32px;
            font-weight:700;
            color:#1F2937;
            background:#FFFFFF;
            border:2px solid #F97316;
            border-radius:12px;
            box-shadow:0 4px 10px rgba(249,115,22,.12);
        }

        .visitor-id-note{
            display:flex;
            align-items:flex-start;
            margin:16px 0 0;
            text-align:left;
            font-size:13.5px;
        }

        @media (max-width:480px){
            .visitor-id-digits{ gap:8px; }
            .visitor-id-digit{ width:44px; height:56px; font-size:26px; }
        }
    </style>

    @include('partials.head-assets')

</head>
<body>

@php
    /*
     * ID Visitor (5 digit) dikirim BACKEND lewat session flash setelah registrasi:
     *     return redirect()->route('success')->with('visitor_id', '58321');
     * Kalau tidak ada (mis. visitor yang login), kartu ID tidak ditampilkan.
     *
     * Preview tampilan saat development (hanya APP_ENV=local):
     *     /success?preview_id=58321
     */
    $visitorId = session('visitor_id');

    if (! $visitorId && app()->environment('local')) {
        $visitorId = request('preview_id');
    }

    $visitorId = preg_match('/^[0-9]{5}$/', (string) $visitorId) ? (string) $visitorId : null;
@endphp


<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="card shadow success-card">

                <div class="card-body text-center p-5">

                    <div class="success-icon mb-4">

                        <i class="bi bi-check-lg"></i>

                    </div>

                    <h2 class="fw-bold text-success">

                        Check In Berhasil

                    </h2>

                    <p class="text-muted mt-3">

                        Terima kasih.

                        Data visitor berhasil disimpan ke sistem Data Center.
                    </p>

                    @if ($visitorId)
                        <div class="visitor-id-card" id="visitorIdCard">
                            <p class="visitor-id-label">ID Visitor Anda</p>

                            <div class="visitor-id-digits"
                                 role="img"
                                 aria-label="ID Visitor Anda: {{ implode(' ', str_split($visitorId)) }}">
                                @foreach (str_split($visitorId) as $digit)
                                    <span class="visitor-id-digit">{{ $digit }}</span>
                                @endforeach
                            </div>

                            <button type="button"
                                    class="btn btn-outline-primary btn-sm"
                                    id="copyVisitorId"
                                    data-visitor-id="{{ $visitorId }}">
                                <i class="bi bi-clipboard me-1"></i>
                                <span>Salin ID</span>
                            </button>

                            <div class="alert alert-warning visitor-id-note" role="note">
                                <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>
                                <div>
                                    Simpan ID ini. Anda akan membutuhkannya bersama
                                    Nomor Telepon untuk login pada kunjungan berikutnya.
                                </div>
                            </div>
                        </div>
                    @endif

                    <hr>

                    <div class="row text-start">

                        <div class="col-6 mb-3">

                            <small class="text-muted">

                                Status

                            </small>

                            <h6 class="mb-0">

                                <span class="badge bg-success">

                                    Berhasil

                                </span>

                            </h6>

                        </div>

                        <div class="col-6 mb-3">

                            <small class="text-muted">

                                Waktu Check In

                            </small>

                            <h6 class="mb-0">

                                {{ now()->format('d M Y H:i') }}

                            </h6>

                        </div>

                    </div>

                    <div class="d-grid mt-4">

                        <a href="{{ route('home') }}" class="btn btn-primary btn-lg">

                            <i class="bi bi-house-door-fill me-2"></i>

                            Kembali ke Beranda

                        </a>

                    </div>

                    @if ($visitorId)
                        <p class="text-muted mt-4 mb-0">
                            Halaman ini tidak akan kembali otomatis agar Anda
                            sempat menyimpan ID Visitor.
                        </p>
                    @else
                        <p class="text-muted mt-4 mb-0">
                            Halaman akan kembali otomatis dalam
                            <span id="timer" class="fw-bold text-primary">
                                5
                            </span>
                            detik.
                        </p>
                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@include('partials.footer')

<script>
@if ($visitorId)
// ID Visitor ditampilkan: tidak ada redirect otomatis, sediakan tombol salin
const copyButton = document.getElementById("copyVisitorId");

copyButton.addEventListener("click", async function () {

    const visitorId = this.dataset.visitorId;
    const label = this.querySelector("span");
    const icon = this.querySelector("i");

    let copied = false;

    try {

        if (navigator.clipboard && window.isSecureContext) {

            await navigator.clipboard.writeText(visitorId);

            copied = true;

        } else {

            const helper = document.createElement("textarea");

            helper.value = visitorId;
            helper.setAttribute("readonly", "");
            helper.style.position = "fixed";
            helper.style.opacity = "0";

            document.body.appendChild(helper);
            helper.select();

            copied = document.execCommand("copy");

            document.body.removeChild(helper);

        }

    } catch (error) {

        copied = false;

    }

    label.textContent = copied ? "Tersalin" : "Salin manual";
    icon.className = copied ? "bi bi-clipboard-check me-1" : "bi bi-clipboard me-1";

    setTimeout(function () {

        label.textContent = "Salin ID";
        icon.className = "bi bi-clipboard me-1";

    }, 2000);

});
@else
let time = 5;
const timer = document.getElementById("timer");

const countdown = setInterval(function(){

    time--;

    timer.innerHTML = time;

    if(time <= 0){

        clearInterval(countdown);

        window.location.href = "{{ route('home') }}";

    }

},1000);
@endif
</script>

</body>
</html>