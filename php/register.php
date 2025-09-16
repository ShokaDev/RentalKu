<?php
include("../config/koneksi.php");

if (isset($_POST['register'])) {
    $username   = mysqli_real_escape_string($conn, $_POST['username']);
    $email      = mysqli_real_escape_string($conn, $_POST['email']);
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nama       = mysqli_real_escape_string($conn, $_POST['nama']);
    $alamat     = mysqli_real_escape_string($conn, $_POST['alamat']);
    $no_hp      = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $no_ktp     = mysqli_real_escape_string($conn, $_POST['no_ktp']);
    $role       = mysqli_real_escape_string($conn, $_POST['role']);
    $keterangan = isset($_POST['keterangan']) ? mysqli_real_escape_string($conn, $_POST['keterangan']) : NULL;

    // Cek username atau email sudah ada
    $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' OR email='$email'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Username atau Email sudah digunakan!'); window.location.href='../public/login.php';</script>";
        exit();
    }

    // Insert user baru
    $query = mysqli_query($conn, "INSERT INTO users 
        (username, email, password, role, nama, alamat, no_hp, no_ktp, keterangan) 
        VALUES 
        ('$username', '$email', '$password', '$role', '$nama', '$alamat', '$no_hp', '$no_ktp', '$keterangan')");

    if ($query) {
        echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location.href='../public/login.php';</script>";
    } else {
        echo "<script>alert('Registrasi gagal: " . mysqli_error($conn) . "'); window.location.href='../public/login.php';</script>";
    }
}
?>
