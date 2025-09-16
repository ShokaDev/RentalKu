<?php
session_start();
include("../../config/koneksi.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access denied");
}

// Query ringkasan
$q1 = mysqli_query($conn, "SELECT COUNT(*) AS total_tersedia FROM kendaraan WHERE status='tersedia'");
$tersedia = mysqli_fetch_assoc($q1)['total_tersedia'];

$q2 = mysqli_query($conn, "SELECT COUNT(*) AS total_disewa FROM kendaraan WHERE status='disewa'");
$disewa = mysqli_fetch_assoc($q2)['total_disewa'];

$q3 = mysqli_query($conn, "SELECT COUNT(*) AS sewa_aktif FROM sewa WHERE status='aktif'");
$sewa_aktif = mysqli_fetch_assoc($q3)['sewa_aktif'];

$q4 = mysqli_query($conn, "SELECT COALESCE(SUM(harga_total),0) AS total_pendapatan FROM sewa WHERE status='selesai'");
$pendapatan = mysqli_fetch_assoc($q4)['total_pendapatan'];

$q5 = mysqli_query($conn, "SELECT COUNT(*) AS total_pemilik FROM pemilik");
$pemilik = mysqli_fetch_assoc($q5)['total_pemilik'];

$q6 = mysqli_query($conn, "SELECT COUNT(*) AS total_pelanggan FROM pelanggan");
$pelanggan = mysqli_fetch_assoc($q6)['total_pelanggan'];
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Dashboard Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
</head>
<body class="bg-gray-100 min-h-screen p-10">
  <h1 class="text-3xl font-bold mb-8 text-blue-700">📊 Dashboard Admin</h1>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-blue-500 text-white p-6 rounded-2xl shadow-lg flex items-center justify-between">
      <div>
        <h3 class="text-lg font-semibold">Kendaraan Tersedia</h3>
        <p class="text-3xl font-bold"><?= $tersedia ?></p>
      </div>
      <i class="ri-car-line text-4xl"></i>
    </div>

    <div class="bg-blue-600 text-white p-6 rounded-2xl shadow-lg flex items-center justify-between">
      <div>
        <h3 class="text-lg font-semibold">Kendaraan Disewa</h3>
        <p class="text-3xl font-bold"><?= $disewa ?></p>
      </div>
      <i class="ri-car-fill text-4xl"></i>
    </div>

    <div class="bg-blue-400 text-white p-6 rounded-2xl shadow-lg flex items-center justify-between">
      <div>
        <h3 class="text-lg font-semibold">Sewa Aktif</h3>
        <p class="text-3xl font-bold"><?= $sewa_aktif ?></p>
      </div>
      <i class="ri-timer-line text-4xl"></i>
    </div>

    <div class="bg-blue-700 text-white p-6 rounded-2xl shadow-lg flex items-center justify-between ">
      <div>
        <h3 class="text-lg font-semibold">Total Pendapatan</h3>
        <p class="text-3xl font-bold">Rp <?= number_format($pendapatan,0,',','.') ?></p>
      </div>
      <i class="ri-money-dollar-circle-line text-4xl"></i>
    </div>

    <div class="bg-blue-500 text-white p-6 rounded-2xl shadow-lg flex items-center justify-between">
      <div>
        <h3 class="text-lg font-semibold">Total Pemilik</h3>
        <p class="text-3xl font-bold"><?= $pemilik ?></p>
      </div>
      <i class="ri-user-star-line text-4xl"></i>
    </div>

    <div class="bg-blue-600 text-white p-6 rounded-2xl shadow-lg flex items-center justify-between">
      <div>
        <h3 class="text-lg font-semibold">Total Pelanggan</h3>
        <p class="text-3xl font-bold"><?= $pelanggan ?></p>
      </div>
      <i class="ri-team-line text-4xl"></i>
    </div>
  </div>
</body>
</html>
