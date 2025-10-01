
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Agen - RentalKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        
        .stat-card {
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
        }
        
        .sidebar-link {
            transition: all 0.2s ease;
        }
        
        .sidebar-link:hover {
            transform: translateX(5px);
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-slide-in {
            animation: slideIn 0.5s ease forwards;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-50 to-green-50 min-h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside class="w-64 bg-gradient-to-b from-green-600 to-green-700 text-white fixed h-full shadow-2xl z-50">
        <div class="p-6">
            <!-- Brand -->
            <div class="flex items-center space-x-3 mb-10">
                <div class="bg-white p-2 rounded-xl">
                    <i class="ri-car-line text-green-600 text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold">RentalKu</h2>
            </div>
            
            <!-- User Info -->
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 mb-6">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="ri-user-star-line text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm opacity-80">Agen</p>
                        <p class="font-semibold"><?= htmlspecialchars($_SESSION['username']) ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Navigation -->
            <nav class="flex flex-col space-y-2">
                <a href="index.php" class="sidebar-link flex items-center space-x-3 hover:bg-white/10 p-3 rounded-xl">
                    <i class="ri-home-line text-xl"></i>
                    <span>Home</span>
                </a>
                <a href="dashboard_agen.php" class="sidebar-link flex items-center space-x-3 bg-white/20 p-3 rounded-xl shadow-lg">
                    <i class="ri-dashboard-line text-xl"></i>
                    <span class="font-semibold">Dashboard</span>
                </a>
                <a href="kendaraan_agen.php" class="sidebar-link flex items-center space-x-3 hover:bg-white/10 p-3 rounded-xl">
                    <i class="ri-car-line text-xl"></i>
                    <span>Kendaraan Saya</span>
                </a>
                <div class="pt-4 mt-4 border-t border-white/20">
                    <a href="../../php/logout.php" class="sidebar-link flex items-center space-x-3 hover:bg-red-500/80 p-3 rounded-xl">
                        <i class="ri-logout-box-r-line text-xl"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </nav>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="ml-64 p-6 md:p-10">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-4xl font-bold text-gray-800 mb-2">
                        <i class="ri-car-line text-green-600"></i> Dashboard Agen
                    </h1>
                    <p class="text-gray-600">Kelola kendaraan rental Anda dengan mudah</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Tanggal</p>
                    <p class="text-lg font-semibold text-gray-800" id="current-date"></p>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Card 1 -->
            <div class="stat-card bg-gradient-to-br from-green-500 to-green-600 text-white rounded-2xl shadow-lg overflow-hidden animate-slide-in" style="animation-delay: 0.1s">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="ri-car-line text-3xl"></i>
                        </div>
                        <i class="ri-arrow-right-up-line text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2 opacity-90">Total Kendaraan</h3>
                    <p class="text-4xl font-bold counter" data-target="<?= $kendaraan ?>">0</p>
                    <p class="text-sm opacity-80 mt-2">Unit yang Anda miliki</p>
                </div>
                <div class="h-2 bg-white/20"></div>
            </div>

            <!-- Card 2 -->
            <div class="stat-card bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl shadow-lg overflow-hidden animate-slide-in" style="animation-delay: 0.2s">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="ri-car-fill text-3xl"></i>
                        </div>
                        <span class="text-xs bg-white/20 px-2 py-1 rounded-full">Disewa</span>
                    </div>
                    <h3 class="text-lg font-semibold mb-2 opacity-90">Sedang Disewa</h3>
                    <p class="text-4xl font-bold counter" data-target="<?= $disewa ?>">0</p>
                    <p class="text-sm opacity-80 mt-2">Unit dalam rental</p>
                </div>
                <div class="h-2 bg-white/20"></div>
            </div>

            <!-- Card 3 -->
            <div class="stat-card bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-2xl shadow-lg overflow-hidden animate-slide-in" style="animation-delay: 0.3s">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="ri-timer-line text-3xl"></i>
                        </div>
                        <span class="text-xs bg-white/20 px-2 py-1 rounded-full">Aktif</span>
                    </div>
                    <h3 class="text-lg font-semibold mb-2 opacity-90">Sewa Aktif</h3>
                    <p class="text-4xl font-bold counter" data-target="<?= $aktif ?>">0</p>
                    <p class="text-sm opacity-80 mt-2">Transaksi berlangsung</p>
                </div>
                <div class="h-2 bg-white/20"></div>
            </div>

            <!-- Card 4 -->
            <div class="stat-card bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-2xl shadow-lg overflow-hidden animate-slide-in" style="animation-delay: 0.4s">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="ri-money-dollar-circle-line text-3xl"></i>
                        </div>
                        <i class="ri-funds-line text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2 opacity-90">Total Pendapatan</h3>
                    <p class="text-2xl font-bold">Rp <span class="counter" data-target="<?= $pendapatan ?>">0</span></p>
                    <p class="text-sm opacity-80 mt-2">Penghasilan keseluruhan</p>
                </div>
                <div class="h-2 bg-white/20"></div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-10 bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">
                <i class="ri-flashlight-line text-green-600"></i> Quick Actions
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="kendaraan_agen.php" class="flex flex-col items-center p-4 bg-green-50 hover:bg-green-100 rounded-xl transition-all duration-200">
                    <i class="ri-car-line text-3xl text-green-600 mb-2"></i>
                    <span class="text-sm font-semibold text-gray-700">Lihat Kendaraan</span>
                </a>
                <a href="tambah_kendaraan.php" class="flex flex-col items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-xl transition-all duration-200">
                    <i class="ri-add-circle-line text-3xl text-blue-600 mb-2"></i>
                    <span class="text-sm font-semibold text-gray-700">Tambah Unit</span>
                </a>
            </div>
        </div>

        
        </div>
    </main>

    <script>
        // Current date
        const dateEl = document.getElementById('current-date');
        const today = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        dateEl.textContent = today.toLocaleDateString('id-ID', options);

        // Counter animation
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