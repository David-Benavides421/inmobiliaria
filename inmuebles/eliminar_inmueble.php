<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inmobiliaria";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cod_inm']) && is_numeric($_POST['cod_inm'])) {
    $cod_inm = intval($_POST['cod_inm']); // Seguridad: convertir a entero

    $sql = "DELETE FROM inmuebles WHERE cod_inm = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $cod_inm);
        if ($stmt->execute()) {
            echo "<script>alert('Inmueble eliminado correctamente.'); window.location.href='consulta_inmuebles.php';</script>";
        } else {
            echo "Error al eliminar el inmueble: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error en la preparación de la eliminación: " . $conn->error;
    }
} else {
    echo "Error: ID inválido o no recibido.";
}

$conn->close();
?>
