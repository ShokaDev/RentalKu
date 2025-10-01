<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include(__DIR__ . "/../../config/koneksi.php");
session_start();

// Pastikan user pelanggan sudah login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pelanggan') {
    header("Location: /RentalKu/public/login.php?error=unauthorized");
    exit;
}

$pelanggan_id = $_SESSION['user_id'];

// Ambil id kendaraan dari query string
if (!isset($_GET['id'])) {
    header("Location: kendaraan.php");
    exit;
}

$id_kendaraan = (int)$_GET['id'];

// Ambil data kendaraan
$query = mysqli_query($conn, "SELECT * FROM kendaraan WHERE id_kendaraan = $id_kendaraan AND status = 'tersedia'");
$kendaraan = mysqli_fetch_assoc($query);

if (!$kendaraan) {
    echo "<script>alert('Kendaraan tidak tersedia.'); window.location.href='kendaraan.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Sewa - <?= htmlspecialchars($kendaraan['merk'] . ' ' . $kendaraan['tipe']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .input-focus:focus {
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-slide-up {
            animation: slideUp 0.6s ease forwards;
        }

        .decorative-circle {
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
        }
    </style>
    <script>
        function hitungTotal() {
            const tglSewa = document.getElementById('tgl_sewa').value;
            const tglKembali = document.getElementById('tgl_kembali').value;
            const hargaPerHari = <?= (int)$kendaraan['harga_sewa'] ?>;

            if (tglSewa && tglKembali) {
                const start = new Date(tglSewa);
                const end = new Date(tglKembali);
                const diffTime = end - start;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                
                if (diffDays > 0) {
                    const total = diffDays * hargaPerHari;
                    document.getElementById('lama_sewa').value = diffDays;
                    document.getElementById('harga_total').value = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
                    document.getElementById('harga_total_hidden').value = total;
                    
                    // Show summary
                    document.getElementById('summary-section').classList.remove('hidden');
                    document.getElementById('summary-days').textContent = diffDays;
                    document.getElementById('summary-price').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(hargaPerHari);
                    document.getElementById('summary-total').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
                } else {
                    document.getElementById('lama_sewa').value = 0;
                    document.getElementById('harga_total').value = 'Rp 0';
                    document.getElementById('harga_total_hidden').value = 0;
                    document.getElementById('summary-section').classList.add('hidden');
                }
            }
        }

        function validateForm() {
            const lama = document.getElementById('lama_sewa').value;
            if (lama <= 0) {
                alert("Tanggal kembali harus setelah tanggal sewa.");
                return false;
            }
            return true;
        }

        // Set minimum date to today
        window.onload = function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('tgl_sewa').setAttribute('min', today);
            document.getElementById('tgl_kembali').setAttribute('min', today);
        }
    </script>
</head>

<body class="min-h-screen w-full bg-gradient-to-br from-purple-500 to-indigo-500 flex items-center justify-center p-6">
    <div class="w-full max-w-6xl grid lg:grid-cols-2 gap-6">
        
        <!-- Card Kendaraan -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col">
            <div class="relative h-64 bg-gray-50 flex items-center justify-center">
                <?php if (!empty($kendaraan['gambar'])): ?>
                    <img src="../../uploads/<?= htmlspecialchars($kendaraan['gambar']) ?>"
                         alt="<?= htmlspecialchars($kendaraan['merk']) ?>"
                         class="max-h-56 object-contain">
                <?php else: ?>
                    <i class="ri-image-line text-6xl text-gray-400"></i>
                <?php endif; ?>
                <div class="absolute top-3 left-3 bg-green-500 text-white px-3 py-1 rounded-full text-sm font-bold shadow">
                    <i class="ri-checkbox-circle-fill mr-1"></i>Tersedia
                </div>
            </div>
            <div class="p-6 flex-1 flex flex-col">
                <h2 class="text-xl font-bold text-gray-800 mb-3">
                    <?= htmlspecialchars($kendaraan['merk'] . ' ' . $kendaraan['tipe']) ?>
                </h2>
                <p class="text-gray-600 text-sm mb-1"><i class="ri-calendar-line text-purple-600 mr-1"></i>Tahun: <b><?= $kendaraan['tahun'] ?></b></p>
                <p class="text-gray-600 text-sm"><i class="ri-profile-line text-purple-600 mr-1"></i>No Plat: <b><?= $kendaraan['no_plat'] ?></b></p>

                <div class="bg-gradient-to-r from-purple-500 to-indigo-500 rounded-xl p-4 text-white mt-auto">
                    <p class="text-xs opacity-80">Harga / Hari</p>
                    <p class="text-2xl font-bold">Rp <?= number_format($kendaraan['harga_sewa'], 0, ',', '.') ?></p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h3 class="text-xl font-bold text-gray-800 mb-2">Form Pemesanan</h3>
            <p class="text-sm text-gray-500 mb-6">Isi data dengan benar</p>

            <!-- isi form di sini -->

                <form action="simpan_sewa.php" method="POST" class="space-y-3 flex-1 flex flex-col" onsubmit="return validateForm()">
                    <input type="hidden" name="id_kendaraan" value="<?= $kendaraan['id_kendaraan'] ?>">
                    <input type="hidden" name="id_pelanggan" value="<?= $pelanggan_id ?>">
                    <input type="hidden" name="harga_per_hari" value="<?= $kendaraan['harga_sewa'] ?>">
                    <input type="hidden" id="harga_total_hidden" name="harga_total">

                    <!-- Tanggal Sewa -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Tanggal Mulai</label>
                        <input type="date" name="tgl_sewa" id="tgl_sewa" required
                               class="w-full px-3 py-2 border rounded-lg text-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-200"
                               onchange="hitungTotal()">
                    </div>

                    <!-- Tanggal Kembali -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Tanggal Kembali</label>
                        <input type="date" name="tgl_kembali" id="tgl_kembali" required
                               class="w-full px-3 py-2 border rounded-lg text-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-200"
                               onchange="hitungTotal()">
                    </div>

                    <!-- Lama Sewa -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Lama Sewa (hari)</label>
                        <input type="number" id="lama_sewa" readonly
                               class="w-full px-3 py-2 border rounded-lg bg-gray-100 text-sm font-semibold">
                    </div>

                    <!-- Total Harga -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Total Harga</label>
                        <input type="text" id="harga_total" readonly
                               class="w-full px-3 py-2 border rounded-lg bg-gray-100 text-sm font-bold">
                    </div>

                    <!-- Metode Pembayaran -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Metode Pembayaran</label>
                        <select name="metode_pembayaran" id="metode_pembayaran" required
                                class="w-full px-3 py-2 border rounded-lg text-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-200 bg-white">
                            <option value="">-- Pilih Metode --</option>
                            <option value="Transfer Bank">Transfer Bank</option>
                            <option value="E-Wallet">E-Wallet</option>
                            <option value="Tunai">Tunai</option>
                        </select>
                    </div>

                    <!-- Ringkasan -->
                    <div id="summary-section" class="hidden bg-purple-50 border border-purple-200 rounded-lg p-3 text-sm">
                        <p><strong>Durasi:</strong> <span id="summary-days">0</span> hari</p>
                        <p><strong>Harga/hari:</strong> <span id="summary-price">Rp 0</span></p>
                        <p class="font-bold text-purple-600 border-t mt-2 pt-2">
                            Total: <span id="summary-total">Rp 0</span>
                        </p>
                    </div>

                    <!-- Submit -->
                    <button type="submit"
                            class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-3 rounded-lg font-bold text-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
                        Konfirmasi Pemesanan
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>


</html>