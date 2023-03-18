<?php
session_start();
include '../assets/template/header.php';
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
                LEFT JOIN petugas ON petugas.id_petugas = tanggapan.id_petugas ORDER BY status ASC" );

// Cari
if ( isset( $_POST['cari'] ) ) {
    $getData = cari( $_POST["keyword"] );
}
?>

<script>
window.print();
window.onafterprint = function(event) {
    window.location.href = 'generate.php'
};
</script>
<h1 class="mb-4">Daftar Laporan</h1>

<?php if ( $getData !== [] ): ?>
<table class="table table-bordered">
    <thead>
        <tr>
            <th class="text-center" style="width: 50px;">No</th>
            <th class="text-center" style="width: 150px;">Foto</th>
            <th style="width: 400px;">Isi Laporan</th>
            <th>Isi Tanggapan</th>
            <th>Admin</th>
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
            <td data-bs-toggle="modal" data-bs-target="#modalDetail<?=$row['id_pengaduan'];?>" style="cursor: pointer;"
                title="Detai pengaduan">
                <p class="text-justify"><?=$row["isi_laporan"]?>
                </p>
            </td>
            <?php if ( $row['tanggapan'] == '' ): ?>
            <td>-</td>
            <?php else: ?>
            <td data-bs-toggle="modal" data-bs-target="#modalDetailTanggapan<?=$row['id_tanggapan'];?>"
                style="cursor: pointer;" title="Detail tanggapan">
                <p><?=$row["tanggapan"]?></p>
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

<?php
include '../assets/template/footer.php';
?>