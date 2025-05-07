<?php
include '../conexion.php'
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Propietarios</title>
    <link rel="stylesheet" href="../estilos.css">
</head>

<body>

    

    <form action="guardar_propietario.php" method="post">
     <h2>Formulario Propietario</h2>
        <label>Tipo de empresa:</label>
        
        <select name="tipo_empresa" id="tipo_empresa">
            <option value="">Selecciona</option>
            
            <?php
            $sqlEnum = "SHOW COLUMNS FROM propietarios LIKE 'tipo_empresa'";
            $resultEnum = $conn->query($sqlEnum);
            $rowEnum = $resultEnum->fetch_assoc();
            preg_match("/^enum\((.*)\)$/", $rowEnum['Type'], $matches);
            $enumValues = explode(",", $matches[1]);
            foreach ($enumValues as $value) {
                $cleanValue = trim($value, "'");
                echo "<option value='$cleanValue'>$cleanValue</option>";
            }
            ?> 
            
        </select><br><br>

        <label>Tipo de documento:</label>
        <select name="tipo_doc" id="tipo_doc">
            <option value="">Selecciona</option>
            
            <?php
            $sqlEnum = "SHOW COLUMNS FROM propietarios LIKE 'tipo_doc'";
            $resultEnum = $conn->query($sqlEnum);
            $rowEnum = $resultEnum->fetch_assoc();
            preg_match("/^enum\((.*)\)$/", $rowEnum['Type'], $matches);
            $enumValues = explode(",", $matches[1]);
            foreach ($enumValues as $value) {
                $cleanValue = trim($value, "'");
                echo "<option value='$cleanValue'>$cleanValue</option>";
            }
            ?>
            
        </select><br><br>

        <label>Numero de documento:</label>
        <input type="text" name="numero_documento"><br><br>

        <label>Nombre del propietario:</label>
        <input type="text" name="nombre_propietario"><br><br>

        <label>Dirección:</label>
        <input type="text" name="direccion"><br><br>

        <label>Telefono del propietario:</label>
        <input type="text" name="telefono_propietario"><br><br>

        <label>Email del propietario:</label>
        <input type="email" name="email_propietario"><br><br>

        <label>Contacto del propietario:</label>
        <input type="text" name="contacto_propietario"><br><br>

        <label>Telefono contacto:</label>
        <input type="text" name="telefono_contacto"><br><br>

        <label>Email contacto:</label>
        <input type="email" name="email_contacto"><br><br>

        <input type="submit" value="Guardar Propietario"><br><br>

        <input type="button" value="Consultar" onclick="location.href='consultar_propietario.php'">
    </form>

</body>

</html>