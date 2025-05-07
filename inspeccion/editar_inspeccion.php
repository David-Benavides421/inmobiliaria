<?php
require '/xampp/htdocs/inmobiliaria/conexion.php';
$id = $_GET['id'];
$row = $conn->query("SELECT * FROM inspeccion WHERE cod_ins=$id")->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("UPDATE inspeccion SET fecha_ins=?, cod_imm=?, cod_emp=?, comentario=? WHERE cod_ins=?");
    $stmt->bind_param("siisi", $_POST['fecha_ins'], $_POST['cod_imm'], $_POST['cod_emp'], $_POST['comentario'], $id);
    $stmt->execute();
    header("Location: inspecciones.php");
}
?>

<h2>Editar Inspección</h2>
<form method="POST">
    Fecha: <input type="date" name="fecha_ins" value="<?= $row['fecha_ins'] ?>"><br>
    Código Inmueble: <input type="number" name="cod_imm" value="<?= $row['cod_imm'] ?>"><br>
    Código Empleado: <input type="number" name="cod_emp" value="<?= $row['cod_emp'] ?>"><br>
    Comentario: <input type="text" name="comentario" value="<?= $row['comentario'] ?>"><br>
    <button type="submit">Actualizar</button>
</form>
