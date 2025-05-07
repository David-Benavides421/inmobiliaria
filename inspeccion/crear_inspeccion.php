<?php
require '/xampp/htdocs/inmobiliaria/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("INSERT INTO inspeccion (fecha_ins, cod_imm, cod_emp, comentario) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siis", $_POST['fecha_ins'], $_POST['cod_imm'], $_POST['cod_emp'], $_POST['comentario']);
    $stmt->execute();
    header("Location: inspecciones.php");
}
?>

<h2>Nueva Inspección</h2>
<form method="POST">
    Fecha: <input type="date" name="fecha_ins" required><br>
    Código Inmueble: <input type="number" name="cod_imm" required><br>
    Código Empleado: <input type="number" name="cod_emp" required><br>
    Comentario: <input type="text" name="comentario" maxlength="255"><br>
    <button type="submit">Guardar</button>
</form>
