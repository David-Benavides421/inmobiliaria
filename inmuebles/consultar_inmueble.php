<?php
include '../conexion.php';

// Consulta para obtener los inmuebles
$sql = "SELECT * FROM inmuebles";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Inmuebles</title>
    <link rel="stylesheet" href="consultar.css">
</head>
<body>

<h2>Lista de Inmuebles</h2>
<table>
    <thead>
        <tr>
            <th>Código</th>
            <th>Dirección</th>
            <th>Barrio</th>
            <th>Ciudad</th>
            <th>Departamento</th>
            <th>Ubicación</th>
            <th>Foto</th>
            <th>Web 1</th>
            <th>Web 2</th>
            <th>Tipo Inmueble</th>
            <th>Habitaciones</th>
            <th>Alquiler</th>
            <th>Propietario</th>
            <th>Características</th>
            <th>Notas</th>
            <th>Empleado</th>
            <th>Oficina</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['cod_inm']; ?></td>
                    <td><?php echo $row['dir_inm']; ?></td>
                    <td><?php echo $row['barrio_inm']; ?></td>
                    <td><?php echo $row['ciudad_inm']; ?></td>
                    <td><?php echo $row['departamento_inm']; ?></td>
                    <td>
                        <a href="https://www.google.com/maps/search/?api=1&query=<?php echo $row['latitud']; ?>,<?php echo $row['longitud']; ?>" target="_blank">Maps</a>
                    </td>
                    <td>
                        <?php if ($row['foto']) { ?>
                            <img src="<?php echo $row['foto']; ?>" alt="Foto del Inmueble" width="100" height="100">
                        <?php } else { ?>
                            Sin foto
                        <?php } ?>
                    </td>
                    <td><?php echo $row['web_p1']; ?></td>
                    <td><?php echo $row['web_p2']; ?></td>
                    <td><?php echo $row['cod_tipoinm']; ?></td>
                    <td><?php echo $row['num_hab']; ?></td>
                    <td><?php echo $row['precio_alq']; ?></td>
                    <td><?php echo $row['cod_propietario']; ?></td>
                    <td><?php echo $row['caracteristica_inm']; ?></td>
                    <td><?php echo $row['notas_inm']; ?></td>
                    <td><?php echo $row['cod_emp']; ?></td>
                    <td><?php echo $row['cod_ofi']; ?></td>
                </tr>
            <?php }
        } else { ?>
            <tr>
                <td colspan="17">No hay inmuebles registrados</td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<br>
<a href="/inmobiliaria/inmuebles/inmueble_crud.php">Volver a inicio</a>

</body>
</html>

<?php $conn->close(); ?>
