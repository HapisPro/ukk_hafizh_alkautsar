<?php
session_start();
include '../assets/template/header.php';
include '../assets/template/sidebarad.php';
include '../functions/query.php';

// Cek login
if ( isset( $_SESSION['loginadm'] ) ) {
    if ( $_SESSION["level"] !== "admin" ) {
        echo "<script>
            alert ('Anda bukan admin');
            document.location.href = '../logout.php';
            </script>";
    }
} else {
    header( "Location: ../loginadmin.php" );
    exit;
}

// Verifikasi
// Ubah data
if ( isset( $_POST["verif"] ) ) {
    if ( tanggap( $_POST ) > 0 ) {
        echo "<script>
        alert ('Data telah diverifikasi');
         document.location.href = 'tanggapan.php';
        </script>";
    } else {
        echo "<script>
        alert ('Data gagal diubah');
        </script>";
    }
}

// Ambil data
$getData = query( "SELECT pengaduan.*, masyarakat.nama, masyarakat.telp, tanggapan.*, petugas.nama_petugas, petugas.level
                FROM pengaduan INNER JOIN masyarakat ON masyarakat.nik = pengaduan.nik
                LEFT JOIN tanggapan ON tanggapan.id_pengaduan = pengaduan.id_pengaduan
                LEFT JOIN petugas ON petugas.id_petugas = tanggapan.id_petugas ORDER BY status ASC, tgl_pengaduan DESC" );

// Cari
if ( isset( $_POST['cari'] ) ) {
    $getData = cari( $_POST["keyword"] );
}

?>

<div class="container mt-3" style="padding-left: 230px;">

    <h1 class="mb-4">Daftar Laporan</h1>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <form action="" method="post" class="w-50">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Cari Data.." name="keyword" autocomplete="off">
                <button class="btn btn-primary" type="submit" name="cari"><i class="fa fa-search"></i></button>
            </div>
        </form>
        <a href="print.php" class="btn btn-info"><i class="fa fa-print text-light fa-lg"></i>
            <strong class="text-light ms-2 fs-5">Cetak Laporan</strong>
        </a>
    </div>

    <?php if ( $getData !== [] ): ?>
    <table class="table table-hover table-bordered">
        <thead class="thead-primary">
            <tr>
                <th class="text-center" style="width: 50px;">No</th>
                <th class="text-center" style="width: 100px;">Foto</th>
                <th>Isi Laporan</th>
                <th>Isi Tanggapan</th>
                <th style="width: 200px;">Admin</th>
                <th class="text-center" style="width: 80px;">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;?>
            <?php foreach ( $getData as $row ): ?>
            <tr>
                <td class="text-center"><?=$no;?></td>
                <td class="text-center">
                    <img src="../assets/img/upload/<?=$row["foto"]?>" alt="" width="100px">
                </td>
                <td data-bs-toggle="modal" data-bs-target="#modalDetail<?=$row['id_pengaduan'];?>"
                    style="cursor: pointer;" title="Detai pengaduan">
                    <p class="d-inline-block text-truncate" style="max-width: 100px;"><?=$row["isi_laporan"]?>
                    </p>
                </td>
                <?php if ( $row['tanggapan'] == '' ): ?>
                <td>-</td>
                <?php else: ?>
                <td data-bs-toggle="modal" data-bs-target="#modalDetailTanggapan<?=$row['id_tanggapan'];?>"
                    style="cursor: pointer;" title="Detail tanggapan">
                    <p class="d-inline-block text-truncate" style="max-width: 200px;"><?=$row["tanggapan"]?>
                    </p>
                </td>
                <?php endif;?>
                <td>
                    <?php if ( $row['level'] == 'admin' ): ?>
                    <span class="text-primary"><?=$row['nama_petugas'];?></span>
                    <?php else: ?>
                    <?=$row['nama_petugas'] == '' ? '-' : $row['nama_petugas']?>
                    <?php endif;?>
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
            </tr>
            <?php $no++;?>
            <?php endforeach;?>
        </tbody>
    </table>
    <?php else: ?>
    <div class="text-center">
        <img class="mt-4" src="../assets/img/nodata.jpg" width="400px">
        <h1 class="fw-bold">Data tidak ditemukan</h1>
    </div>
    <?php endif;?>

    <!-- Modal verifikasi -->
    <?php foreach ( $getData as $row ): ?>
    <div class="modal fade" id="modalVerif<?=$row['id_pengaduan'];?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Tanggapi pengaduan</h3>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id_pengaduan" value="<?=$row['id_pengaduan'];?>">
                        <div class="form-group mb-4">
                            <label for="status">
                                <h5>Status</h5>
                            </label>
                            <select class="form-select" name="status" id="status">
                                <option value="selesai">Selesai</option>
                                <option value="proses">Proses</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tanggap">
                                <h5>Tanggapan</h5>
                            </label>
                            <textarea name="tanggapan" id="tanggap" class="form-control"></textarea>
                        </div>
                        <div class="modal-footer mt-4">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" name="verif">Verifikasi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach;?>

    <!-- Modal detail -->
    <?php foreach ( $getData as $row ): ?>
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
                            <img class="img-fluid" src="../assets/img/upload/<?=$row['foto'];?>" alt=""
                                style="min-width: 430px;">
                        </div>
                        <p class="text-justify mt-1">
                            <?=$row['isi_laporan'];?>
                        </p>
                        <hr>
                        <table>
                            <tr>
                                <th>Pelapor</th>
                                <th class="py-1 ps-3 pe-1">:</th>
                                <td><?=$row['nama'];?></td>
                            </tr>
                            <tr>
                                <th>Telp Pelapor</th>
                                <th class="py-1 ps-3 pe-1">:</th>
                                <td><?=$row['telp'];?></td>
                            </tr>
                            <tr>
                                <th>Tanggal</th>
                                <th class="py-1 ps-3 pe-1">:</th>
                                <td><?=$row['tgl_pengaduan'];?></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <th class="py-1 ps-3 pe-1">:</th>
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
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach;?>

    <!-- Modal detail tanggapan -->
    <?php foreach ( $getData as $row ): ?>
    <div class="modal fade" id="modalDetailTanggapan<?=$row['id_tanggapan'];?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Detail Tanggapan</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <table>
                            <tr class="align-text-top">
                                <th>Isi Laporan</th>
                                <th class="py-2 ps-3 pe-1">:</th>
                                <td><?=$row['isi_laporan'];?></td>
                            </tr>
                            <tr class="align-text-top">
                                <th>Isi Tanggapan</th>
                                <th class="py-2 ps-3 pe-1">:</th>
                                <td><?=$row['tanggapan'];?></td>
                            </tr>
                            <tr>
                                <th style="width: 150px;">Tanggal Pengaduan</th>
                                <th class="py-2 ps-3 pe-1">:</th>
                                <td><?=$row['tgl_pengaduan'];?></td>
                            </tr>
                            <tr>
                                <th style="width: 150px;">Tanggal Tanggapan</th>
                                <th class="py-2 ps-3 pe-1">:</th>
                                <td><?=$row['tgl_tanggapan'];?></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <th class="py-2 ps-3 pe-1">:</th>
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
                            <tr>
                                <th>Admin</th>
                                <th class="py-2 ps-3 pe-1">:</th>
                                <td><?=$row['nama_petugas'];?> (<?=$row['id_petugas'];?>)</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach;?>

</div>

<!-- Close tag for sidebar -->
</div>

<?php
include '../assets/template/footer.php';
?>