<?php
include '../conexion.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Propietario</title>
    <link rel="stylesheet" href="../estilos.css">
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
</head>
<body>
<div class="tabla-container">
    <table>
    <h2>Propietarios</h2>
        <tr>
            <th>Cod Propietario</th>
            <th>Tipo de empresa</th>
            <th>Tipo de documento</th>
            <th>Numero de documento</th>
            <th>Nombre del propietario</th>
            <th>Dirección del propietario</th>
            <th>Telefono del propietario</th>
            <th>Email del propietario</th>
            <th>Contacto del propietario</th>
            <th>Telefono contacto</th>
            <th>Email contacto</th>
            <th>Acciones</th>
        </tr>

        <?php
    $sql = "SELECT * FROM propietarios";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["cod_propietario"] . "</td>";
        echo "<td>" . $row["tipo_empresa"] . "</td>";
        echo "<td>" . $row["tipo_doc"] . "</td>";
        echo "<td>" . $row["num_doc"] . "</td>";
        echo "<td>" . $row["nombre_propietario"] . "</td>";
        echo "<td>" . $row["dir_propietario"] . "</td>";
        echo "<td>" . $row["tel_propietario"] . "</td>";
        echo "<td>" . $row["email_propietario"] . "</td>";
        echo "<td>" . $row["contacto_prop"] . "</td>";
        echo "<td>" . $row["tel_contacto_prop"] . "</td>";
        echo "<td>" . $row["email_contacto_prop"] . "</td>";
        echo "<td>";
        echo "<a href='editar_propietario.php?id=" . $row["cod_propietario"] . "'>Editar</a> | ";
        echo "<a href='eliminar_propietario.php?cod_propietario=" . $row["cod_propietario"] . "' onclick='return confirm(\"¿Estás seguro de eliminar este proveedor?\");'>Eliminar</a>";
        echo "</td>";
        echo "</tr>";
    }
    ?>
    </table>
    <input type="button" value="Registro Propietario" onclick="location.href='crear_propietario.php'">
</div>
</body>
</html>