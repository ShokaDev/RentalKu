<?php
session_start();
if (isset($_SESSION['user_id'])) {
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
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - RentalKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }

        .input-focus:focus {
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .float-animation {
            animation: float 3s ease-in-out infinite;
        }

        .decorative-circle {
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
        }

        .step-indicator {
            transition: all 0.3s ease;
        }

        .step-indicator.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
    </style>
</head>

<body class="gradient-bg h-screen flex items-center justify-center relative overflow-hidden">
    <!-- Decorative Elements -->
    <div class="decorative-circle w-80 h-80 bg-white top-0 -left-40"></div>
    <div class="decorative-circle w-80 h-80 bg-white bottom-0 -right-40"></div>

    <div class="w-full max-w-3xl relative z-10 px-4">
        <!-- Register Card -->
        <div class="glass-effect rounded-2xl shadow-2xl p-6 md:p-8 max-h-[90vh] overflow-y-auto">
            <!-- Step Indicator -->
            <div class="flex justify-center mb-6">
                <div class="flex items-center space-x-3">
                    <div class="step-indicator active flex items-center justify-center w-8 h-8 rounded-full bg-purple-600 text-white text-sm font-bold">
                        1
                    </div>
                    <div class="w-12 h-1 bg-purple-200"></div>
                    <div class="step-indicator flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 text-gray-500 text-sm font-bold">
                        2
                    </div>
                </div>
            </div>

            <!-- FORM -->
            <form action="../php/register.php" method="POST" class="space-y-4 text-sm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Username -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">
                            <i class="ri-user-line mr-1"></i>Username
                        </label>
                        <input type="text" name="username" required
                            class="input-focus w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none"
                            placeholder="Masukkan username">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">
                            <i class="ri-mail-line mr-1"></i>Email
                        </label>
                        <input type="email" name="email" required
                            class="input-focus w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none"
                            placeholder="email@example.com">
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">
                            <i class="ri-lock-line mr-1"></i>Password
                        </label>
                        <input type="password" name="password" required
                            class="input-focus w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none"
                            placeholder="Minimal 6 karakter">
                    </div>

                    <!-- Nama -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">
                            <i class="ri-user-heart-line mr-1"></i>Nama Lengkap
                        </label>
                        <input type="text" name="nama" required
                            class="input-focus w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none"
                            placeholder="Nama lengkap Anda">
                    </div>

                    <!-- No HP -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">
                            <i class="ri-phone-line mr-1"></i>No HP
                        </label>
                        <input type="text" name="no_hp" required
                            class="input-focus w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none"
                            placeholder="08xxxxxxxxxx">
                    </div>

                    <!-- No KTP -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">
                            <i class="ri-bank-card-line mr-1"></i>No KTP
                        </label>
                        <input type="text" name="no_ktp" required
                            class="input-focus w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none"
                            placeholder="16 digit nomor KTP">
                    </div>
                </div>

                <!-- Alamat -->
                <div>
                    <label class="block text-gray-700 font-medium mb-1">
                        <i class="ri-map-pin-line mr-1"></i>Alamat Lengkap
                    </label>
                    <textarea name="alamat" required rows="2"
                        class="input-focus w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none resize-none"
                        placeholder="Masukkan alamat lengkap Anda"></textarea>
                </div>

                <!-- Role -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">
                            <i class="ri-shield-user-line mr-1"></i>Daftar Sebagai
                        </label>
                        <select name="role" required
                            class="input-focus w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none">
                            <option value="" disabled selected>Pilih Role</option>
                            <option value="pelanggan">👤 Pelanggan</option>
                            <option value="agen">🚗 Agen / Pemilik Kendaraan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-1">
                            <i class="ri-information-line mr-1"></i>Keterangan <span class="text-gray-400">(Opsional)</span>
                        </label>
                        <input type="text" name="keterangan"
                            class="input-focus w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none"
                            placeholder="Contoh: Agen mobil premium">
                    </div>
                </div>

                <!-- Button -->
                <button type="submit" name="register"
                    class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold py-3 rounded-lg shadow-lg transition">
                    <i class="ri-user-add-line mr-1"></i>Daftar Sekarang
                </button>
            </form>

            <!-- Divider -->
            <div class="my-4 text-center text-sm text-gray-500">Sudah punya akun?</div>

            <!-- Login Link -->
            <a href="login.php"
                class="block text-center w-full border-2 border-purple-600 text-purple-600 font-semibold py-2 rounded-lg hover:bg-purple-50 transition">
                <i class="ri-login-box-line mr-1"></i>Login Sekarang
            </a>
        </div>

        <!-- Back -->
        <div class="text-center mt-4">
            <a href="index.php" class="text-white hover:text-purple-200 font-medium text-sm">
                <i class="ri-arrow-left-line mr-1"></i>Kembali ke Beranda
            </a>
        </div>
    </div>
</body>


</html>