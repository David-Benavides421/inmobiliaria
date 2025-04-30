<?php
require '/xampp/htdocs/inmobiliaria/conexion.php';

$codigo = $_GET['cod_tipoinm'];
$sql = "DELETE FROM tipo_inmueble WHERE cod_tipoinm = $codigo";

if ($conn->query($sql) === TRUE) {
    header("Location: /inmobiliaria/tipo_inmueble/tipo_inmueble.php");
} else {
    echo "Error al eliminar: " . $conn->error;
}
?>