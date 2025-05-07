<?php
include '../conexion.php';

if (isset($_GET['id'])) {
    $cod_propietario = $_GET['id'];
    $query = "SELECT * FROM propietarios WHERE cod_propietario = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $cod_propietario);
    $stmt->execute();
    $result = $stmt->get_result();
    $propietario = $result->fetch_assoc();

    if (!$propietario) {
        echo "Propietario no encontrado.";
        exit();
    }
} else {
    echo "ID de propietario no proporcionado.";
    exit();
}

// Función para obtener los valores de un enum
function getEnumValues($conn, $table, $column) {
    $sql = "SHOW COLUMNS FROM $table LIKE '$column'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    preg_match("/^enum\((.*)\)$/", $row['Type'], $matches);
    $enumValues = explode(",", $matches[1]);
    return array_map(function($value) {
        return trim($value, "'");
    }, $enumValues);
}

// Obtener los valores del enum para tipo_empresa
$tipoEmpresaValues = getEnumValues($conn, 'propietarios', 'tipo_empresa');

// Obtener los valores del enum para tipo_doc
$tipoDocValues = getEnumValues($conn, 'propietarios', 'tipo_doc');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Propietario</title>
    <link rel="stylesheet" href="../estilos.css">
</head>
<body>
    <form action="actualizar_propietario.php" method="post">
        <h1>Editar Propietario</h1>
        <input type="hidden" name="cod_propietarios" value="<?php echo $propietario['cod_propietarios']; ?>">

        <label for="tipo_empresa">Tipo de empresa:</label>
        <select name="tipo_empresa" id="tipo_empresa">
            <option value="">Seleccionar</option>
            <?php
            foreach ($tipoEmpresaValues as $value) {
                $selected = ($value == $propietario['tipo_empresa']) ? 'selected' : '';
                echo "<option value='$value' $selected>$value</option>";
            }
            ?>
        </select><br><br>

        <label for="tipo_documento">Tipo de documento:</label>
        <select name="tipo_documento" id="tipo_documento">
            <option value="">Seleccionar</option>
            <?php
            foreach ($tipoDocValues as $value) {
                $selected = ($value == $propietario['tipo_doc']) ? 'selected' : '';
                echo "<option value='$value' $selected>$value</option>";
            }
            ?>
        </select><br><br>

        <label for="numero_documento">Número de documento:</label>
        <input type="text" name="numero_documento" id="numero_documento" value="<?php echo $propietario['num_doc']; ?>"><br><br>

        <label for="nombre_propietario">Nombre del propietario:</label>
        <input type="text" name="nombre_propietario" id="nombre_propietario" value="<?php echo $propietario['nombre_propietario']; ?>"><br><br>

        <label for="direccion">Dirección:</label>
        <input type="text" name="direccion" id="direccion" value="<?php echo $propietario['dir_propietario']; ?>"><br><br>

        <label for="telefono_propietario">Teléfono del propietario:</label>
        <input type="text" name="telefono_propietario" id="telefono_propietario" value="<?php echo $propietario['tel_propietario']; ?>"><br><br>

        <label for="email_propietario">Email del propietario:</label>
        <input type="email" name="email_propietario" id="email_propietario" value="<?php echo $propietario['email_propietario']; ?>"><br><br>

        <label for="contacto_propietario">Contacto del propietario:</label>
        <input type="text" name="contacto_propietario" id="contacto_propietario" value="<?php echo $propietario['contacto_prop']; ?>"><br><br>

        <label for="telefono_contacto">Teléfono contacto:</label>
        <input type="text" name="telefono_contacto" id="telefono_contacto" value="<?php echo $propietario['tel_contacto_prop']; ?>"><br><br>

        <label for="email_contacto">Email contacto:</label>
        <input type="email" name="email_contacto" id="email_contacto" value="<?php echo $propietario['email_contacto_prop']; ?>"><br><br>

        <input type="submit" value="Guardar Cambios">
        <input type="button" value="Cancelar" onclick="window.location.href='propietarios.php'">
    </form>
</body>
</html>