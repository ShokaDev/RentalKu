<?php
include("../../config/koneksi.php");
session_start();

// Pastikan hanya agen
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'agen') {
    header("Location: ../login.php?error=unauthorized");
    exit;
}

// Ambil data agen dari session
$id_pemilik = $_SESSION['user_id'];   // asumsi saat login id_pemilik disimpan di session user_id
$nama_pemilik = $_SESSION['username']; // asumsi nama agen disimpan di session username
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Kendaraan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
</head>
<body class="bg-gradient-to-br from-green-200 via-white to-green-100 min-h-screen flex items-center justify-center p-6">

    <!-- Card -->
    <div id="formCard" class="bg-white shadow-2xl rounded-2xl p-8 w-full max-w-lg border border-gray-200 opacity-0 translate-y-8">
        <!-- Header -->
        <h2 class="text-3xl font-extrabold mb-6 text-center text-green-700 tracking-wide flex items-center justify-center gap-2">
            🚗 Tambah Kendaraan
        </h2>

        <!-- Form -->
        <form action="../../php/kendaraan/simpan_kendaraan.php" method="POST" enctype="multipart/form-data" class="space-y-5">
            
            <!-- Nama Agen (Auto dari Session) -->
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Nama Agen</label>
                <input type="text" value="<?= htmlspecialchars($nama_pemilik) ?>" 
                       class="w-full border border-gray-300 bg-gray-100 px-3 py-2 rounded-lg" disabled>
                <input type="hidden" name="id_pemilik" value="<?= $id_pemilik ?>">
            </div>

            <!-- No Plat -->
            <div>
                <label class="block mb-1 font-semibold text-gray-700">No Plat</label>
                <input type="text" name="no_plat" placeholder="Contoh: B 1234 XY" class="w-full border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 px-3 py-2 rounded-lg transition" required>
            </div>

            <!-- Merk -->
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Merk</label>
                <input type="text" name="merk" placeholder="Contoh: Toyota" class="w-full border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 px-3 py-2 rounded-lg transition">
            </div>

            <!-- Tipe -->
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Tipe</label>
                <input type="text" name="tipe" placeholder="Contoh: Avanza" class="w-full border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 px-3 py-2 rounded-lg transition">
            </div>

            <!-- Tahun -->
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Tahun</label>
                <input type="number" name="tahun" min="1990" max="2099" placeholder="Contoh: 2023" class="w-full border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 px-3 py-2 rounded-lg transition">
            </div>

            <!-- Gambar -->
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Gambar</label>
                <input type="file" name="gambar" id="gambarInput" accept="image/*" class="w-full text-gray-600">
                <p id="fileError" class="text-red-500 text-sm mt-1 hidden"></p>
                <img id="previewImage" src="" alt="Preview Gambar" class="mt-3 hidden w-48 h-32 object-cover rounded-lg border border-gray-300 shadow-sm">
            </div>

            <!-- Harga Sewa -->
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Harga Sewa (Rp)</label>
                <input type="number" name="harga_sewa" step="0.01" placeholder="Contoh: 350000" class="w-full border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 px-3 py-2 rounded-lg transition" required>
            </div>

            <!-- Status -->
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Status</label>
                <select name="status" class="w-full border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 px-3 py-2 rounded-lg transition">
                    <option value="tersedia">Tersedia</option>
                    <option value="disewa">Disewa</option>
                    <option value="perbaikan">Perbaikan</option>
                </select>
            </div>

            <!-- Tombol -->
            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 active:bg-green-700 text-white py-3 rounded-lg font-bold shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5 animate-pulse">
                💾 Simpan Kendaraan
            </button>
        </form>
    </div>

    <script>
        // Animasi GSAP fade-in
        gsap.to("#formCard", {duration: 1, opacity: 1, y: 0, ease: "power3.out"});

        // Preview Gambar + Validasi
        document.getElementById("gambarInput").addEventListener("change", function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById("previewImage");
            const errorMsg = document.getElementById("fileError");

            if (file) {
                const fileSize = file.size / 1024 / 1024; // MB
                const fileType = file.type;

                if (!fileType.match("image.*")) {
                    errorMsg.textContent = "❌ File harus berupa gambar (JPG/PNG).";
                    errorMsg.classList.remove("hidden");
                    preview.classList.add("hidden");
                    event.target.value = ""; // reset input
                    return;
                }

                if (fileSize > 2) { // > 2MB
                    errorMsg.textContent = "❌ Ukuran file maksimal 2MB.";
                    errorMsg.classList.remove("hidden");
                    preview.classList.add("hidden");
                    event.target.value = ""; // reset input
                    return;
                }

                // Jika lolos validasi
                errorMsg.classList.add("hidden");
                preview.src = URL.createObjectURL(file);
                preview.classList.remove("hidden");
            } else {
                preview.classList.add("hidden");
                errorMsg.classList.add("hidden");
            }
        });
    </script>
</body>
</html>
