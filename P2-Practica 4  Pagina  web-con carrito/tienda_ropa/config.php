<?php
$conexion = mysqli_connect("localhost", "root", "", "tienda_ropa");
if (!$conexion) {
    die("Error de conexion: " . mysqli_connect_error());
}
?>