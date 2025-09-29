<?php
include(__DIR__ . "/../../config/koneksi.php");
session_start();

// Pastikan login sebagai agen
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'agen') {
    header("Location: ../../public/login.php?error=unauthorized");
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Pastikan kendaraan milik agen yang login
    $id_pemilik = $_SESSION['id_pemilik'];
    $cek = mysqli_query($conn, "SELECT * FROM kendaraan WHERE id_kendaraan='$id' AND id_pemilik='$id_pemilik'");
    
    if (mysqli_num_rows($cek) > 0) {
        mysqli_query($conn, "DELETE FROM kendaraan WHERE id_kendaraan='$id'");
        header("Location: ../../public/views/kendaraan.php?success=deleted");
        exit;
    } else {
        header("Location: ../../public/views/kendaraan.php?error=not_owner");
        exit;
    }
} else {
    header("Location: ../../public/views/kendaraan.php?error=invalid_id");
    exit;
}
