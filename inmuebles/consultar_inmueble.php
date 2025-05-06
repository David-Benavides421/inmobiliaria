<?php

include '../conexion.php';

//Conslta para obtener los almacenes
$sql = "SELECT * FROM inmuebles";
$result = $conn -> query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Inmuebles</title>
    <link rel="stylesheet" href="../estilos.css">
</head>
<body>
   
    <table>
    <h2>Lista de Inmuebles</h2>
        <thead>
            <tr>
                <th>Código</th>
                <th>Dirección</th>
                <th>Barrio</th>
                <th>Ciudad</th>
                <th>Departamento</th>
                <th>ubicacion</th>
                <th>Foto</th>
                <th>Web 1</th>
                <th>Web 2</th>
                <th>Codigo Inmueble</th>
                <th># habitaciones</th>
                <th>Alquiler</th>
                <th>Propietario</th>
                <th>Caracteristicas</th>
                <th>Notas</th>
                <th>Empleado</th>
                <th>Oficina</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result -> num_rows > 0) {
                while ($row = $result -> fetch_assoc()) {?>
                    <tr>
                        <td><?php echo $row['cod_inm']; ?></td>
                        <td><?php echo $row['di_inm']; ?></td>
                        <td><?php echo $row['barrio_inm']; ?></td>
                        <td><?php echo $row['ciudad_inm']; ?></td>
                        <td><?php echo $row['departamento_inm']; ?></td>
                        <td>
                            <a href="https://www.google.com/maps/search/?api=1&query= <?php echo $row['latitud']; ?>, <?php echo $row['longitud']; ?>" target="_blank"> Maps</a>
                        </td>
                        <td>
                            <?php if ($row['foto']) { ?>
                                <img src="<?php echo $row['foto']; ?>" alt="Foto del Almacen" width="100" height="100">
                            <?php}  else { ?>
                                Sin foto
                            <?php } ?>
                        </td>
                        <td><?php echo $row['web_p1']; ?></td>
                        <td><?php echo $row['web_p2']; ?></td>
                        <td><?php echo $row['cod_tipoinm']; ?></td>
                        <td><?php echo $row['num_hab']; ?></td>
                        <td><?php echo $row['precio_alq']; ?></td>
                        <td><?php 
                            echo $row['cod_propietarios']; ?></td>
                        <td><?php echo $row['caracteristicas_inm']; ?></td>
                        <td><?php echo $row['notas_inm']; ?></td>
                        <td><?php echo $row['cod_emp']; ?></td>
                        <td><?php echo $row['cod_ofi']; ?></td>
                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="5">No hay inmuebles registrados </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <a href="inmueble_crud.php">Volver a inicio</a>
</body>
</html>

<?php $conn -> close(); ?>