<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Sedang Maintenance | Sengked.com</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
          rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f6fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .maintenance-icon {
            font-size: 4rem;
            color: #0d6efd;
        }
    </style>

    @include('partials.head-assets')

</head>

<body>

    <div class="text-center px-3">

        <i class="bi bi-tools maintenance-icon"></i>

        <h2 class="mt-3 fw-bold">Sistem Sedang Maintenance</h2>

        <p class="text-muted">
            Mohon maaf, layanan check-in visitor sedang tidak tersedia<br>
            karena sistem sedang dalam perbaikan. Silakan coba lagi nanti.
        </p>

    </div>

</body>

</html>