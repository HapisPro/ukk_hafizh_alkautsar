<?php
session_start();
include 'assets/template/header.php';
include 'assets/template/usernav.php';
require 'functions/query.php';

// Cek login
if ( !isset( $_SESSION['login'] ) ) {
    header( "Location: login.php?msg=log" );
    exit;
}
?>

<div class="container d-flex align-items-center justify-content-between pt-5">
    <div class="d-inline-block col-6 me-5">
        <h1 class="fw-bold mb-4">Selamat datang <?php echo $_SESSION['nama']; ?></h1>
        <p class="mb-4 text-justify">
            Aplikasi pengaduan masyarakat adalah platform digital yang mempermudah masyarakat untuk melaporkan masalah
            lingkungan. Fitur utamanya termasuk pengiriman laporan, tracking status, dan komunikasi dengan pihak
            berwenang. Ini memfasilitasi interaksi antara masyarakat dan pemerintah dan memastikan bahwa masalah
            diterima dan ditindaklanjuti dengan baik. mantap!
        </p>
        <a href="pengaduan.php" class="btn btn-primary px-5">Tulis Pengaduan</a>
    </div>

    <img class="img-fluid col-5 d-inline-block" src="assets/img/herohome.jpg" alt="">
</div>

<?php
include 'assets/template/footer.php';
?>