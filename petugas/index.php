<?php
session_start();
include '../assets/template/header.php';
include '../assets/template/sidebarpet.php';
require '../functions/query.php';

// Cek login
if ( isset( $_SESSION['loginadm'] ) ) {
    if ( $_SESSION["level"] !== "petugas" ) {
        header( 'Location: ../logout.php' );
        exit;
    }
} else {
    header( "Location: ../loginadmin.php" );
    exit;
}
?>

<div class="container mt-4" style="padding-left: 270px;">
    <div class="text-center">
        <img class="w-50 mt-3" src="../assets/img/herodash.jpg">
        <h1 class="fw-bold">Dashboard <span class="text-primary">Petugas</span></h1>
        <h1 class="fw-bold">Aplikasi Pengaduan Masyarakat</h1>
    </div>
</div>


<!-- Close tag for sidebar -->
</div>

<?php
include '../assets/template/footer.php';
?>