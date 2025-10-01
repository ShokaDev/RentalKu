<?php
session_start();

// Default user info
$username = null;
$role = null;

// Kalau user sudah login
if (isset($_SESSION['user_id'])) {
    $username = $_SESSION['username'];
    $role     = $_SESSION['role'];
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RentalKu - Sewa Mobil Premium</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Remix Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            scroll-behavior: smooth;
        }

        .hero-pattern {
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(99, 102, 241, 0.3) 0%, transparent 50%);
        }

        .card-hover {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            transform: translateY(-12px) scale(1.02);
        }

        .feature-card {
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .badge-shine {
            position: relative;
            overflow: hidden;
        }

        .badge-shine::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            animation: shine 3s infinite;
        }

        @keyframes shine {
            0% { left: -100%; }
            50%, 100% { left: 100%; }
        }

        .float-animation {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>

<body class="bg-white overflow-x-hidden">
    <!-- Header -->
    <?php include(__DIR__ . "/../../src/includes/header.php"); ?>

    <!-- Main -->
    <main>
        <!-- Hero Section -->
        <section class="relative min-h-screen hero-pattern bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 text-white flex items-center pt-[80px] overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute top-20 left-10 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-10 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl"></div>

            <div class="container mx-auto px-6 lg:px-10 relative z-10">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <!-- Left Content -->
                    <div class="space-y-8 text-center md:text-left">
                        <!-- Badge -->
                        <div class="inline-flex items-center bg-white/20 backdrop-blur-md rounded-full px-4 py-2 text-sm font-semibold">
                            <i class="ri-star-fill text-yellow-300 mr-2"></i>
                            Pilihan Terpercaya #1 di Indonesia
                        </div>

                        <!-- Main Headline -->
                        <h1 class="text-5xl md:text-7xl font-black leading-tight">
                            Rental Mobil
                            <span class="block text-yellow-300">Premium</span>
                        </h1>

                        <!-- Subheadline -->
                        <p class="text-xl md:text-2xl font-light text-blue-100">
                            Mudah, cepat, dan terpercaya untuk perjalanan sempurna Anda
                        </p>

                        <!-- Description -->
                        <p class="text-lg text-blue-50 leading-relaxed max-w-xl">
                            Nikmati pengalaman sewa mobil yang nyaman bersama <span class="font-bold text-yellow-300">RentalKu</span>.
                            Armada berkualitas, harga kompetitif, dan layanan 24/7 untuk kebutuhan perjalanan Anda.
                        </p>

                        <!-- CTA Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                            <a href="#list-mobil"
                                class="group inline-flex items-center justify-center bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold px-8 py-4 rounded-2xl shadow-2xl hover:shadow-yellow-400/50 transition-all duration-300">
                                <i class="ri-car-line text-2xl mr-2 group-hover:scale-110 transition-transform"></i>
                                Lihat Kendaraan
                                <i class="ri-arrow-right-line ml-2 group-hover:translate-x-1 transition-transform"></i>
                            </a>
                            <a href="#features"
                                class="inline-flex items-center justify-center bg-white/10 backdrop-blur-md hover:bg-white/20 text-white font-semibold px-8 py-4 rounded-2xl border-2 border-white/30 transition-all duration-300">
                                <i class="ri-information-line text-xl mr-2"></i>
                                Pelajari Lebih Lanjut
                            </a>
                        </div>

                        <!-- Stats -->
                        <div class="grid grid-cols-3 gap-6 pt-8">
                           
                        </div>
                    </div>

                    <!-- Right Content - Hero Image -->
                    <div class="relative flex justify-center md:justify-end">
                        <div class="float-animation">
                            <img src="../../dashboard.png" alt="Rental Mobil Premium"
                                class="w-full max-w-xl drop-shadow-2xl relative z-0">
                        </div>
                        <!-- Decorative Circle -->
                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-yellow-300/20 rounded-full blur-3xl"></div>
                    </div>
                </div>
            </div>

            <!-- Wave Divider -->
            <div class="absolute bottom-0 left-0 w-full">
                <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="#F9FAFB"/>
                </svg>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-20 bg-gray-50">
            <div class="container mx-auto px-6 lg:px-10">
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                        Mengapa Memilih <span class="gradient-text">RentalKu</span>?
                    </h2>
                    <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                        Kami memberikan pengalaman rental terbaik dengan layanan profesional dan terpercaya
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="feature-card bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-6">
                            <i class="ri-time-line text-3xl text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Proses Cepat</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Booking online yang mudah dan cepat. Kendaraan siap dalam hitungan menit setelah konfirmasi.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="feature-card bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mb-6">
                            <i class="ri-shield-check-line text-3xl text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Terpercaya & Aman</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Semua kendaraan terawat dengan baik dan diasuransikan penuh untuk keamanan perjalanan Anda.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="feature-card bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl">
                        <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl flex items-center justify-center mb-6">
                            <i class="ri-price-tag-3-line text-3xl text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Harga Kompetitif</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Dapatkan harga terbaik dengan berbagai pilihan paket dan promo menarik setiap bulannya.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- List Mobil Section -->
        <section id="list-mobil" class="py-20 bg-white">
            <div class="container mx-auto px-6 lg:px-10">
                <!-- Section Header -->
                <div class="text-center mb-16">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl mb-6">
                        <i class="ri-car-line text-3xl text-white"></i>
                    </div>
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                        Pilihan Kendaraan <span class="gradient-text">Premium</span>
                    </h2>
                    <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                        Koleksi kendaraan berkualitas tinggi untuk memenuhi kebutuhan perjalanan Anda
                    </p>
                </div>

                <?php
                include(__DIR__ . "/../../config/koneksi.php");

                $query = "
                SELECT k.*, 
                       p.nama_pemilik, 
                       pl.nama AS nama_penyewa
                FROM kendaraan k
                JOIN pemilik p ON k.id_pemilik = p.id_pemilik
                LEFT JOIN sewa s ON k.id_kendaraan = s.id_kendaraan AND s.status = 'aktif'
                LEFT JOIN pelanggan pl ON s.id_pelanggan = pl.id_pelanggan
                LIMIT 6
            ";
                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0):
                ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <div class="card-hover bg-white rounded-2xl shadow-lg overflow-hidden">
                                <!-- Image Container -->
                                <div class="relative h-56 overflow-hidden">
                                    <?php if (!empty($row['gambar'])): ?>
                                        <img src="/RentalKu/uploads/<?= htmlspecialchars($row['gambar']) ?>"
                                            alt="<?= htmlspecialchars($row['merk'] . ' ' . $row['tipe']) ?>"
                                            class="w-full h-full object-cover hover:scale-110 transition-transform duration-700">
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                            <i class="ri-image-line text-6xl text-gray-400"></i>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Status Badge -->
                                    <?php if ($row['status'] === 'tersedia'): ?>
                                        <div class="badge-shine absolute top-4 left-4 bg-gradient-to-r from-green-500 to-green-600 text-white text-xs font-bold px-4 py-2 rounded-full shadow-lg">
                                            <i class="ri-checkbox-circle-fill mr-1"></i>Tersedia
                                        </div>
                                    <?php elseif ($row['status'] === 'disewa'): ?>
                                        <div class="absolute top-4 left-4 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-bold px-4 py-2 rounded-full shadow-lg">
                                            <i class="ri-time-fill mr-1"></i>Disewa
                                        </div>
                                    <?php else: ?>
                                        <div class="absolute top-4 left-4 bg-gradient-to-r from-gray-500 to-gray-600 text-white text-xs font-bold px-4 py-2 rounded-full shadow-lg">
                                            <i class="ri-tools-fill mr-1"></i>Maintenance
                                        </div>
                                    <?php endif; ?>

                                    <!-- Price Badge -->
                                    <div class="absolute bottom-4 right-4 bg-black/80 backdrop-blur-sm text-white px-4 py-2 rounded-xl shadow-xl">
                                        <div class="text-xs opacity-90">Per Hari</div>
                                        <div class="text-lg font-bold text-yellow-300">Rp <?= number_format($row['harga_sewa'], 0, ',', '.') ?></div>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="p-6 space-y-3">
                                    <h3 class="text-xl font-bold text-gray-800">
                                        <?= htmlspecialchars($row['merk'] . ' ' . $row['tipe']) ?>
                                    </h3>

                                    <div class="flex items-center text-gray-600 text-sm">
                                        <i class="ri-calendar-line mr-2"></i>
                                        <span class="mr-4"><?= htmlspecialchars($row['tahun']) ?></span>
                                        <i class="ri-profile-line mr-2"></i>
                                        <span class="uppercase font-semibold"><?= htmlspecialchars($row['no_plat']) ?></span>
                                    </div>

                                    <div class="bg-blue-50 rounded-lg p-3">
                                        <div class="flex items-center text-sm">
                                            <i class="ri-user-line text-blue-600 mr-2"></i>
                                            <span class="text-gray-600">Owner:</span>
                                            <span class="ml-2 font-semibold text-gray-800"><?= htmlspecialchars($row['nama_pemilik']) ?></span>
                                        </div>
                                    </div>

                                    <?php if ($row['status'] === 'disewa' && $row['nama_penyewa']): ?>
                                        <div class="bg-red-50 rounded-lg p-3">
                                            <div class="flex items-center text-sm">
                                                <i class="ri-user-follow-line text-red-600 mr-2"></i>
                                                <span class="text-gray-600">Dirental oleh:</span>
                                                <span class="ml-2 font-semibold text-red-600"><?= htmlspecialchars($row['nama_penyewa']) ?></span>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Action Button -->
                                    <?php if ($row['status'] === 'tersedia'): ?>
                                        <?php if (!isset($_SESSION['user_id'])): ?>
                                            <a href="../login.php"
                                                class="block w-full bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-gray-900 py-3 rounded-xl font-bold text-center shadow-lg hover:shadow-xl transition-all duration-200">
                                                <i class="ri-login-box-line mr-2"></i>Login untuk Rental
                                            </a>
                                        <?php else: ?>
                                            <a href="sewa.php?id=<?= $row['id_kendaraan'] ?>"
                                                class="block w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white py-3 rounded-xl font-bold text-center shadow-lg hover:shadow-xl transition-all duration-200">
                                                <i class="ri-car-line mr-2"></i>Rental Sekarang
                                            </a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <button class="w-full bg-gray-300 text-gray-600 py-3 rounded-xl font-bold cursor-not-allowed" disabled>
                                            <i class="ri-close-circle-line mr-2"></i>Tidak Tersedia
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>

                    <!-- View All Button -->
                    <div class="text-center mt-12">
                        <a href="kendaraan.php"
                            class="inline-flex items-center bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-8 py-4 rounded-2xl font-bold shadow-lg hover:shadow-xl transition-all duration-200">
                            <i class="ri-car-line text-xl mr-2"></i>
                            Lihat Semua Kendaraan
                            <i class="ri-arrow-right-line ml-2"></i>
                        </a>
                    </div>

                <?php else: ?>
                    <!-- Empty State -->
                    <div class="bg-gray-50 rounded-2xl p-16 text-center">
                        <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-200 rounded-full mb-6">
                            <i class="ri-car-line text-5xl text-gray-400"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-700 mb-3">Belum Ada Kendaraan</h3>
                        <p class="text-gray-500">Kendaraan akan segera tersedia. Silakan cek kembali nanti.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 bg-gradient-to-r from-blue-600 to-purple-600 text-white">
            <div class="container mx-auto px-6 lg:px-10 text-center">
                <h2 class="text-4xl md:text-5xl font-bold mb-6">
                    Siap Memulai Perjalanan Anda?
                </h2>
                <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                    Dapatkan pengalaman rental mobil terbaik dengan RentalKu. Booking sekarang dan nikmati perjalanan Anda!
                </p>
                <a href="#list-mobil"
                    class="inline-flex items-center bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold px-10 py-5 rounded-2xl shadow-2xl hover:shadow-yellow-400/50 transition-all duration-300">
                    <i class="ri-car-line text-2xl mr-2"></i>
                    Mulai Sekarang
                    <i class="ri-arrow-right-line ml-2"></i>
                </a>
            </div>
        </section>
    </main>
</body>

</html>