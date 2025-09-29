<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header class="h-[60px] w-full flex items-center justify-between px-10 fixed top-0 z-10 backdrop-blur-[7px] bg-white/80 shadow">
    <!-- Logo -->
    <div class="kiri flex items-center w-[70%]">
        <div class="logo">
            <a href="index.php">
                <h1 class="text-[24px] font-bold text-green-600">RentalKu</h1>
            </a>
        </div>

        <!-- Navbar -->
        <nav class="relative w-[65%]">
            <ul class="relative flex gap-x-6 h-full text-black font-light text-[18px] w-full px-6 py-2">
                <li><a href="index.php" class="active hover:underline">Home</a></li>
                <li><a href="kendaraan.php" class="hover:underline">Kendaraan</a></li>

                <!-- Hanya admin yang bisa lihat Mitra & Pelanggan -->
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li><a href="pemilik.php" class="hover:underline">Mitra</a></li>
                    <li><a href="pelanggan.php" class="hover:underline">Pelanggan</a></li>
                <?php endif; ?>

                <!-- Dashboard -->
                <?php if (isset($_SESSION['role'])): ?>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <li><a href="dashboard_admin.php" class="hover:underline text-blue-600 font-semibold">Dashboard</a></li>
                    <?php elseif ($_SESSION['role'] === 'agen'): ?>
                        <li><a href="dashboard_agen.php" class="hover:underline text-green-600 font-semibold">Dashboard</a></li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <!-- Bagian kanan -->
    <div class="kanan flex items-end justify-end w-[380px] h-[50px]">
        <?php if (!isset($_SESSION['user_id'])): ?>
            <!-- Jika belum login -->
            <div class="navbar-actions flex items-center gap-4 h-full w-full justify-end">
                <a href="../register.php"
                   class="px-4 py-2 cursor-pointer hover:underline transition font-medium">
                   Sign Up
                </a>
                <a href="../login.php"
                   class="bg-blue-500 text-white px-4 py-2 rounded-3xl cursor-pointer font-semibold hover:bg-blue-600 transition">
                   Log In
                </a>
            </div>
        <?php else: ?>
            <!-- Jika sudah login -->
            <div class="flex items-center gap-4 h-full w-full justify-end">
                <span class="font-medium">
                    Halo, <?= htmlspecialchars($_SESSION['username']); ?> 👋
                </span>
                <a href="../../php/logout.php"
                   class="bg-red-500 text-white px-4 py-2 rounded-3xl cursor-pointer font-semibold hover:bg-red-600 transition">
                   Logout
                </a>
            </div>
        <?php endif; ?>
    </div>
</header>
