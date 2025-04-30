<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de Empleados</title>
  <link rel="stylesheet" href="./style.css">
  <style>
    body { font-family: sans-serif; margin: 20px; }
    .contenedor { max-width: 1000px; margin: auto; }
    .acciones { display: flex; gap: 15px; justify-content: center; margin-top: 30px; }
    .acciones form { display: inline; padding: 0; border: none; }
    .boton { padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
    .boton:hover { background-color: #0056b3; }
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

<div class="contenedor">

  <form action="/inmobiliaria/crud_empleados/insertar_empleados.php" method="POST">
    <fieldset>
      <legend>Datos Personales</legend>

      <label>Cédula:</label>
      <input type="text" name="ced_emp" maxlength="20" required>

      <label>Tipo de Documento:</label>
      <select name="tipo_doc" required>
        <option value="CEDULA">Cédula de Ciudadanía</option>
        <option value="CE">Cédula de Extranjería</option>
        <option value="TI">Tarjeta de Identidad</option>
      </select>

      <label>Nombre:</label>
      <input type="text" name="nom_emp" maxlength="150" required>

      <label>Dirección:</label>
      <input type="text" name="dir_emp" maxlength="150" required>

      <label>Teléfono:</label>
      <input type="text" name="tel_emp" maxlength="12">

      <label>Correo:</label>
      <input type="email" name="email_emp" maxlength="50">

      <label>RH:</label>
      <select name="rh_emp" required>
        <option value="A+">A+</option>
        <option value="A-">A-</option>
        <option value="B+">B+</option>
        <option value="B-">B-</option>
        <option value="AB+">AB+</option>
        <option value="AB-">AB-</option>
        <option value="O+">O+</option>
        <option value="O-">O-</option>
      </select>

      <label>Fecha de Nacimiento:</label>
      <input type="date" name="fecha_nac" required>
    </fieldset>

    <fieldset>
      <legend>Información Laboral</legend>

      <label>Cargo:</label>
      <select name="cod_cargo" required>
        <option value="">-- Seleccione --</option>
        <?php
          require '/xampp/htdocs/inmobiliaria/conexion.php';
          $cargo = $conn->query("SELECT cod_cargo, nom_cargo FROM cargos ORDER BY nom_cargo");
          while ($row = $cargo->fetch_assoc()) {
            echo "<option value='{$row["cod_cargo"]}'>" . htmlspecialchars($row["nom_cargo"]) . "</option>";
          }
        ?>
      </select>

      <label>Oficina:</label>
      <select name="cod_ofi" required>
        <option value="">-- Seleccione --</option>
        <?php
          $ofi = $conn->query("SELECT cod_ofi, nom_ofi FROM oficinas ORDER BY nom_ofi");
          while ($row = $ofi->fetch_assoc()) {
            echo "<option value='{$row["cod_ofi"]}'>" . htmlspecialchars($row["nom_ofi"]) . "</option>";
          }
        ?>
      </select>

      <label>Salario:</label>
      <input type="text" name="salario" maxlength="20" required>

      <label>Gastos:</label>
      <input type="text" name="gastos" maxlength="20" required>

      <label>Comisión:</label>
      <input type="text" name="comision" maxlength="20" required>

      <label>Fecha de Ingreso:</label>
      <input type="date" name="fecha_ing" required>

      <label>Fecha de Retiro:</label>
      <input type="date" name="fecha_ret">
    </fieldset>

    <fieldset>
      <legend>Contacto de Emergencia</legend>

      <label>Nombre del Contacto:</label>
      <input type="text" name="nom_contacto" maxlength="100">

      <label>Dirección del Contacto:</label>
      <input type="text" name="dir_contacto" maxlength="150">

      <label>Teléfono del Contacto:</label>
      <input type="text" name="tel_contacto" maxlength="12">

      <label>Email del Contacto:</label>
      <input type="email" name="email_contacto" maxlength="50">

      <label>Relación:</label>
      <input type="text" name="relacion_contacto" maxlength="30">
    </fieldset>

    <input type="submit" value="Registrar Empleado">
  </form>

  <div class="acciones">
    <form action="/inmobiliaria/crud_empleados/consultar_empleados.php" method="GET">
      <button class="boton">Consultar Empleados</button>
    </form>
  </div>

</div>

</body>
</html>
