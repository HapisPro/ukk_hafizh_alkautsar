<?php
$conn = mysqli_connect( "localhost", "root", "", "pengaduan_masyarakat" );

// if ($conn) {
//     echo "tersambung";
// }

// Registrasi
function registrasi( $data ) {
    global $conn;

    $nik = $data["nik"];
    $nama = strtolower( stripslashes( $data["nama"] ) );
    $username = strtolower( stripslashes( $data["username"] ) );
    $pass = mysqli_real_escape_string( $conn, $data["password"] );
    $pass2 = mysqli_real_escape_string( $conn, $data["password2"] );
    $telp = $data["telp"];
    
    // Cek username
    $result = mysqli_query( $conn, "SELECT username FROM masyarakat WHERE username = '$username'" );
    if ( mysqli_fetch_assoc( $result ) ) {
        header( "Location: registrasi.php?msg=usrn" );
        return false;
    }
    
    // Cek konfirmasi pass
    if ( $pass !== $pass2 ) {
        header( "Location: registrasi.php?msg=conpass" );
        return false;
    }
    
    // Encrypt
    $pass = password_hash( $pass, PASSWORD_DEFAULT );
    
    // Tambah user
    mysqli_query( $conn, "INSERT INTO masyarakat VALUES('$nik', '$nama' , '$username', '$pass', '$telp')" );
    return mysqli_affected_rows( $conn );
}

// Login user
function loginuser( $data ) {
    global $conn;
    $username = strtolower( stripslashes( $data["username"] ) );
    $pass = mysqli_real_escape_string( $conn,  $data["password"] );

    $result = mysqli_query( $conn, "SELECT*FROM masyarakat WHERE username='$username'" );

    // Cek username
    if ( mysqli_num_rows( $result ) === 1 ) {
        // Cek pass
        $row = mysqli_fetch_assoc( $result );
        if ( password_verify( $pass, $row["password"] ) ) {
            // Set session
            $_SESSION["login"] = true;
            $_SESSION["nama"] = $row["nama"];
            $_SESSION["nik"] = $row["nik"];
        } else {
            header( 'Location: login.php?msg=pw' );
            exit;
            return false;
        }
    } else {
        header( 'Location: login.php?msg=pw' );
        exit;
        return false;
    }

    return mysqli_affected_rows( $conn );
}

// Login admin
function loginadmin( $data ) {
        global $conn;
        $username = $data["username"];
        $pass = md5( $data["password"] );
    
        $result = mysqli_query( $conn, "SELECT*FROM petugas WHERE username='$username' AND password='$pass'" );
    
        // Cek username
        if ( mysqli_num_rows( $result ) === 1 ) {
            $row = mysqli_fetch_assoc( $result );
            $_SESSION["loginadm"] = true;
            $_SESSION["level"] = $row["level"];
            $_SESSION["id_petugas"] = $row["id_petugas"];
            $_SESSION["nama_petugas"] = $row["nama_petugas"];
        } else {
            header( 'Location: loginadmin.php?msg=pw' );
            exit;
            return false;
        }
    
        return mysqli_affected_rows( $conn );
}

// Get data
function query( $query ) {
    global $conn;
    $result = mysqli_query( $conn, $query );
    $rows = [];
    while ( $row = mysqli_fetch_assoc( $result ) ) {
        $rows[] = $row;
    }
    return $rows;
}

// Upload handler
function upload() {
    $namaFile = $_FILES['foto']['name'];
    $err = $_FILES['foto']['error'];
    $tmp = $_FILES['foto']['tmp_name'];

    // Verifikasi extension file
    $extensi = ['jpg', 'jpeg', 'png'];
    $extUpload = explode( '.', $namaFile );
    $extUpload = strtolower( end( $extUpload ) );

    if ( !in_array( $extUpload, $extensi ) ) {
        header( 'Location: pengaduan.php?msg=ext' );
        return false;
    }

    // Insert ke dir
    // Generate foto baru
    $newname = uniqid();
    $newname .= '.';
    $newname .= $extUpload;
    move_uploaded_file( $tmp, 'assets/img/upload/' . $newname );
    return $newname;

}

// Tambah data
function tambah( $data ) {
    global $conn;

    $tanggal = date( 'Y-m-d' );
    $isi = htmlspecialchars( $data["isi"] );
    $nik = $_SESSION["nik"];

    // Upload gambar
    $foto = upload();
    if ( !$foto ) {
        return false;
    }

    $query = "INSERT INTO pengaduan VALUES ('', '$tanggal', '$nik' , '$isi', '$foto', '0')";
    mysqli_query( $conn, $query );
    return mysqli_affected_rows( $conn );
}

