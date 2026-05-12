<?php
session_start();
if (!isset($_SESSION['id_guru'])) { header("Location: login.php"); exit(); }

require_once 'config/koneksi.php';

$nama    = $_SESSION['nama'];
$inisial = strtoupper(substr($nama, 0, 1));
$hari_ini = date('Y-m-d');

// Ambil data statistik
$totalSantri     = mysqli_num_rows(mysqli_query($koneksi, "SELECT id_siswa FROM siswa"));
$totalProgress   = mysqli_num_rows(mysqli_query($koneksi, "SELECT id_progress FROM progress_belajar"));
$progressHariIni = mysqli_num_rows(mysqli_query($koneksi,
    "SELECT id_progress FROM progress_belajar WHERE tanggal='$hari_ini' AND id_guru='{$_SESSION['id_guru']}'"
));

// Ambil 5 progress terbaru
$qTerbaru = mysqli_query($koneksi,
    "SELECT progress_belajar.*, siswa.nama
     FROM progress_belajar
     JOIN siswa ON progress_belajar.id_siswa = siswa.id_siswa
     ORDER BY tanggal DESC, id_progress DESC LIMIT 5"
);

// Fungsi badge grade
function badgeGrade($grade) {
    $map = ['A+' => 'badge-green', 'A' => 'badge-green', 'B+' => 'badge-blue',
            'B'  => 'badge-blue',  'C+' => 'badge-amber', 'C' => 'badge-amber'];
    $class = $map[$grade] ?? 'badge-red';
    return "<span class='badge $class'>$grade</span>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Guru TPA</title>
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
            <a href="dashboard.php"       class="nav-item active"><span class="icon">&#9783;</span> Dashboard</a>
            <a href="tambah_progress.php" class="nav-item"><span class="icon">&#43;</span> Tambah Progress</a>
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
                <div class="topbar-title">Dashboard Guru</div>
                <div class="topbar-sub"><?= date('l, d F Y') ?></div>
            </div>
        </div>

        <div class="content">

            <!-- Greeting -->
            <div class="greeting-bar">
                <div>
                    <h2>Bismillah, <?= htmlspecialchars($nama) ?>! &#128075;</h2>
                    <p>Pantau dan catat progress belajar santri Ulul Albab.</p>
                </div>
                <a href="tambah_progress.php" class="btn-primary">&#43; Tambah Progress</a>
            </div>

            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon blue">&#128100;</div>
                    <div>
                        <div class="stat-label">Total Santri</div>
                        <div class="stat-value"><?= $totalSantri ?></div>
                        <div class="stat-sub">Santri terdaftar</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon amber">&#128214;</div>
                    <div>
                        <div class="stat-label">Progress Dicatat</div>
                        <div class="stat-value"><?= $totalProgress ?></div>
                        <div class="stat-sub">Total entri progress</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green">&#9733;</div>
                    <div>
                        <div class="stat-label">Progress Hari Ini</div>
                        <div class="stat-value"><?= $progressHariIni ?></div>
                        <div class="stat-sub">Entri hari ini</div>
                    </div>
                </div>
            </div>

            <!-- Tabel Progress Terbaru -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Progress Terbaru</div>
                    <a href="data_progress.php" style="font-size:12px; color:#2563a8; font-weight:500;">Lihat semua &rarr;</a>
                </div>
                <div class="card-body" style="padding:0;">
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Nama Santri</th>
                                    <th>Tanggal</th>
                                    <th>Materi</th>
                                    <th>Progress</th>
                                    <th>Skor</th>
                                    <th>Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if (mysqli_num_rows($qTerbaru) === 0): ?>
                                <tr>
                                    <td colspan="6" style="text-align:center; color:#8a93a8; padding:24px;">
                                        Belum ada data progress.
                                    </td>
                                </tr>
                            <?php else: while ($row = mysqli_fetch_assoc($qTerbaru)): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($row['nama']) ?></strong></td>
                                    <td><?= date('d M Y', strtotime($row['tanggal'])) ?></td>
                                    <td><?= htmlspecialchars($row['materi']) ?></td>
                                    <td><?= htmlspecialchars($row['progress']) ?></td>
                                    <td><?= $row['skor'] ?></td>
                                    <td><?= badgeGrade($row['grade']) ?></td>
                                </tr>
                            <?php endwhile; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </main>

</div>
</body>
</html>