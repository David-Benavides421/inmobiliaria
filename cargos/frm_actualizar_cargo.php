<?php
require '/xampp/htdocs/inmobiliaria/conexion.php';

$codigo = $_GET['cod_cargo'];
$resultado = $conn->query("SELECT * FROM cargos WHERE cod_cargo = $codigo");
$cargo = $resultado->fetch_assoc();
?>

<h2>Editar Cargo</h2>
<form action="/inmobiliaria/cargos/actualizar_cargo.php" method="post">
    <input type="hidden" name="cod_cargo" value="<?php echo $cargo['cod_cargo']; ?>">
    <label>Nombre del Cargo:</label>
    <input type="text" name="nom_cargo" value="<?php echo $cargo['nom_cargo']; ?>" required>
    <br><br>
    <input type="submit" value="Actualizar">
</form>
