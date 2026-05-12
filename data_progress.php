<?php
session_start();
if (!isset($_SESSION['id_guru'])) { header("Location: login.php"); exit(); }

require_once 'config/koneksi.php';

$nama    = $_SESSION['nama'];
$inisial = strtoupper(substr($nama, 0, 1));

$query = mysqli_query($koneksi,
    "SELECT progress_belajar.*, siswa.nama AS nama_siswa
     FROM progress_belajar
     JOIN siswa ON progress_belajar.id_siswa = siswa.id_siswa
     ORDER BY tanggal DESC, id_progress DESC"
);

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
    <title>Data Progress — Guru TPA</title>
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
            <a href="data_progress.php"   class="nav-item active"><span class="icon">&#9776;</span> Data Progress</a>
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
                <div class="topbar-title">Data Progress Belajar</div>
                <div class="topbar-sub">Rekap seluruh progress santri</div>
            </div>
            <a href="tambah_progress.php" class="btn-primary">&#43; Tambah Progress</a>
        </div>

        <div class="content">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Semua Data Progress</div>
                    <span style="font-size:12px; color:#8a93a8;"><?= mysqli_num_rows($query) ?> entri ditemukan</span>
                </div>
                <div class="card-body" style="padding:0;">
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Santri</th>
                                    <th>Tanggal</th>
                                    <th>Materi</th>
                                    <th>Progress</th>
                                    <th>Skor</th>
                                    <th>Grade</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if (mysqli_num_rows($query) === 0): ?>
                                <tr>
                                    <td colspan="8" style="text-align:center; color:#8a93a8; padding:32px;">
                                        Belum ada data progress.
                                        <a href="tambah_progress.php" style="color:#2563a8;">Tambah sekarang &rarr;</a>
                                    </td>
                                </tr>
                            <?php else: $no = 1; while ($data = mysqli_fetch_assoc($query)): ?>
                                <tr>
                                    <td style="color:#8a93a8; font-size:12px;"><?= $no++ ?></td>
                                    <td><strong><?= htmlspecialchars($data['nama_siswa']) ?></strong></td>
                                    <td><?= date('d M Y', strtotime($data['tanggal'])) ?></td>
                                    <td><?= htmlspecialchars($data['materi']) ?></td>
                                    <td><?= htmlspecialchars($data['progress']) ?></td>
                                    <td><strong><?= $data['skor'] ?></strong></td>
                                    <td><?= badgeGrade($data['grade']) ?></td>
                                    <td style="color:#8a93a8; font-size:13px;"><?= htmlspecialchars($data['catatan']) ?></td>
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