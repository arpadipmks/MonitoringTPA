<?php
session_start();
if (!isset($_SESSION['id_guru'])) { header("Location: login.php"); exit(); }

require_once 'config/koneksi.php';

if(isset($_GET['hapus'])){

    $id = intval($_GET['hapus']);

    mysqli_query($koneksi,
        "DELETE FROM siswa
         WHERE id_siswa='$id'");

    header("Location: data_siswa.php");
    exit;
}

$nama    = $_SESSION['nama'];
$inisial = strtoupper(substr($nama, 0, 1));

$query          = mysqli_query($koneksi, "SELECT * FROM siswa ORDER BY nama ASC");
$totalSiswa     = mysqli_num_rows($query);
$totalLaki      = mysqli_num_rows(mysqli_query($koneksi, "SELECT id_siswa FROM siswa WHERE jenis_kelamin='Laki-laki'"));
$totalPerempuan = $totalSiswa - $totalLaki;
$success        = isset($_GET['success']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa — Guru TPA</title>
    <link rel="stylesheet" href="tampilan/style.css">
    <style>
        .search-bar { display:flex; align-items:center; gap:8px; background:#fff; border:1px solid #dde2ee; border-radius:8px; padding:0 14px; max-width:260px; }
        .search-bar input { border:none; outline:none; padding:9px 0; font-size:13.5px; width:100%; font-family:inherit; background:transparent; }
    </style>
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
            <a href="data_siswa.php"      class="nav-item active"><span class="icon">&#128100;</span> Data Siswa</a>
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
                <div class="topbar-title">Data Siswa</div>
                <div class="topbar-sub">Daftar seluruh santri yang terdaftar</div>
            </div>
            <a href="tambah_siswa.php" class="btn-primary">&#43; Tambah Siswa</a>
        </div>

        <div class="content">

            <?php if ($success): ?>
                <div class="alert alert-success">&#10003; Santri baru berhasil ditambahkan!</div>
            <?php endif; ?>

            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon blue">&#128100;</div>
                    <div>
                        <div class="stat-label">Total Santri</div>
                        <div class="stat-value"><?= $totalSiswa ?></div>
                        <div class="stat-sub">Terdaftar</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon blue">&#9794;</div>
                    <div>
                        <div class="stat-label">Laki-laki</div>
                        <div class="stat-value"><?= $totalLaki ?></div>
                        <div class="stat-sub">Santri putra</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon amber">&#9792;</div>
                    <div>
                        <div class="stat-label">Perempuan</div>
                        <div class="stat-value"><?= $totalPerempuan ?></div>
                        <div class="stat-sub">Santri putri</div>
                    </div>
                </div>
            </div>

            <!-- Tabel -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Semua Santri</div>
                    <div class="search-bar">
                        <span>&#128269;</span>
                        <input type="text" id="searchInput" placeholder="Cari nama santri..." onkeyup="filterTable()">
                    </div>
                </div>
                <div class="card-body" style="padding:0;">
                    <div class="table-wrap">
                        <table id="siswaTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Santri</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if ($totalSiswa === 0): ?>
                                <tr>
                                    <td colspan="4" style="text-align:center; padding:40px; color:#8a93a8;">
                                        <div style="font-size:36px; margin-bottom:12px;">&#128100;</div>
                                        <div style="margin-bottom:14px;">Belum ada data santri.</div>
                                        <a href="tambah_siswa.php" class="btn-primary">&#43; Tambah Siswa Pertama</a>
                                    </td>
                                </tr>
                            <?php else: $no = 1; while ($s = mysqli_fetch_assoc($query)):
                                $isLaki      = ($s['jenis_kelamin'] === 'Laki-laki');
                                $genderIcon  = $isLaki ? '♂' : '♀';
                                $genderColor = $isLaki ? '#2563a8' : '#d94f8a';
                                $genderBg    = $isLaki ? '#e8f0fb' : '#fde8f3';
                            ?>
                                <tr>
                                    <td style="color:#8a93a8; font-size:12px;"><?= $no++ ?></td>
                                    <td>
                                        <div style="display:flex; align-items:center; gap:10px;">
                                            <div style="width:34px; height:34px; border-radius:50%; background:<?= $genderBg ?>; color:<?= $genderColor ?>; display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:700;">
                                                <?= strtoupper(substr($s['nama'], 0, 1)) ?>
                                            </div>
                                            <strong><?= htmlspecialchars($s['nama']) ?></strong>
                                        </div>
                                    </td>
                                    <td>
                                        <span style="display:inline-flex; align-items:center; gap:5px; background:<?= $genderBg ?>; color:<?= $genderColor ?>; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:600;">
                                            <?= $genderIcon ?> <?= htmlspecialchars($s['jenis_kelamin']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="data_siswa.php?hapus=<?= $s['id_siswa'] ?>"
                                           onclick="return confirm('Yakin hapus santri <?= htmlspecialchars(addslashes($s['nama'])) ?>?')"
                                           class="btn-danger">&#10006; Hapus</a>
                                    </td>
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

<script>
function filterTable() {
    const input = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('#siswaTable tbody tr').forEach(row => {
        const nama = row.cells[1] ? row.cells[1].textContent.toLowerCase() : '';
        row.style.display = nama.includes(input) ? '' : 'none';
    });
}
</script>
</body>
</html>