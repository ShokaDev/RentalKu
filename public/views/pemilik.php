<?php
session_start();
// Contoh login sederhana (buat demo aja)
if (!isset($_SESSION['user'])) {
    // $_SESSION['user'] = "Faharel"; // Contoh kalau sudah login
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    <title>Data Pemilik - RentalKu</title>
    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        
        .table-row-hover {
            transition: all 0.2s ease;
        }
        
        .table-row-hover:hover {
            transform: scale(1.01);
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-50 to-blue-50 overflow-x-hidden">
    <?php include("../../src/includes/header.php"); ?>

    <main class="pt-[80px] px-4 md:px-10 pb-10 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-800 mb-2">
                            <i class="ri-user-star-line text-blue-600"></i> Data Pemilik / Agen
                        </h1>
                        <p class="text-gray-600">Kelola data pemilik kendaraan dan agen rental</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Total Pemilik</p>
                        <p class="text-3xl font-bold text-blue-600">
                            <?php
                            include("../../config/koneksi.php");
                            $count = mysqli_query($conn, "SELECT COUNT(*) as total FROM pemilik");
                            echo mysqli_fetch_assoc($count)['total'];
                            ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <!-- Table Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <i class="ri-list-check mr-2"></i>
                            Daftar Pemilik Kendaraan
                        </h2>
                        <div class="flex items-center space-x-2 text-white text-sm">
                            <i class="ri-user-line"></i>
                            <span>Admin Panel</span>
                        </div>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 border-b-2 border-gray-200">
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">No</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Nama Pemilik</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Alamat</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">No HP</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">No KTP</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Keterangan</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php
                            $result = mysqli_query($conn, "SELECT * FROM pemilik ORDER BY id_pemilik DESC");
                            $no = 1;
                            if (mysqli_num_rows($result) > 0):
                                while ($row = mysqli_fetch_assoc($result)):
                            ?>
                                <tr class="table-row-hover hover:bg-blue-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-semibold text-gray-700"><?= $no ?></span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mr-3">
                                                <i class="ri-user-line text-white text-lg"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-gray-800"><?= htmlspecialchars($row['nama_pemilik']) ?></p>
                                                <p class="text-xs text-gray-500">ID: <?= $row['id_pemilik'] ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-start">
                                            <i class="ri-map-pin-line text-gray-400 mr-2 mt-1"></i>
                                            <span class="text-sm text-gray-700"><?= htmlspecialchars($row['alamat']) ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <i class="ri-phone-line text-gray-400 mr-2"></i>
                                            <span class="text-sm text-gray-700"><?= htmlspecialchars($row['no_hp']) ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <i class="ri-bank-card-line text-gray-400 mr-2"></i>
                                            <span class="text-sm text-gray-700 font-mono"><?= htmlspecialchars($row['no_ktp']) ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-gray-600"><?= htmlspecialchars($row['keterangan']) ?: '-' ?></span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="hapus_pemilik.php?id=<?= urlencode($row['id_pemilik']) ?>"
                                           onclick="return confirm('Yakin ingin menghapus data <?= htmlspecialchars($row['nama_pemilik']) ?>?')"
                                           class="inline-flex items-center bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 shadow-md hover:shadow-lg">
                                            <i class="ri-delete-bin-line mr-1"></i>
                                            Hapus
                                        </a>
                                    </td>
                                </tr>
                            <?php
                                    $no++;
                                endwhile;
                            else:
                            ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                <i class="ri-user-line text-3xl text-gray-400"></i>
                                            </div>
                                            <p class="text-gray-500 font-semibold">Belum ada data pemilik</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Table Footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <p class="text-sm text-gray-600">
                        Menampilkan <span class="font-semibold"><?= mysqli_num_rows($result) ?></span> data pemilik
                    </p>
                </div>
            </div>
        </div>
    </main>
</body>

</html
