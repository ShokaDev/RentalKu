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

<body class="bg-gray-100 min-h-screen flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-blue-700 text-white fixed h-full p-6 space-y-6">
        <h2 class="text-2xl font-bold mb-10">RentalKu</h2>
        <nav class="flex flex-col space-y-4">
            <a href="index.php" class="flex items-center space-x-2 hover:bg-blue-600 p-2 rounded">
                <i class="ri-home-line"></i><span>Home</span>
            </a>
            <a href="dashboard_admin.php" class="flex items-center space-x-2 hover:bg-blue-600 p-2 rounded">
                <i class="ri-dashboard-line"></i><span>Dashboard</span>
            </a>
            <a href="pemilik.php" class="flex items-center space-x-2 hover:bg-blue-600 p-2 rounded">
                <i class="ri-user-star-line"></i><span>Data Pemilik</span>
            </a>
            <a href="pelanggan.php" class="flex items-center space-x-2 hover:bg-blue-600 p-2 rounded">
                <i class="ri-team-line"></i><span>Data Pelanggan</span>
            </a>
            <a href="kendaraan_pelanggan.php" class="flex items-center space-x-2 hover:bg-blue-600 p-2 rounded">
                <i class="ri-car-line"></i><span>Data Kendaraan</span>
            </a>
            <a href="../../php/logout.php" class="flex items-center space-x-2 hover:bg-red-600 p-2 rounded">
                <i class="ri-logout-box-r-line"></i><span>Logout</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="ml-64 p-10 w-full">
        <h1 class="text-3xl font-bold mb-8 text-blue-700">📊 Dashboard Admin</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-blue-500 text-white p-6 rounded-2xl shadow-lg flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Kendaraan Tersedia</h3>
                    <p class="text-3xl font-bold counter" data-target="<?= $tersedia ?>">0</p>
                </div>
                <i class="ri-car-line text-5xl opacity-80"></i>
            </div>

            <div class="bg-blue-600 text-white p-6 rounded-2xl shadow-lg flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Kendaraan Disewa</h3>
                    <p class="text-3xl font-bold counter" data-target="<?= $disewa ?>">0</p>
                </div>
                <i class="ri-car-fill text-5xl opacity-80"></i>
            </div>

            <div class="bg-blue-400 text-white p-6 rounded-2xl shadow-lg flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Sewa Aktif</h3>
                    <p class="text-3xl font-bold counter" data-target="<?= $sewa_aktif ?>">0</p>
                </div>
                <i class="ri-timer-line text-5xl opacity-80"></i>
            </div>

            <div class="bg-blue-700 text-white p-6 rounded-2xl shadow-lg flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Total Pendapatan</h3>
                    <p class="text-2xl font-bold">Rp <span class="counter" data-target="<?= $pendapatan ?>">0</span></p>
                </div>
                <i class="ri-money-dollar-circle-line text-5xl opacity-80"></i>
            </div>

            <div class="bg-blue-500 text-white p-6 rounded-2xl shadow-lg flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Total Pemilik</h3>
                    <p class="text-3xl font-bold counter" data-target="<?= $pemilik ?>">0</p>
                </div>
                <i class="ri-user-star-line text-5xl opacity-80"></i>
            </div>

            <div class="bg-blue-600 text-white p-6 rounded-2xl shadow-lg flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Total Pelanggan</h3>
                    <p class="text-3xl font-bold counter" data-target="<?= $pelanggan ?>">0</p>
                </div>
                <i class="ri-team-line text-5xl opacity-80"></i>
            </div>
        </div>
    </main>

    <script>
        // Animasi counter
        document.querySelectorAll('.counter').forEach(counter => {
            let target = +counter.getAttribute('data-target');
            let current = 0;
            let increment = Math.ceil(target / 100);

            let updateCounter = () => {
                if (current < target) {
                    current += increment;
                    if (current > target) current = target;
                    counter.innerText = new Intl.NumberFormat('id-ID').format(current);
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.innerText = new Intl.NumberFormat('id-ID').format(target);
                }
            };
            updateCounter();
        });
    </script>
</body>
</html>
