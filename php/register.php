<?php
include("../config/koneksi.php");

if (isset($_POST['register'])) {
    $username   = mysqli_real_escape_string($conn, $_POST['username']);
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nama       = mysqli_real_escape_string($conn, $_POST['nama']);
    $alamat     = mysqli_real_escape_string($conn, $_POST['alamat']);
    $no_hp      = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $no_ktp     = mysqli_real_escape_string($conn, $_POST['no_ktp']);
    $role       = $_POST['role'];
    $keterangan = isset($_POST['keterangan']) ? mysqli_real_escape_string($conn, $_POST['keterangan']) : '';

    // cek apakah username sudah dipakai
    $check = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Username sudah digunakan!'); window.location.href='../public/register.php';</script>";
        exit();
    }

    // masukkan ke tabel users
    $query = mysqli_query($conn, "INSERT INTO users (username, password, role) 
                                  VALUES ('$username', '$password', '$role')");

    if ($query) {
        $user_id = mysqli_insert_id($conn); // ambil id_user terbaru

        // kalau role agen → masuk juga ke tabel pemilik
        if ($role === 'agen') {
            mysqli_query($conn, "INSERT INTO pemilik (id_user, nama_pemilik, alamat, no_hp, no_ktp, keterangan) 
                                 VALUES ('$user_id', '$nama', '$alamat', '$no_hp', '$no_ktp', '$keterangan')");
        }
        // kalau role pelanggan → masuk juga ke tabel pelanggan
        elseif ($role === 'pelanggan') {
            mysqli_query($conn, "INSERT INTO pelanggan (id_user, nama, alamat, no_hp, no_ktp) 
                                 VALUES ('$user_id', '$nama', '$alamat', '$no_hp', '$no_ktp')");
        }

        echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location.href='../public/login.php';</script>";
    } else {
        echo "<script>alert('Registrasi gagal: " . mysqli_error($conn) . "'); window.location.href='../public/register.php';</script>";
    }
}
?>
