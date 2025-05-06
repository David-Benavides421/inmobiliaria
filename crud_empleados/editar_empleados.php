<?php
// Requerir el archivo de conexión - Ajusta la ruta si es necesario
require '/xampp/htdocs/inmobiliaria/conexion.php';

// Verificar si se proporcionó cod_emp en la URL y si es numérico
if (!isset($_GET['cod_emp']) || !is_numeric($_GET['cod_emp'])) {
    die("Error: Código de empleado no válido o no proporcionado.");
}

$cod_emp_editar = intval($_GET['cod_emp']); // Convertir a entero por seguridad

// Preparar y ejecutar la consulta para obtener los datos del empleado
$consulta = $conn->prepare("SELECT * FROM empleados WHERE cod_emp = ?");
if (!$consulta) {
    // Usar $conn->error para obtener el error específico de MySQL
    die("Error al preparar la consulta: " . $conn->error);
}
$consulta->bind_param("i", $cod_emp_editar); // "i" indica que es un entero
$consulta->execute();
$resultado = $consulta->get_result();

// Verificar si se encontró el empleado
if ($resultado->num_rows === 0) {
    die("Error: Empleado con código " . htmlspecialchars($cod_emp_editar) . " no encontrado.");
}

// Obtener los datos del empleado en un array asociativo
$empleado = $resultado->fetch_assoc();
$consulta->close(); // Cerrar la consulta preparada

