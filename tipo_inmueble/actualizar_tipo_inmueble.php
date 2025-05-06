<?php
require '/xampp/htdocs/inmobiliaria/conexion.php';

$codigo = $_POST['cod_tipoinm'];
$nombre = $_POST['nom_tipoinm'];

$sql = "UPDATE tipo_inmueble SET nom_tipoinm = '$nombre' WHERE cod_tipoinm = $codigo";
if ($conn->query($sql) === TRUE) {
    header("Location: /inmobiliaria/tipo_inmueble/tipo_inmueble.php");
} else {
    echo "Error al actualizar: " . $conn->error;
}
?>