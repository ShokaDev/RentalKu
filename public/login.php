<?php
include("../config/koneksi.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Register</title>
    <link rel="stylesheet" href="../src/css/login.css">
</head>

<body>
    <div class="wrapper active-popup">
        <!-- <span class="icon-close"><ion-icon name="close"></ion-icon></span> -->

        <!-- Login Form -->
        <div class="form-box login">
            <h2>Login</h2>
            <form action="../php/auth.php" method="POST">
                <div class="input-box">
                    <span class="icon"><ion-icon name="mail"></ion-icon></span>
                    <input type="text" name="username" required>
                    <label>Username</label>
                </div>
                <div class="input-box">
                    <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
                    <input type="password" name="password" required>
                    <label>Password</label>
                </div>
                <!-- <div class="remember-forgot">
                    <label><input type="checkbox">Remember Me</label>
                    <a href="#">Forgot Password?</a>
                </div> -->
                <button type="submit" name="login" class="btn">Masuk</button>
                <div class="login-register">
                    <p>Don't have an account? <a href="#" class="register-link">Register</a></p>
                </div>
            </form>
        </div>

        <!-- Register Form -->
        <div class="form-box register">
            <h2>Register</h2>
            <form action="../php/register.php" method="POST">

                <!-- Username -->
                <div class="input-box">
                    <span class="icon"><ion-icon name="person"></ion-icon></span>
                    <input type="text" name="username" required>
                    <label>Username</label>
                </div>

                <!-- Email -->
                <div class="input-box">
                    <span class="icon"><ion-icon name="mail"></ion-icon></span>
                    <input type="email" name="email" required>
                    <label>Email</label>
                </div>

                <!-- Password -->
                <div class="input-box">
                    <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
                    <input type="password" name="password" required>
                    <label>Password</label>
                </div>

                <!-- Nama Lengkap -->
                <div class="input-box">
                    <span class="icon"><ion-icon name="id-card"></ion-icon></span>
                    <input type="text" name="nama" required>
                    <label>Nama Lengkap</label>
                </div>

                <!-- Alamat -->
                <div class="input-box">
                    <span class="icon"><ion-icon name="home"></ion-icon></span>
                    <input type="text" name="alamat" required>
                    <label>Alamat</label>
                </div>

                <!-- No HP -->
                <div class="input-box">
                    <span class="icon"><ion-icon name="call"></ion-icon></span>
                    <input type="text" name="no_hp" required>
                    <label>No HP</label>
                </div>

                <!-- No KTP -->
                <div class="input-box">
                    <span class="icon"><ion-icon name="card"></ion-icon></span>
                    <input type="text" name="no_ktp" required>
                    <label>No KTP</label>
                </div>

                <!-- Role -->
                <div class="input-box">
                    <span class="icon"><ion-icon name="people"></ion-icon></span>
                    <select name="role" required>
                        <option value="" disabled selected>Pilih Role</option>
                        <option value="pelanggan">Pelanggan</option>
                        <option value="agen">Agen / Pemilik Kendaraan</option>
                    </select>
                    <label>Daftar Sebagai</label>
                </div>

                <!-- Keterangan (opsional, khusus agen) -->
                <div class="input-box">
                    <span class="icon"><ion-icon name="document-text"></ion-icon></span>
                    <input type="text" name="keterangan" placeholder="Keterangan (opsional)">
                    <label>Keterangan</label>
                </div>

                <!-- Submit -->
                <button type="submit" name="register" class="btn">Register</button>

                <div class="login-register">
                    <p>Already have an account? <a href="#" class="login-link">Login</a></p>
                </div>
            </form>
        </div>


    </div>

    <!-- Script -->
    <script src="../src/js/add-user.js"></script>
    <script src="../src/js/login.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script>
        const wrapper = document.querySelector('.wrapper');
        const loginLink = document.querySelector('.login-link');
        const registerLink = document.querySelector('.register-link');
        const btnPopup = document.querySelector('.btnLogin-popup');
        const iconClose = document.querySelector('.icon-close');

        registerLink.addEventListener('click', () => {
            wrapper.classList.add('active');
        });

        loginLink.addEventListener('click', () => {
            wrapper.classList.remove('active');
        });

        btnPopup.addEventListener('click', () => {
            wrapper.classList.add('active-popup');
        });

        iconClose.addEventListener('click', () => {
            wrapper.classList.remove('active-popup');
        });
    </script>
</body>

</html>