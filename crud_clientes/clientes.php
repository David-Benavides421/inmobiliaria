<?php
session_start(); // Para mensajes de estado (opcional pero útil)
require '/xampp/htdocs/inmobiliaria/conexion.php'; // Asegúrate que la ruta es correcta

// --- FUNCIONES AUXILIARES PARA DESPLEGABLES ---
function obtenerTiposInmueble($conn) {
    $tipos = [];
    $sql = "SELECT cod_tipoinm, nom_tipoinm FROM tipo_inmueble ORDER BY nom_tipoinm";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $tipos[] = $row;
        }
    }
    return $tipos;
}

function obtenerEmpleados($conn) {
    $empleados = [];
    $sql = "SELECT cod_emp, nom_emp FROM empleados ORDER BY nom_emp";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $empleados[] = $row;
        }
    }
    return $empleados;
}

// --- LÓGICA DE ACCIONES ---
$action = $_GET['action'] ?? 'list'; // Acción por defecto es listar
$id_cliente = $_GET['id'] ?? null;
$cliente = null; // Para guardar datos del cliente a editar

// --- PROCESAR FORMULARIOS (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperar y limpiar datos comunes
    $nom_cli = trim($_POST['nom_cli'] ?? null);
    $doc_cli = trim($_POST['doc_cli'] ?? null);
    $tipo_doc_cli = $_POST['tipo_doc_cli'] ?? null;
    $dir_cli = trim($_POST['dir_cli'] ?? null);
    $tel_cli = trim($_POST['tel_cli'] ?? null);
    $email_cli = trim($_POST['email_cli'] ?? null);
    $cod_tipoinm = $_POST['cod_tipoinm'] ?? null;
    $valor_maximo = $_POST['valor_maximo'] ?? null;
    $fecha_creacion_form = $_POST['fecha_creacion'] ?? date('Y-m-d'); // Usar fecha del form o actual
    $cod_emp = $_POST['cod_emp'] ?? null;
    $notas_cliente = trim($_POST['notas_cliente'] ?? null);

    // Validaciones básicas (puedes expandirlas)
    if (empty($nom_cli) || empty($doc_cli) || empty($email_cli)) {
        $_SESSION['error_message'] = "Error: Nombre, Documento y Email son obligatorios.";
        // Si es edición, redirige al formulario de edición, sino al de inserción
        if (isset($_POST['cod_cli'])) {
            header("Location: /inmobiliaria/crud_clientes/clientes.php?action=edit&id=" . $_POST['cod_cli']);
        } else {
            header("Location: /inmobiliaria/crud_clientes/clientes.php?action=add");
        }
        exit();
    }
    if (!filter_var($email_cli, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "Error: El formato del Email no es válido.";
        if (isset($_POST['cod_cli'])) {
            header("Location: /inmobiliaria/crud_clientes/clientes.php?action=edit&id=" . $_POST['cod_cli']);
        } else {
            header("Location: /inmobiliaria/crud_clientes/clientes.php?action=add");
        }
        exit();
    }

    $doc_cli_db = !empty($doc_cli) ? (int)$doc_cli : null;
    $cod_tipoinm_db = !empty($cod_tipoinm) ? (int)$cod_tipoinm : null;
    $valor_maximo_db = !empty($valor_maximo) ? (float)str_replace(',', '.', $valor_maximo) : null;
    $cod_emp_db = !empty($cod_emp) ? (int)$cod_emp : null;

    // INSERTAR
    if ($action === 'add' && !isset($_POST['cod_cli'])) {
        $sql = "INSERT INTO clientes
                    (nom_cli, doc_cli, tipo_doc_cli, dir_cli, tel_cli, email_cli, cod_tipoinm, valor_maximo, fecha_creacion, cod_emp, notas_cliente)
                VALUES
                    (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error al preparar la consulta de inserción: " . $conn->error);
        }
        $stmt->bind_param(
            "sissssidisi",
            $nom_cli, $doc_cli_db, $tipo_doc_cli, $dir_cli, $tel_cli, $email_cli,
            $cod_tipoinm_db, $valor_maximo_db, $fecha_creacion_form, $cod_emp_db, $notas_cliente
        );

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Cliente registrado exitosamente.";
        } else {
            $_SESSION['error_message'] = "Error al registrar el cliente: " . $stmt->error;
        }
        $stmt->close();
        header("Location: /inmobiliaria/crud_clientes/clientes.php?action=list");
        exit();
    }
    // ACTUALIZAR
    elseif ($action === 'edit' && isset($_POST['cod_cli'])) {
        $cod_cli_update = (int)$_POST['cod_cli'];
        $sql = "UPDATE clientes SET
                    nom_cli = ?, doc_cli = ?, tipo_doc_cli = ?, dir_cli = ?, tel_cli = ?, email_cli = ?,
                    cod_tipoinm = ?, valor_maximo = ?, fecha_creacion = ?, cod_emp = ?, notas_cliente = ?
                WHERE cod_cli = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error al preparar la consulta de actualización: " . $conn->error);
        }
        $stmt->bind_param(
            "sissssidisii",
            $nom_cli, $doc_cli_db, $tipo_doc_cli, $dir_cli, $tel_cli, $email_cli,
            $cod_tipoinm_db, $valor_maximo_db, $fecha_creacion_form, $cod_emp_db, $notas_cliente,
            $cod_cli_update
        );

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Cliente actualizado exitosamente.";
        } else {
            $_SESSION['error_message'] = "Error al actualizar el cliente: " . $stmt->error;
        }
        $stmt->close();
        header("Location: /inmobiliaria/crud_clientes/clientes.php?action=list");
        exit();
    }
}

