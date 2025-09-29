<?php
session_start();
include("../../config/koneksi.php");

if (!isset($_SESSION['id_pemilik'])) {
    die("Error: Anda belum login sebagai agen. Silakan login ulang.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pemilik = $_SESSION['id_pemilik'];
    $no_plat     = trim($_POST['no_plat']);
    $merk        = $_POST['merk'];
    $tipe        = $_POST['tipe'];
    $tahun       = $_POST['tahun'];
    $harga_sewa  = $_POST['harga_sewa'];
    $status      = "Tersedia";

    // Upload gambar
    $gambar = null;
    if (!empty($_FILES['gambar']['name'])) {
        $targetDir = "../../uploads/";
        $gambar = time() . "_" . basename($_FILES["gambar"]["name"]);
        $targetFile = $targetDir . $gambar;
        move_uploaded_file($_FILES["gambar"]["tmp_name"], $targetFile);
    }

    // Cek plat unik
    $cek = mysqli_query($conn, "SELECT id_kendaraan FROM kendaraan WHERE no_plat = '$no_plat'");
    if (mysqli_num_rows($cek) > 0) {
        header("Location: ../../public/views/tambah_kendaraan.php?error=No plat sudah terdaftar");
        exit;
    }

    $sql = "INSERT INTO kendaraan (id_pemilik, no_plat, merk, tipe, tahun, harga_sewa, gambar, status) 
            VALUES ('$id_pemilik','$no_plat','$merk','$tipe','$tahun','$harga_sewa','$gambar','$status')";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: ../../public/views/kendaraan.php?success=Kendaraan berhasil ditambahkan");
        exit;
    } else {
        header("Location: ../../public/views/tambah_kendaraan.php?error=Gagal menyimpan data");
        exit;
    }
}
?>
