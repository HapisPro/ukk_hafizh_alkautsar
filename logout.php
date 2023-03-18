<?php
session_start();

if ( $_SESSION["level"] === "admin" || $_SESSION["level"] === "petugas" ) {
    session_unset();
    session_destroy();
    header( "location: loginadmin.php" );
} else {
    session_unset();
    session_destroy();
    header( "location: login.php" );
}

?>