// Hapus data
function hapus( $data ) {
    global $conn;
    $id = $data["id"];
    $result = mysqli_query( $conn, "SELECT foto FROM pengaduan WHERE id_pengaduan = $id" );
    $file = mysqli_fetch_assoc( $result );

    $namaFile = implode( '.', $file );
    $location = 'assets/img/upload/' . $namaFile;

    // Menghapus file dari dir
    if ( file_exists( $location ) ) {
        unlink( 'assets/img/upload/' . $namaFile );
    }

    mysqli_query( $conn, "DELETE FROM pengaduan WHERE id_pengaduan = $id" );
    return mysqli_affected_rows( $conn );

}

// Ubah data
function ubah( $data ) {
    global $conn;

    $id_pengaduan = $data["id_pengaduan"];
    $tanggal = date( 'Y-m-d' );
    $nik = $_SESSION["nik"];
    $isi = htmlspecialchars( $data["isi"] );
    $status = 0;
    $statuslama = $data["status"];
    $fotoLama = htmlspecialchars( $data["fotoLama"] );
    
    // Cek apakah foto diupdate atau tidak
    if ( $_FILES['foto']['error'] === 4 ) {
        $foto = $fotoLama;
    } else {
        $result = mysqli_query( $conn, "SELECT foto FROM pengaduan WHERE id_pengaduan=$id_pengaduan" );
        $file = mysqli_fetch_assoc( $result );
        
        // Menghapus file dari dir
        $namaFile = implode( '.', $file );
        unlink( 'assets/img/upload/' . $namaFile );
        
        $foto = upload();
    }
    
    // Cek status
    if ( $statuslama == "tolak" ) {
        mysqli_query( $conn, "DELETE FROM tanggapan WHERE id_pengaduan = $id_pengaduan" );
    }
        
    $query = "UPDATE pengaduan SET
                tgl_pengaduan = '$tanggal',
                nik = '$nik',
                isi_laporan = '$isi',
                foto = '$foto',
                status = '$status' WHERE id_pengaduan = $id_pengaduan
                ";

    mysqli_query( $conn, $query );
    return mysqli_affected_rows( $conn );
}

// Function verif
function verif( $data ) {
    global $conn;

    $id_pengaduan = $data["id_pengaduan"];
    $id_petugas = $_SESSION["id_petugas"];
    $status = $data["status"];
    $tanggapan = $data["tanggapan"];
    $tanggal = date( 'Y-m-d' );
    $level = $_SESSION["level"];

    if ( $tanggapan === "" ) {
        if ( $status === "proses" ) {
            $tanggapan = "Laporan telah diterima $level dan sedang di proses";
        } else if ( $status === "tolak" ) {
            $tanggapan = "Laporan ditolak $level, harap edit atau hapus laporan";
        } else {
            $tanggapan = "Laporan telah diselesaikan";
        }
    }

    mysqli_query( $conn, "UPDATE pengaduan SET status = '$status' WHERE id_pengaduan = $id_pengaduan" );
    mysqli_query( $conn, "INSERT INTO tanggapan VALUES ('', '$id_pengaduan', '$tanggal', '$tanggapan', '$id_petugas')" );
    return mysqli_affected_rows( $conn );
}

// Function tanggap
function tanggap( $data ) {
    global $conn;

    $id_pengaduan = $data["id_pengaduan"];
    $status = $data["status"];
    $tanggapan = $data["tanggapan"];
    $tanggal = date( 'Y-m-d' );
    $level = $_SESSION["level"];

    if ( $tanggapan === "" ) {
        if ( $status === "proses" ) {
            $tanggapan = "Laporan telah diterima $level dan sedang di proses";
        } else if ( $status === "tolak" ) {
            $tanggapan = "Laporan ditolak $level, harap edit atau hapus laporan";
        } else {
            $tanggapan = "Laporan telah diselesaikan";
        }
    }

    mysqli_query( $conn, "UPDATE pengaduan SET status = '$status' WHERE id_pengaduan = $id_pengaduan" );
    mysqli_query( $conn, "UPDATE tanggapan SET tgl_tanggapan = '$tanggal', tanggapan = '$tanggapan' WHERE id_pengaduan = $id_pengaduan" );
    return mysqli_affected_rows( $conn );
}

// Cari
function cari( $keyword ) {
    $query = "SELECT pengaduan.*, tanggapan.*, petugas.*, masyarakat.* FROM pengaduan
                 INNER JOIN masyarakat ON masyarakat.nik = pengaduan.nik
                 LEFT JOIN tanggapan ON tanggapan.id_pengaduan = pengaduan.id_pengaduan
                 LEFT JOIN petugas ON petugas.id_petugas = tanggapan.id_petugas
                 WHERE isi_laporan LIKE '%$keyword%' OR status LIKE '$keyword' OR
                 tanggapan LIKE '%$keyword%' ORDER BY status ASC, tgl_pengaduan DESC";
    return query( $query );
}