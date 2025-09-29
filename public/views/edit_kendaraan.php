<?php
include(__DIR__ . "/../../config/koneksi.php");
session_start();

// Pastikan login sebagai agen
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'agen') {
    header("Location: ../../public/login.php?error=unauthorized");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: kendaraan.php?error=invalid_id");
    exit;
}

$id = intval($_GET['id']);
$id_pemilik = $_SESSION['id_pemilik'];

// Ambil data kendaraan milik agen
$query = mysqli_query($conn, "SELECT * FROM kendaraan WHERE id_kendaraan='$id' AND id_pemilik='$id_pemilik'");
if (mysqli_num_rows($query) == 0) {
    header("Location: kendaraan.php?error=not_owner");
    exit;
}
$data = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Kendaraan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-200 via-white to-blue-100 min-h-screen flex items-center justify-center p-6">

    <!-- Card -->
    <div id="formCard" class="bg-white shadow-2xl rounded-2xl p-8 w-full max-w-lg border border-gray-200">
        <!-- Header -->
        <h2 class="text-3xl font-extrabold mb-6 text-center text-blue-700 tracking-wide flex items-center justify-center gap-2">
            ✏️ Edit Kendaraan
        </h2>

        <!-- Form -->
        <form action="../../php/kendaraan/update_kendaraan.php" method="POST" enctype="multipart/form-data" class="space-y-5">
            <input type="hidden" name="id_kendaraan" value="<?= $data['id_kendaraan'] ?>">

            <!-- No Plat -->
            <div>
                <label class="block mb-1 font-semibold text-gray-700">No Plat</label>
                <input type="text" name="no_plat" value="<?= $data['no_plat'] ?>" class="w-full border border-gray-300 px-3 py-2 rounded-lg">
            </div>

            <!-- Merk -->
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Merk</label>
                <input type="text" name="merk" value="<?= $data['merk'] ?>" class="w-full border border-gray-300 px-3 py-2 rounded-lg">
            </div>

            <!-- Tipe -->
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Tipe</label>
                <input type="text" name="tipe" value="<?= $data['tipe'] ?>" class="w-full border border-gray-300 px-3 py-2 rounded-lg">
            </div>

            <!-- Tahun -->
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Tahun</label>
                <input type="number" name="tahun" value="<?= $data['tahun'] ?>" class="w-full border border-gray-300 px-3 py-2 rounded-lg">
            </div>

            <!-- Gambar -->
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Gambar</label>
                <input type="file" name="gambar" class="w-full text-gray-600">
                <?php if (!empty($data['gambar'])): ?>
                    <img src="/RentalKu/uploads/<?= $data['gambar'] ?>" class="mt-3 w-48 h-32 object-cover rounded-lg border">
                <?php endif; ?>
            </div>

            <!-- Harga Sewa -->
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Harga Sewa (Rp)</label>
                <input type="number" name="harga_sewa" value="<?= $data['harga_sewa'] ?>" class="w-full border border-gray-300 px-3 py-2 rounded-lg">
            </div>

            <!-- Status -->
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Status</label>
                <select name="status" class="w-full border border-gray-300 px-3 py-2 rounded-lg">
                    <option value="tersedia" <?= $data['status'] === 'tersedia' ? 'selected' : '' ?>>Tersedia</option>
                    <option value="disewa" <?= $data['status'] === 'disewa' ? 'selected' : '' ?>>Disewa</option>
                    <option value="perbaikan" <?= $data['status'] === 'perbaikan' ? 'selected' : '' ?>>Perbaikan</option>
                </select>
            </div>

            <!-- Tombol -->
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-bold shadow-md">
                💾 Update Kendaraan
            </button>
        </form>
    </div>
</body>
</html>
