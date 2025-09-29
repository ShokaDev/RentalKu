<?php
include("../../config/koneksi.php");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); 

    $query = "DELETE FROM pelanggan WHERE id_pelanggan = $id";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Akun berhasil dihapus'); window.location.href='pelanggan.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus akun'); window.location.href='pelanggan.php';</script>";
    }
} else {
    header("Location: pelanggan.php");
    exit;
}
?>
