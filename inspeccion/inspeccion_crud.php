<?php
require_once '../conexion.php';

// Obtener valores para selects
$inmuebles = mysqli_query($conexion, "SELECT cod_inm FROM inmuebles");
$empleados = mysqli_query($conexion, "SELECT cod_emp FROM empleados");

// Insertar inspeccion
if (isset($_POST['agregar'])) {
    $fecha = $_POST['fecha_ins'];
    $cod_inm = $_POST['cod_inm'];
    $cod_emp = $_POST['cod_emp'];
    $comentario = $_POST['comentario'];

    $sql = "INSERT INTO inspeccion (fecha_ins, cod_inm, cod_emp, comentario) VALUES ('$fecha', '$cod_inm', '$cod_emp', '$comentario')";
    mysqli_query($conexion, $sql);
    header("Location: inspeccion_crud.php");
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    mysqli_query($conexion, "DELETE FROM inspeccion WHERE cod_ins = $id");
    header("Location: inspeccion_crud.php");
}

// Obtener inspección para editar
$editando = false;
$edit_data = null;
if (isset($_GET['editar'])) {
    $editando = true;
    $id = $_GET['editar'];
    $result = mysqli_query($conexion, "SELECT * FROM inspeccion WHERE cod_ins = $id");
    $edit_data = mysqli_fetch_assoc($result);
}

// Actualizar inspección
if (isset($_POST['actualizar'])) {
    $id = $_POST['cod_ins'];
    $fecha = $_POST['fecha_ins'];
    $cod_inm = $_POST['cod_inm'];
    $cod_emp = $_POST['cod_emp'];
    $comentario = $_POST['comentario'];

    $sql = "UPDATE inspeccion SET fecha_ins='$fecha', cod_inm='$cod_inm', cod_emp='$cod_emp', comentario='$comentario' WHERE cod_ins = $id";
    mysqli_query($conexion, $sql);
    header("Location: inspeccion_crud.php");
}

$inspecciones = mysqli_query($conexion, "SELECT * FROM inspeccion");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CRUD Inspección</title>
</head>
<body>
    <h2><?= $editando ? 'Editar' : 'Agregar' ?> Inspección</h2>
    <form method="post">
        <input type="hidden" name="cod_ins" value="<?= $editando ? $edit_data['cod_ins'] : '' ?>">
        Fecha: <input type="date" name="fecha_ins" required value="<?= $editando ? $edit_data['fecha_ins'] : '' ?>"><br>
        Inmueble:
        <select name="cod_inm" required>
            <option value="">Seleccione</option>
            <?php
            $res_inm = mysqli_query($conexion, "SELECT cod_inm FROM inmuebles");
            while ($row = mysqli_fetch_assoc($res_inm)) {
                $sel = ($editando && $edit_data['cod_inm'] == $row['cod_inm']) ? 'selected' : '';
                echo "<option value='{$row['cod_inm']}' $sel>{$row['cod_inm']}</option>";
            }
            ?>
        </select><br>
        Empleado:
        <select name="cod_emp" required>
            <option value="">Seleccione</option>
            <?php
            $res_emp = mysqli_query($conexion, "SELECT cod_emp FROM empleados");
            while ($row = mysqli_fetch_assoc($res_emp)) {
                $sel = ($editando && $edit_data['cod_emp'] == $row['cod_emp']) ? 'selected' : '';
                echo "<option value='{$row['cod_emp']}' $sel>{$row['cod_emp']}</option>";
            }
            ?>
        </select><br>
        Comentario: <input type="text" name="comentario" required value="<?= $editando ? $edit_data['comentario'] : '' ?>"><br>
        <button type="submit" name="<?= $editando ? 'actualizar' : 'agregar' ?>">
            <?= $editando ? 'Actualizar' : 'Guardar' ?>
        </button>
    </form>

    <h2>Inspecciones Registradas</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Fecha</th>
            <th>Inmueble</th>
            <th>Empleado</th>
            <th>Comentario</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($inspecciones)) : ?>
            <tr>
                <td><?= $row['cod_ins'] ?></td>
                <td><?= $row['fecha_ins'] ?></td>
                <td><?= $row['cod_inm'] ?></td>
                <td><?= $row['cod_emp'] ?></td>
                <td><?= $row['comentario'] ?></td>
                <td>
                    <a href="?editar=<?= $row['cod_ins'] ?>">Editar</a> |
                    <a href="?eliminar=<?= $row['cod_ins'] ?>" onclick="return confirm('¿Eliminar esta inspección?');">Eliminar</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>



 
