<?php
require_once '../conexion.php';

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$camposRequeridos = ['nombre', 'direccion', 'latitud', 'longitud', 'email', 'telefono'];
$errores = [];

foreach ($camposRequeridos as $campo) {
    if (!isset($_POST[$campo]) || empty($_POST[$campo])) {
        $errores[] = "El campo $campo es requerido.";
    }
}

if (!empty($errores)) {
    foreach ($errores as $error) {
        echo $error . "<br>";
    }
    exit;
}

$nombre = $_POST['nombre'];
$direccion = $_POST['direccion'];
$latitud = $_POST['latitud'];
$longitud = $_POST['longitud'];
$email = $_POST['email'];
$telefono = $_POST['telefono'];

// Manejo de foto
$foto = null;
if (!empty($_FILES['foto']['name'])) {
    $directorio = "uploads/";
    
    // Asegurarse de que el directorio de destino existe
    if (!is_dir($directorio)) {
        mkdir($directorio, 0777, true); // Crear el directorio si no existe
    }
    $foto = $directorio . basename($_FILES['foto']['name']);    
    // Mover el archivo al directorio de destino
    if (move_uploaded_file($_FILES['foto']['tmp_name'], $foto)) {
        echo "La imagen se ha subido correctamente.<br>";
    } else {
        echo "Error al subir la imagen.<br>";
        $foto = null; // Si hay un error, evitar guardar un enlace incorrecto
    }
}

// Insertar en la base de datos
$sql = "INSERT INTO oficinas (nom_ofi, dir_ofi, latitud, longitud, foto_ofi, tel_ofi, email_ofi) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssddsss", $nombre, $direccion, $latitud, $longitud, $foto, $telefono, $email);

if ($stmt->execute()) {
    echo "Oficina registrada con éxito.";
} else {
    echo "Error: " . $stmt->error;
}
$stmt->close();
$conn->close();
?>