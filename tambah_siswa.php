<?php
session_start();
if (!isset($_SESSION['id_guru'])) { header("Location: login.php"); exit(); }

require_once 'config/koneksi.php';

$nama    = $_SESSION['nama'];
$inisial = strtoupper(substr($nama, 0, 1));
$error   = '';

if (isset($_POST['simpan'])) {
    $nama_siswa    = trim($_POST['nama_siswa']);
    $jenis_kelamin = $_POST['jenis_kelamin'];

    if ($nama_siswa === '') {
        $error = 'Nama santri tidak boleh kosong.';
    } else {
        $nama_esc = mysqli_real_escape_string($koneksi, $nama_siswa);
        mysqli_query($koneksi, "INSERT INTO siswa (nama, jenis_kelamin) VALUES ('$nama_esc', '$jenis_kelamin')");
        header("Location: data_siswa.php?success=1");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Siswa — Guru TPA</title>
    <link rel="stylesheet" href="tampilan/style.css">
</head>
<body>
<div class="wrapper">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <img src="tampilan/logoululalbab.png" alt="Logo BQH" class="sidebar-logo-img">

            <div>
                <div class="app-name">Formula BQH</div>
                <div class="app-sub">Qur'an Enthusiast</div>
            </div>
        </div>

        <div class="user-card">
            <div class="avatar"><?= $inisial ?></div>
            <div>
                <div class="user-name"><?= htmlspecialchars($nama) ?></div>
                <div class="user-role">Guru Pengajar</div>
            </div>
        </div>

        <nav>
            <div class="nav-label">Menu</div>
            <a href="dashboard.php"       class="nav-item"><span class="icon">&#9783;</span> Dashboard</a>
            <a href="tambah_progress.php" class="nav-item"><span class="icon">&#43;</span> Tambah Progress</a>
            <a href="data_progress.php"   class="nav-item"><span class="icon">&#9776;</span> Data Progress</a>
            <a href="data_siswa.php"      class="nav-item"><span class="icon">&#128100;</span> Data Siswa</a>
            <a href="tambah_siswa.php"    class="nav-item active"><span class="icon">&#43;</span> Tambah Siswa</a>
        </nav>

        <div class="logout-area">
            <a href="logout.php" class="logout-btn"><span class="icon">&#10148;</span> Keluar</a>
        </div>
    </aside>

    <!-- MAIN -->
    <main class="main">

        <div class="topbar">
            <div>
                <div class="topbar-title">Tambah Siswa Baru</div>
                <div class="topbar-sub">Daftarkan santri baru ke sistem</div>
            </div>
        </div>

        <div class="content">
            <div class="card" style="max-width:480px;">
                <div class="card-header">
                    <div class="card-title">Form Data Santri</div>
                </div>
                <div class="card-body">

                    <?php if ($error): ?>
                        <div class="alert alert-error">&#9888; <?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST">

                        <div class="form-group">
                            <label for="nama_siswa">Nama Santri</label>
                            <input type="text" id="nama_siswa" name="nama_siswa"
                                placeholder="Masukkan nama lengkap santri"
                                value="<?= htmlspecialchars($_POST['nama_siswa'] ?? '') ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Jenis Kelamin</label>
                            <div style="display:flex; gap:12px; margin-top:4px;">
                                <label class="gender-option" id="lbl-laki">
                                    <input type="radio" name="jenis_kelamin" value="Laki-laki" required onchange="highlightGender()">
                                    <span style="font-size:18px;">♂</span> Laki-laki
                                </label>
                                <label class="gender-option" id="lbl-perempuan">
                                    <input type="radio" name="jenis_kelamin" value="Perempuan" onchange="highlightGender()">
                                    <span style="font-size:18px;">♀</span> Perempuan
                                </label>
                            </div>
                        </div>

                        <div style="display:flex; gap:12px; margin-top:20px;">
                            <button type="submit" name="simpan" class="btn-submit">&#10003; Simpan Santri</button>
                            <a href="data_siswa.php" class="btn-edit">Batal</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </main>

</div>

<style>
.gender-option {
    flex:1; border:1px solid #dde2ee; border-radius:8px;
    padding:12px 16px; cursor:pointer; display:flex;
    align-items:center; gap:10px; font-size:14px;
    color:#3a4a6a; transition:all 0.15s; font-weight:normal;
}
</style>

<script>
function highlightGender() {
    const laki      = document.querySelector('input[value="Laki-laki"]').checked;
    const lblLaki   = document.getElementById('lbl-laki');
    const lblPrmpn  = document.getElementById('lbl-perempuan');

    lblLaki.style.border      = laki ? '2px solid #2563a8' : '1px solid #dde2ee';
    lblLaki.style.background  = laki ? '#f0f5ff' : '#fff';
    lblPrmpn.style.border     = laki ? '1px solid #dde2ee' : '2px solid #d94f8a';
    lblPrmpn.style.background = laki ? '#fff' : '#fff0f7';
}
</script>
</body>
</html>