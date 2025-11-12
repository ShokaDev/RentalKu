<!-- DASHBOARD ADMIN -->
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - RentalKu</title>
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

<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-gradient-to-b from-blue-600 to-blue-700 text-white fixed h-full shadow-2xl z-50">
        <div class="p-6">
            <!-- Brand -->
            <div class="flex items-center space-x-3 mb-10">
                <div class="bg-white p-2 rounded-xl">
                    <i class="ri-car-line text-blue-600 text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold">RentalKu</h2>
            </div>

            <!-- User Info -->
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 mb-6">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="ri-shield-user-line text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm opacity-80">Admin</p>
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
                <a href="dashboard_admin.php" class="sidebar-link flex items-center space-x-3 bg-white/20 p-3 rounded-xl shadow-lg">
                    <i class="ri-dashboard-line text-xl"></i>
                    <span class="font-semibold">Dashboard</span>
                </a>
                <a href="pemilik.php" class="sidebar-link flex items-center space-x-3 hover:bg-white/10 p-3 rounded-xl">
                    <i class="ri-user-star-line text-xl"></i>
                    <span>Data Pemilik</span>
                </a>
                <a href="pelanggan.php" class="sidebar-link flex items-center space-x-3 hover:bg-white/10 p-3 rounded-xl">
                    <i class="ri-team-line text-xl"></i>
                    <span>Data Pelanggan</span>
                </a>
                <a href="kendaraan_pelanggan.php" class="sidebar-link flex items-center space-x-3 hover:bg-white/10 p-3 rounded-xl">
                    <i class="ri-car-line text-xl"></i>
                    <span>Data Kendaraan</span>
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
                        <i class="ri-dashboard-line text-blue-600"></i> Dashboard Admin
                    </h1>
                    <p class="text-gray-600">Selamat datang kembali, kelola sistem RentalKu Anda</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Tanggal</p>
                    <p class="text-lg font-semibold text-gray-800" id="current-date"></p>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Card 1 -->
            <div class="stat-card bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl shadow-lg overflow-hidden animate-slide-in" style="animation-delay: 0.1s">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="ri-car-line text-3xl"></i>
                        </div>
                        <div class="text-right">
                            <p class="text-sm opacity-90">Status</p>
                            <span class="text-xs bg-white/20 px-2 py-1 rounded-full">Tersedia</span>
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold mb-2 opacity-90">Kendaraan Tersedia</h3>
                    <p class="text-4xl font-bold counter" data-target="<?= $tersedia ?>">0</p>
                </div>
                <div class="h-2 bg-white/20"></div>
            </div>

            <!-- Card 2 -->
            <div class="stat-card bg-gradient-to-br from-indigo-500 to-indigo-600 text-white rounded-2xl shadow-lg overflow-hidden animate-slide-in" style="animation-delay: 0.2s">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="ri-car-fill text-3xl"></i>
                        </div>
                        <div class="text-right">
                            <p class="text-sm opacity-90">Status</p>
                            <span class="text-xs bg-white/20 px-2 py-1 rounded-full">Disewa</span>
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold mb-2 opacity-90">Kendaraan Disewa</h3>
                    <p class="text-4xl font-bold counter" data-target="<?= $disewa ?>">0</p>
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
                        <div class="text-right">
                            <p class="text-sm opacity-90">Status</p>
                            <span class="text-xs bg-white/20 px-2 py-1 rounded-full">Aktif</span>
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold mb-2 opacity-90">Sewa Aktif</h3>
                    <p class="text-4xl font-bold counter" data-target="<?= $sewa_aktif ?>">0</p>
                </div>
                <div class="h-2 bg-white/20"></div>
            </div>

            <!-- Card 4 -->
            <div class="stat-card bg-gradient-to-br from-green-500 to-green-600 text-white rounded-2xl shadow-lg overflow-hidden animate-slide-in" style="animation-delay: 0.4s">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="ri-money-dollar-circle-line text-3xl"></i>
                        </div>
                        <i class="ri-arrow-up-line text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2 opacity-90">Total Pendapatan</h3>
                    <p class="text-3xl font-bold">Rp <span class="counter" data-target="<?= $pendapatan ?>">0</span></p>
                </div>
                <div class="h-2 bg-white/20"></div>
            </div>

            <!-- Card 5 -->
            <div class="stat-card bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-2xl shadow-lg overflow-hidden animate-slide-in" style="animation-delay: 0.5s">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="ri-user-star-line text-3xl"></i>
                        </div>
                        <i class="ri-team-line text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2 opacity-90">Total Pemilik</h3>
                    <p class="text-4xl font-bold counter" data-target="<?= $pemilik ?>">0</p>
                </div>
                <div class="h-2 bg-white/20"></div>
            </div>

            <!-- Card 6 -->
            <div class="stat-card bg-gradient-to-br from-pink-500 to-pink-600 text-white rounded-2xl shadow-lg overflow-hidden animate-slide-in" style="animation-delay: 0.6s">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="ri-team-line text-3xl"></i>
                        </div>
                        <i class="ri-user-add-line text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2 opacity-90">Total Pelanggan</h3>
                    <p class="text-4xl font-bold counter" data-target="<?= $pelanggan ?>">0</p>
                </div>
                <div class="h-2 bg-white/20"></div>
            </div>
        </div>

        
        
        <!-- Quick Actions -->
        <div class="mt-10 bg-white rounded-2xl shadow-xl p-6">
            <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-6 flex items-center justify-center gap-2">
                <i class="ri-flashlight-line text-blue-600 text-2xl"></i>
                Quick Actions
            </h2>

            <div class="flex justify-center">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 max-w-4xl">
                    <!-- Data Pemilik -->
                    <a href="pemilik.php"
                        class="flex flex-col items-center p-6 bg-gradient-to-br from-blue-50 to-blue-100 
                      hover:from-blue-100 hover:to-blue-200 rounded-2xl shadow-sm hover:shadow-md 
                      transition-all duration-300 transform hover:-translate-y-1 w-52">
                        <i class="ri-user-star-line text-4xl text-blue-600 mb-3"></i>
                        <span class="text-sm font-semibold text-gray-700">Data Pemilik</span>
                    </a>

                    <!-- Data Pelanggan -->
                    <a href="pelanggan.php"
                        class="flex flex-col items-center p-6 bg-gradient-to-br from-green-50 to-green-100 
                      hover:from-green-100 hover:to-green-200 rounded-2xl shadow-sm hover:shadow-md 
                      transition-all duration-300 transform hover:-translate-y-1 w-52">
                        <i class="ri-team-line text-4xl text-green-600 mb-3"></i>
                        <span class="text-sm font-semibold text-gray-700">Data Pelanggan</span>
                    </a>

                    <!-- Data Kendaraan -->
                    <a href="kendaraan_pelanggan.php"
                        class="flex flex-col items-center p-6 bg-gradient-to-br from-purple-50 to-purple-100 
                      hover:from-purple-100 hover:to-purple-200 rounded-2xl shadow-sm hover:shadow-md 
                      transition-all duration-300 transform hover:-translate-y-1 w-52">
                        <i class="ri-car-line text-4xl text-purple-600 mb-3"></i>
                        <span class="text-sm font-semibold text-gray-700">Data Kendaraan</span>
                    </a>
                </div>
            </div>
        </div>

        <form method="POST" action="tambah_lokasi.php" class="flex gap-2 my-4">
            <input type="text" name="nama_lokasi" placeholder="Nama lokasi" required class="border px-3 py-2 rounded-lg w-full">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg">Tambah</button>
        </form>
    
        <table class="w-full border">
            <tr class="bg-gray-100">
                <th class="p-2 text-left">Nama Lokasi</th>
                <th class="p-2 text-left">Aksi</th>
            </tr>
            <?php
            $lokasi_list = mysqli_query($conn, "SELECT * FROM lokasi");
            while ($lok = mysqli_fetch_assoc($lokasi_list)): ?>
                <tr>
                    <td class="p-2"><?= htmlspecialchars($lok['nama_lokasi']) ?></td>
                    <td class="p-2">
                        <a href="hapus_lokasi.php?id=<?= $lok['id_lokasi'] ?>" class="text-red-500 hover:underline">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

    </main>

    <script>
        // Current date
        const dateEl = document.getElementById('current-date');
        const today = new Date();
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
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

<!-- ============================================ -->
<!-- DASHBOARD AGEN -->
<!-- ============================================ -->