<?php
include(__DIR__ . "/../../config/koneksi.php");
session_start();

// Pastikan hanya admin yang bisa tambah lokasi
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php?error=unauthorized");
    exit;
}

// Cek apakah form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lokasi = trim($_POST['nama_lokasi']);

    if (!empty($nama_lokasi)) {
        $nama_lokasi = mysqli_real_escape_string($conn, $nama_lokasi);

        // Cek apakah lokasi sudah ada
        $cek = mysqli_query($conn, "SELECT * FROM lokasi WHERE nama_lokasi = '$nama_lokasi' LIMIT 1");
        if (mysqli_num_rows($cek) > 0) {
            echo "<script>alert('Lokasi sudah ada!'); window.location.href='dashboard_admin.php';</script>";
            exit;
        }

        // Simpan ke database
        $query = "INSERT INTO lokasi (nama_lokasi) VALUES ('$nama_lokasi')";
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Lokasi berhasil ditambahkan!'); window.location.href='dashboard_admin.php';</script>";
        } else {
            echo "<script>alert('Gagal menambah lokasi: " . mysqli_error($conn) . "'); window.location.href='dashboard_admin.php';</script>";
        }
    } else {
        echo "<script>alert('Nama lokasi tidak boleh kosong!'); window.location.href='dashboard_admin.php';</script>";
    }
} else {
    header("Location: dashboard_admin.php");
    exit;
}
?>
