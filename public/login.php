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
    <title>Login - RentalKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white shadow-lg rounded-lg w-full max-w-md p-8">
        <h2 class="text-3xl font-bold text-center text-blue-600 mb-6">Login</h2>
        <form action="../php/auth.php" method="POST" class="space-y-5">
            <!-- Username -->
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <div class="mt-1 relative">
                    <input type="text" name="username" id="username" required
                        class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <div class="mt-1 relative">
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>

            <!-- Tombol Login -->
            <button type="submit" name="login"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition">
                Masuk
            </button>

            <!-- Link ke Register -->
            <p class="text-center text-sm text-gray-600 mt-4">
                Belum punya akun?
                <a href="register.php" class="text-blue-600 hover:underline">Daftar</a>
            </p>
        </form>
    </div>
</body>

</html>
