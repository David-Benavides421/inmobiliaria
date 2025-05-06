<?php
require_once '../conexion.php';

// Obtener datos actuales de la oficina
if (isset($_GET['cod_ofi'])) {
    $cod_ofi = intval($_GET['cod_ofi']);
    $sql = "SELECT * FROM oficinas WHERE cod_ofi = $cod_ofi";
    $result = $conn->query($sql);
    $oficina = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cod_ofi = intval($_POST['cod_ofi']);
    $nom_ofi = $_POST['nom_ofi'];
    $dir_ofi = $_POST['dir_ofi'];
    $latitud = $_POST['latitud'];
    $longitud = $_POST['longitud'];
    $tel_ofi = $_POST['tel_ofi'];
    $email_ofi = $_POST['email_ofi'];

    // Obtener la foto actual
    $foto_ofi = $oficina['foto_ofi'];

    // Manejo de nueva imagen si se sube
    if (!empty($_FILES['foto']['name'])) {
        $directorio = "uploads/";
        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        $nombreArchivo = basename($_FILES['foto']['name']);
        $rutaDestino = $directorio . $nombreArchivo;
        $ext = strtolower(pathinfo($rutaDestino, PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($ext, $permitidas)) {
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $rutaDestino)) {
                // Borrar imagen anterior si existe
                if ($foto_ofi && file_exists($foto_ofi)) {
                    unlink($foto_ofi);
                }
                $foto_ofi = $rutaDestino;
                echo "Imagen actualizada correctamente.<br>";
            } else {
                echo "Error al subir la nueva imagen.<br>";
            }
        } else {
            echo "Formato de imagen no permitido.<br>";
        }
    }

    // Actualizar en base de datos
    $sql = "UPDATE oficinas SET 
            nom_ofi = ?, 
            dir_ofi = ?, 
            latitud = ?, 
            longitud = ?, 
            foto_ofi = ?, 
            tel_ofi = ?, 
            email_ofi = ?
            WHERE cod_ofi = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssddsssi", $nom_ofi, $dir_ofi, $latitud, $longitud, $foto_ofi, $tel_ofi, $email_ofi, $cod_ofi);

    if ($stmt->execute()) {
        header("Location: consultar_oficinas.php");
        exit();
    } else {
        echo "Error al actualizar: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
<link rel="stylesheet" href="oficinas.css">
<h2>Editar Oficina</h2><br><br>
<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="cod_ofi" value="<?php echo $oficina['cod_ofi']; ?>">
    Nombre: <input type="text" name="nom_ofi" value="<?php echo $oficina['nom_ofi']; ?>"><br>
    Dirección: <input type="text" name="dir_ofi" value="<?php echo $oficina['dir_ofi']; ?>"><br>
    Latitud: <input type="text" name="latitud" value="<?php echo $oficina['latitud']; ?>"><br>
    Longitud: <input type="text" name="longitud" value="<?php echo $oficina['longitud']; ?>"><br>
    Foto actual:<br>
    <?php if (!empty($oficina['foto_ofi'])): ?>
        <img src="<?php echo $oficina['foto_ofi']; ?>" width="150"><br>
    <?php endif; ?>
    Cambiar foto: <input type="file" name="foto"><br>
    Teléfono: <input type="text" name="tel_ofi" value="<?php echo $oficina['tel_ofi']; ?>"><br>
    Email: <input type="email" name="email_ofi" value="<?php echo $oficina['email_ofi']; ?>"><br>
    <button type="submit">Guardar Cambios</button>
</form>