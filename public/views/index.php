<?php
session_start();

// Default user info
$username = null;
$role = null;

// Kalau user sudah login
if (isset($_SESSION['user_id'])) {
    $username = $_SESSION['username'];
    $role     = $_SESSION['role'];
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RentalKu</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>
    <!-- CSS -->
    <style>
        * {
            font-family: Arial, sans-serif;
        }

        body {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body class="bg-white overflow-x-hidden w-screen scroll-smooth">
    <!-- Header -->
    <?php include(__DIR__ . "/../../src/includes/header.php"); ?>

    <!-- Main -->
    <main>
        <!-- Hero Section -->
        <section class="h-[90vh] bg-gradient-to-r from-blue-600 to-indigo-700 text-white flex flex-col md:flex-row items-center justify-between px-10 pt-[60px]">
            <!-- Title & Description -->
            <div class="max-w-xl space-y-6">
                <h1 class="text-5xl md:text-6xl font-extrabold leading-tight drop-shadow-lg">
                    Rental Mobil Premium
                </h1>
                <h2 class="text-2xl md:text-3xl font-semibold text-yellow-300">
                    Mudah, cepat, dan terpercaya
                </h2>
                <p class="text-lg text-gray-100 leading-relaxed">
                    Nikmati pengalaman sewa mobil yang nyaman bersama <span class="font-bold">RentalKu</span>.
                    Pilihan mobil berkualitas dengan harga terjangkau. Proses cepat, fleksibel, sesuai kebutuhan perjalanan Anda.
                </p>
                <a href="#list-mobil"
                    class="inline-block bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold px-6 py-3 rounded-full shadow-lg transition">
                    Lihat Kendaraan
                </a>
            </div>

            <!-- Hero Image -->
            <div class="mt-10 md:mt-0">
                <img src="../../dashboard.png" alt="Rental Mobil"
                    class="h-[320px] md:h-[450px] w-auto object-contain drop-shadow-2xl">
            </div>
        </section>

        <!-- List Mobil -->
        <section id="list-mobil" class="py-20 px-10 bg-gray-50">
            <h2 class="text-4xl font-bold mb-12 text-center text-gray-800">🚗 Daftar Kendaraan</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                <?php
                include(__DIR__ . "/../../config/koneksi.php");

                $query = "
                SELECT k.*, 
                       p.nama_pemilik, 
                       pl.nama AS nama_penyewa
                FROM kendaraan k
                JOIN pemilik p ON k.id_pemilik = p.id_pemilik
                LEFT JOIN sewa s ON k.id_kendaraan = s.id_kendaraan AND s.status = 'aktif'
                LEFT JOIN pelanggan pl ON s.id_pelanggan = pl.id_pelanggan
            ";
                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0):
                    while ($row = mysqli_fetch_assoc($result)):
                ?>
                        <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-1 overflow-hidden">
                            <!-- Image -->
                            <div class="relative">
                                <img src="/RentalKu/uploads/<?= htmlspecialchars($row['gambar']) ?>"
                                    alt="<?= htmlspecialchars($row['merk'] . ' ' . $row['tipe']) ?>"
                                    class="w-full h-52 object-cover">

                                <!-- Status Badge -->
                                <?php if ($row['status'] === 'tersedia'): ?>
                                    <span class="absolute top-3 left-3 bg-green-500 text-white text-xs font-semibold px-3 py-1 rounded-full shadow">
                                        Tersedia
                                    </span>
                                <?php elseif ($row['status'] === 'disewa'): ?>
                                    <span class="absolute top-3 left-3 bg-red-500 text-white text-xs font-semibold px-3 py-1 rounded-full shadow">
                                        Disewa
                                    </span>
                                <?php else: ?>
                                    <span class="absolute top-3 left-3 bg-gray-500 text-white text-xs font-semibold px-3 py-1 rounded-full shadow">
                                        Maintenance
                                    </span>
                                <?php endif; ?>

                                <!-- Price -->
                                <span class="absolute bottom-3 right-3 bg-black/70 text-yellow-300 text-sm font-bold px-4 py-2 rounded-lg">
                                    Rp <?= number_format($row['harga_sewa'], 0, ',', '.') ?>/hari
                                </span>
                            </div>

                            <!-- Content -->
                            <div class="p-6 space-y-2">
                                <h3 class="text-xl font-semibold text-gray-800"><?= $row['merk'] . ' ' . $row['tipe'] ?></h3>
                                <p class="text-sm text-gray-500"><?= $row['tahun'] ?> • <?= strtoupper($row['no_plat']) ?></p>
                                <p class="text-sm"><strong>Owner:</strong> <?= htmlspecialchars($row['nama_pemilik']) ?></p>

                                <?php if ($row['status'] === 'disewa' && $row['nama_penyewa']): ?>
                                    <p class="text-sm text-red-600"><strong>Rented by:</strong> <?= htmlspecialchars($row['nama_penyewa']) ?></p>
                                <?php endif; ?>

                                <!-- Action Button -->
                                <?php if ($row['status'] === 'tersedia'): ?>
                                    <?php if (!isset($_SESSION['user_id'])): ?>
                                        <a href="../public/login.php"
                                            class="mt-4 block w-full bg-yellow-400 text-gray-900 py-2 rounded-lg font-semibold text-center hover:bg-yellow-500 transition">
                                            Login untuk Rental
                                        </a>
                                    <?php else: ?>
                                        <a href="sewa.php?id=<?= $row['id_kendaraan'] ?>"
                                            class="mt-4 block w-full bg-blue-600 text-white py-2 rounded-lg font-semibold text-center hover:bg-blue-700 transition">
                                            Rental Sekarang
                                        </a>

                                    <?php endif; ?>
                                <?php else: ?>
                                    <button class="mt-4 w-full bg-gray-400 text-white py-2 rounded-lg font-semibold cursor-not-allowed" disabled>
                                        Tidak Tersedia
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php
                    endwhile;
                else:
                    ?>
                    <p class="col-span-3 text-center text-gray-500">Belum ada kendaraan tersedia</p>
                <?php endif; ?>
            </div>
        </section>
    </main>


    <script>
        feather.replace();
    </script>

</body>

</html>