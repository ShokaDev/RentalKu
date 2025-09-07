<?php
session_start();
$conn = new mysqli("localhost", "root", "", "rentalku");
if ($conn->connect_error) die("Koneksi gagal: " . $conn->connect_error);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Rental Mobil</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 text-gray-900">
  <!-- Header -->
  <header class="bg-blue-600 text-white p-4 flex justify-between">
    <h1 class="text-xl font-bold">Rental Mobil Premium</h1>
    <nav>
      <a href="index.php" class="px-3">Dashboard</a>
      <a href="?page=list-mobil" class="px-3">List Mobil</a>
      <a href="?page=sewa" class="px-3">Rental</a>
      <a href="?page=pembayaran" class="px-3">Pembayaran</a>
      <a href="?page=pengembalian" class="px-3">Pengembalian</a>
      <a href="?page=admin" class="px-3">Admin</a>
    </nav>
  </header>

  <main class="p-6">
    <?php
    $page = $_GET['page'] ?? 'dashboard';

    // Dashboard
    if ($page == 'dashboard') {
      echo "<h2 class='text-2xl font-bold mb-4'>Selamat datang di Rental Mobil</h2>";
      echo "<p>Pilih menu di atas untuk mulai menyewa mobil.</p>";
    }

    // List Mobil (ambil data dari tabel kendaraan + pemilik)
    if ($page == 'list-mobil') {
      $result = $conn->query("SELECT k.*, p.nama AS pemilik FROM kendaraan k 
                              LEFT JOIN pemilik p ON k.id_pemilik=p.id");
      echo "<h2 class='text-xl font-bold mb-4'>Daftar Mobil</h2>";
      echo "<div class='grid grid-cols-3 gap-4'>";
      while ($row = $result->fetch_assoc()) {
        echo "<div class='p-4 border rounded bg-white'>
                <h3 class='font-bold'>{$row['merk']} {$row['model']}</h3>
                <p>Plat: {$row['plat_nomor']}</p>
                <p>Tahun: {$row['tahun']}</p>
                <p>Harga/hari: Rp{$row['harga']}</p>
                <p>Pemilik: {$row['pemilik']}</p>
              </div>";
      }
      echo "</div>";
    }

    // Form Sewa
    if ($page == 'sewa') {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id_pelanggan = $_POST['id_pelanggan'];
        $id_kendaraan = $_POST['id_kendaraan'];
        $tgl_mulai = $_POST['tgl_mulai'];
        $tgl_selesai = $_POST['tgl_selesai'];
        $conn->query("INSERT INTO sewa(id_pelanggan,id_kendaraan,tgl_mulai,tgl_selesai) 
                      VALUES('$id_pelanggan','$id_kendaraan','$tgl_mulai','$tgl_selesai')");
        echo "<p class='text-green-600'>Sewa berhasil dicatat!</p>";
      }
      $pel = $conn->query("SELECT * FROM pelanggan");
      $kend = $conn->query("SELECT * FROM kendaraan");
      ?>
      <h2 class="text-xl font-bold mb-4">Form Sewa Mobil</h2>
      <form method="post" class="space-y-3">
        <select name="id_pelanggan" class="border p-2 w-full">
          <?php while ($p = $pel->fetch_assoc()) echo "<option value='{$p['id']}'>{$p['nama']}</option>"; ?>
        </select>
        <select name="id_kendaraan" class="border p-2 w-full">
          <?php while ($k = $kend->fetch_assoc()) echo "<option value='{$k['id']}'>{$k['merk']} {$k['model']}</option>"; ?>
        </select>
        <input type="date" name="tgl_mulai" class="border p-2 w-full">
        <input type="date" name="tgl_selesai" class="border p-2 w-full">
        <button class="bg-blue-500 text-white px-4 py-2">Sewa</button>
      </form>
      <?php
    }

    // Pembayaran
    if ($page == 'pembayaran') {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id_sewa = $_POST['id_sewa'];
        $jumlah = $_POST['jumlah'];
        $conn->query("INSERT INTO pembayaran(id_sewa,jumlah) VALUES('$id_sewa','$jumlah')");
        echo "<p class='text-green-600'>Pembayaran berhasil!</p>";
      }
      $sewa = $conn->query("SELECT s.id, p.nama, k.merk, k.model 
                            FROM sewa s 
                            JOIN pelanggan p ON s.id_pelanggan=p.id
                            JOIN kendaraan k ON s.id_kendaraan=k.id");
      ?>
      <h2 class="text-xl font-bold mb-4">Pembayaran</h2>
      <form method="post" class="space-y-3">
        <select name="id_sewa" class="border p-2 w-full">
          <?php while ($s = $sewa->fetch_assoc()) echo "<option value='{$s['id']}'>Sewa {$s['id']} - {$s['nama']} ({$s['merk']})</option>"; ?>
        </select>
        <input type="number" name="jumlah" placeholder="Jumlah" class="border p-2 w-full">
        <button class="bg-blue-500 text-white px-4 py-2">Bayar</button>
      </form>
      <?php
    }

    // Pengembalian
    if ($page == 'pengembalian') {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id_sewa = $_POST['id_sewa'];
        $tgl_kembali = $_POST['tgl_kembali'];
        $conn->query("INSERT INTO pengembalian(id_sewa,tgl_kembali) VALUES('$id_sewa','$tgl_kembali')");
        echo "<p class='text-green-600'>Pengembalian berhasil dicatat!</p>";
      }
      $sewa = $conn->query("SELECT * FROM sewa");
      ?>
      <h2 class="text-xl font-bold mb-4">Pengembalian</h2>
      <form method="post" class="space-y-3">
        <select name="id_sewa" class="border p-2 w-full">
          <?php while ($s = $sewa->fetch_assoc()) echo "<option value='{$s['id']}'>Sewa {$s['id']}</option>"; ?>
        </select>
        <input type="date" name="tgl_kembali" class="border p-2 w-full">
        <button class="bg-blue-500 text-white px-4 py-2">Kembalikan</button>
      </form>
      <?php
    }

    // Admin (lihat data user & pelanggan)
    if ($page == 'admin') {
      $users = $conn->query("SELECT * FROM users");
      $pelanggan = $conn->query("SELECT * FROM pelanggan");
      echo "<h2 class='text-xl font-bold mb-4'>Data Users</h2><ul>";
      while ($u = $users->fetch_assoc()) echo "<li>{$u['username']} - {$u['role']}</li>";
      echo "</ul>";
      echo "<h2 class='text-xl font-bold mt-6 mb-4'>Data Pelanggan</h2><ul>";
      while ($p = $pelanggan->fetch_assoc()) echo "<li>{$p['nama']} - {$p['email']}</li>";
      echo "</ul>";
    }
    ?>
  </main>
</body>
</html>
