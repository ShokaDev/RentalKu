<?php
session_start();
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
                Data Pelanggan
            </h2>

            <div class="overflow-x-auto">
                <table class="w-full border border-gray-200 rounded-lg overflow-hidden">
                    <thead class="bg-blue-600 text-white">
                        <tr>
                            <th class="p-3 text-left">No</th>
                            <th class="p-3 text-left">Nama</th>
                            <th class="p-3 text-left">Alamat</th>
                            <th class="p-3 text-left">No HP</th>
                            <th class="p-3 text-left">No KTP</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include("../../config/koneksi.php");
                        $result = mysqli_query($conn, "SELECT * FROM pelanggan");
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "
                        <tr class='odd:bg-white even:bg-gray-50 hover:bg-gray-100 transition'>
                            <td class='p-3 border'>$no</td>
                            <td class='p-3 border font-medium text-gray-800'>{$row['nama']}</td>
                            <td class='p-3 border'>{$row['alamat']}</td>
                            <td class='p-3 border'>{$row['no_hp']}</td>
                            <td class='p-3 border'>{$row['no_ktp']}</td>
                            <td class='p-3 border text-center'>
    <a href='hapus_pelanggan.php?id={$row['id_pelanggan']}' 
       onclick=\"return confirm('Yakin ingin menghapus akun ini?');\" 
       class='bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm font-semibold transition'>
        Hapus
    </a>
</td>

                        </tr>";
                            $no++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>

</html>