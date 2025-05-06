<?php
require '/xampp/htdocs/inmobiliaria/conexion.php';

$resultado = $conn->query("SELECT * FROM tipo_inmueble");
?>

<h2>Listado de Tipos de Inmuebles</h2>
<a href="/inmobiliaria/tipo_inmueble/frm_agregar_tipo_inmueble.php">Agregar Nuevo Tipo de Inmueble</a>
<table border="1">
    <tr>
        <th>Código del Tipo de Inmueble</th>
        <th>Nombre del Tipo de Inmueble</th>
        <th>Acciones</th>
    </tr>
    <?php while ($row = $resultado->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['cod_tipoinm']; ?></td>
            <td><?php echo $row['nom_tipoinm']; ?></td>
            <td>
                <a href="frm_actualizar_tipo_inmueble.php?cod_tipoinm=<?php echo $row['cod_tipoinm']; ?>">Editar</a> |
                <a href="eliminar_tipo_inmueble.php?cod_tipoinm=<?php echo $row['cod_tipoinm']; ?>" onclick="return confirm('¿Seguro que deseas eliminar?')">Eliminar</a>
            </td>
        </tr>
    <?php } ?>
</table>