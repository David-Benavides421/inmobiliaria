<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inmobiliaria";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("error de conexion: " . $conn->connect_error);
}

$cod_propietarios = $_GET['cod_propietario'];

$sql = "DELETE FROM propietarios WHERE cod_propietario = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $cod_propietarios);
    if ($stmt->execute()) {
        echo "<script>alert('Propietarios eliminado correctamente.'); window.location.href='consultar_propietario.php';</script>";
    } else {
        echo "Error al eliminar el proveedor: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "<script>alert('Error: No se proporciona un ID de proveedor válido.'); window.location.href='eliminar_propietario.php';</script>";
}

$conn->close();
?>