<?php
include 'assets/template/header.php';
require 'functions/query.php';

if ( isset( $_POST["daftar"] ) ) {
    if ( registrasi( $_POST ) > 0 ) {
        header( "Location: login.php?msg=reg" );
        exit;
    } else {
        echo mysqli_error( $conn );
    }
}
?>

<div class="vh-100 d-flex align-items-center justify-content-center bg-primary">
    <div class="d-flex rounded-4 p-5 bg-light align-items-center justify-content-between shadow-lg w-75">

        <div class="col-6">
            <img class="img-fluid" src="assets/img/heroregist.png">
        </div>

        <div class="col-6">
            <div class="d-flex align-items-center pb-3 mb-3 border-bottom border-primary border-2">
                <img src="assets/img/logo.png" class="d-inline-block align-text-center" alt="" width="50px">
                <h4 class="d-inline-block ms-2" style="margin-top: 10px;">Aplikasi Pengaduan Masyarakat</h4>
            </div>

            <!-- Flasher -->
            <?php if ( isset( $_GET['msg'] ) ): ?>
            <?php if ( $_GET['msg'] == "usrn" ): ?>
            <div class="alert alert-warning alert-dismissible text-start fade show align-items-center" role="alert">
                <i class="fa fa-warning"></i> Username sudah digunakan
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                    onclick="document.location.href= 'registrasi.php'"></button>
            </div>
            <?php endif;?>
            <?php if ( $_GET['msg'] == "conpass" ): ?>
            <div class="alert alert-danger alert-dismissible text-start fade show align-items-center" role="alert">
                <i class="fa fa-warning"></i> Password tidak sesuai
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                    onclick="document.location.href= 'registrasi.php'"></button>
            </div>
            <?php endif;?>
            <?php endif;?>

            <form class="d-flex row align-items-center text-center" method="post">
                <div class="input-group mb-3">
                    <input type="number" class="form-control w-75" placeholder="NIK" required name="nik">
                </div>

                <div class="input-group mb-3">
                    <input type="text" class="form-control w-75" placeholder="Nama" required name="nama">
                </div>

                <div class="input-group mb-3">
                    <input type="text" class="form-control w-75" placeholder="Username" required name="username">
                </div>

                <div class="input-group mb-3">
                    <input type="password" class="form-control w-75" placeholder="Password" required name="password">
                </div>

                <div class="input-group mb-3">
                    <input type="password" class="form-control w-75" placeholder="Konfirmasi Password" required
                        name="password2">
                </div>

                <div class="input-group mb-3">
                    <input type="number" class="form-control w-75" placeholder="Nomor Handphone" required name="telp">
                </div>

                <div class="d-flex align-items-center justify-content-between">
                    <a href="login.php" class="text-decoration-underline p-1">
                        <small>Sudah punya akun?</small>
                    </a>
                    <button type="submit" class="btn btn-primary btn-block p-1 w-50" name="daftar">Daftar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'assets/template/footer.php';?>