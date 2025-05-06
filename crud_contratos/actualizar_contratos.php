<?php
require '/xampp/htdocs/inmobiliaria/conexion.php';

if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Inicializar variables para el formulario
$cod_con = $cod_cli = $fecha_con = $fecha_ini = $fecha_fin = $meses = $valor_con = $deposito_con = $metodo_pago_con = $dato_pago = $archivo_con_actual = "";
$clientes_options = "";
$mensaje = "";

// Obtener la lista de clientes para el select
$clientes_result = $conn->query("SELECT cod_cli, nom_cli FROM clientes ORDER BY nom_cli");
while ($row = $clientes_result->fetch_assoc()) {
    $clientes_options .= "<option value='{$row["cod_cli"]}'>" . htmlspecialchars($row["nom_cli"]) . "</option>";
}

// --- Procesar el formulario de edición (si se envía por POST) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cod_con = $_POST['cod_con'];
    $cod_cli = $_POST['cod_cli'];
    $fecha_con = $_POST['fecha_con'];
    $fecha_ini = $_POST['fecha_ini'];
    $fecha_fin = $_POST['fecha_fin'];
    $meses = $_POST['meses'];
    $valor_con = $_POST['valor_con'];
    $deposito_con = $_POST['deposito_con'];
    $metodo_pago_con = $_POST['metodo_pago_con'];
    $dato_pago = $_POST['dato_pago'];

    // Determinar el valor de archivo_con para la actualización
    if (!empty($_FILES['archivo_con']['name'])) {
        // Se ha subido un nuevo archivo, aquí iría la lógica para subir el nuevo archivo
        // Por ahora, solo guardamos el nombre (necesitarás implementar la subida real)
        $archivo_con = $_FILES['archivo_con']['name'];
    } else {
        // No se subió un nuevo archivo, mantenemos el archivo existente
        $archivo_con = $_POST['archivo_con_actual'];
    }

    // Validación básica (similar a la inserción)
    if (empty($cod_cli) || empty($fecha_con) || empty($fecha_ini) || empty($fecha_fin) || empty($valor_con) || empty($metodo_pago_con)) {
        $mensaje = "<p style='color: red;'>Error: Faltan campos obligatorios.</p>";
    } else {
        // Preparar la consulta SQL para actualizar el contrato
        $sql_update = "UPDATE contratos SET
                        cod_cli = ?,
                        fecha_con = ?,
                        fecha_ini = ?,
                        fecha_fin = ?,
                        meses = ?,
                        valor_con = ?,
                        deposito_con = ?,
                        metodo_pago_con = ?,
                        dato_pago = ?,
                        archivo_con = ?
                    WHERE cod_con = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("isssiiisssi", $cod_cli, $fecha_con, $fecha_ini, $fecha_fin, $meses, $valor_con, $deposito_con, $metodo_pago_con, $dato_pago, $archivo_con, $cod_con);

        if ($stmt_update->execute()) {
            $mensaje = "<p style='color: green;'>Contrato actualizado exitosamente.</p>";
        } else {
            $mensaje = "<p style='color: red;'>Error al actualizar el contrato: " . $stmt_update->error . "</p>";
        }
        $stmt_update->close();
    }
}

