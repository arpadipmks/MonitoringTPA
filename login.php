<?php
session_start();
require_once 'config/koneksi.php';

$error = '';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query($koneksi,
        "SELECT * FROM guru WHERE username='$username' AND password='$password'"
    );

    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);
        $_SESSION['id_guru']  = $data['id_guru'];
        $_SESSION['nama']     = $data['nama'];
        $_SESSION['username'] = $data['username'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Username atau password salah. Silakan coba lagi.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Guru TPA</title>
    <link rel="stylesheet" href="tampilan/style.css">
</head>
<body>

<div class="login-page">
    <div class="login-card">

<div class="login-logo">
    <img src="tampilan/logoululalbab.png" alt="Logo BQH" class="logo-img">

    <h1>Forum Pemuda Masjid Ulul Albab</h1>
    <p>Masuk ke akun guru Anda</p>
</div>

        <?php if ($error): ?>
            <div class="alert alert-error">&#9888; <?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username"
                    placeholder="Masukkan username" required autocomplete="username">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password"
                    placeholder="Masukkan password" required autocomplete="current-password">
            </div>

            <button type="submit" name="login" class="btn-login">Masuk</button>
        </form>

    </div>
</div>

</body>
</html>