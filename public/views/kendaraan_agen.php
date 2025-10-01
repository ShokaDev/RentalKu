<?php
session_start();
include("../../config/koneksi.php");

// Cek role agen
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'agen') {
    die("Access denied");
}

$id_pemilik = intval($_SESSION['id_pemilik'] ?? 0);

// Ambil semua kendaraan milik pemilik yang login
$q = mysqli_query($conn, "SELECT * FROM kendaraan WHERE id_pemilik = $id_pemilik ORDER BY id_kendaraan DESC");
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kendaraan Saya - RentalKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
</head>


<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-gradient-to-b from-green-600 to-green-700 text-white fixed h-full shadow-2xl z-50">
        <div class="p-6">
            <div class="flex items-center space-x-3 mb-10">
                <div class="bg-white p-2 rounded-lg">
                    <i class="ri-car-line text-green-600 text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold">RentalKu</h2>
            </div>
            
            <nav class="flex flex-col space-y-2">
                <a href="dashboard_agen.php" class="flex items-center space-x-3 hover:bg-white/10 p-3 rounded-lg transition-all duration-200">
                    <i class="ri-dashboard-line text-xl"></i>
                    <span>Dashboard</span>
                </a>
                <a href="kendaraan.php" class="flex items-center space-x-3 bg-white/20 p-3 rounded-lg shadow-lg">
                    <i class="ri-car-line text-xl"></i>
                    <span class="font-semibold">Kendaraan Saya</span>
                </a>
                <a href="../../php/logout.php" class="flex items-center space-x-3 hover:bg-red-500/80 p-3 rounded-lg transition-all duration-200 mt-6">
                    <i class="ri-logout-box-r-line text-xl"></i>
                    <span>Logout</span>
                </a>
            </nav>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="ml-64 p-6 md:p-10">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-2">
                <h1 class="text-4xl font-bold text-gray-800">
                    <i class="ri-car-line text-green-600"></i> Kendaraan Saya
                </h1>
                <a href="tambah_kendaraan.php" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200 flex items-center space-x-2">
                    <i class="ri-add-line text-xl"></i>
                    <span>Tambah Kendaraan</span>
                </a>
            </div>
            <p class="text-gray-600">Kelola semua kendaraan rental Anda di sini</p>
        </div>

        <!-- Grid Kendaraan -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (mysqli_num_rows($q) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($q)): ?>
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden group">
                        <!-- Gambar Kendaraan -->
                        <div class="relative overflow-hidden">
                            <?php if (!empty($row['gambar'])): ?>
                                <img src="/RentalKu/uploads/<?= htmlspecialchars($row['gambar']) ?>" 
                                     alt="<?= htmlspecialchars($row['merk'] . ' ' . $row['tipe']) ?>" 
                                     class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-500">
                            <?php else: ?>
                                <div class="w-full h-48 flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                    <i class="ri-image-line text-6xl text-gray-400"></i>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Status Badge -->
                            <?php if ($row['status'] === 'disewa'): ?>
                                <div class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold shadow-lg">
                                    <i class="ri-time-line"></i> Disewa
                                </div>
                            <?php else: ?>
                                <div class="absolute top-4 right-4 bg-green-500 text-white px-3 py-1 rounded-full text-sm font-semibold shadow-lg">
                                    <i class="ri-check-line"></i> Tersedia
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Info Kendaraan -->
                        <div class="p-5">
                            <h3 class="text-xl font-bold text-gray-800 mb-2">
                                <?= htmlspecialchars($row['merk'] . ' ' . $row['tipe']) ?>
                            </h3>
                            
                            <div class="flex items-center text-gray-600 mb-4">
                                <i class="ri-calendar-line mr-2"></i>
                                <span>Tahun <?= htmlspecialchars($row['tahun']) ?></span>
                            </div>

                            <?php if (isset($row['harga_sewa'])): ?>
                                <div class="flex items-center text-green-600 font-bold text-lg mb-4">
                                    <i class="ri-money-dollar-circle-line mr-2"></i>
                                    <span>Rp <?= number_format($row['harga_sewa'], 0, ',', '.') ?>/hari</span>
                                </div>
                            <?php endif; ?>

                            <!-- Tombol Aksi -->
                            <div class="space-y-2">
                                <?php if ($row['status'] === 'disewa'): ?>
                                    <a href="../../php/kendaraan/set_tersedia.php?id=<?= $row['id_kendaraan'] ?>"
                                       onclick="return confirm('Tandai kendaraan ini sebagai tersedia?')"
                                       class="block w-full bg-yellow-500 hover:bg-yellow-600 text-white py-2.5 rounded-xl text-center font-semibold transition-all duration-200 shadow-md hover:shadow-lg">
                                        <i class="ri-check-double-line"></i> Tandai Tersedia
                                    </a>
                                <?php else: ?>
                                    <div class="block w-full bg-green-100 text-green-700 py-2.5 rounded-xl text-center font-semibold border-2 border-green-300">
                                        <i class="ri-checkbox-circle-line"></i> Kendaraan Tersedia
                                    </div>
                                <?php endif; ?>

                                <div class="flex space-x-2">
                                    <a href="edit_kendaraan.php?id=<?= $row['id_kendaraan'] ?>"
                                       class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2.5 rounded-xl text-center font-semibold transition-all duration-200 shadow-md hover:shadow-lg">
                                        <i class="ri-edit-line"></i> Edit
                                    </a>

                                    <a href="hapus_kendaraan.php?id=<?= $row['id_kendaraan'] ?>"
                                       onclick="return confirm('Yakin ingin menghapus kendaraan ini?')"
                                       class="flex-1 bg-red-500 hover:bg-red-600 text-white py-2.5 rounded-xl text-center font-semibold transition-all duration-200 shadow-md hover:shadow-lg">
                                        <i class="ri-delete-bin-line"></i> Hapus
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <!-- Empty State -->
                <div class="col-span-full flex flex-col items-center justify-center py-20">
                    <div class="bg-gray-100 p-8 rounded-full mb-6">
                        <i class="ri-car-line text-6xl text-gray-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-700 mb-2">Belum Ada Kendaraan</h3>
                    <p class="text-gray-500 mb-6">Mulai tambahkan kendaraan rental Anda</p>
                    <a href="tambah_kendaraan.php" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200">
                        <i class="ri-add-line"></i> Tambah Kendaraan Pertama
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>