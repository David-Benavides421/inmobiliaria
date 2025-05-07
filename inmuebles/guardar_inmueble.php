<?php
include '../conexion.php';

// Recibir Datos de forma segura
$dir_inm = $_POST['dir_inm'] ?? null;
$barrio_inm = $_POST['barrio_inm'] ?? null;
$ciudad_inm = $_POST['ciudad_inm'] ?? null;
$departamento_inm = $_POST['departamento_inm'] ?? null;
$latitud = isset($_POST['latitud']) ? floatval($_POST['latitud']) : null;
$longitud = isset($_POST['longitud']) ? floatval($_POST['longitud']) : null;
$web_p1 = $_POST['web_p1'] ?? null;
$web_p2 = $_POST['web_p2'] ?? null;
$cod_tipoinm = isset($_POST['cod_tipoinm']) ? intval($_POST['cod_tipoinm']) : null;
$num_hab = isset($_POST['num_hab']) ? intval($_POST['num_hab']) : null;
$precio_alq = isset($_POST['precio_alq']) ? floatval($_POST['precio_alq']) : null;
$cod_propietario = isset($_POST['cod_propietario']) ? intval($_POST['cod_propietario']) : null;
$caracteristica_inm = $_POST['caracteristica_inm'] ?? null;
$notas_inm = $_POST['notas_inm'] ?? null;
$cod_emp = isset($_POST['cod_emp']) ? intval($_POST['cod_emp']) : null;
$cod_ofi = isset($_POST['cod_ofi']) ? intval($_POST['cod_ofi']) : null;

// Manejo de foto
$foto = null;
if (!empty($_FILES['foto']['name'])) {
    $uploadDir = 'uploads/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $fileExtension = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
    $fileName = uniqid() . '.' . $fileExtension;
    $foto = $uploadDir . $fileName;
    
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($fileExtension, $allowedExtensions)) {
        move_uploaded_file($_FILES['foto']['tmp_name'], $foto);
    } else {
        echo "<script>alert('Formato de imagen no válido.'); window.history.back();</script>";
        exit;
    }
}

// Insertar en la base de datos
$sql = "INSERT INTO inmuebles (
    dir_inm, barrio_inm, ciudad_inm, departamento_inm, latitud, longitud, 
    foto, web_p1, web_p2, cod_tipoinm, num_hab, precio_alq, 
    cod_propietario, caracteristica_inm, notas_inm, cod_emp, cod_ofi
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo "<script>alert('Error en la preparación de la consulta: " . addslashes($conn->error) . "'); window.history.back();</script>";
    exit;
}

// ¡IMPORTANTE! 17 letras (tipos) y 17 valores:
$stmt->bind_param(
    "ssssddsssiidssiii", 
    $dir_inm, 
    $barrio_inm, 
    $ciudad_inm, 
    $departamento_inm, 
    $latitud, 
    $longitud, 
    $foto, 
    $web_p1, 
    $web_p2, 
    $cod_tipoinm, 
    $num_hab, 
    $precio_alq, 
    $cod_propietario, 
    $caracteristica_inm, 
    $notas_inm, 
    $cod_emp, 
    $cod_ofi
);

if ($stmt->execute()) {
    echo "<script>
        alert('Inmueble registrado correctamente.');
        window.location.href = 'inmueble_crud.php';
    </script>";
} else {
    echo "<script>
        alert('Error al guardar: " . addslashes($stmt->error) . "');
        window.history.back();
    </script>";
}

$stmt->close();
$conn->close();
?>
  
  