<?php
require '../conexion.php';


$result = $conn->query("SELECT * FROM propietarios");
?>

<h2>Lista de Propietarios</h2>
<table border="1">
    <tr>
        <th>Código</th><th>Tipo Empresa</th><th>Tipo Documento</th><th>Número Documento</th>
        <th>Nombre Propietario</th><th>Dirección de Propietario</th><th>Teléfono  Propietario</th><th>Email Propietario</th>
        <th>Contacto Propiedad</th><th>Tel. Contacto Propiedad</th><th>Email Contacto Propiedad</th><th>Acciones</th>
    </tr>
    <?php while($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?= $row['cod_propietario'] ?></td>
        <td><?= $row['tipo_empresa'] ?></td>
        <td><?= $row['tipo_doc'] ?></td>
        <td><?= $row['num_doc'] ?></td>
        <td><?= $row['nombre_propietario'] ?></td>
        <td><?= $row['dir_propietario'] ?></td>
        <td><?= $row['tel_propietario'] ?></td>
        <td><?= $row['email_propietario'] ?></td>
        <td><?= $row['contacto_prop'] ?></td>
        <td><?= $row['tel_contacto_prop'] ?></td>
        <td><?= $row['email_contacto_prop'] ?></td>
        <td>
            <a href="editar_propietario.php?id=<?= $row['cod_propietario'] ?>">Editar</a> |
            <a href="eliminar_propietario.php?id=<?= $row['cod_propietario'] ?>" onclick="return confirm('¿Seguro que deseas eliminar este registro?')">Eliminar</a>
        </td>

    </tr>

    <?php } ?>
</table>
<br><button onclick="window.history.back();" style="padding:10px 20px; background-color:#1976d2; color:white; border:none; border-radius:5px; cursor:pointer;">Volver atrás</button><br>
