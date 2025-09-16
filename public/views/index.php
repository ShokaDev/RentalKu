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
    <script src="https://unpkg.com/feather-icons"></script>
    <!-- CSS -->
    <!-- <link rel="stylesheet" href="../../src/css/login.css"> -->
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
                    <li><a href="#" class="active">Home</a></li>
                    <li><a href="kendaraan.php">Kendaraan</a></li>
                    <li><a href="pemilik.php">Mitra</a></li>

                    <!-- underline -->
                    <!-- <span
                        class="underline absolute bottom-1 h-[3px] bg-blue-600 rounded transition-all duration-400 ease-in-out"
                        style="width:0; left:0; top: 37px;">
                    </span> -->

                </ul>
            </nav>
        </div>

        <!-- Bagian kanan -->
        <div class="kanan flex items-end justify-end w-[380px] h-[50px]">
            <?php if (!isset($_SESSION['user'])): ?>
                <!-- Jika belum login -->
                <div class="navbar-actions flex items-center gap-4 h-full w-full justify-end">
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin') : ?>
                        <button id="add-task">Tambah</button>
                    <?php endif; ?>
                </div>
                <!-- Tombol trigger -->
                <button onclick="openPopup()"
                    class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    Login
                </button>

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
            <div class="dashboard-title max-w-lg">
                <div class="dashboard-header mb-4">
                    <h1 class="text-[48px] font-bold">Rental Mobil Premium</h1>
                    <h2 class="text-[32px] text-gray-600 font-bold">Mudah dan cepat</h2>
                    <hr class="my-2 border-gray-300">
                </div>
                <p class="desc text-gray-700">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                    Doloremque quis veniam temporibus accusantium fugit enim
                    architecto sit dolorum eos, dolore similique vitae soluta
                    minus recusandae tempora cupiditate quidem magnam sunt.
                </p>
            </div>

            <!-- Dashboard Image -->
            <div class="dashboard-img relative mt-6 md:mt-0">
                <img src="../../dashboard.png" alt="Ilustrasi Rental Mobil" class="h-[350px] md:h-[450px] w-auto object-contain">
            </div>
        </div>

        <!-- List Mobil -->
        <section id="list-mobil" class="py-16 px-10 bg-white flex items-center justify-center flex-col">
            <h2 class="text-3xl font-bold mb-8 text-center">LIST KENDARAAN</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php
                include("../../config/koneksi.php");

                // Query ambil data kendaraan + nama pemilik + nama penyewa (kalau sedang disewa)
                $query = "
            SELECT k.*, 
                   p.nama_pemilik, 
                   pl.nama AS nama_penyewa
            FROM kendaraan k
            JOIN pemilik p ON k.id_pemilik = p.id_pemilik
            LEFT JOIN sewa s ON k.id_kendaraan = s.id_kendaraan AND s.status = 'aktif'
            LEFT JOIN pelanggan pl ON s.id_pelanggan = pl.id_pelanggan
        ";
                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0):
                    while ($row = mysqli_fetch_assoc($result)):
                ?>
                        <div class="bg-white rounded-2xl shadow hover:shadow-lg transition relative overflow-hidden">
                            <!-- Status Badge -->
                            <?php if ($row['status'] === 'tersedia'): ?>
                                <span class="absolute top-3 left-3 bg-blue-600 text-white text-xs font-semibold px-3 py-1 rounded-full">
                                    Tersedia
                                </span>
                            <?php elseif ($row['status'] === 'disewa'): ?>
                                <span class="absolute top-3 left-3 bg-red-600 text-white text-xs font-semibold px-3 py-1 rounded-full">
                                    Tidak Tersedia
                                </span>
                            <?php else: ?>
                                <span class="absolute top-3 left-3 bg-gray-500 text-white text-xs font-semibold px-3 py-1 rounded-full">
                                    Maintenance
                                </span>
                            <?php endif; ?>

                            <!-- Price Tag -->
                            <span class="absolute bottom-47 right-3 bg-black/70 text-white text-sm font-bold px-4 py-2 rounded-lg">
                                Rp <?= number_format($row['harga_sewa'], 0, ',', '.') ?> / day
                            </span>

                            <!-- Image -->
                            <img src="<?= htmlspecialchars($row['gambar']) ?>"
                                alt="<?= htmlspecialchars($row['merk'] . ' ' . $row['tipe']) ?>"
                                class="w-full h-52 object-cover rounded-t-2xl">

                            <!-- Content -->
                            <div class="p-5">
                                <h3 class="text-lg font-semibold"><?= $row['merk'] . ' ' . $row['tipe'] ?></h3>
                                <p class="text-sm text-gray-500"><?= $row['tahun'] ?> • <?= strtoupper($row['no_plat']) ?></p>

                                <!-- Nama Pemilik -->
                                <p class="text-sm mt-2"><strong>Owner:</strong> <?= htmlspecialchars($row['nama_pemilik']) ?></p>

                                <!-- Nama Penyewa kalau sedang disewa -->
                                <?php if ($row['status'] === 'disewa' && $row['nama_penyewa']): ?>
                                    <p class="text-sm text-red-600"><strong>Rented by:</strong> <?= htmlspecialchars($row['nama_penyewa']) ?></p>
                                <?php endif; ?>

                                <!-- Action -->
                                <?php if ($row['status'] === 'tersedia'): ?>
                                    <?php if (!isset($_SESSION['user'])): ?>
                                        <!-- Belum login -->
                                        <a href="../login.php"
                                            class="mt-5 block w-full bg-blue-600 text-white py-2 rounded-xl font-semibold text-center hover:bg-yellow-600">
                                            Rental
                                        </a>
                                    <?php else: ?>
                                        <!-- Sudah login -->
                                        <a href="form_rental.php?id_kendaraan=<?= $row['id_kendaraan'] ?>"
                                            class="mt-5 block w-full bg-blue-600 text-white py-2 rounded-xl font-semibold text-center hover:bg-blue-700">
                                            Rental Sekarang
                                        </a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <!-- Kalau mobil tidak tersedia -->
                                    <button class="mt-5 w-full bg-gray-400 text-white py-2 rounded-xl font-semibold cursor-not-allowed" disabled>
                                        Tidak Tersediax`
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php
                    endwhile;
                else:
                    ?>
                    <p class="col-span-3 text-center text-gray-500">No vehicles available.</p>
                <?php endif; ?>
            </div>
        </section>



        <!-- Rental -->
        <!-- <section id="rental" class="py-16 px-10 bg-[#f9fafb]">
            <h2 class="text-3xl font-bold mb-8 text-center">Form Rental</h2>
            <form action="proses_rental.php" method="post" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-2 font-medium">Nama Lengkap</label>
                    <input type="text" name="nama" class="w-full border rounded-lg px-4 py-2" required>
                </div>
                <div>
                    <label class="block mb-2 font-medium">Nomor HP</label>
                    <input type="text" name="no_hp" class="w-full border rounded-lg px-4 py-2" required>
                </div>
                <div>
                    <label class="block mb-2 font-medium">Pilih Mobil</label>
                    <select name="mobil" class="w-full border rounded-lg px-4 py-2">
                        <option value="Avanza">Toyota Avanza</option>
                        <option value="Brio">Honda Brio</option>
                        <option value="Pajero">Mitsubishi Pajero</option>
                    </select>
                </div>
                <div>
                    <label class="block mb-2 font-medium">Tanggal Rental</label>
                    <input type="date" name="tanggal" class="w-full border rounded-lg px-4 py-2" required>
                </div>
                <div class="col-span-2">
                    <button type="submit" class="bg-green-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-600">Pesan Sekarang</button>
                </div>
            </form>
        </section> -->
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
    <script>
        function openPopup() {
            document.getElementById('authPopup').classList.remove('hidden');
        }

        function closePopup() {
            document.getElementById('authPopup').classList.add('hidden');
        }

        function switchToRegister() {
            document.querySelector('.form-box.login').classList.add('hidden');
            document.querySelector('.form-box.register').classList.remove('hidden');
        }

        function switchToLogin() {
            document.querySelector('.form-box.register').classList.add('hidden');
            document.querySelector('.form-box.login').classList.remove('hidden');
        }
    </script>

    <script src="../../src/js/add-user.js"></script>
    <script src="../../src/js/add-user.js"></script>
    <script src="../../src/js/login.js"></script>
    <script>
        feather.replace();
    </script>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>

</html>