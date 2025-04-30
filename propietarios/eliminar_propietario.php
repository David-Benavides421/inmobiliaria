<?php
require '/xampp/htdocs/inmobiliaria/conexion.php';

$id = $_GET['id'];
$conn->query("DELETE FROM propietarios WHERE cod_propietario=$id");
header("Location: propietarios.php");
?>
