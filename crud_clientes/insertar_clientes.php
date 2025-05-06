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

<form action="/inmobiliaria/crud_clientes/insertar_clientes.php" method="POST">
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


<?php
// 1. Incluir archivo de conexión
require '/xampp/htdocs/inmobiliaria/crud_clientes/conexion.php'; // Asegúrate que la ruta es correcta

// Verificar si la conexión se estableció (opcional pero recomendado)
if ($conn->connect_error) {
    // Usar die() o manejar el error de forma más elegante
    die("Error de Conexión: (" . $conn->connect_errno . ") " . $conn->connect_error);
}

// 2. Verificar si se recibieron datos por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 3. Recuperar y limpiar datos del formulario
    // Usamos trim() para quitar espacios al inicio/final y ?? null para evitar errores si no viene el dato
    $nom_cli = trim($_POST['nom_cli'] ?? null);
    $doc_cli = trim($_POST['doc_cli'] ?? null);
    $tipo_doc_cli = $_POST['tipo_doc_cli'] ?? null; // Enum, usualmente de un select
    $dir_cli = trim($_POST['dir_cli'] ?? null);
    $tel_cli = trim($_POST['tel_cli'] ?? null);
    $email_cli = trim($_POST['email_cli'] ?? null); // Campo obligatorio (NOT NULL en BD)
    $cod_tipoinm = $_POST['cod_tipoinm'] ?? null; // FK, usualmente de un select
    $valor_maximo = $_POST['valor_maximo'] ?? null; // Decimal
    $cod_emp = $_POST['cod_emp'] ?? null; // FK, usualmente de un select o dato del usuario logueado
    $notas_cliente = trim($_POST['notas_cliente'] ?? null); // Text, usualmente de un textarea

    // La fecha de creación la podemos generar automáticamente
    $fecha_creacion = date('Y-m-d'); // Fecha actual en formato YYYY-MM-DD

    // 4. Validación básica (¡MUY IMPORTANTE!)
    // Aquí deberías añadir más validaciones según tus reglas de negocio
    // Ejemplo: verificar que los campos obligatorios no estén vacíos
    if (empty($email_cli)) {
        die("Error: El campo Email es obligatorio.");
        // En una aplicación real, redirigirías al formulario con un mensaje de error
        // header("Location: formulario_cliente.php?error=email_requerido");
        // exit();
    }

    // Ejemplo: Validar formato de email
    if (!filter_var($email_cli, FILTER_VALIDATE_EMAIL)) {
        die("Error: El formato del Email no es válido.");
    }

    // Ejemplo: Convertir campos numéricos/decimales vacíos a NULL si la BD lo permite
    $doc_cli_db = !empty($doc_cli) ? (int)$doc_cli : null;
    $cod_tipoinm_db = !empty($cod_tipoinm) ? (int)$cod_tipoinm : null;
    $valor_maximo_db = !empty($valor_maximo) ? (float)str_replace(',', '.', $valor_maximo) : null; // Permitir coma como separador decimal
    $cod_emp_db = !empty($cod_emp) ? (int)$cod_emp : null;

    // Validar que tipo_doc_cli sea uno de los valores permitidos ('CC', 'CE', 'TI') si viene del formulario
    $tipos_validos = ['CC', 'CE', 'TI'];
    if (!empty($tipo_doc_cli) && !in_array($tipo_doc_cli, $tipos_validos)) {
        die("Error: Tipo de documento no válido.");
    }


    // 5. Preparar la consulta SQL (¡Usando Sentencias Preparadas para seguridad!)
    $sql = "INSERT INTO clientes
                (nom_cli, doc_cli, tipo_doc_cli, dir_cli, tel_cli, email_cli, cod_tipoinm, valor_maximo, fecha_creacion, cod_emp, notas_cliente)
            VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    // Verificar si la preparación falló
    if ($stmt === false) {
        // Loguear el error real para el desarrollador
        error_log("Error al preparar la consulta: " . $conn->error);
        // Mensaje genérico para el usuario
        die("Error al procesar la solicitud. Por favor, inténtelo más tarde.");
    }

    // 6. Vincular parámetros
    // 's' para string, 'i' para integer, 'd' para double/decimal, 'b' para blob
    // El orden y los tipos DEBEN coincidir con los '?' de la consulta y las variables
    $stmt->bind_param(
        "sissssidisi", // s(nom), i(doc), s(tipo_doc), s(dir), s(tel), s(email), i(cod_tipoinm), d(valor), s(fecha), i(cod_emp), s(notas)
        $nom_cli,
        $doc_cli_db,
        $tipo_doc_cli,
        $dir_cli,
        $tel_cli,
        $email_cli,
        $cod_tipoinm_db,
        $valor_maximo_db,
        $fecha_creacion, // La fecha generada
        $cod_emp_db,
        $notas_cliente
    );

    // 7. Ejecutar la consulta
    if ($stmt->execute()) {
        // Éxito: Redirigir a una página de éxito o al listado de clientes
        // Es una buena práctica redirigir después de un POST exitoso (Patrón PRG)
        // $nuevo_id = $stmt->insert_id; // Obtener el ID del cliente recién insertado (opcional)
        header("Location: /inmobiliaria/crud_clientes/clientes.php?status=creado"); // Cambia 'listado_clientes.php' por tu página destino
        exit(); // Terminar el script después de la redirección

    } else {
        // Error en la ejecución
        // Loguear el error real
        error_log("Error al ejecutar la inserción: " . $stmt->error);
        // Mensaje genérico para el usuario
        die("Error al guardar los datos del cliente. Por favor, inténtelo más tarde.");
        // O redirigir con un mensaje de error específico si es seguro mostrarlo
        // header("Location: formulario_cliente.php?error=db_error");
        // exit();
    }

    // 8. Cerrar la sentencia preparada
    $stmt->close();

} else {
    // Si no es POST, redirigir al formulario o mostrar un error
    // header("Location: /inmobiliaria/clientes/formulario_cliente.php");
    // exit();
    echo "Método no permitido.";
}

// 9. Cerrar la conexión a la base de datos
$conn->close();

?>