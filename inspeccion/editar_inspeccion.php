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
<h2>Editar Inspección</h2>
<form method="POST">
    Fecha: <input type="date" name="fecha_ins" value="<?= $row['fecha_ins'] ?>"><br>
    Código Inmueble: <input type="number" name="cod_imm" value="<?= $row['cod_imm'] ?>"><br>
    Código Empleado: <input type="number" name="cod_emp" value="<?= $row['cod_emp'] ?>"><br>
    Comentario: <input type="text" name="comentario" value="<?= $row['comentario'] ?>"><br>
    <button type="submit">Actualizar</button>
</form>
