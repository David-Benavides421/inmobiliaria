<?php
require '/xampp/htdocs/inmobiliaria/conexion.php';

$resultado = $conn->query("SELECT * FROM cargos");
?>

<h2>Listado de Cargos</h2>
<table border="1">
    <tr>
        <th>Código</th>
        <th>Nombre</th>
        <th>Acciones</th>
    </tr>
    <?php while ($row = $resultado->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['cod_cargo']; ?></td>
            <td><?php echo $row['nom_cargo']; ?></td>
            <td>
                <a href="frm_actualizar_cargo.php?cod_cargo=<?php echo $row['cod_cargo']; ?>">Editar</a> |
                <a href="eliminar_cargo.php?cod_cargo=<?php echo $row['cod_cargo']; ?>" onclick="return confirm('¿Seguro que deseas eliminar?')">Eliminar</a>
            </td>
        </tr>
    <?php } ?>
</table><br>
<input type="button" value="Inicio" onclick="location.href='../dashboard.php'"><br><br>

<a href="/inmobiliaria/cargos/frm_agregar_cargo.php">Agregar Nuevo Cargo</a>
<br><br>
