<?php
require '/xampp/htdocs/inmobiliaria/conexion.php';

$codigo = $_GET['cod_tipoinm'];
$resultado = $conn->query("SELECT * FROM tipo_inmueble WHERE cod_tipoinm = $codigo");
$cargo = $resultado->fetch_assoc();
?>

<h2>Editar Tipo de Inmueble</h2>
<form action="/inmobiliaria/tipo_inmueble/actualizar_tipo_inmueble.php" method="post">
    <input type="hidden" name="cod_tipoinm" value="<?php echo $cargo['cod_tipoinm']; ?>">
    <label>Nombre del Tipo de Inmueble:</label>
    <input type="text" name="nom_tipoinm" value="<?php echo $cargo['nom_tipoinm']; ?>" required>
    <br><br>
    <input type="submit" value="Actualizar">
</form>