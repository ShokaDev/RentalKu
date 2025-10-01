<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include(__DIR__ . "/../../config/koneksi.php");
session_start();

// Role default
$role = $_SESSION['role'] ?? 'guest'; // agen / pelanggan / guest

// Ambil filter status (default = all)
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$filter_esc = mysqli_real_escape_string($conn, $filter);

// Query utama kendaraan (join sewa + pelanggan biar bisa "Rented by")
if ($filter_esc === 'all') {
    $query = "SELECT k.*, p.nama_pemilik, pl.nama AS nama_penyewa 
              FROM kendaraan k
              JOIN pemilik p ON k.id_pemilik = p.id_pemilik
              LEFT JOIN sewa s ON k.id_kendaraan = s.id_kendaraan AND s.status='aktif'
              LEFT JOIN pelanggan pl ON s.id_pelanggan = pl.id_pelanggan
              ORDER BY k.id_kendaraan DESC";
} else {
    $query = "SELECT k.*, p.nama_pemilik, pl.nama AS nama_penyewa 
              FROM kendaraan k
              JOIN pemilik p ON k.id_pemilik = p.id_pemilik
              LEFT JOIN sewa s ON k.id_kendaraan = s.id_kendaraan AND s.status='aktif'
              LEFT JOIN pelanggan pl ON s.id_pelanggan = pl.id_pelanggan
              WHERE k.status = '$filter_esc'
              ORDER BY k.id_kendaraan DESC";
}
$result = mysqli_query($conn, $query);

