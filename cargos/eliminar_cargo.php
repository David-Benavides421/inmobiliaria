<?php
require '/xampp/htdocs/inmobiliaria/conexion.php';

$codigo = $_GET['cod_cargo'];
$sql = "DELETE FROM cargos WHERE cod_cargo = $codigo";

if ($conn->query($sql) === TRUE) {
    header("Location: /inmobiliaria/cargos/cargos.php");
} else {
    echo "Error al eliminar: " . $conn->error;
}
?>


