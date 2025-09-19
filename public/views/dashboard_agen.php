<?php
session_start();
include("../../config/koneksi.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'agen') {
    die("Access denied");
}

$username = $_SESSION['username'];
$qPemilik = mysqli_query($conn, "SELECT id_pemilik FROM pemilik WHERE nama_pemilik = '$username' LIMIT 1");
$pemilik = mysqli_fetch_assoc($qPemilik);
$id_pemilik = $pemilik['id_pemilik'] ?? 0;

$q1 = mysqli_query($conn, "SELECT COUNT(*) AS total_kendaraan FROM kendaraan WHERE id_pemilik=$id_pemilik");
$kendaraan = mysqli_fetch_assoc($q1)['total_kendaraan'];

$q2 = mysqli_query($conn, "SELECT COUNT(*) AS disewa FROM kendaraan WHERE id_pemilik=$id_pemilik AND status='disewa'");
$disewa = mysqli_fetch_assoc($q2)['disewa'];

$q3 = mysqli_query($conn, "SELECT COUNT(*) AS aktif FROM sewa s
    JOIN kendaraan k ON s.id_kendaraan=k.id_kendaraan
    WHERE k.id_pemilik=$id_pemilik AND s.status='aktif'");
$aktif = mysqli_fetch_assoc($q3)['aktif'];

$q4 = mysqli_query($conn, "SELECT COALESCE(SUM(s.harga_total),0) AS pendapatan 
    FROM sewa s
    JOIN kendaraan k ON s.id_kendaraan=k.id_kendaraan
    WHERE k.id_pemilik=$id_pemilik");
$pendapatan = mysqli_fetch_assoc($q4)['pendapatan'];

?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Dashboard Agen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
</head>

<body class="bg-gray-100 min-h-screen">
    <!-- Header -->
    <?php include("../../src/includes/header.php"); ?>

    <!-- Main Content -->
    <main class="pt-[80px] px-10">
        <h1 class="text-3xl font-bold mb-8 text-green-700">🚗 Dashboard Agen</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
            <div class="bg-green-500 text-white p-6 rounded-2xl shadow-lg flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Total Kendaraan Saya</h3>
                    <p class="text-3xl font-bold"><?= $kendaraan ?></p>
                </div>
                <i class="ri-car-line text-5xl opacity-80"></i>
            </div>

            <div class="bg-green-600 text-white p-6 rounded-2xl shadow-lg flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Kendaraan Sedang Disewa</h3>
                    <p class="text-3xl font-bold"><?= $disewa ?></p>
                </div>
                <i class="ri-car-fill text-5xl opacity-80"></i>
            </div>

            <div class="bg-green-400 text-white p-6 rounded-2xl shadow-lg flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Sewa Aktif</h3>
                    <p class="text-3xl font-bold"><?= $aktif ?></p>
                </div>
                <i class="ri-timer-line text-5xl opacity-80"></i>
            </div>

            <div class="bg-green-700 text-white p-6 rounded-2xl shadow-lg flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Total Pendapatan</h3>
                    <p class="text-2xl font-bold">Rp <?= number_format($pendapatan, 0, ',', '.') ?></p>
                </div>
                <i class="ri-money-dollar-circle-line text-5xl opacity-80"></i>
            </div>
        </div>
    </main>
</body>
</html>