// --- Obtener los datos del contrato a editar (si se proporciona cod_con por GET) ---
if (isset($_GET['cod_con']) && is_numeric($_GET['cod_con'])) {
    $cod_con_get = $_GET['cod_con'];
    $sql_select = "SELECT * FROM contratos WHERE cod_con = ?";
    $stmt_select = $conn->prepare($sql_select);
    $stmt_select->bind_param("i", $cod_con_get);
    $stmt_select->execute();
    $result_select = $stmt_select->get_result();

    if ($result_select->num_rows == 1) {
        $row = $result_select->fetch_assoc();
        $cod_con = $row['cod_con'];
        $cod_cli = $row['cod_cli'];
        $fecha_con = $row['fecha_con'];
        $fecha_ini = $row['fecha_ini'];
        $fecha_fin = $row['fecha_fin'];
        $meses = $row['meses'];
        $valor_con = $row['valor_con'];
        $deposito_con = $row['deposito_con'];
        $metodo_pago_con = $row['metodo_pago_con'];
        $dato_pago = $row['dato_pago'];
        $archivo_con_actual = $row['archivo_con'];
    } else {
        $mensaje = "<p style='color: red;'>No se encontró el contrato con el código especificado.</p>";
    }
    $stmt_select->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Contrato</title>
    <link rel="stylesheet" href="./style.css">
    <style>
        body { font-family: sans-serif; margin: 20px; }
        form { max-width: 800px; margin: auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; }
        fieldset { margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        legend { font-weight: bold; }
        label { display: block; margin-top: 10px; }
        input, select, button { width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box; }
        .botones-finales {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-top: 20px;
        }
        .botones-finales button,
        .botones-finales input[type="submit"] {
            width: 100%;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
        .botones-finales button:hover,
        .botones-finales input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<h2>Editar Contrato</h2>

<?php echo $mensaje; ?>

<form action="" method="POST" enctype="multipart/form-data">
    <fieldset>
        <legend>Datos del Contrato</legend>

        <input type="hidden" name="cod_con" value="<?php echo htmlspecialchars($cod_con); ?>">

        <label>Cliente:</label>
        <select name="cod_cli" required>
            <option value="">-- Seleccione --</option>
            <?php
                echo str_replace("value='".htmlspecialchars($cod_cli)."'", "value='".htmlspecialchars($cod_cli)."' selected", $clientes_options);
            ?>
        </select>

        <label>Fecha del contrato:</label>
        <input type="date" name="fecha_con" value="<?php echo htmlspecialchars($fecha_con); ?>" required>

        <label>Fecha de inicio:</label>
        <input type="date" name="fecha_ini" value="<?php echo htmlspecialchars($fecha_ini); ?>" required>

        <label>Fecha de fin:</label>
        <input type="date" name="fecha_fin" value="<?php echo htmlspecialchars($fecha_fin); ?>" required>

        <label>Meses:</label>
        <input type="number" name="meses" min="1" value="<?php echo htmlspecialchars($meses); ?>" required>

        <label>Valor del contrato:</label>
        <input type="text" name="valor_con" value="<?php echo htmlspecialchars($valor_con); ?>" required>

        <label>Depósito:</label>
        <input type="text" name="deposito_con" value="<?php echo htmlspecialchars($deposito_con); ?>" required>

        <label>Método de pago:</label>
        <select name="metodo_pago_con" required>
            <option value="">-- Seleccione --</option>
            <option value="efectivo" <?php if ($metodo_pago_con == 'efectivo') echo 'selected'; ?>>Efectivo</option>
            <option value="transferencia" <?php if ($metodo_pago_con == 'transferencia') echo 'selected'; ?>>Transferencia</option>
        </select>

        <label>Dato del pago:</label>
        <input type="text" name="dato_pago" maxlength="20" value="<?php echo htmlspecialchars($dato_pago); ?>">

        <label>Archivo del contrato (PDF):</label>
        <input type="file" name="archivo_con" accept=".pdf">
        <?php if (!empty($archivo_con_actual)): ?>
            <p>Archivo actual: <a href="<?php echo htmlspecialchars($archivo_con_actual); ?>" target="_blank">Ver archivo actual</a> (Si selecciona un nuevo archivo, el anterior podría ser reemplazado).</p>
            <input type="hidden" name="archivo_con_actual" value="<?php echo htmlspecialchars($archivo_con_actual); ?>">
        <?php endif; ?>

        <div class="botones-finales">
            <input type="submit" value="Guardar Cambios">
            <button type="button" onclick="location.href='consultar_contratos.php'">Cancelar</button>
        </div>

    </fieldset>
</form>

</body>
</html>