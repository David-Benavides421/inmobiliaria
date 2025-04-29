<?php
require '/xampp/htdocs/inmobiliaria/conexion.php';

$codigo = $_POST['cod_cargo'];
$nombre = $_POST['nom_cargo'];

$sql = "UPDATE cargos SET nom_cargo = '$nombre' WHERE cod_cargo = $codigo";
if ($conn->query($sql) === TRUE) {
    header("Location: /inmobiliaria/cargos/cargos.php");
} else {
    echo "Error al actualizar: " . $conn->error;
}
?>


