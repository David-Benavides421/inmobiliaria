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

// Obtener los valores del enum
$tipoDocValues = getEnumValues($conn, 'propietarios', 'tipo_doc');
$tipoEmpresaValues = getEnumValues($conn, 'propietarios', 'tipo_empresa');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Propietario</title>
    <link rel="stylesheet" href="../estilos.css">
    <style>
    body { font-family: sans-serif; margin: 20px; background-color: #f4f4f4; }
    .container { max-width: 1000px; margin: auto; background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    h1, h2 { color: #333; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { padding: 10px; border: 1px solid #ddd; text-align: left; font-size: 0.9em;}
    th { background-color: #007bff; color: white; }
    tr:nth-child(even) { background-color: #f9f9f9; }
    .actions a { margin-right: 8px; text-decoration: none; padding: 5px 8px; border-radius: 4px; font-size: 0.9em;}
    .actions .edit { background-color: #ffc107; color: #333; }
    .actions .delete { background-color: #dc3545; color: white; }
    .actions .add { display: inline-block; margin-bottom:20px; background-color: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;}

    form { max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; background-color: #fff; }
    label { display: block; margin-top: 10px; font-weight: bold; }
    input[type="date"], select, textarea {
        width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;
    }
    textarea { min-height: 100px; resize: vertical; }
    input[type="submit"] { margin-top: 20px; background-color: #007bff; color: #fff; border: none; cursor: pointer; padding: 10px 20px; border-radius: 4px; font-size: 16px; }
    input[type="submit"]:hover { background-color: #0056b3; }
    .message { padding: 10px; margin-bottom: 15px; border-radius: 4px; }
    .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <form action="actualizar_propietario.php" method="post">
        <h1>Editar Propietario</h1>
        <input type="hidden" name="cod_propietario" value="<?php echo $propietario['cod_propietario']; ?>">

        <label for="tipo_doc">Tipo de Documento:</label>
        <select name="tipo_doc" id="tipo_doc">
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
