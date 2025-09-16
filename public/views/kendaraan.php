<?php
session_start();
include("../../config/koneksi.php");

// Tentukan filter
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'tersedia';

// Query ambil data kendaraan + pemilik + penyewa (kalau ada)
$query = "
    SELECT k.*, 
           p.nama_pemilik, 
           pl.nama AS nama_penyewa
    FROM kendaraan k
    JOIN pemilik p ON k.id_pemilik = p.id_pemilik
    LEFT JOIN sewa s ON k.id_kendaraan = s.id_kendaraan AND s.status = 'aktif'
    LEFT JOIN pelanggan pl ON s.id_pelanggan = pl.id_pelanggan
";

// Tambahkan filter jika bukan "all"
if ($filter !== 'all') {
    $query .= " WHERE k.status = '$filter'";
}

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Remixicon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    <title>RentalKu</title>
    <style>
        * {
            font-family: Arial, sans-serif;
        }
    </style>
</head>

<body class="bg-[#fff] overflow-x-hidden w-screen">
    <!-- Header -->
    <header class="h-[60px] w-full flex items-center justify-between px-10 fixed z-10 backdrop-blur-[7px] bg-black/20">
        <!-- Logo -->
        <div class="kiri flex items-center w-[70%]">
            <div class="logo">
                <a href="index.php">
                    <img src="../../logo-sementara.jpg" alt="logo" class="h-[50px] w-auto px-2">
                </a>
            </div>

            <!-- Navbar -->
            <nav class="relative w-[65%]">
                <ul class="relative flex gap-x-6 h-full text-black font-semibold text-[18px] w-full px-6 py-2">
                    <li><a href="index.php" class="active">Home</a></li>
                    <li><a href="kendaraan.php">Kendaraan</a></li>
                    <li><a href="pemilik.php">Mitra</a></li>
                </ul>
            </nav>
        </div>

        <!-- Bagian kanan -->
        <div class="kanan flex items-end justify-end w-[380px] h-[50px]">
            <?php if (!isset($_SESSION['user'])): ?>
                <!-- Jika belum login -->
                <div class="navbar-actions flex items-center gap-4 h-full w-full justify-end">
                    <a href="signup.php" class="px-4 py-2 cursor-pointer hover:underline transition font-medium">
                        Sign In
                    </a>
                    <a href="../login.php" class="bg-blue-500 text-white px-4 py-2 rounded-3xl cursor-pointer font-semibold hover:bg-blue-600 transition">
                        Log In
                    </a>
                </div>
            <?php else: ?>
                <!-- Jika sudah login -->
                <div class="flex items-center gap-4 h-full w-full justify-end">
                    <span class="font-medium">Halo, <?php echo $_SESSION['username']; ?> 👋</span>
                    <a href="../../php/logout.php" class="bg-red-500 text-white px-4 py-2 rounded-3xl cursor-pointer font-semibold hover:bg-red-600 transition">
                        Logout
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <!-- Main -->
    <main>
        <!-- Dashboard -->
        <div class="dashboard-container min-h-screen bg-[#f1f4f8] flex flex-col px-10 pt-[80px]">
            <!-- Title -->
            <section class="py-10 px-8 w-full">
                <h2 class="text-3xl font-bold mb-8 text-center">LIST KENDARAAN</h2>

                <!-- Filter -->
                <div class="user-filter flex items-center justify-between mb-6">
                    <ul class="flex space-x-4">
                        <li>
                            <a href="?filter=tersedia"
                                class="px-4 py-2 rounded-lg font-medium 
                                    <?php echo ($filter === 'tersedia') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>">
                                Tersedia
                            </a>
                        </li>
                        <li>
                            <a href="?filter=disewa"
                                class="px-4 py-2 rounded-lg font-medium 
                                    <?php echo ($filter === 'disewa') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>">
                                Disewa
                            </a>
                        </li>
                        <li>
                            <a href="?filter=perbaikan"
                                class="px-4 py-2 rounded-lg font-medium 
                                    <?php echo ($filter === 'perbaikan') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>">
                                Perbaikan
                            </a>
                        </li>
                        <li>
                            <a href="?filter=all"
                                class="px-4 py-2 rounded-lg font-medium 
                                    <?php echo ($filter === 'all') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>">
                                Semua Kendaraan
                            </a>
                        </li>
                    </ul>

                    <!-- Tambah kendaraan hanya untuk admin -->
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') : ?>
                        <a href="tambah_kendaraan.php"
                            class="ml-4 bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-lg font-semibold transition">
                            + Tambah Kendaraan
                        </a>
                    <?php endif; ?>
                </div>

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

                                <!-- Price Tag -->
                                <span class="absolute bottom-47 right-3 bg-black/70 text-white text-sm font-bold px-4 py-2 rounded-lg">
                                    Rp <?= number_format($row['harga_sewa'], 0, ',', '.') ?> / Hari
                                </span>

                                <!-- Image -->
                                <img src="<?= htmlspecialchars($row['gambar']) ?>"
                                    alt="<?= htmlspecialchars($row['merk'] . ' ' . $row['tipe']) ?>"
                                    class="w-full h-52 object-cover rounded-t-2xl">

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
                                        <?php if (!isset($_SESSION['user'])): ?>
                                            <a href="../login.php"
                                                class="mt-5 block w-full bg-yellow-500 text-white py-2 rounded-xl font-semibold text-center hover:bg-yellow-600">
                                                Login untuk Rental
                                            </a>
                                        <?php else: ?>
                                            <a href="form_rental.php?id_kendaraan=<?= $row['id_kendaraan'] ?>"
                                                class="mt-5 block w-full bg-blue-600 text-white py-2 rounded-xl font-semibold text-center hover:bg-blue-700">
                                                Rental Sekarang
                                            </a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <button class="mt-5 w-full bg-gray-400 text-white py-2 rounded-xl font-semibold cursor-not-allowed" disabled>
                                            Tidak Tersedia
                                        </button>
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
