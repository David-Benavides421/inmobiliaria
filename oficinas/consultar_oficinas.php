<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inmobiliaria"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta para obtener los almacenes
$sql = "SELECT cod_ofi,nom_ofi, dir_ofi, latitud, longitud, foto_ofi, tel_ofi, email_ofi FROM oficinas";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta De Oficinas</title>
</head>
<body>
    <h2>Lista de oficinas</h2>
    <table border="1">
    <thead>
        <tr>
            <th>Id</th>
            <th>Nombre</th>
            <th>Dirección</th>
            <th>Ubicación</th>
            <th>Foto</th>
            <th>Teléfono</th>
            <th>Email</th>
            <th>Acciones</th> <!-- Nueva columna -->
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0) { 
            while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['cod_ofi']; ?></td>
                    <td><?php echo $row['nom_ofi']; ?></td>
                    <td><?php echo $row['dir_ofi']; ?></td>
                    <td>
                        <a href="https://www.google.com/maps/search/?api=1&query=<?php echo $row['latitud']; ?>,<?php echo $row['longitud']; ?>" target="_blank">Ver en Maps</a>
                    </td>
                    <td>
                        <?php if (!empty($row['foto_ofi'])) { ?>
                            <img src="<?php echo $row['foto_ofi']; ?>" alt="Foto de la oficina" width="100" height="100"/>
                        <?php } else { ?>
                            Sin Foto
                        <?php } ?>
                    </td>
                    <td><?php echo $row['tel_ofi']; ?></td>
                    <td><?php echo $row['email_ofi']; ?></td>
                    <td>
                        <a href="editar_oficina.php?cod_ofi=<?php echo $row['cod_ofi']; ?>">Editar</a> |
                        <a href="eliminar_oficina.php?cod_ofi=<?php echo $row['cod_ofi']; ?>" onclick="return confirm('¿Estás seguro de eliminar esta oficina?');">Eliminar</a>
                    </td>
                </tr>    
            <?php } 
        } else { ?>
            <tr>
                <td colspan="8">No hay oficinas registradas</td>
            </tr>    
        <?php } ?>
    </tbody>
</table>
<button type="button" onclick="window.location.href='/inmobiliaria/oficinas/oficinas_crud.php'">Oficinas</button>
</body>
</html>