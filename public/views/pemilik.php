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
    <?php include("../../src/includes/header.php"); ?>

    <!-- Main -->
    <main class="pt-[60px] px-10 bg-[#f1f4f8] min-h-screen">
        <section class="py-10 px-8 bg-white rounded-2xl shadow-md">
            <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-3">
                Data Pemilik / Agen
            </h2>

            <div class="overflow-x-auto">
                <table class="w-full border border-gray-200 rounded-lg overflow-hidden">
                    <thead class="bg-blue-600 text-white">
                        <tr>
                            <th class="p-3 text-left">No</th>
                            <th class="p-3 text-left">Nama Pemilik</th>
                            <th class="p-3 text-left">Alamat</th>
                            <th class="p-3 text-left">No HP</th>
                            <th class="p-3 text-left">No KTP</th>
                            <th class="p-3 text-left">Keterangan</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include("../../config/koneksi.php");
                        $result = mysqli_query($conn, "SELECT * FROM pemilik");
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                            <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100 transition">
                                <td class="p-3 border"><?= $no ?></td>
                                <td class="p-3 border font-medium text-gray-800"><?= htmlspecialchars($row['nama_pemilik']) ?></td>
                                <td class="p-3 border"><?= htmlspecialchars($row['alamat']) ?></td>
                                <td class="p-3 border"><?= htmlspecialchars($row['no_hp']) ?></td>
                                <td class="p-3 border"><?= htmlspecialchars($row['no_ktp']) ?></td>
                                <td class="p-3 border text-sm text-gray-600"><?= htmlspecialchars($row['keterangan']) ?></td>
                                <td class="p-3 border text-center">
                                    <a href="hapus_pemilik.php?id=<?= urlencode($row['id_pemilik']) ?>"
                                       onclick="return confirm('Yakin ingin menghapus data ini?')"
                                       class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm font-semibold transition">
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php
                            $no++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
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
