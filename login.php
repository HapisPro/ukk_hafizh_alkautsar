<?php
session_start();
include 'assets/template/header.php';
require 'functions/query.php';

if ( isset( $_POST["masuk"] ) ) {
    if ( loginuser( $_POST ) > 0 ) {
        header( 'Location: index.php' );
        exit;
    } else {
        echo mysqli_error( $conn );
    }
}
?>

<div class="vh-100 d-flex align-items-center justify-content-center bg-primary">
    <div class="d-flex rounded-4 p-5 bg-light align-items-center justify-content-between shadow-lg" style="width: 60%;">

        <div class="col-5">
            <a href="loginadmin.php">
                <img class="img-fluid" src="assets/img/herologin.png">
            </a>
        </div>

        <div class="col-6 text-center">
            <div class="d-flex align-items-center pb-3 mb-3 border-bottom border-primary border-2">
                <img src="assets/img/logo.png" class="d-inline-block align-text-center" alt="" width="50px">
                <h1 class="d-inline-block ms-2" style="margin-top: 10px; font-size: 1.2rem;">Aplikasi
                    Pengaduan
                    Masyarakat
                </h1>
            </div>

            <!-- Flasher -->
            <?php if ( isset( $_GET['msg'] ) ): ?>
            <?php if ( $_GET['msg'] == "pw" ): ?>
            <div class="alert alert-danger alert-dismissible text-start fade show align-items-center" role="alert">
                <i class="fa fa-warning"></i> Username/Password salah
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                    onclick="document.location.href= 'login.php'"></button>
            </div>
            <?php endif;?>
            <?php if ( $_GET['msg'] == "reg" ): ?>
            <div class="alert alert-success alert-dismissible text-start fade show align-items-center" role="alert">
                <i class="fa fa-check"></i> Berhasil mendaftarkan akun
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                    onclick="document.location.href= 'login.php'"></button>
            </div>
            <?php endif;?>
            <?php if ( $_GET['msg'] == "log" ): ?>
            <div class="alert alert-warning alert-dismissible text-start fade show align-items-center" role="alert">
                <i class="fa fa-warning"></i> Login untuk menggunakan aplikasi
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                    onclick="document.location.href= 'login.php'"></button>
            </div>
            <?php endif;?>
            <?php endif;?>

            <form class="d-flex row align-items-center text-center" method="post">
                <div class="input-group mb-3">
                    <input type="text" class="form-control w-75" placeholder="Username" name="username" required>
                    <span class="input-group-text">
                        <i class="fas fa-user"></i>
                    </span>
                </div>

                <div class="input-group mb-3">
                    <input type="password" class="form-control w-75" placeholder="Password" name="password" required>
                    <span class="input-group-text">
                        <i class="fas fa-lock"></i>
                    </span>
                </div>

                <div class="d-flex align-items-center justify-content-between">
                    <a href="registrasi.php" class="text-decoration-underline p-1">
                        <small>Daftar pengguna baru</small>
                    </a>
                    <button type="submit" class="btn btn-primary btn-block p-1 w-50" name="masuk">Masuk</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'assets/template/footer.php';?>