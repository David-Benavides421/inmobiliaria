<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Empleados</title>
    <link rel="stylesheet" href="./style.css">
    <style>
    body { font-family: sans-serif; margin: 20px; }
    form { max-width: 800px; margin: auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; }
    fieldset { margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
    legend { font-weight: bold; }
    label { display: block; margin-top: 10px; }
    input, select { width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box; }
    input[type="submit"] { margin-top: 15px; background-color: #007bff; color: #fff; border: none; cursor: pointer; border-radius: 4px; }
    input[type="submit"]:hover { background-color: #0056b3; }
    </style>
</head>
<body>

<form action="/inmobiliaria/crud_clientes/clientes.php" method="POST">
<fieldset>
    <legend>Datos Personales</legend>

    <label>Cédula:</label>
    <input type="number" name="doc_cli" required>

    <label>Tipo de Documento:</label>
    <select name="tipo_doc_cli" required>
      <option value="CC">Cédula de Ciudadanía</option>
      <option value="CE">Cédula de Extranjería</option>
      <option value="TI">Tarjeta de Identidad</option>
    </select>

    <label>Nombre:</label>
    <input type="text" name="nom_cli" maxlength="150" required>

    <label>Dirección:</label>
    <input type="text" name="dir_cli" maxlength="150" required>

    <label>Teléfono:</label>
    <input type="text" name="tel_cli" maxlength="12">

    <label>Correo:</label>
    <input type="email" name="email_cli" maxlength="50">

  </fieldset>

  <fieldset>
    <legend>Inmueble</legend>

    <label>Tipo de inmueble:</label>
    <select name="cod_tipoinm" required>
      <option value="">-- Seleccione --</option>
      <?php
        require '/xampp/htdocs/inmobiliaria/conexion.php';
        $inmueble = $conn->query("SELECT cod_tipoinm, nom_tipoinm FROM tipo_inmueble ORDER BY nom_tipoinm");
        while ($row = $inmueble->fetch_assoc()) {
          echo "<option value='{$row["cod_tipoinm"]}'>" . htmlspecialchars($row["nom_tipoinm"]) . "</option>";
        }
      ?>
    </select>

    <label>Valor maximo:</label>
    <input type="number" name="valor_maximo" maxlength="20">

    <label>fecha de creacion:</label>
    <input type="date" name="fecha_creacion" require>

    <label>Empleado:</label>
    <select name="cod_emp" required>
      <option value="">-- Seleccione --</option>
      <?php
        $emp = $conn->query("SELECT cod_emp, nom_emp FROM empleados ORDER BY nom_emp");
        while ($row = $emp->fetch_assoc()) {
          echo "<option value='{$row["cod_emp"]}'>" . htmlspecialchars($row["nom_emp"]) . "</option>";
        }
      ?>
    </select>

    <label>Notas de cliente:</label>
    <input type="text" name="notas_cliente">


  <input type="submit" value="Registrar Usuario">
</form>

</body>
</html>
