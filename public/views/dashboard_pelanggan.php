<?php
session_start();
include("../../config/koneksi.php");

// Pastikan user sudah login sebagai pelanggan
if (!isset($_SESSION['pelanggan_id'])) {
    header("Location: login.php");
    exit;
}

$nama_pelanggan = $_SESSION['pelanggan_nama'] ?? 'Pelanggan';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pelanggan - RentalKu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8fafc;
        }

        .navbar-brand {
            font-weight: bold;
            color: #16a34a !important;
        }

        .welcome {
            margin-top: 50px;
        }

        .card {
            border-radius: 15px;
            transition: transform 0.2s;
        }

        .card:hover {
            transform: scale(1.03);
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="dashboard_pelanggan.php">RentalKu</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="kendaraan.php">Kendaraan</a></li>
                </ul>
                <span class="navbar-text me-3">
                    Halo, <?= htmlspecialchars($nama_pelanggan) ?> 👋
                </span>
                <!-- Perbaikan path logout -->
                <a href="php/logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Konten Dashboard -->
    <div class="container welcome text-center">
        <h1 class="fw-bold">Selamat Datang di Dashboard Pelanggan</h1>
        <p class="text-muted">Kelola perjalananmu dengan mudah bersama RentalKu 🚗</p>

        <div class="row mt-4">
            <!-- Menu Kendaraan -->
            <div class="col-md-4 mb-3">
                <a href="kendaraan.php" class="text-decoration-none text-dark">
                    <div class="card shadow p-4">
                        <h4>🚘 Lihat Kendaraan</h4>
                        <p class="text-muted">Pilih kendaraan sesuai kebutuhanmu.</p>
                    </div>
                </a>
            </div>

            <!-- Menu Riwayat Sewa -->
            <div class="col-md-4 mb-3">
                <a href="pelanggan_kendaraan.php" class="text-decoration-none text-dark">
                    <div class="card shadow p-4">
                        <h4>📜 Riwayat Sewa</h4>
                        <p class="text-muted">Cek daftar kendaraan yang pernah kamu sewa.</p>
                    </div>
                </a>
            </div>

            <!-- Menu Profil -->
            <div class="col-md-4 mb-3">
                <a href="pelanggan.php" class="text-decoration-none text-dark">
                    <div class="card shadow p-4">
                        <h4>👤 Profil</h4>
                        <p class="text-muted">Lihat dan edit data pribadimu.</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
