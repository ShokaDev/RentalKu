<?php
include(__DIR__ . "/../../config/koneksi.php");
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'agen') {
    header("Location: /RentalKu/public/login.php?error=unauthorized");
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$stmt = mysqli_prepare($conn, "SELECT id_pemilik FROM pemilik WHERE id_user = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$pemilik = mysqli_stmt_get_result($stmt)->fetch_assoc();
mysqli_stmt_close($stmt);

if (!$pemilik) {
    die("Error: Data pemilik tidak ditemukan.");
}

$pemilik_id = $pemilik['id_pemilik'];
$stmt = mysqli_prepare($conn, "
    SELECT s.id_sewa, k.merk, k.tipe, s.tgl_sewa, s.tgl_kembali, s.lama_sewa, s.harga_total, s.metode_pembayaran
    FROM sewa s
    JOIN kendaraan k ON s.id_kendaraan = k.id_kendaraan
    WHERE k.id_pemilik = ? AND s.status = 'menunggu_konfirmasi'
");
mysqli_stmt_bind_param($stmt, "i", $pemilik_id);
mysqli_stmt_execute($stmt);
$sewa_list = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Sewa - RentalKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .btn-confirm {
            transition: all 0.3s ease;
        }
        
        .btn-confirm:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 6px rgba(34, 197, 94, 0.3);
        }
        
        .btn-reject {
            transition: all 0.3s ease;
        }
        
        .btn-reject:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 6px rgba(239, 68, 68, 0.3);
        }
        
        .badge {
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
        
        .table-row {
            transition: all 0.2s ease;
        }
        
        .table-row:hover {
            background-color: #f9fafb;
            transform: scale(1.01);
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Header -->
    <div class="gradient-bg text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-6 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="bg-white/20 backdrop-blur-sm p-3 rounded-xl">
                        <i class="fas fa-clipboard-check text-3xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold">Konfirmasi Pesanan</h1>
                        <p class="text-purple-100 text-sm mt-1">Kelola pesanan sewa kendaraan Anda</p>
                    </div>
                </div>
                <a href="dashboard_agen.php" class="bg-white/20 hover:bg-white/30 backdrop-blur-sm px-6 py-3 rounded-xl font-semibold transition duration-300 flex items-center space-x-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Dashboard</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-6 py-8">
        <!-- Stats Card -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 card-hover">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="bg-purple-100 p-4 rounded-xl">
                        <i class="fas fa-hourglass-half text-purple-600 text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Pesanan Menunggu</h3>
                        <p class="text-3xl font-bold text-gray-800"><?= mysqli_num_rows($sewa_list) ?></p>
                    </div>
                </div>
                <div class="badge bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full font-semibold">
                    <i class="fas fa-bell mr-2"></i>Perlu Konfirmasi
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-list-alt mr-3 text-purple-600"></i>
                    Daftar Pesanan Sewa
                </h2>
            </div>
            
            <?php if (mysqli_num_rows($sewa_list) > 0): ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="gradient-bg text-white">
                                <th class="px-6 py-4 text-left text-sm font-semibold">
                                    <i class="fas fa-car mr-2"></i>Kendaraan
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">
                                    <i class="fas fa-calendar-alt mr-2"></i>Tanggal Sewa
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">
                                    <i class="fas fa-calendar-check mr-2"></i>Tanggal Kembali
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">
                                    <i class="fas fa-clock mr-2"></i>Lama Sewa
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">
                                    <i class="fas fa-money-bill-wave mr-2"></i>Total Harga
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-semibold">
                                    <i class="fas fa-credit-card mr-2"></i>Pembayaran
                                </th>
                                <th class="px-6 py-4 text-center text-sm font-semibold">
                                    <i class="fas fa-cog mr-2"></i>Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php 
                            mysqli_data_seek($sewa_list, 0);
                            while ($sewa = $sewa_list->fetch_assoc()): 
                            ?>
                                <tr class="table-row">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="bg-purple-100 p-2 rounded-lg">
                                                <i class="fas fa-car text-purple-600"></i>
                                            </div>
                                            <span class="font-semibold text-gray-800">
                                                <?= htmlspecialchars($sewa['merk'] . ' ' . $sewa['tipe']) ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">
                                        <i class="fas fa-calendar text-purple-500 mr-2"></i>
                                        <?= date('d/m/Y', strtotime($sewa['tgl_sewa'])) ?>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">
                                        <i class="fas fa-calendar text-purple-500 mr-2"></i>
                                        <?= date('d/m/Y', strtotime($sewa['tgl_kembali'])) ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                                            <?= $sewa['lama_sewa'] ?> hari
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-green-600">
                                        Rp <?= number_format($sewa['harga_total'], 0, ',', '.') ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-lg text-sm font-medium capitalize">
                                            <?= htmlspecialchars($sewa['metode_pembayaran']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="konfirmasi_sewa.php?action=confirm&id=<?= $sewa['id_sewa'] ?>" 
                                               class="btn-confirm bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-semibold inline-flex items-center space-x-2"
                                               onclick="return confirm('Konfirmasi pesanan ini?')">
                                                <i class="fas fa-check"></i>
                                                <span>Konfirmasi</span>
                                            </a>
                                            <a href="konfirmasi_sewa.php?action=reject&id=<?= $sewa['id_sewa'] ?>" 
                                               class="btn-reject bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-semibold inline-flex items-center space-x-2"
                                               onclick="return confirm('Tolak pesanan ini?')">
                                                <i class="fas fa-times"></i>
                                                <span>Tolak</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="p-12 text-center">
                    <div class="inline-block bg-gray-100 p-6 rounded-full mb-4">
                        <i class="fas fa-inbox text-gray-400 text-5xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Tidak Ada Pesanan</h3>
                    <p class="text-gray-500">Belum ada pesanan yang menunggu konfirmasi saat ini.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Info Card -->
        <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 rounded-lg p-6">
            <div class="flex items-start space-x-3">
                <i class="fas fa-info-circle text-blue-500 text-xl mt-1"></i>
                <div>
                    <h4 class="font-semibold text-blue-900 mb-1">Informasi Penting</h4>
                    <p class="text-blue-800 text-sm">
                        Pastikan untuk memeriksa detail setiap pesanan sebelum mengkonfirmasi. Setelah dikonfirmasi, status kendaraan akan berubah menjadi "Disewa".
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id_sewa = (int)$_GET['id'];
    $action = $_GET['action'];
    $new_status = $action === 'confirm' ? 'aktif' : 'ditolak';

    // Update status sewa
    $stmt = mysqli_prepare($conn, "UPDATE sewa SET status = ? WHERE id_sewa = ?");
    mysqli_stmt_bind_param($stmt, "si", $new_status, $id_sewa);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Update status kendaraan
    $new_kendaraan_status = $action === 'confirm' ? 'disewa' : 'tersedia';
    $stmt = mysqli_prepare($conn, "
        UPDATE kendaraan k
        JOIN sewa s ON k.id_kendaraan = s.id_kendaraan
        SET k.status = ?
        WHERE s.id_sewa = ?
    ");
    mysqli_stmt_bind_param($stmt, "si", $new_kendaraan_status, $id_sewa);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Update status notifikasi menjadi 'dibaca'
    $stmt = mysqli_prepare($conn, "UPDATE notifikasi SET status = 'dibaca' WHERE id_sewa = ?");
    mysqli_stmt_bind_param($stmt, "i", $id_sewa);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // header("Location: konfirmasi_sewa.php");
    exit;
}
?>