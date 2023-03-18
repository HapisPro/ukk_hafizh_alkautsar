<?php
$page = substr( $_SERVER['REQUEST_URI'], strrpos( $_SERVER['REQUEST_URI'], "/" ) + 1 );
?>

<div class="d-flex main-container sidebar">
    <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark z-1 min-vh-100"
        style="width: 250px; position: fixed;">
        <a href="index.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <img src="http://localhost/ukk_hafizh_alkautsar/assets/img/logo.png"
                class="d-inline-block align-text-center me-2" alt="" width="50px">
            <h5 class="d-inline-block ms-2" style="margin-top: 10px;">Aplikasi Pengaduan Masyarakat</h5>
        </a>
        <hr>
        <ul class="nav flex-column mb-auto">
            <li class="nav-item mb-3 side-hov <?=$page == 'index.php' ? 'bg-primary' : ''?>">
                <a href="index.php" class="nav-link text-white">
                    <i class="fa fa-house me-2"></i>
                    <span>Home</span>
                </a>
            </li>
            <li class="nav-item mb-3 side-hov <?=$page == 'verifikasi.php' ? 'bg-primary' : ''?>">
                <a href="verifikasi.php" class="nav-link text-white">
                    <i class="fa fa-clipboard-list fa-lg me-2"></i>
                    <span>Verifikasi Pengaduan</span>
                </a>
            </li>
            <li class="nav-item mb-3 side-hov <?=$page == 'tanggapan.php' ? 'bg-primary' : ''?>">
                <a href="tanggapan.php" class="nav-link text-white">
                    <i class="fa fa-clipboard-check fa-lg me-2"></i>
                    <span>Data Tanggapan</span>
                </a>
            </li>
            <li class="nav-item mb-3 side-hov <?=$page == 'generate.php' ? 'bg-primary' : ''?>">
                <a href="generate.php" class="nav-link text-white">
                    <i class="fa fa-print fa-lg me-2"></i>
                    <span>Generate Laporan</span>
                </a>
            </li>
        </ul>
        <span style="opacity: .6;">
            <i class="fa fa-lg fa-circle-user me-2"></i>
            <?= $_SESSION['nama_petugas']; ?>
        </span>
        <hr>
        <div>
            <a href="http://localhost/ukk_hafizh_alkautsar/logout.php" class="nav-link text-danger fs-5">
                <i class="fa fa-right-from-bracket"></i>
                Logout
            </a>
        </div>
    </div>