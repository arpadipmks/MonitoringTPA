<?php
session_start();

if (!isset($_SESSION['id_guru'])) {
    header("Location: login.php");
    exit();
}

require_once 'config/koneksi.php';

if (isset($_GET['id'])) {

    $id = intval($_GET['id']);

    $hapus = mysqli_query($koneksi,
        "DELETE FROM progress_belajar
         WHERE id_progress = '$id'"
    );

    if ($hapus) {
        echo "
        <script>
            alert('Progress berhasil dihapus!');
            window.location='data_progress.php';
        </script>";
    } else {
        echo "
        <script>
            alert('Gagal menghapus progress!');
            window.location='data_progress.php';
        </script>";
    }

} else {
    header("Location: data_progress.php");
}
?>