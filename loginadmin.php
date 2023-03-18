<?php
session_start();
include 'assets/template/header.php';
require 'functions/query.php';

if ( isset( $_POST["masuk"] ) ) {
    if ( loginadmin( $_POST ) > 0 ) {
        // Cek level
        if ( $_SESSION["level"] === "admin" ) {
            header( 'Location: admin/index.php' );
            exit;
        } else if ( $_SESSION["level"] === "petugas" ) {
            header( 'Location: petugas/index.php' );
            exit;
        }
    } else {
        echo mysqli_error( $conn );
    }
}
?>

<div class="vh-100 d-flex align-items-center justify-content-center bg-primary">
    <div class="w-50 d-flex rounded-4 p-5 bg-light align-items-center justify-content-between shadow-lg">

        <div class="col-4">
            <a href="login.php"><img class="img-fluid" src="assets/img/logo.png"></a>
            <h5 class="text-center">Aplikasi Pengaduan Masyarakat</h5>
        </div>

        <div class=" col-7 text-center">
            <h1 class="border border-primary border-top-0 border-end-0 border-start-0 p-1 mb-3">LOGIN ADMIN</h1>

            <!-- Flasher -->
            <?php if ( isset( $_GET['msg'] ) ): ?>
            <?php if ( $_GET['msg'] == "pw" ): ?>
            <div class="alert alert-danger alert-dismissible text-start fade show" role="alert">
                <i class="fa fa-warning"></i> Username/Password salah
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                    onclick="document.location.href= 'loginadmin.php'"></button>
            </div>
            <?php endif;?>
            <?php endif;?>

            <form class="d-flex row align-items-center text-center" method="post">
                <div class="input-group mb-3">
                    <input type="text" class="form-control w-75" placeholder="Username" name="username">
                    <span class="input-group-text">
                        <i class="fas fa-user"></i>
                    </span>
                </div>

                <div class="input-group mb-3">
                    <input type="password" class="form-control w-75" placeholder="Password" name="password">
                    <span class="input-group-text">
                        <i class="fas fa-lock"></i>
                    </span>
                </div>

                <div class="input-group">
                    <button type="submit" class="btn btn-primary p-1 w-100" name="masuk">Masuk</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'assets/template/footer.php';?>