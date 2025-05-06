<?php
require '/xampp/htdocs/inmobiliaria/conexion.php';

$nombre = $_POST['nom_tipoinm'];

$sql = "INSERT INTO tipo_inmueble (nom_tipoinm) VALUES ('$nombre')";
if ($conn->query($sql) === TRUE) {
    header("Location: /inmobiliaria/tipo_inmueble/tipo_inmueble.php");
} else {
    echo "Error al guardar: " . $conn->error;
}
?>