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
                    <img src="logo-sementara.jpg" alt="logo" class="h-[50px] w-auto px-2">
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
                    <a href="../index.php" class="bg-blue-500 text-white px-4 py-2 rounded-3xl cursor-pointer font-semibold hover:bg-blue-600 transition">
                        Log In
                    </a>
                </div>
            <?php else: ?>
                <!-- Jika sudah login -->
                <div class="flex items-center gap-4 h-full w-full justify-end">
                    <span class="font-medium">Halo, <?php echo $_SESSION['user']; ?> 👋</span>
                    <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded-3xl cursor-pointer font-semibold hover:bg-red-600 transition">
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
                <img src="dashboard.png" alt="Ilustrasi Rental Mobil" class="h-[350px] md:h-[450px] w-auto object-contain">
            </div>
        </div>

        <!-- List Mobil -->
        <section id="list-mobil" class="py-16 px-10 bg-white">
            <h2 class="text-3xl font-bold mb-8">List Mobil</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Card mobil -->
                <div class="bg-gray-100 p-4 rounded-xl shadow hover:shadow-lg transition">
                    <img src="BojongLali.jpg" alt="Mobil 1" class="w-full h-[200px] object-cover rounded-lg mb-4">
                    <h3 class="text-xl font-semibold">Toyota Avanza</h3>
                    <p class="text-gray-600">Rp 300.000 / hari</p>
                    <button class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Sewa Sekarang</button>
                </div>
                <div class="bg-gray-100 p-4 rounded-xl shadow hover:shadow-lg transition">
                    <img src="BojongLali.jpg" alt="Mobil 2" class="w-full h-[200px] object-cover rounded-lg mb-4">
                    <h3 class="text-xl font-semibold">Honda Brio</h3>
                    <p class="text-gray-600">Rp 250.000 / hari</p>
                    <button class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Sewa Sekarang</button>
                </div>
                <div class="bg-gray-100 p-4 rounded-xl shadow hover:shadow-lg transition">
                    <img src="BojongLali.jpg" alt="Mobil 3" class="w-full h-[200px] object-cover rounded-lg mb-4">
                    <h3 class="text-xl font-semibold">Mitsubishi Pajero</h3>
                    <p class="text-gray-600">Rp 600.000 / hari</p>
                    <button class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Sewa Sekarang</button>
                </div>
            </div>
        </section>

        <!-- Rental -->
        <section id="rental" class="py-16 px-10 bg-[#f9fafb]">
            <h2 class="text-3xl font-bold mb-8">Form Rental</h2>
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
        </section>
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