// --- LÓGICA PARA ACCIONES GET ---
// ELIMINAR
if ($action === 'delete' && $id_cliente) {
    $stmt = $conn->prepare("DELETE FROM clientes WHERE cod_cli = ?");
    if ($stmt === false) {
        die("Error al preparar la consulta de eliminación: " . $conn->error);
    }
    $stmt->bind_param("i", $id_cliente);
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Cliente eliminado exitosamente.";
    } else {
        $_SESSION['error_message'] = "Error al eliminar el cliente: " . $stmt->error;
    }
    $stmt->close();
    header("Location: /inmobiliaria/crud_clientes/clientes.php?action=list");
    exit();
}

// CARGAR DATOS PARA EDITAR
if ($action === 'edit' && $id_cliente) {
    $stmt = $conn->prepare("SELECT * FROM clientes WHERE cod_cli = ?");
    if ($stmt === false) {
        die("Error al preparar la consulta para editar: " . $conn->error);
    }
    $stmt->bind_param("i", $id_cliente);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $cliente = $result->fetch_assoc();
    } else {
        $_SESSION['error_message'] = "Cliente no encontrado.";
        header("Location: /inmobiliaria/crud_clientes/clientes.php?action=list");
        exit();
    }
    $stmt->close();
}

// OBTENER LISTA DE CLIENTES (para la acción 'list')
$clientes = [];
if ($action === 'list') {
    $sql = "SELECT c.*, ti.nom_tipoinm, e.nom_emp
            FROM clientes c
            LEFT JOIN tipo_inmueble ti ON c.cod_tipoinm = ti.cod_tipoinm
            LEFT JOIN empleados e ON c.cod_emp = e.cod_emp
            ORDER BY c.nom_cli ASC";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $clientes[] = $row;
        }
    }
}