// Hitung jumlah kendaraan sesuai filter
if ($filter_esc === 'all') {
    $count_sql = "SELECT COUNT(*) AS jumlah FROM kendaraan";
} else {
    $count_sql = "SELECT COUNT(*) AS jumlah FROM kendaraan WHERE status = '$filter_esc'";
}
$count_result = mysqli_query($conn, $count_sql);
$total = 0;
if ($count_result) {
    $row_count = mysqli_fetch_assoc($count_result);
    $total = isset($row_count['jumlah']) ? (int)$row_count['jumlah'] : 0;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kendaraan - RentalKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        
        .filter-btn {
            transition: all 0.3s ease;
        }
        
        .filter-btn:hover {
            transform: translateY(-2px);
        }
        
        .vehicle-card {
            transition: all 0.3s ease;
        }
        
        .vehicle-card:hover {
            transform: translateY(-8px);
        }
        
        .badge-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .8;
            }
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-50 via-blue-50 to-gray-100 overflow-x-hidden">
    <!-- Header -->
    <?php include(__DIR__ . "/../../src/includes/header.php"); ?>

    <!-- Main -->
    <main class="min-h-screen pt-[80px]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            
            <!-- Hero Section -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl mb-4 shadow-lg">
                    <i class="ri-car-line text-3xl text-white"></i>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-3">
                    Katalog Kendaraan
                </h1>
                <p class="text-gray-600 text-lg">Pilih kendaraan impian Anda untuk pengalaman berkendara yang tak terlupakan</p>
            </div>

            <!-- Filter Section -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <!-- Filter Buttons -->
                    <div class="flex flex-wrap gap-3">
                        <a href="?filter=all"
                            class="filter-btn px-6 py-3 rounded-xl font-semibold shadow-md <?= ($filter === 'all') ? 'bg-gradient-to-r from-blue-600 to-blue-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?>">
                            <i class="ri-list-check mr-2"></i>Semua
                        </a>
                        <a href="?filter=tersedia"
                            class="filter-btn px-6 py-3 rounded-xl font-semibold shadow-md <?= ($filter === 'tersedia') ? 'bg-gradient-to-r from-green-500 to-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?>">
                            <i class="ri-checkbox-circle-line mr-2"></i>Tersedia
                        </a>
                        <a href="?filter=disewa"
                            class="filter-btn px-6 py-3 rounded-xl font-semibold shadow-md <?= ($filter === 'disewa') ? 'bg-gradient-to-r from-red-500 to-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?>">
                            <i class="ri-time-line mr-2"></i>Disewa
                        </a>
                        <a href="?filter=perbaikan"
                            class="filter-btn px-6 py-3 rounded-xl font-semibold shadow-md <?= ($filter === 'perbaikan') ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?>">
                            <i class="ri-tools-line mr-2"></i>Perbaikan
                        </a>
                    </div>

                    <!-- Tambah Kendaraan Button -->
                    <?php if ($role === 'agen'): ?>
                        <a href="tambah_kendaraan.php"
                            class="inline-flex items-center justify-center bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200">
                            <i class="ri-add-circle-line text-xl mr-2"></i>
                            Tambah Kendaraan
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Info Count -->
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="flex items-center text-gray-700">
                        <i class="ri-information-line text-blue-600 text-xl mr-2"></i>
                        <?php if ($filter === 'all'): ?>
                            <span>Total <span class="font-bold text-blue-600"><?= $total ?></span> kendaraan tersedia di katalog</span>
                        <?php elseif ($filter === 'tersedia'): ?>
                            <span><span class="font-bold text-green-600"><?= $total ?></span> kendaraan siap untuk dirental</span>
                        <?php elseif ($filter === 'disewa'): ?>
                            <span><span class="font-bold text-red-600"><?= $total ?></span> kendaraan sedang dalam masa rental</span>
                        <?php elseif ($filter === 'perbaikan'): ?>
                            <span><span class="font-bold text-orange-600"><?= $total ?></span> kendaraan dalam proses maintenance</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Grid Kendaraan -->
            <?php if (mysqli_num_rows($result) > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <div class="vehicle-card bg-white rounded-2xl shadow-lg overflow-hidden">
                            <!-- Image Container -->
                            <div class="relative h-56 overflow-hidden">
                                <?php if (!empty($row['gambar'])): ?>
                                    <img src="/RentalKu/uploads/<?= htmlspecialchars($row['gambar']) ?>"
                                        alt="<?= htmlspecialchars($row['merk'] . ' ' . $row['tipe']) ?>"
                                        class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                        <i class="ri-image-line text-6xl text-gray-400"></i>
                                    </div>
                                <?php endif; ?>

                                <!-- Status Badge -->
                                <?php if ($row['status'] === 'tersedia'): ?>
                                    <div class="absolute top-4 left-4 bg-gradient-to-r from-green-500 to-green-600 text-white text-xs font-bold px-4 py-2 rounded-full shadow-lg badge-pulse">
                                        <i class="ri-checkbox-circle-fill mr-1"></i>Tersedia
                                    </div>
                                <?php elseif ($row['status'] === 'disewa'): ?>
                                    <div class="absolute top-4 left-4 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-bold px-4 py-2 rounded-full shadow-lg">
                                        <i class="ri-time-fill mr-1"></i>Disewa
                                    </div>
                                <?php else: ?>
                                    <div class="absolute top-4 left-4 bg-gradient-to-r from-orange-500 to-orange-600 text-white text-xs font-bold px-4 py-2 rounded-full shadow-lg">
                                        <i class="ri-tools-fill mr-1"></i>Maintenance
                                    </div>
                                <?php endif; ?>

                                <!-- Price Badge -->
                                <div class="absolute bottom-4 right-4 bg-black/80 backdrop-blur-sm text-white px-4 py-2 rounded-xl shadow-xl">
                                    <div class="text-xs opacity-90">Per Hari</div>
                                    <div class="text-lg font-bold">Rp <?= number_format($row['harga_sewa'], 0, ',', '.') ?></div>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-6">
                                <!-- Title -->
                                <h3 class="text-xl font-bold text-gray-800 mb-2">
                                    <?= htmlspecialchars($row['merk'] . ' ' . $row['tipe']) ?>
                                </h3>

                                <!-- Info -->
                                <div class="flex items-center text-gray-600 text-sm mb-4">
                                    <i class="ri-calendar-line mr-2"></i>
                                    <span class="mr-4"><?= htmlspecialchars($row['tahun']) ?></span>
                                    <i class="ri-profile-line mr-2"></i>
                                    <span class="uppercase font-semibold"><?= htmlspecialchars($row['no_plat']) ?></span>
                                </div>

                                <!-- Owner Info -->
                                <div class="bg-blue-50 rounded-xl p-3 mb-3">
                                    <div class="flex items-center text-sm">
                                        <i class="ri-user-line text-blue-600 mr-2"></i>
                                        <span class="text-gray-600">Owner:</span>
                                        <span class="ml-2 font-semibold text-gray-800"><?= htmlspecialchars($row['nama_pemilik']) ?></span>
                                    </div>
                                </div>

                                <!-- Renter Info -->
                                <?php if ($row['status'] === 'disewa' && $row['nama_penyewa']): ?>
                                    <div class="bg-red-50 rounded-xl p-3 mb-4">
                                        <div class="flex items-center text-sm">
                                            <i class="ri-user-follow-line text-red-600 mr-2"></i>
                                            <span class="text-gray-600">Dirental oleh:</span>
                                            <span class="ml-2 font-semibold text-red-600"><?= htmlspecialchars($row['nama_penyewa']) ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- Action Buttons -->
                                <div class="space-y-2">
                                    <?php if ($row['status'] === 'tersedia'): ?>
                                        <?php if ($role === 'guest'): ?>
                                            <a href="../login.php"
                                                class="block w-full bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white py-3 rounded-xl font-bold text-center shadow-lg hover:shadow-xl transition-all duration-200">
                                                <i class="ri-login-box-line mr-2"></i>Login untuk Rental
                                            </a>
                                        <?php elseif ($role === 'pelanggan'): ?>
                                            <a href="sewa.php?id=<?= $row['id_kendaraan'] ?>"
                                                class="block w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white py-3 rounded-xl font-bold text-center shadow-lg hover:shadow-xl transition-all duration-200">
                                                <i class="ri-car-line mr-2"></i>Rental Sekarang
                                            </a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <button class="w-full bg-gray-300 text-gray-600 py-3 rounded-xl font-bold cursor-not-allowed" disabled>
                                            <i class="ri-close-circle-line mr-2"></i>Tidak Tersedia
                                        </button>
                                    <?php endif; ?>

                                    <!-- Edit & Delete Buttons for Owner -->
                                    <?php if ($role === 'agen' && isset($_SESSION['id_pemilik']) && $_SESSION['id_pemilik'] == $row['id_pemilik']): ?>
                                        <div class="flex gap-2 mt-3">
                                            <a href="edit_kendaraan.php?id=<?= $row['id_kendaraan'] ?>"
                                                class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2.5 rounded-xl font-semibold text-center transition-all duration-200 shadow-md hover:shadow-lg">
                                                <i class="ri-edit-line mr-1"></i>Edit
                                            </a>
                                            <a href="hapus_kendaraan.php?id=<?= $row['id_kendaraan'] ?>"
                                                onclick="return confirm('Yakin ingin menghapus kendaraan ini?')"
                                                class="flex-1 bg-red-500 hover:bg-red-600 text-white py-2.5 rounded-xl font-semibold text-center transition-all duration-200 shadow-md hover:shadow-lg">
                                                <i class="ri-delete-bin-line mr-1"></i>Hapus
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <!-- Empty State -->
                <div class="bg-white rounded-2xl shadow-lg p-16 text-center">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
                        <i class="ri-car-line text-5xl text-gray-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-700 mb-3">Tidak Ada Kendaraan</h3>
                    <p class="text-gray-500 mb-6">
                        <?php if ($filter === 'all'): ?>
                            Belum ada kendaraan yang terdaftar di sistem
                        <?php elseif ($filter === 'tersedia'): ?>
                            Tidak ada kendaraan yang tersedia saat ini
                        <?php elseif ($filter === 'disewa'): ?>
                            Tidak ada kendaraan yang sedang disewa
                        <?php elseif ($filter === 'perbaikan'): ?>
                            Tidak ada kendaraan dalam perbaikan
                        <?php endif; ?>
                    </p>
                    <?php if ($role === 'agen'): ?>
                        <a href="tambah_kendaraan.php" 
                            class="inline-flex items-center bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-8 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200">
                            <i class="ri-add-circle-line text-xl mr-2"></i>
                            Tambah Kendaraan Pertama
                        </a>
                    <?php else: ?>
                        <a href="?filter=all" 
                            class="inline-flex items-center bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-8 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200">
                            <i class="ri-refresh-line text-xl mr-2"></i>
                            Lihat Semua Kendaraan
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer Spacer -->
    <div class="h-20"></div>
</body>

</html>