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