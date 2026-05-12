<?php
session_start();
if (!isset($_SESSION['id_guru'])) { header("Location: login.php"); exit(); }

require_once 'config/koneksi.php';
require_once 'classes/ProgressBelajar.php';
require_once 'classes/Absensi.php';

$nama    = $_SESSION['nama'];
$inisial = strtoupper(substr($nama, 0, 1));

// Proses simpan form
if (isset($_POST['simpan'])) {
    $progress = new ProgressBelajar(0, $_POST['tanggal'], $_POST['materi'],
        $_POST['progress'], $_POST['catatan'], $_POST['id_siswa'], $_SESSION['id_guru']);
    $progress->setSkor($_POST['skor']);
    $progress->simpanProgress($koneksi);

    $absensi = new Absensi($_POST['tanggal'], $_POST['status'], $_POST['id_siswa'], $_SESSION['id_guru']);
    $absensi->simpanAbsensi($koneksi);

    header("Location: data_progress.php");
    exit();
}

$querySiswa = mysqli_query($koneksi, "SELECT * FROM siswa ORDER BY nama ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Progress — Guru TPA</title>
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
            <a href="tambah_progress.php" class="nav-item active"><span class="icon">&#43;</span> Tambah Progress</a>
            <a href="data_progress.php"   class="nav-item"><span class="icon">&#9776;</span> Data Progress</a>
            <a href="data_siswa.php"      class="nav-item"><span class="icon">&#128100;</span> Data Siswa</a>
            <a href="tambah_siswa.php"    class="nav-item"><span class="icon">&#43;</span> Tambah Siswa</a>
        </nav>

        <div class="logout-area">
            <a href="logout.php" class="logout-btn"><span class="icon">&#10148;</span> Keluar</a>
        </div>
    </aside>

    <!-- MAIN -->
    <main class="main">

        <div class="topbar">
            <div>
                <div class="topbar-title">Tambah Progress Belajar</div>
                <div class="topbar-sub">Guru: <?= htmlspecialchars($nama) ?></div>
            </div>
        </div>

        <div class="content">
            <div class="card" style="max-width:760px;">
                <div class="card-header">
                    <div class="card-title">Form Progress Santri</div>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="form-grid">

                            <div class="form-group">
                                <label for="tanggal">Tanggal</label>
                                <input type="date" id="tanggal" name="tanggal" value="<?= date('Y-m-d') ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="id_siswa">Pilih Santri</label>
                                <select id="id_siswa" name="id_siswa" required>
                                    <option value="">— Pilih Santri —</option>
                                    <?php while ($s = mysqli_fetch_assoc($querySiswa)): ?>
                                        <option value="<?= $s['id_siswa'] ?>"><?= htmlspecialchars($s['nama']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="materi">Materi</label>
                                <input type="text" id="materi" name="materi" placeholder="Contoh: Iqro 2 hal. 15" required>
                            </div>

                            <div class="form-group">
                                <label for="progress">Progress</label>
                                <input type="text" id="progress" name="progress" placeholder="Contoh: Lancar, Perlu latihan" required>
                            </div>

                            <div class="form-group">
                                <label for="skor">Skor (0–100)</label>
                                <input type="number" id="skor" name="skor" min="0" max="100" placeholder="Contoh: 85" required>
                            </div>

                            <div class="form-group">
                                <label for="status">Status Absensi</label>
                                <select id="status" name="status" required>
                                    <option value="">— Pilih Status —</option>
                                    <option value="Hadir">&#10003; Hadir</option>
                                    <option value="Izin">&#9888; Izin</option>
                                    <option value="Alpha">&#10006; Alpha</option>
                                </select>
                            </div>

                            <div class="form-group full">
                                <label for="catatan">Catatan (opsional)</label>
                                <textarea id="catatan" name="catatan" placeholder="Tuliskan catatan perkembangan santri..."></textarea>
                            </div>

                        </div>

                        <div style="display:flex; gap:12px; margin-top:8px;">
                            <button type="submit" name="simpan" class="btn-submit">&#10003; Simpan Progress</button>
                            <a href="data_progress.php" class="btn-edit">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

</div>
</body>
</html>