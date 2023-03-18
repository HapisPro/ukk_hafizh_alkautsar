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

// Ambil data
$nik = $_SESSION["nik"];
$getData = query( "SELECT * FROM pengaduan WHERE nik=$nik ORDER BY status ASC, tgl_pengaduan DESC" );

// Simpan data
if ( isset( $_POST["simpan"] ) ) {
    if ( tambah( $_POST ) > 0 ) {
        header( 'Location: pengaduan.php?msg=simpan' );
    }
}

// Hapus data
if ( isset( $_POST["hapus"] ) ) {
    if ( hapus( $_POST ) > 0 ) {
        header( 'Location: pengaduan.php?msg=hapus' );
    }
}

// Ubah data
if ( isset( $_POST["ubah"] ) ) {
    if ( ubah( $_POST ) > 0 ) {
        header( 'Location: pengaduan.php?msg=ubah' );
    }
}

?>

<?php if ( $getData !== [] ): ?>
<div class="container pt-5">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Daftar Pengaduan</h1>
        <a href="" class="btn btn-primary px-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="fa fa-square-plus me-2 fa-lg"></i><strong>Tulis Pengaduan</strong>
        </a>
    </div>

    <!-- Flasher -->
    <?php if ( isset( $_GET['msg'] ) ): ?>
    <?php if ( $_GET['msg'] == "simpan" ): ?>
    <div class="alert alert-success alert-dismissible text-start fade show align-items-center" role="alert">
        <i class="fa fa-circle-check fa-xl me-2"></i> Berhasil membuat pengaduan
        <button type="button" class="btn-close" onclick="document.location.href= 'pengaduan.php'"></button>
    </div>
    <?php endif;?>

    <?php if ( $_GET['msg'] == "ubah" ): ?>
    <div class="alert alert-primary alert-dismissible text-start fade show align-items-center" role="alert">
        <i class="fa fa-circle-check fa-xl me-2"></i> Berhasil mengubah pengaduan
        <button type="button" class="btn-close" onclick="document.location.href= 'pengaduan.php'"></button>
    </div>
    <?php endif;?>

    <?php if ( $_GET['msg'] == "hapus" ): ?>
    <div class="alert alert-warning alert-dismissible text-start fade show align-items-center" role="alert">
        <i class="fa fa-circle-info fa-xl me-2"></i> Berhasil menghapus pengaduan
        <button type="button" class="btn-close" onclick="document.location.href= 'pengaduan.php'"></button>
    </div>
    <?php endif;?>

    <?php if ( $_GET['msg'] == "ext" ): ?>
    <div class="alert alert-danger alert-dismissible text-start fade show align-items-center" role="alert">
        <i class="fa fa-warning fa-xl me-2"></i> Upload gagal, harap gunakan foto dengan extensi jpeg/png!
        <button type="button" class="btn-close" onclick="document.location.href= 'pengaduan.php'"></button>
    </div>
    <?php endif;?>
    <?php endif;?>

    <table class="table table-hover table-bordered">
        <thead class="thead-primary">
            <tr>
                <th class="text-center" style="width: 50px;">No</th>
                <th class="text-center" style="width: 200px;">Foto</th>
                <th style="width: 100px;">Tanggal</th>
                <th>Isi Laporan</th>
                <th class="text-center" style="width: 100px;">Status</th>
                <th class="text-center" style="width: 130px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;?>
            <?php foreach ( $getData as $row ): ?>
            <tr>
                <td class="text-center"><?=$no;?></td>
                <td class="text-center">
                    <img src="assets/img/upload/<?=$row["foto"]?>" alt="" width="200px">
                </td>
                <td><?=$row["tgl_pengaduan"]?></td>
                <td data-bs-toggle="modal" data-bs-target="#modalDetail<?=$row['id_pengaduan'];?>"
                    style="cursor: pointer;" title="Detail">
                    <p class="d-inline-block text-truncate" style="max-width: 500px;"><?=$row["isi_laporan"]?>
                    </p>
                </td>
                <td class="text-center">
                    <?php if ( $row['status'] == '0' ) {?>
                    <span class="badge bg-warning p-2">Menunggu</span>
                    <?php } else if ( $row['status'] == 'proses' ) {?>
                    <span class="badge bg-primary p-2">Proses</span>
                    <?php } else if ( $row['status'] == 'tolak' ) {?>
                    <span class="badge bg-danger p-2">Ditolak</span>
                    <?php } else {?>
                    <span class="badge bg-success p-2">Selesai</span>
                    <?php }?>
                </td>
                <td class="text-center">
                    <?php if ( $row['status'] == 'tolak' || $row['status'] == '0' ): ?>
                    <a class="btn btn-info text-light" data-bs-toggle="modal"
                        data-bs-target="#modalUbah<?=$row['id_pengaduan'];?>">
                        <i class="fa fa-edit"></i>
                    </a>
                    <a class="btn btn-danger" data-bs-toggle="modal"
                        data-bs-target="#modalHapus<?=$row['id_pengaduan'];?>">
                        <i class="fa fa-trash"></i>
                    </a>
                    <?php else: ?>
                    <a class="btn btn-info text-light disabled" data-bs-toggle="modal"
                        data-bs-target="#modalUbah<?=$row['id_pengaduan'];?>">
                        <i class="fa fa-edit"></i>
                    </a>
                    <a href="functions/delete.php?id=<?php echo $row["id_pengaduan"]; ?>"
                        onclick="return confirm('Yang benerr?')" class="btn btn-danger disabled">
                        <i class="fa fa-trash"></i>
                    </a>
                    <?php endif;?>
                </td>
            </tr>
            <?php $no++;?>
            <?php endforeach;?>
        </tbody>
    </table>
    <?php else: ?>
    <div class="w-100 pt-4 mt-2 d-flex flex-column align-items-center justify-content-center text-center">
        <img class="mb-4" src="assets/img/nodata.jpg" width="290px">
        <h3 class="fw-bold">Anda belum melakukan pengaduan</h3>
        <p style="width: 500px;">
            Ayo tulis keluhanmu melalui aplikasi pengaduan masyarakat! Dengan menulis pengaduan, kami dapat
            memberikan solusi yang tepat dan memperbaiki layanan yang kurang memuaskan.
        </p>
        <a class="btn btn-primary px-3 my-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <strong>Tulis Pengaduan</strong>
        </a>
    </div>
    <?php endif;?>

    <!-- Modal tambah -->
    <div class="modal fade" id="modalTambah" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Tulis pengaduan</h3>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data">
                        <div class="form-group mb-4">
                            <label for="isi">
                                <h5>Isi Laporan</h5>
                            </label>
                            <textarea class="form-control" name="isi" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="isi">
                                <h5>Foto</h5>
                            </label>
                            <input type="file" name="foto" class="form-control" required>
                        </div>
                        <div class="modal-footer mt-4">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" name="simpan">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal ubah -->
    <?php foreach ( $getData as $row ): ?>
    <div class="modal fade" id="modalUbah<?=$row['id_pengaduan'];?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Edit pengaduan</h3>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id_pengaduan" value="<?=$row['id_pengaduan'];?>">
                        <input type="hidden" name="status" value="<?=$row['status'];?>">
                        <input type="hidden" name="fotoLama" value="<?=$row['foto'];?>">
                        <div class="form-group mb-4">
                            <label for="isi">
                                <h5>Isi Laporan</h5>
                            </label>
                            <textarea class="form-control" name="isi" required><?=$row['isi_laporan'];?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="foto">
                                <h5>Foto (Kosongkan jika tidak ingin mengubah)</h5>
                            </label>
                            <input type="file" name="foto" class="form-control">
                        </div>
                        <div class="modal-footer mt-4">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" name="ubah" id="ubah">Ubah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach;?>

    <!-- Modal hapus -->
    <?php foreach ( $getData as $row ): ?>
    <div class="modal fade mt-5" id="modalHapus<?=$row['id_pengaduan'];?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="container text-center">
                    <i class="fa-regular fa-circle-xmark fa-10x text-danger my-4"></i>

                    <h3 class="mb-4">Hapus data?</h3>
                    <p class="text-muted">Apakah anda yakin untuk menghapus pengaduan ini? <br>
                        Pengaduan yang dihapus tidak bisa dikembalikan.
                    </p>

                    <div class="d-flex justify-content-center my-4">
                        <form method="post">
                            <input type="hidden" name="id" value="<?=$row['id_pengaduan'];?>">
                            <button type="button" class="btn btn-secondary px-5 me-2"
                                data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger px-5" name="hapus" id="hapus">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach;?>

    <!-- Modal detail -->
    <?php
