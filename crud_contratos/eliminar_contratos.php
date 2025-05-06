<?php
require '/xampp/htdocs/inmobiliaria/conexion.php';

if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

if (isset($_GET['cod_con']) && is_numeric($_GET['cod_con'])) {
    $cod_con = $_GET['cod_con'];

    // Preparar la consulta SQL para eliminar el contrato
    $sql = "DELETE FROM contratos WHERE cod_con = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cod_con);

    if ($stmt->execute()) {
        // Redirigir con un mensaje de éxito
        header("Location: consultar_contratos.php?mensaje=contrato_eliminado");
        exit();
    } else {
        // Redirigir con un mensaje de error
        header("Location: consultar_contratos.php?mensaje=error_eliminar");
        exit();
    }

    $stmt->close();
} else {
    // Si no se proporciona un código de contrato válido
    header("Location: consultar_contratos.php?mensaje=codigo_invalido");
    exit();
}

$conn->close();
?>