// Obtener datos para desplegables (se usan en add y edit)
$tipos_inmueble_list = obtenerTiposInmueble($conn);
$empleados_list = obtenerEmpleados($conn);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Clientes</title>
    <!-- <link rel="stylesheet" href="./style.css"> Si tienes un style.css global -->
    <style>
    body { font-family: sans-serif; margin: 20px; background-color: #f4f4f4; }
    .container { max-width: 1000px; margin: auto; background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    h1, h2 { color: #333; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
    th { background-color: #007bff; color: white; }
    tr:nth-child(even) { background-color: #f9f9f9; }
    .actions a { margin-right: 8px; text-decoration: none; padding: 5px 8px; border-radius: 4px; }
    .actions .edit { background-color: #ffc107; color: #333; }
    .actions .delete { background-color: #dc3545; color: white; }
    .actions .add { display: inline-block; margin-bottom:20px; background-color: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;}

    form { max-width: 800px; margin: 20px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; background-color: #fff; }
    fieldset { margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
    legend { font-weight: bold; padding: 0 10px; }
    label { display: block; margin-top: 10px; font-weight: bold; }
    input[type="text"], input[type="number"], input[type="email"], input[type="date"], select, textarea {
        width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;
    }
    textarea { min-height: 80px; }
    input[type="submit"] { margin-top: 20px; background-color: #007bff; color: #fff; border: none; cursor: pointer; padding: 10px 20px; border-radius: 4px; font-size: 16px; }
    input[type="submit"]:hover { background-color: #0056b3; }
    .message { padding: 10px; margin-bottom: 15px; border-radius: 4px; }
    .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>

<div class="container">
    <h1>Gestión de Clientes</h1>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="message success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="message error"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
    <?php endif; ?>

    <?php if ($action === 'list'): ?>
        <h2>Listado de Clientes</h2>
        <a href="/inmobiliaria/crud_clientes/clientes.php?action=add" class="actions add">Agregar Nuevo Cliente</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Documento</th>
                    <th>Tipo Doc.</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Tipo Inm. Interés</th>
                    <th>Empleado Asig.</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($clientes)): ?>
                    <tr><td colspan="9">No hay clientes registrados.</td></tr>
                <?php else: ?>
                    <?php foreach ($clientes as $c): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($c['cod_cli']); ?></td>
                        <td><?php echo htmlspecialchars($c['nom_cli']); ?></td>
                        <td><?php echo htmlspecialchars($c['doc_cli']); ?></td>
                        <td><?php echo htmlspecialchars($c['tipo_doc_cli']); ?></td>
                        <td><?php echo htmlspecialchars($c['email_cli']); ?></td>
                        <td><?php echo htmlspecialchars($c['tel_cli']); ?></td>
                        <td><?php echo htmlspecialchars($c['nom_tipoinm'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($c['nom_emp'] ?? 'N/A'); ?></td>
                        <td class="actions">
                            <a href="/inmobiliaria/crud_clientes/clientes.php?action=edit&id=<?php echo $c['cod_cli']; ?>" class="edit">Editar</a>
                            <a href="/inmobiliaria/crud_clientes/clientes.php?action=delete&id=<?php echo $c['cod_cli']; ?>" class="delete" onclick="return confirm('¿Está seguro de que desea eliminar este cliente?');">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

    <?php elseif ($action === 'add' || ($action === 'edit' && $cliente)): ?>
        <h2><?php echo ($action === 'add') ? 'Agregar Nuevo Cliente' : 'Editar Cliente'; ?></h2>
        <form action="/inmobiliaria/crud_clientes/clientes.php?action=<?php echo $action; ?><?php echo ($action==='edit' ? '&id='.$cliente['cod_cli'] : ''); ?>" method="POST">
            <?php if ($action === 'edit'): ?>
                <input type="hidden" name="cod_cli" value="<?php echo htmlspecialchars($cliente['cod_cli']); ?>">
            <?php endif; ?>

            <fieldset>
                <legend>Datos Personales</legend>

                <label for="doc_cli">Cédula/Documento:</label>
                <input type="number" id="doc_cli" name="doc_cli" value="<?php echo htmlspecialchars($cliente['doc_cli'] ?? ''); ?>" required>

                <label for="tipo_doc_cli">Tipo de Documento:</label>
                <select id="tipo_doc_cli" name="tipo_doc_cli" required>
                  <option value="CC" <?php echo (($cliente['tipo_doc_cli'] ?? '') === 'CC') ? 'selected' : ''; ?>>Cédula de Ciudadanía</option>
                  <option value="CE" <?php echo (($cliente['tipo_doc_cli'] ?? '') === 'CE') ? 'selected' : ''; ?>>Cédula de Extranjería</option>
                  <option value="TI" <?php echo (($cliente['tipo_doc_cli'] ?? '') === 'TI') ? 'selected' : ''; ?>>Tarjeta de Identidad</option>
                </select>

                <label for="nom_cli">Nombre Completo:</label>
                <input type="text" id="nom_cli" name="nom_cli" maxlength="100" value="<?php echo htmlspecialchars($cliente['nom_cli'] ?? ''); ?>" required>

                <label for="dir_cli">Dirección:</label>
                <input type="text" id="dir_cli" name="dir_cli" maxlength="150" value="<?php echo htmlspecialchars($cliente['dir_cli'] ?? ''); ?>">

                <label for="tel_cli">Teléfono:</label>
                <input type="text" id="tel_cli" name="tel_cli" maxlength="12" value="<?php echo htmlspecialchars($cliente['tel_cli'] ?? ''); ?>">

                <label for="email_cli">Correo Electrónico:</label>
                <input type="email" id="email_cli" name="email_cli" maxlength="50" value="<?php echo htmlspecialchars($cliente['email_cli'] ?? ''); ?>" required>
            </fieldset>

            <fieldset>
                <legend>Interés Inmobiliario y Asignación</legend>

                <label for="cod_tipoinm">Tipo de inmueble de interés:</label>
                <select id="cod_tipoinm" name="cod_tipoinm">
                  <option value="">-- Seleccione Tipo Inmueble --</option>
                  <?php foreach ($tipos_inmueble_list as $tipo): ?>
                    <option value="<?php echo $tipo['cod_tipoinm']; ?>" <?php echo (($cliente['cod_tipoinm'] ?? '') == $tipo['cod_tipoinm']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($tipo['nom_tipoinm']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>

                <label for="valor_maximo">Valor máximo presupuesto (opcional):</label>
                <input type="number" id="valor_maximo" name="valor_maximo" step="0.01" value="<?php echo htmlspecialchars($cliente['valor_maximo'] ?? ''); ?>">

                <label for="fecha_creacion">Fecha de Creación/Contacto:</label>
                <input type="date" id="fecha_creacion" name="fecha_creacion" value="<?php echo htmlspecialchars($cliente['fecha_creacion'] ?? date('Y-m-d')); ?>" required>

                <label for="cod_emp">Empleado Asignado:</label>
                <select id="cod_emp" name="cod_emp">
                  <option value="">-- Seleccione Empleado --</option>
                  <?php foreach ($empleados_list as $emp): ?>
                    <option value="<?php echo $emp['cod_emp']; ?>" <?php echo (($cliente['cod_emp'] ?? '') == $emp['cod_emp']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($emp['nom_emp']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>

                <label for="notas_cliente">Notas Adicionales:</label>
                <textarea id="notas_cliente" name="notas_cliente"><?php echo htmlspecialchars($cliente['notas_cliente'] ?? ''); ?></textarea>
            </fieldset>

            <input type="submit" value="<?php echo ($action === 'add') ? 'Registrar Cliente' : 'Actualizar Cliente'; ?>">
            <a href="/inmobiliaria/crud_clientes/clientes.php?action=list" style="margin-left: 10px; text-decoration:none; color: #333; padding:10px; background-color:#eee; border-radius:4px; border:1px solid #ccc;">Cancelar</a>
        </form>
        

    <?php else: ?>
        <p>Acción no válida o cliente no encontrado.</p>
        <a href="/inmobiliaria/crud_clientes/clientes.php?action=list">Volver al listado</a>
    <?php endif; ?>

    <br><button onclick="window.history.back();" style="padding:10px 20px; background-color:#1976d2; color:white; border:none; border-radius:5px; cursor:pointer;">Volver atrás</button><br>


</div>



<?php $conn->close(); ?>
</body>
</html>