$getData = query( "SELECT pengaduan.*, tanggapan.tanggapan, tanggapan.tgl_tanggapan
                FROM pengaduan LEFT JOIN tanggapan
                ON tanggapan.id_pengaduan = pengaduan.id_pengaduan
                WHERE nik = $nik" );
foreach ( $getData as $row ):
?>
    <div class="modal fade" id="modalDetail<?=$row['id_pengaduan'];?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Detail pengaduan</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="text-center">
                            <img class="img-fluid" src="assets/img/upload/<?=$row['foto'];?>" alt=""
                                style="min-width: 430px;">
                        </div>
                        <p class="text-justify mt-1">
                            <?=$row['isi_laporan'];?>
                        </p>
                        <table class="mt-3">
                            <tr class="align-text-top">
                                <th>Tanggal</th>
                                <th class="p-1">:</th>
                                <td><?=$row['tgl_pengaduan'];?></td>
                            </tr>
                            <tr class="align-text-top">
                                <th>Status</th>
                                <th class="p-1">:</th>
                                <td>
                                    <?php if ( $row['status'] == '0' ) {?>
                                    <span class="text-warning">Menunggu</span>
                                    <?php } else if ( $row['status'] == 'proses' ) {?>
                                    <span class="text-primary">Proses</span>
                                    <?php } else if ( $row['status'] == 'tolak' ) {?>
                                    <span class="text-danger">Ditolak</span>
                                    <?php } else {?>
                                    <span class="text-success">Selesai</span>
                                    <?php }?>
                                </td>
                            </tr>
                            <tr class="align-text-top">
                                <th>Tanggapan</th>
                                <th class="p-1">:</th>
                                <?php if ( $row['tanggapan'] == null ): ?>
                                <td>-</td>
                                <?php else: ?>
                                <td><?=$row['tanggapan'];?></td>
                                <?php endif;?>
                            </tr>
                            <tr class="align-text-top">
                                <th style="width: 150px;">Tanggal Tanggapan</th>
                                <th class="p-1">:</th>
                                <?php if ( $row['tgl_tanggapan'] == null ): ?>
                                <td>-</td>
                                <?php else: ?>
                                <td><?=$row['tgl_tanggapan'];?></td>
                                <?php endif;?>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach;?>

</div>

<?php
include 'assets/template/footer.php';
?>