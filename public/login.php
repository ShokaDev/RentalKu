<!-- LOGIN PAGE -->
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
    <title>Login - RentalKu</title>
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
    </style>
</head>

<body class="gradient-bg min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Decorative Elements -->
    <div class="decorative-circle w-96 h-96 bg-white top-0 -left-48"></div>
    <div class="decorative-circle w-96 h-96 bg-white bottom-0 -right-48"></div>

    <div class="max-w-md w-full relative z-10">
        

        <!-- Login Card -->
        <div class="glass-effect rounded-3xl shadow-2xl p-8">
            <form action="../php/auth.php" method="POST" class="space-y-6">
                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="ri-user-line mr-1"></i>Username
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="ri-user-line text-gray-400 text-xl"></i>
                        </div>
                        <input type="text" name="username" id="username" required
                            class="input-focus w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-200 transition-all duration-300 outline-none"
                            placeholder="Masukkan username">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="ri-lock-line mr-1"></i>Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="ri-lock-line text-gray-400 text-xl"></i>
                        </div>
                        <input type="password" name="password" id="password" required
                            class="input-focus w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-200 transition-all duration-300 outline-none"
                            placeholder="Masukkan password">
                    </div>
                </div>


                <!-- Login Button -->
                <button type="submit" name="login"
                    class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold py-3.5 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                    <i class="ri-login-box-line mr-2"></i>Masuk
                </button>
            </form>

            <!-- Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-white text-gray-500">Atau</span>
                </div>
            </div>

            <!-- Register Link -->
            <div class="text-center">
                <p class="text-gray-600 mb-3">Belum punya akun?</p>
                <a href="register.php"
                    class="inline-flex items-center justify-center w-full bg-white border-2 border-purple-600 text-purple-600 font-bold py-3 rounded-xl hover:bg-purple-50 transition-all duration-200">
                    <i class="ri-user-add-line mr-2"></i>Daftar Sekarang
                </a>
            </div>
        </div>

        <!-- Back to Home -->
        <div class="text-center mt-6">
            <a href="index.php" class="inline-flex items-center text-white hover:text-purple-200 font-semibold">
                <i class="ri-arrow-left-line mr-2"></i>Kembali ke Beranda
            </a>
        </div>
    </div>
</body>

</html>