<?php
$page = substr( $_SERVER['REQUEST_URI'], strrpos( $_SERVER['REQUEST_URI'], "/" ) + 1 );
?>

<nav class="navbar bg-light navbar-expand-lg border-bottom border-3 sticky-top p-2">
    <div class="container-fluid container">
        <img src="http://localhost/ukk_hafizh_alkautsar/assets/img/logo.png" class="d-inline-block align-text-center"
            alt="" width="50px">
        <a class="navbar-brand text-primary" href="index.php">
            <h4 class="d-inline-block ms-2 fs-4" style="margin-top: 10px;">Aplikasi Pengaduan Masyarakat</h4>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
            aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-link link-hov <?=$page == 'index.php' ? 'link-act' : ''?>" href="
                    index.php">Home</a>
                <a class="nav-link link-hov <?=$page == 'pengaduan.php' ? 'link-act' : ''?>" href="pengaduan.php">
                    Pengaduan</a>
                <a class="nav-link link-hov text-danger" href="logout.php">
                    Logout
                    <i class="fa fa-right-from-bracket"></i>
                </a>
            </div>
        </div>
    </div>
</nav>