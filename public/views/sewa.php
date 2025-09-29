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
    <style>
        .card {
            border-radius: 15px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card img {
            height: 200px;
            object-fit: cover;
            border-radius: 15px 15px 0 0;
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
                    document.getElementById('lama_sewa').value = diffDays;
                    document.getElementById('harga_total').value = diffDays * hargaPerHari;
                } else {
                    document.getElementById('lama_sewa').value = 0;
                    document.getElementById('harga_total').value = 0;
                }
            }
        }
    </script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white p-6 rounded-xl shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-4 text-center text-blue-600">Form Sewa</h2>

        <div class="card mb-4">
            <?php if (!empty($kendaraan['gambar'])): ?>
                <img src="../../uploads/<?= htmlspecialchars($kendaraan['gambar']) ?>"
                    alt="<?= htmlspecialchars($kendaraan['merk']) ?>"
                    class="w-full h-52 object-cover rounded-t-xl">
            <?php else: ?>
                <div class="w-full h-48 flex items-center justify-center bg-gray-200 text-gray-500">
                    Tidak ada gambar
                </div>
            <?php endif; ?>
            <div class="p-4 text-center">
                <h3 class="text-xl font-semibold"><?= htmlspecialchars($kendaraan['merk'] . ' ' . $kendaraan['tipe']) ?></h3>
                <p class="text-gray-600">Harga per hari: Rp <?= number_format($kendaraan['harga_sewa'], 0, ',', '.') ?></p>
            </div>
        </div>


        <form action="simpan_sewa.php" method="POST" class="space-y-4" onsubmit="return validateForm()">
            <input type="hidden" name="id_kendaraan" value="<?= $kendaraan['id_kendaraan'] ?>">
            <input type="hidden" name="id_pelanggan" value="<?= $pelanggan_id ?>">
            <input type="hidden" name="harga_per_hari" value="<?= $kendaraan['harga_sewa'] ?>">

            <div>
                <label for="tgl_sewa" class="block text-sm font-medium text-gray-700">Tanggal Sewa</label>
                <input type="date" name="tgl_sewa" id="tgl_sewa" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    onchange="hitungTotal()">
            </div>

            <div>
                <label for="tgl_kembali" class="block text-sm font-medium text-gray-700">Tanggal Kembali</label>
                <input type="date" name="tgl_kembali" id="tgl_kembali" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    onchange="hitungTotal()">
            </div>

            <div>
                <label for="lama_sewa" class="block text-sm font-medium text-gray-700">Lama Sewa (hari)</label>
                <input type="number" id="lama_sewa" name="lama_sewa" readonly
                    class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>

            <div>
                <label for="harga_total" class="block text-sm font-medium text-gray-700">Total Harga</label>
                <input type="text" id="harga_total" name="harga_total" readonly
                    class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>

            <!-- Tambahan metode pembayaran -->
            <div>
                <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                <select name="metode_pembayaran" id="metode_pembayaran" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">-- Pilih Metode Pembayaran --</option>
                    <option value="Transfer Bank">Transfer Bank</option>
                    <option value="E-Wallet">E-Wallet (OVO, Dana, GoPay, dll)</option>
                    <option value="Tunai">Tunai</option>
                </select>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition">
                Sewa Sekarang
            </button>

            <a href="kendaraan.php"
                class="block text-center mt-2 text-gray-600 hover:underline">
                Kembali ke daftar kendaraan
            </a>
        </form>
    </div>

    <script>
        function validateForm() {
            const lama = document.getElementById('lama_sewa').value;
            if (lama <= 0) {
                alert("Tanggal kembali harus setelah tanggal sewa.");
                return false;
            }
            return true;
        }
    </script>

</body>

</html>