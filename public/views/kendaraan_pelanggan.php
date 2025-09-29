<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../../config/koneksi.php");
session_start();

// Ambil filter status
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$page   = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$limit  = 6;
$offset = ($page - 1) * $limit;

// Filter query
$where = "";
if ($filter !== 'all') {
    $filter_esc = mysqli_real_escape_string($conn, $filter);
    $where = "WHERE k.status = '$filter_esc'";
}

// Query join ke pemilik
$query = "
    SELECT k.*, p.nama_pemilik 
    FROM kendaraan k
    JOIN pemilik p ON k.id_pemilik = p.id_pemilik
    $where
    LIMIT $limit OFFSET $offset
";
$result = mysqli_query($conn, $query);

// Hitung total data untuk pagination
$count_sql = "
    SELECT COUNT(*) AS total 
    FROM kendaraan k
    JOIN pemilik p ON k.id_pemilik = p.id_pemilik
    $where
";
$count_result = mysqli_query($conn, $count_sql);
$total = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total / $limit);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kendaraan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

    <!-- Navbar -->
    <nav class="bg-blue-600 p-4 text-white flex justify-between items-center shadow">
        <h1 class="text-xl font-bold">RentalKu - Kendaraan</h1>
        <div>
            <a href="dashboard_pelanggan.php"
                class="bg-white text-blue-600 px-4 py-2 rounded-lg font-semibold hover:bg-gray-200 transition">Dashboard</a>
            <a href="../../php/logout.php"
                class="ml-2 bg-red-500 px-4 py-2 rounded-lg font-semibold hover:bg-red-600 transition">Logout</a>
        </div>
    </nav>

    <!-- Konten -->
    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-gray-700">Daftar Kendaraan</h2>
            <form method="get">
                <select name="filter" onchange="this.form.submit()"
                    class="px-3 py-2 border rounded-lg focus:ring focus:ring-blue-300">
                    <option value="all" <?= $filter === 'all' ? 'selected' : '' ?>>Semua</option>
                    <option value="tersedia" <?= $filter === 'tersedia' ? 'selected' : '' ?>>Tersedia</option>
                    <option value="disewa" <?= $filter === 'disewa' ? 'selected' : '' ?>>Disewa</option>
                    <option value="perbaikan" <?= $filter === 'perbaikan' ? 'selected' : '' ?>>Perbaikan</option>
                </select>
            </form>
        </div>

        <!-- Grid Kendaraan -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <?php if (!empty($row['gambar'])) : ?>
                        <img src="../../uploads/<?= htmlspecialchars($row['gambar']) ?>"
                            alt="<?= htmlspecialchars($row['merk']) ?>"
                            class="w-full h-48 object-cover">
                    <?php else : ?>
                        <div class="w-full h-48 flex items-center justify-center bg-gray-200 text-gray-500">
                            Tidak ada gambar
                        </div>
                    <?php endif; ?>

                    <div class="p-4">
                        <h3 class="text-lg font-bold text-gray-800">
                            <?= htmlspecialchars($row['merk']) ?> <?= htmlspecialchars($row['tipe']) ?>
                        </h3>
                        <p class="text-gray-600 text-sm">Tahun: <?= htmlspecialchars($row['tahun']) ?></p>
                        <p class="text-gray-600 text-sm">Agen: <?= htmlspecialchars($row['nama_pemilik']) ?></p>
                        <p class="mt-2 text-sm font-semibold 
                            <?= $row['status'] === 'tersedia' ? 'text-green-600' : 
                               ($row['status'] === 'disewa' ? 'text-red-600' : 'text-yellow-600') ?>">
                            Status: <?= ucfirst($row['status']) ?>
                        </p>

                        <!-- Rental + Harga -->
                        <div class="flex w-full mt-4">
                            <?php if ($row['status'] === 'tersedia') : ?>
                                <a href="form_rental.php?id_kendaraan=<?= $row['id_kendaraan'] ?>"
                                   class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center px-4 py-4 rounded-l-lg font-semibold transition">
                                   Rental
                                </a>
                                <div class="flex-1 bg-blue-700 text-white px-4 py-2 rounded-r-lg text-center">
                                    Rp <?= number_format($row['harga_sewa'], 0, ',', '.') ?>/hari
                                </div>
                            <?php else : ?>
                                <button disabled
                                    class="w-full bg-gray-400 text-white text-center py-2 rounded-lg font-semibold cursor-not-allowed">
                                    Tidak Tersedia
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Pagination -->
        <div class="flex justify-center mt-6 space-x-2">
            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                <a href="?page=<?= $i ?>&filter=<?= $filter ?>"
                    class="px-4 py-2 rounded-lg font-semibold <?= $i == $page ? 'bg-blue-600 text-white' : 'bg-white border text-gray-700 hover:bg-gray-200' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
    </div>
</body>
</html>
