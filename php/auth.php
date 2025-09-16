<?php
session_start();
include("../config/koneksi.php");

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // ngecek username sudah ada atau belum
    $check = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($check) > 0) {  // Ngecek username sudah digunain atau belum
        echo "<script>alert('Username sudah digunakan!'); window.location.href='../index.php';</script>";
        exit();
    }

    // Insert ke database (akun baru)
    $query = mysqli_query($conn, "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')");
    if ($query) {
        echo "<script>alert('Registrasi berhasil!'); window.location.href='../views/users.php';</script>";
        // echo "<script>alert('Registrasi berhasil!'); window.location.href='../views/contoh.php';</script>";
    } else {
        echo "<script>alert('Registrasi gagal: " . mysqli_error($conn) . "'); window.location.href='index.php';</script>";
    }


    exit();
}


// Ini untuk deteksi bagian Login nya berhasil / ngga
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    $user = mysqli_fetch_assoc($query);

    if ($user && $user['password'] === md5($password)) {
        $_SESSION['user'] = $user;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        header("Location: ../public/views/index.php");
    } else {
        echo "<script>alert('Login gagal! Username atau password salah.'); window.location.href='../public/login.php';</script>";
    }

    exit();
}

?>  