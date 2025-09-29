<?php
session_start();
include("../../config/koneksi.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'agen') {
    die("Access denied");
}

$id_pemilik = intval($_SESSION['id_pemilik'] ?? 0);

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
    JOIN kendaraan k ON s.id_kendaraan = k.id_kendaraan
    WHERE k.id_pemilik = $id_pemilik");
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

<body class="bg-gray-100 min-h-screen flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-green-700 text-white fixed h-full p-6 space-y-6">
        <h2 class="text-2xl font-bold mb-10">RentalKu</h2>
        <nav class="flex flex-col space-y-4">
            <a href="index.php" class="flex items-center space-x-2 hover:bg-green-600 p-2 rounded">
                <i class="ri-home-line"></i><span>Home</span>
            </a>
            <a href="dashboard_agen.php" class="flex items-center space-x-2 hover:bg-green-600 p-2 rounded">
                <i class="ri-dashboard-line"></i><span>Dashboard</span>
            </a>
            <a href="kendaraan.php" class="flex items-center space-x-2 hover:bg-green-600 p-2 rounded">
                <i class="ri-car-line"></i><span>Kendaraan Saya</span>
            </a>
            <a href="../../php/logout.php"  class="flex items-center space-x-2 hover:bg-red-600 p-2 rounded">
                <i class="ri-logout-box-r-line"></i><span>Logout</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="ml-64 p-10 w-full">
        <h1 class="text-3xl font-bold mb-8 text-green-700">🚗 Dashboard Agen</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-green-500 text-white p-6 rounded-2xl shadow-lg flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Total Kendaraan Saya</h3>
                    <p class="text-3xl font-bold counter" data-target="<?= $kendaraan ?>">0</p>
                </div>
                <i class="ri-car-line text-5xl opacity-80"></i>
            </div>

            <div class="bg-green-600 text-white p-6 rounded-2xl shadow-lg flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Kendaraan Sedang Disewa</h3>
                    <p class="text-3xl font-bold counter" data-target="<?= $disewa ?>">0</p>
                </div>
                <i class="ri-car-fill text-5xl opacity-80"></i>
            </div>

            <div class="bg-green-400 text-white p-6 rounded-2xl shadow-lg flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Sewa Aktif</h3>
                    <p class="text-3xl font-bold counter" data-target="<?= $aktif ?>">0</p>
                </div>
                <i class="ri-timer-line text-5xl opacity-80"></i>
            </div>

            <div class="bg-green-700 text-white p-6 rounded-2xl shadow-lg flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Total Pendapatan</h3>
                    <p class="text-2xl font-bold">Rp <span class="counter" data-target="<?= $pendapatan ?>">0</span></p>
                </div>
                <i class="ri-money-dollar-circle-line text-5xl opacity-80"></i>
            </div>
        </div>
    </main>

    <script>
        // Animasi counter
        document.querySelectorAll('.counter').forEach(counter => {
            let target = +counter.getAttribute('data-target');
            let current = 0;
            let increment = Math.ceil(target / 100); // semakin besar 100 semakin cepat

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
