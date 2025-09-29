<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../../config/koneksi.php");
session_start();

// Pastikan hanya pelanggan yang bisa akses
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pelanggan') {
    header("Location: ../../public/login.php?error=unauthorized");
    exit;
}

// Ambil id_user dari session
$id_user = (int) $_SESSION['user_id'];

// Cari id_pelanggan berdasarkan id_user
$qPelanggan = mysqli_query($conn, "SELECT id_pelanggan FROM pelanggan WHERE id_user = $id_user LIMIT 1");
$pelanggan = mysqli_fetch_assoc($qPelanggan);

if (!$pelanggan) {
    echo "<script>alert('Data pelanggan tidak ditemukan!'); window.location.href='kendaraan.php';</script>";
    exit;
}

$id_pelanggan = (int) $pelanggan['id_pelanggan'];

// Pastikan data form sudah dikirim
if (!isset($_POST['id_kendaraan'], $_POST['tgl_sewa'], $_POST['tgl_kembali'], $_POST['lama_sewa'], $_POST['harga_total'])) {
    echo "<script>alert('Data sewa tidak lengkap!'); window.location.href='kendaraan.php';</script>";
    exit;
}

$id_kendaraan   = (int) $_POST['id_kendaraan'];
$tgl_sewa       = $_POST['tgl_sewa'];
$tgl_kembali    = $_POST['tgl_kembali'];
$lama_sewa      = (int) $_POST['lama_sewa'];
$harga_total    = (float) $_POST['harga_total'];

// Cek kendaraan masih tersedia
$queryKendaraan = mysqli_query($conn, "SELECT * FROM kendaraan WHERE id_kendaraan = $id_kendaraan LIMIT 1");
$kendaraan = mysqli_fetch_assoc($queryKendaraan);

if (!$kendaraan || $kendaraan['status'] !== 'tersedia') {
    echo "<script>alert('Kendaraan tidak tersedia untuk disewa!'); window.location.href='kendaraan.php';</script>";
    exit;
}

// Simpan ke tabel sewa
$sqlInsert = "
    INSERT INTO sewa (id_pelanggan, id_kendaraan, tgl_sewa, tgl_kembali, lama_sewa, harga_total, status)
    VALUES ($id_pelanggan, $id_kendaraan, '$tgl_sewa', '$tgl_kembali', $lama_sewa, $harga_total, 'aktif')
";

if (mysqli_query($conn, $sqlInsert)) {
    // Update status kendaraan jadi disewa
    mysqli_query($conn, "UPDATE kendaraan SET status='disewa' WHERE id_kendaraan = $id_kendaraan");

    echo "<script>
            alert('Sewa berhasil! Terima kasih sudah menggunakan layanan kami.');
            window.location.href='kendaraan.php';
          </script>";
} else {
    echo "<script>
            alert('Terjadi kesalahan saat menyimpan data sewa: ". mysqli_error($conn) ."');
            window.location.href='kendaraan.php';
          </script>";
}
