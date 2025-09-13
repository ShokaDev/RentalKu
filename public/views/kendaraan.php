<?php
session_start();
// Contoh login sederhana (buat demo aja)
if (!isset($_SESSION['user'])) {
    // $_SESSION['user'] = "Faharel"; // Contoh kalau sudah login
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Remixicon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    <title>RentalKu</title>
</head>

<style>
    * {
        font-family: Arial, sans-serif;
    }
</style>

<body class="bg-[#fff] overflow-x-hidden w-screen">
    <!-- Header -->
    <header class="h-[60px] w-full flex items-center justify-between px-10 fixed z-10 backdrop-blur-[7px] bg-black/20">
        <!-- Logo -->
        <div class="kiri flex items-center w-[70%]">
            <div class="logo">
                <a href="index.php">
                    <img src="../../logo-sementara.jpg" alt="logo" class="h-[50px] w-auto px-2">
                </a>
            </div>

            <!-- Navbar -->
            <nav class="relative w-[65%]">
                <ul class="relative flex gap-x-6 h-full text-black font-semibold text-[18px] w-full px-6 py-2">
                    <li><a href="#" class="active">Dashboard</a></li>
                    <li><a href="#list-mobil">List Mobil</a></li>
                    <li><a href="#rental">Rental</a></li>
                    <!-- underline -->
                    <span
                        class="underline absolute bottom-1 h-[3px] bg-blue-600 rounded transition-all duration-400 ease-in-out"
                        style="width:0; left:0; top: 37px;"></span>
                </ul>
            </nav>
        </div>

        <!-- Bagian kanan -->
        <div class="kanan flex items-end justify-end w-[380px] h-[50px]">
            <?php if (!isset($_SESSION['user'])): ?>
                <!-- Jika belum login -->
                <div class="navbar-actions flex items-center gap-4 h-full w-full justify-end">
                    <a href="signup.php" class="px-4 py-2 cursor-pointer hover:underline transition font-medium">
                        Sign In
                    </a>
                    <a href="../login.php" class="bg-blue-500 text-white px-4 py-2 rounded-3xl cursor-pointer font-semibold hover:bg-blue-600 transition">
                        Log In
                    </a>
                </div>
            <?php else: ?>
                <!-- Jika sudah login -->
                <div class="flex items-center gap-4 h-full w-full justify-end">
                    <span class="font-medium">Halo, <?php echo $_SESSION['username']; ?> 👋</span>
                    <a href="../../php/logout.php" class="bg-red-500 text-white px-4 py-2 rounded-3xl cursor-pointer font-semibold hover:bg-red-600 transition">
                        Logout
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <!-- Main -->
    <main>
        <!-- Dashboard -->
        <div class="dashboard-container h-screen bg-[#f1f4f8] flex flex-col md:flex-row items-center justify-between px-10 pt-[60px]">
            <!-- Title & Description -->
            <section class="py-10 px-8">
                <h2 class="text-2xl font-bold mb-6">Data Kendaraan</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <?php
                    include("../../config/koneksi.php");
                    $result = mysqli_query($conn, "SELECT k.*, p.nama_pemilik FROM kendaraan k 
                                        LEFT JOIN pemilik p ON k.id_pemilik = p.id_pemilik");
                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                        <div class="bg-gray-100 p-4 rounded-xl shadow hover:shadow-lg transition">
                            <img src="<?= htmlspecialchars($row['gambar']) ?>"
                                alt="<?= htmlspecialchars($row['merk'] . ' ' . $row['tipe']) ?>"
                                class="w-full h-[200px] object-cover rounded-lg mb-4">
                            <h3 class="text-xl font-semibold"><?= $row['merk'] . ' ' . $row['tipe'] ?> (<?= $row['tahun'] ?>)</h3>
                            <p class="text-gray-600">Rp <?= number_format($row['harga_sewa'], 0, ',', '.') ?> / hari</p>
                            <p class="text-sm text-gray-500">Pemilik: <?= $row['nama_pemilik'] ?? '-' ?></p>
                            <p class="text-sm text-gray-500">No Plat: <?= $row['no_plat'] ?></p>
                            <?php if ($row['status'] === 'tersedia'): ?>
                                <button class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-lg">Sewa Sekarang</button>
                            <?php elseif ($row['status'] === 'disewa'): ?>
                                <button class="mt-4 bg-red-500 text-white px-4 py-2 rounded-lg" disabled>Sedang Disewa</button>
                            <?php else: ?>
                                <button class="mt-4 bg-gray-400 text-white px-4 py-2 rounded-lg" disabled>Perbaikan</button>
                            <?php endif; ?>
                        </div>
                    <?php } ?>
                </div>
            </section>

        </div>
    </main>

    <script>
        const nav = document.querySelector("nav");
        const underline = document.querySelector(".underline");
        const links = nav.querySelectorAll("a");

        function moveUnderline(el) {
            underline.style.width = `${el.offsetWidth}px`;
            underline.style.left = `${el.offsetLeft}px`;
        }

        // aktif pertama kali (Dashboard)
        const active = nav.querySelector(".active");
        if (active) moveUnderline(active);

        // hover & click
        links.forEach(link => {
            link.addEventListener("mouseenter", e => moveUnderline(e.target));
            link.addEventListener("mouseleave", () => {
                const currentActive = nav.querySelector(".active");
                if (currentActive) moveUnderline(currentActive);
            });
            link.addEventListener("click", e => {
                e.preventDefault();
                links.forEach(l => l.classList.remove("active"));
                e.target.classList.add("active");
                moveUnderline(e.target);
            });
        });
    </script>
</body>

</html>