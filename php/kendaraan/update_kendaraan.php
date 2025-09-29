<?php
include(__DIR__ . "/../../config/koneksi.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id_kendaraan']);
    $no_plat = $_POST['no_plat'];
    $merk = $_POST['merk'];
    $tipe = $_POST['tipe'];
    $tahun = $_POST['tahun'];
    $harga_sewa = $_POST['harga_sewa'];
    $status = $_POST['status'];
    $id_pemilik = $_SESSION['id_pemilik'];

    // Cek apakah agen ini punya kendaraan tersebut
    $cek = mysqli_query($conn, "SELECT * FROM kendaraan WHERE id_kendaraan='$id' AND id_pemilik='$id_pemilik'");
    if (mysqli_num_rows($cek) == 0) {
        header("Location: ../../public/views/kendaraan.php?error=not_owner");
        exit;
    }

    // Upload gambar jika ada
    if (!empty($_FILES['gambar']['name'])) {
        $gambar = time() . "_" . basename($_FILES['gambar']['name']);
        $target = __DIR__ . "/../../uploads/" . $gambar;
        move_uploaded_file($_FILES['gambar']['tmp_name'], $target);

        mysqli_query($conn, "UPDATE kendaraan SET no_plat='$no_plat', merk='$merk', tipe='$tipe', tahun='$tahun', harga_sewa='$harga_sewa', status='$status', gambar='$gambar' WHERE id_kendaraan='$id'");
    } else {
        mysqli_query($conn, "UPDATE kendaraan SET no_plat='$no_plat', merk='$merk', tipe='$tipe', tahun='$tahun', harga_sewa='$harga_sewa', status='$status' WHERE id_kendaraan='$id'");
    }

    header("Location: ../../public/views/kendaraan.php?success=updated");
    exit;
}
