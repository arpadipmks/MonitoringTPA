<?php

session_start();

if (!isset($_SESSION['id_guru'])) {
    header("Location: login.php");
    exit();
}

require_once 'config/koneksi.php';

if (isset($_GET['id'])) {

    $id = (int) $_GET['id'];

    // Hapus progress dulu baru hapus siswa (agar tidak error foreign key)
    mysqli_query($koneksi, "DELETE FROM progress_belajar WHERE id_siswa=$id");
    mysqli_query($koneksi, "DELETE FROM absensi WHERE id_siswa=$id");
    mysqli_query($koneksi, "DELETE FROM siswa WHERE id_siswa=$id");
}

header("Location: data_siswa.php");
exit();