// --- Función auxiliar para verificar y seleccionar opciones en <select> ---
function estaSeleccionado($valorActual, $valorOpcion) {
    // Compara los valores (puede necesitar ajuste si son tipos diferentes, pero aquí deberían ser iguales)
    return ($valorActual == $valorOpcion) ? 'selected' : '';
}
// --- ---

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Actualizar Empleado</title>
  <link rel="stylesheet" href="./style.css"> <!-- Asume que tienes este archivo CSS -->
  <style>
    /* Estilos básicos (puedes mantener los mismos o traducirlos si prefieres) */
    body { font-family: sans-serif; margin: 20px; }
    .contenedor { max-width: 1000px; margin: auto; }
    .acciones { display: flex; gap: 15px; justify-content: center; margin-top: 30px; }
    .acciones form, .acciones a { display: inline; padding: 0; border: none; }
    .boton { padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
    .boton-cancelar { background-color: #6c757d; }
    .boton:hover { background-color: #0056b3; }
    .boton-cancelar:hover { background-color: #5a6268; }
    form { max-width: 800px; margin: auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; }
    fieldset { margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
    legend { font-weight: bold; }
    label { display: block; margin-top: 10px; }
    input, select { width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box; }
    input[type="submit"] { margin-top: 15px; background-color: #28a745; color: #fff; border: none; cursor: pointer; border-radius: 4px; padding: 10px 15px; font-size: 16px;} /* Estilo botón Actualizar */
    input[type="submit"]:hover { background-color: #218838; }
  </style>
</head>
<body>

<div class="contenedor">

  <h2>Actualizar Datos del Empleado</h2>

  <!-- El formulario envía los datos a actualizar_empleados.php usando POST -->
  <form action="/inmobiliaria/crud_empleados/actualizar_empleados.php" method="POST">
    <!-- Campo oculto para enviar el ID del empleado que se está editando -->
    <input type="hidden" name="cod_emp" value="<?php echo htmlspecialchars($empleado['cod_emp']); ?>">

    <fieldset>
      <legend>Datos Personales</legend>

      <label for="ced_emp">Cédula:</label>
      <input type="text" id="ced_emp" name="ced_emp" maxlength="20" required value="<?php echo htmlspecialchars($empleado['ced_emp']); ?>">

      <label for="tipo_doc">Tipo de Documento:</label>
      <select id="tipo_doc" name="tipo_doc" required>
         <!-- Usamos la función auxiliar para marcar la opción correcta -->
        <option value="CEDULA" <?php echo estaSeleccionado($empleado['tipo_doc'], 'CEDULA'); ?>>Cédula de Ciudadanía</option>
        <option value="CE" <?php echo estaSeleccionado($empleado['tipo_doc'], 'CE'); ?>>Cédula de Extranjería</option>
        <option value="TI" <?php echo estaSeleccionado($empleado['tipo_doc'], 'TI'); ?>>Tarjeta de Identidad</option>
      </select>

      <label for="nom_emp">Nombre:</label>
      <!-- Ajustado maxlength según la definición de la tabla (varchar 100) -->
      <input type="text" id="nom_emp" name="nom_emp" maxlength="100" required value="<?php echo htmlspecialchars($empleado['nom_emp']); ?>">

      <label for="dir_emp">Dirección:</label>
      <input type="text" id="dir_emp" name="dir_emp" maxlength="150" required value="<?php echo htmlspecialchars($empleado['dir_emp']); ?>">

      <label for="tel_emp">Teléfono:</label>
      <input type="text" id="tel_emp" name="tel_emp" maxlength="12" value="<?php echo htmlspecialchars($empleado['tel_emp'] ?? ''); // Usar '' si es NULL ?>">

      <label for="email_emp">Correo:</label>
      <input type="email" id="email_emp" name="email_emp" maxlength="50" value="<?php echo htmlspecialchars($empleado['email_emp'] ?? ''); // Usar '' si es NULL ?>">

      <label for="rh_emp">RH:</label>
      <select id="rh_emp" name="rh_emp" required>
        <option value="A+" <?php echo estaSeleccionado($empleado['rh_emp'], 'A+'); ?>>A+</option>
        <option value="A-" <?php echo estaSeleccionado($empleado['rh_emp'], 'A-'); ?>>A-</option>
        <option value="B+" <?php echo estaSeleccionado($empleado['rh_emp'], 'B+'); ?>>B+</option>
        <option value="B-" <?php echo estaSeleccionado($empleado['rh_emp'], 'B-'); ?>>B-</option>
        <option value="AB+" <?php echo estaSeleccionado($empleado['rh_emp'], 'AB+'); ?>>AB+</option>
        <option value="AB-" <?php echo estaSeleccionado($empleado['rh_emp'], 'AB-'); ?>>AB-</option>
        <option value="O+" <?php echo estaSeleccionado($empleado['rh_emp'], 'O+'); ?>>O+</option>
        <option value="O-" <?php echo estaSeleccionado($empleado['rh_emp'], 'O-'); ?>>O-</option>
      </select>

      <label for="fecha_nac">Fecha de Nacimiento:</label>
      <input type="date" id="fecha_nac" name="fecha_nac" required value="<?php echo htmlspecialchars($empleado['fecha_nac']); ?>">
    </fieldset>

    <fieldset>
      <legend>Información Laboral</legend>

      <label for="cod_cargo">Cargo:</label>
      <select id="cod_cargo" name="cod_cargo" required>
        <option value="">-- Seleccione Cargo --</option>
        <?php
          // Volver a obtener los cargos para el menú desplegable
          $cargos = $conn->query("SELECT cod_cargo, nom_cargo FROM cargos ORDER BY nom_cargo");
          while ($fila_cargo = $cargos->fetch_assoc()) {
            $seleccionado = estaSeleccionado($empleado['cod_cargo'], $fila_cargo['cod_cargo']);
            echo "<option value='" . htmlspecialchars($fila_cargo["cod_cargo"]) . "' $seleccionado>" . htmlspecialchars($fila_cargo["nom_cargo"]) . "</option>";
          }
          // Liberar resultado si es necesario $cargos->free();
        ?>
      </select>

      <label for="cod_ofi">Oficina:</label>
      <select id="cod_ofi" name="cod_ofi" required>
        <option value="">-- Seleccione Oficina --</option>
        <?php
          // Volver a obtener las oficinas para el menú desplegable
          $oficinas = $conn->query("SELECT cod_ofi, nom_ofi FROM oficinas ORDER BY nom_ofi");
          while ($fila_ofi = $oficinas->fetch_assoc()) {
             $seleccionado = estaSeleccionado($empleado['cod_ofi'], $fila_ofi['cod_ofi']);
             echo "<option value='" . htmlspecialchars($fila_ofi["cod_ofi"]) . "' $seleccionado>" . htmlspecialchars($fila_ofi["nom_ofi"]) . "</option>";
          }
          // Liberar resultado si es necesario $oficinas->free();
        ?>
      </select>

      <label for="salario">Salario:</label>
       <!-- Asumiendo que salario, gastos, comision son numéricos (INT en la BD) -->
      <input type="number" id="salario" name="salario" required value="<?php echo htmlspecialchars($empleado['salario']); ?>" step="any"> <!-- step="any" para permitir decimales si fuera necesario -->

      <label for="gastos">Gastos:</label>
      <input type="number" id="gastos" name="gastos" required value="<?php echo htmlspecialchars($empleado['gastos']); ?>" step="any">

      <label for="comision">Comisión:</label>
      <input type="number" id="comision" name="comision" required value="<?php echo htmlspecialchars($empleado['comision']); ?>" step="any">

      <label for="fecha_ing">Fecha de Ingreso:</label>
      <input type="date" id="fecha_ing" name="fecha_ing" required value="<?php echo htmlspecialchars($empleado['fecha_ing']); ?>">

      <label for="fecha_ret">Fecha de Retiro:</label>
       <!-- Manejar fecha potencialmente NULL -->
      <input type="date" id="fecha_ret" name="fecha_ret" value="<?php echo htmlspecialchars($empleado['fecha_ret'] ?? ''); // Usar '' si es NULL ?>">
    </fieldset>

    <fieldset>
      <legend>Contacto de Emergencia</legend>

      <label for="nom_contacto">Nombre del Contacto:</label>
      <input type="text" id="nom_contacto" name="nom_contacto" maxlength="100" value="<?php echo htmlspecialchars($empleado['nom_contacto'] ?? ''); ?>">

      <label for="dir_contacto">Dirección del Contacto:</label>
      <input type="text" id="dir_contacto" name="dir_contacto" maxlength="150" value="<?php echo htmlspecialchars($empleado['dir_contacto'] ?? ''); ?>">

      <label for="tel_contacto">Teléfono del Contacto:</label>
      <input type="text" id="tel_contacto" name="tel_contacto" maxlength="12" value="<?php echo htmlspecialchars($empleado['tel_contacto'] ?? ''); ?>">

      <label for="email_contacto">Email del Contacto:</label>
      <input type="email" id="email_contacto" name="email_contacto" maxlength="50" value="<?php echo htmlspecialchars($empleado['email_contacto'] ?? ''); ?>">

      <label for="relacion_contacto">Relación/Parentesco:</label>
      <input type="text" id="relacion_contacto" name="relacion_contacto" maxlength="30" value="<?php echo htmlspecialchars($empleado['relacion_contacto'] ?? ''); ?>">
    </fieldset>

    <input type="submit" value="Actualizar Empleado">
  </form>

  <div class="acciones">
     <!-- Enlace para volver a la lista de empleados o página anterior -->
     <!-- Ajusta la URL de href según tu estructura -->
     <a href="/inmobiliaria/crud_empleados/consultar_empleados.php" class="boton boton-cancelar">Cancelar / Volver</a>
  </div>

</div>

</body>
</html>
<?php
$conn->close(); // Cerrar la conexión a la base de datos al final
?>