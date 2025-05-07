<?php
// Habilitar reporte de errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inmobiliaria";

// Establecer conexión
$conn = new mysqli($servername, $username, $password, $dbname);

if($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recoger y sanitizar datos del formulario
$tipo_empresa = $conn->real_escape_string($_POST['tipo_empresa']);
$tip_doc = $conn->real_escape_string($_POST['tipo_doc']);
$num_doc = (int)$_POST['numero_documento'];
$nombre_propietario = $conn->real_escape_string($_POST['nombre_propietario']);
$dir_propietario = $conn->real_escape_string($_POST['direccion']);
$tel_propietario = $conn->real_escape_string($_POST['telefono_propietario']);
$email_propietario = $conn->real_escape_string($_POST['email_propietario']);
$contacto_prop = $conn->real_escape_string($_POST['contacto_propietario']);
$tel_contacto_prop = $conn->real_escape_string($_POST['telefono_contacto']);
$email_contacto_prop = $conn->real_escape_string($_POST['email_contacto']);

// Consulta SQL con nombres de columnas exactos
$sql = "INSERT INTO propietarios (
            tipo_empresa, 
            tipo_doc, 
            num_doc, 
            nombre_propietario, 
            dir_propietario, 
            tel_propietario, 
            email_propietario, 
            contacto_prop, 
            tel_contacto_prop, 
            email_contacto_prop
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

// Preparar la consulta
$stmt = $conn->prepare($sql);
if(!$stmt) {
    die("Error en la preparación de la consulta: " . $conn->error);
}

// Vincular parámetros (nota el tipo 's' para string e 'i' para integer)
$stmt->bind_param(
    "ssisssssss", 
    $tipo_empresa, 
    $tip_doc, 
    $num_doc, 
    $nombre_propietario, 
    $dir_propietario, 
    $tel_propietario, 
    $email_propietario, 
    $contacto_prop, 
    $tel_contacto_prop, 
    $email_contacto_prop
);

// Ejecutar la consulta
if ($stmt->execute()) {
    // Éxito - redireccionar
    header("Location: propietario_crud.php");
    exit();
} else {
    // Error - mostrar mensaje
    echo "Error al registrar el propietario: " . $stmt->error;
    // Para depuración adicional:
    echo "<br>Consulta SQL: " . $sql;
}

// Cerrar conexiones
$stmt->close();
$conn->close();
?>