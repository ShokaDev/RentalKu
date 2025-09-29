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
    <title>Daftar Kendaraan</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    <style>
        * {
            font-family: Arial, sans-serif;
        }
    </style>
</head>

<body class="bg-[#fff] overflow-x-hidden w-screen">
    <!-- Header -->
    <?php include(__DIR__ . "/../../src/includes/header.php"); ?>

    <!-- Main -->
    <main>
        <div class="dashboard-container min-h-screen bg-[#f1f4f8] flex flex-col px-10 pt-[80px]">
            <section class="py-10 px-8 w-full">
                <h2 class="text-3xl font-bold mb-8 text-center">LIST KENDARAAN</h2>

                <!-- Filter -->
                <div class="user-filter flex items-center justify-between mb-6">
                    <ul class="flex space-x-4">
                        <li>
                            <a href="?filter=tersedia"
                                class="px-4 py-2 rounded-lg font-medium <?= ($filter === 'tersedia') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>">
                                Tersedia
                            </a>
                        </li>
                        <li>
                            <a href="?filter=disewa"
                                class="px-4 py-2 rounded-lg font-medium <?= ($filter === 'disewa') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>">
                                Disewa
                            </a>
                        </li>
                        <li>
                            <a href="?filter=perbaikan"
                                class="px-4 py-2 rounded-lg font-medium <?= ($filter === 'perbaikan') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>">
                                Perbaikan
                            </a>
                        </li>
                        <li>
                            <a href="?filter=all"
                                class="px-4 py-2 rounded-lg font-medium <?= ($filter === 'all') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>">
                                Semua Kendaraan
                            </a>
                        </li>
                    </ul>

                    <!-- Tambah kendaraan hanya untuk agen -->
                    <?php if ($role === 'agen'): ?>
                        <a href="tambah_kendaraan.php"
                            class="ml-4 bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-lg font-semibold transition">
                            + Tambah Kendaraan
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Info jumlah kendaraan -->
                <p class="text-gray-700 mb-4">
                    <?php if ($filter === 'all'): ?>
                        Total ada <span class="font-bold"><?= $total ?></span> kendaraan.
                    <?php elseif ($filter === 'tersedia'): ?>
                        Ada <span class="font-bold"><?= $total ?></span> kendaraan tersedia.
                    <?php elseif ($filter === 'disewa'): ?>
                        Ada <span class="font-bold"><?= $total ?></span> kendaraan sedang disewa.
                    <?php elseif ($filter === 'perbaikan'): ?>
                        Ada <span class="font-bold"><?= $total ?></span> kendaraan dalam perbaikan.
                    <?php endif; ?>
                </p>

                <!-- Grid Kendaraan -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <div class="bg-white rounded-2xl shadow hover:shadow-lg transition relative overflow-hidden">
                                <!-- Status Badge -->
                                <?php if ($row['status'] === 'tersedia'): ?>
                                    <span class="absolute top-3 left-3 bg-blue-600 text-white text-xs font-semibold px-3 py-1 rounded-full">
                                        Tersedia
                                    </span>
                                <?php elseif ($row['status'] === 'disewa'): ?>
                                    <span class="absolute top-3 left-3 bg-red-600 text-white text-xs font-semibold px-3 py-1 rounded-full">
                                        Disewa
                                    </span>
                                <?php else: ?>
                                    <span class="absolute top-3 left-3 bg-gray-500 text-white text-xs font-semibold px-3 py-1 rounded-full">
                                        Maintenance
                                    </span>
                                <?php endif; ?>

                                <!-- Price -->
                                <span class="absolute bottom-47 right-3 bg-black/70 text-white text-sm font-bold px-4 py-2 rounded-lg">
                                    Rp <?= number_format($row['harga_sewa'], 0, ',', '.') ?> / Hari
                                </span>

                                <!-- Image -->
                                <!-- Image -->
                                <?php if (!empty($row['gambar'])): ?>
                                    <img src="/RentalKu/uploads/<?= htmlspecialchars($row['gambar']) ?>"
                                        alt="<?= htmlspecialchars($row['merk'] . ' ' . $row['tipe']) ?>"
                                        class="w-full h-52 object-cover rounded-t-2xl">
                                <?php else: ?>
                                    <div class="w-full h-52 flex items-center justify-center bg-gray-200 text-gray-500 rounded-t-2xl">
                                        Tidak ada gambar
                                    </div>
                                <?php endif; ?>


                                <!-- Content -->
                                <div class="p-5">
                                    <h3 class="text-lg font-semibold"><?= $row['merk'] . ' ' . $row['tipe'] ?></h3>
                                    <p class="text-sm text-gray-500"><?= $row['tahun'] ?> • <?= strtoupper($row['no_plat']) ?></p>

                                    <!-- Owner -->
                                    <p class="text-sm mt-2"><strong>Owner:</strong> <?= htmlspecialchars($row['nama_pemilik']) ?></p>

                                    <!-- Penyewa -->
                                    <?php if ($row['status'] === 'disewa' && $row['nama_penyewa']): ?>
                                        <p class="text-sm text-red-600"><strong>Rented by:</strong> <?= htmlspecialchars($row['nama_penyewa']) ?></p>
                                    <?php endif; ?>

                                    <!-- Action -->
                                    <?php if ($row['status'] === 'tersedia'): ?>
                                        <?php if ($role === 'guest'): ?>
                                            <a href="../public/login.php"
                                                class="mt-5 block w-full bg-yellow-500 text-white py-2 rounded-xl font-semibold text-center hover:bg-yellow-600">
                                                Login untuk Rental
                                            </a>
                                        <?php elseif ($role === 'pelanggan'): ?>
                                            <a href="sewa.php?id=<?= $row['id_kendaraan'] ?>"
                                                class="mt-5 block w-full bg-blue-600 text-white py-2 rounded-xl font-semibold text-center hover:bg-blue-700">
                                                Rental Sekarang
                                            </a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <button class="mt-5 w-full bg-gray-400 text-white py-2 rounded-xl font-semibold cursor-not-allowed" disabled>
                                            Tidak Tersedia
                                        </button>
                                    <?php endif; ?>

                                    <!-- Edit & Hapus hanya agen -->
                                    <!-- Edit & Hapus hanya untuk agen pemilik kendaraan -->
                                    <?php if ($role === 'agen' && isset($_SESSION['id_pemilik']) && $_SESSION['id_pemilik'] == $row['id_pemilik']): ?>
                                        <div class="flex justify-between mt-3">
                                            <a href="edit_kendaraan.php?id=<?= $row['id_kendaraan'] ?>"
                                                class="bg-amber-500 hover:bg-amber-600 text-white px-3 py-1 rounded-lg text-sm">Edit</a>
                                            <a href="hapus_kendaraan.php?id=<?= $row['id_kendaraan'] ?>"
                                                onclick="return confirm('Yakin ingin menghapus kendaraan ini?')"
                                                class="bg-red-500 hover:bg-red-700 text-white px-3 py-1 rounded-lg text-sm">Hapus</a>
                                        </div>
                                    <?php endif; ?>


                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="col-span-3 text-center text-gray-500">Tidak ada kendaraan.</p>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </main>
</body>

</html>