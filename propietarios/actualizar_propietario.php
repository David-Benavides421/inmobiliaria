<?php
// Habilitar reporte de errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inmobiliaria";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: ". $conn->connect_error); 
}

// Obtener y sanitizar datos del formulario
$cod_propietario = (int)$_POST['cod_propietarios'];
$tipo_empresa = $conn->real_escape_string($_POST['tipo_empresa']);
$tipo_doc = $conn->real_escape_string($_POST['tipo_documento']);
$num_doc = (int)$_POST['numero_documento'];
$nombre_propietario = $conn->real_escape_string($_POST['nombre_propietario']);
$dir_propietario = $conn->real_escape_string($_POST['direccion']);
$tel_propietario = $conn->real_escape_string($_POST['telefono_propietario']);
$email_propietario = $conn->real_escape_string($_POST['email_propietario']);
$contacto_prop = $conn->real_escape_string($_POST['contacto_propietario']);
$tel_contacto_prop = $conn->real_escape_string($_POST['telefono_contacto']);
$email_contacto_prop = $conn->real_escape_string($_POST['email_contacto']);

// Consulta SQL con los nombres exactos de campos de tu BD
$sql = "UPDATE propietarios SET 
        tipo_empresa = ?, 
        tipo_doc = ?, 
        num_doc = ?, 
        nombre_propietario = ?, 
        dir_propietario = ?, 
        tel_propietario = ?, 
        email_propietario = ?, 
        contacto_prop = ?, 
        tel_contacto_prop = ?, 
        email_contacto_prop = ? 
        WHERE cod_propietario = ?";

// Preparar la consulta
$stmt = $conn->prepare($sql);
if(!$stmt) {
    die("Error en la preparación de la consulta: " . $conn->error);
}

// Vincular parámetros (11 parámetros: 10 strings + 1 integer al final)
$stmt->bind_param(
    "ssisssssssi", 
    $tipo_empresa, 
    $tipo_doc, 
    $num_doc, 
    $nombre_propietario, 
    $dir_propietario, 
    $tel_propietario, 
    $email_propietario, 
    $contacto_prop, 
    $tel_contacto_prop, 
    $email_contacto_prop, 
    $cod_propietario
);

// Ejecutar la consulta
if ($stmt->execute()) {
    echo "<script>
        alert('Propietario actualizado correctamente');
        window.location.href = 'crear_propietario.php';
    </script>";
} else {
    echo "<script>
        alert('Error al actualizar: " . addslashes($stmt->error) . "');
        window.history.back();
    </script>";
}

// Cerrar conexiones
$stmt->close();
$conn->close();
?>