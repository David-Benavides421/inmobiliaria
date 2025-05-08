<?php
require '../conexion.php';

$nombre = $_POST['nom_cargo'];

$sql = "INSERT INTO cargos (nom_cargo) VALUES ('$nombre')";
if ($conn->query($sql) === TRUE) {
    header("Location: /inmobiliaria/cargos/cargos.php");
} else {
    echo "Error al guardar: " . $conn->error;
}
?>


