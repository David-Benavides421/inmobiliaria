<?php
require_once '../conexion.php';
if (isset($_GET['cod_ofi'])) {
    $cod_ofi = intval($_GET['cod_ofi']);

    $sql = "DELETE FROM oficinas WHERE cod_ofi = $cod_ofi";

    if ($conn->query($sql) === TRUE) {
        header("Location: consultar_oficinas.php"); // Redirige al listado
        exit();
    } else {
        echo "Error al eliminar: " . $conn->error;
    }
} else {
    echo "ID de oficina no especificado.";
}
?>
