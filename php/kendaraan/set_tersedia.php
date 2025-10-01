<?php
include("../../config/koneksi.php");
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'agen') {
    die("Access denied");
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Ubah status kendaraan jadi tersedia
    mysqli_query($conn, "UPDATE kendaraan SET status='tersedia' WHERE id_kendaraan=$id");

    // Tandai sewa terakhir kendaraan ini jadi selesai
    mysqli_query($conn, "UPDATE sewa 
                         SET status='selesai', tgl_kembali=NOW() 
                         WHERE id_kendaraan=$id AND status='disewa'
                         ORDER BY id_sewa DESC LIMIT 1");
}

header("Location: ../../public/views/kendaraan_agen.php");
exit();
