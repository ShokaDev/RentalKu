<?php
session_start();
if (isset($_SESSION['user_id'])) {
    // Kalau sudah login, langsung lempar ke dashboard sesuai role
    if ($_SESSION['role'] === 'admin') {
        header("Location: views/dashboard_admin.php");
    } elseif ($_SESSION['role'] === 'agen') {
        header("Location: views/dashboard_agen.php");
    } elseif ($_SESSION['role'] === 'pelanggan') {
        header("Location: views/dashboard_pelanggan.php");
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - RentalKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center py-10">
    <div class="bg-white shadow-lg rounded-lg w-full max-w-2xl p-8">
        <h2 class="text-3xl font-bold text-center text-green-600 mb-6">Register</h2>
        <form action="../php/register.php" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-5">
            
            <!-- Username -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" name="username" required
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" required
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
            </div>

            <!-- Nama Lengkap -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                <input type="text" name="nama" required
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
            </div>

            <!-- Alamat -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Alamat</label>
                <input type="text" name="alamat" required
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
            </div>

            <!-- No HP -->
            <div>
                <label class="block text-sm font-medium text-gray-700">No HP</label>
                <input type="text" name="no_hp" required
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
            </div>

            <!-- No KTP -->
            <div>
                <label class="block text-sm font-medium text-gray-700">No KTP</label>
                <input type="text" name="no_ktp" required
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
            </div>

            <!-- Role -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Daftar Sebagai</label>
                <select name="role" required
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                    <option value="" disabled selected>Pilih Role</option>
                    <option value="pelanggan">Pelanggan</option>
                    <option value="agen">Agen / Pemilik Kendaraan</option>
                </select>
            </div>

            <!-- Keterangan -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Keterangan (Opsional)</label>
                <input type="text" name="keterangan"
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:outline-none"
                    placeholder="Contoh: Agen khusus mobil premium">
            </div>

            <!-- Tombol -->
            <div class="md:col-span-2">
                <button type="submit" name="register"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-lg transition">
                    Register
                </button>
            </div>
        </form>

        <p class="text-center text-sm text-gray-600 mt-6">
            Sudah punya akun?
            <a href="login.php" class="text-green-600 hover:underline">Login</a>
        </p>
    </div>
</body>

</html>
