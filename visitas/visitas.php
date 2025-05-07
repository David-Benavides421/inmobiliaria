<?php
session_start();

require '../conexion.php'; // Ajusta la ruta si tu conexion.php está en otro lugar

// --- FUNCIONES AUXILIARES PARA DESPLEGABLES ---
function obtenerClientes($conn) {
    $items = [];
    $sql = "SELECT cod_cli, nom_cli FROM clientes ORDER BY nom_cli";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
    }
    return $items;
}

function obtenerEmpleados($conn) {
    $items = [];
    $sql = "SELECT cod_emp, nom_emp FROM empleados ORDER BY nom_emp";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
    }
    return $items;
}

function obtenerInmuebles($conn) {
    $items = [];
    // Ajusta 'dir_inm' al campo descriptivo real de tu tabla 'inmuebles' (ej. ref_inm, descripcion_inm)
    $sql = "SELECT cod_inm, dir_inm FROM inmuebles ORDER BY dir_inm";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
    }
    return $items;
}

// --- LÓGICA DE ACCIONES ---
$action = $_GET['action'] ?? 'list'; // Acción por defecto es listar
$id_visita = $_GET['id'] ?? null;
$visita = null; // Para guardar datos de la visita a editar

// --- PROCESAR FORMULARIOS (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha_vis = $_POST['fecha_vis'] ?? null;
    $cod_cli = !empty($_POST['cod_cli']) ? (int)$_POST['cod_cli'] : null;
    $cod_emp = !empty($_POST['cod_emp']) ? (int)$_POST['cod_emp'] : null;
    $cod_inm = !empty($_POST['cod_inm']) ? (int)$_POST['cod_inm'] : null;
    $comenta_vis = trim($_POST['comenta_vis'] ?? null);

    // Validaciones básicas (puedes expandirlas)
    if (empty($fecha_vis) || empty($cod_cli) || empty($cod_emp) || empty($cod_inm)) {
        $_SESSION['error_message'] = "Error: Fecha, Cliente, Empleado e Inmueble son obligatorios.";
        $redirect_url = "visitas.php?action=" . (isset($_POST['cod_vis']) ? "edit&id=" . $_POST['cod_vis'] : "add");
        header("Location: " . $redirect_url);
        exit();
    }

    // INSERTAR
    if ($action === 'add' && !isset($_POST['cod_vis'])) {
        $sql = "INSERT INTO visitas (fecha_vis, cod_cli, cod_emp, cod_inm, comenta_vis) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error al preparar la consulta de inserción: " . $conn->error);
        }
        $stmt->bind_param("siiis", $fecha_vis, $cod_cli, $cod_emp, $cod_inm, $comenta_vis);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Visita registrada exitosamente.";
        } else {
            $_SESSION['error_message'] = "Error al registrar la visita: " . $stmt->error;
        }
        $stmt->close();
    }
    // ACTUALIZAR
    elseif ($action === 'edit' && isset($_POST['cod_vis'])) {
        $cod_vis_update = (int)$_POST['cod_vis'];
        $sql = "UPDATE visitas SET fecha_vis = ?, cod_cli = ?, cod_emp = ?, cod_inm = ?, comenta_vis = ? WHERE cod_vis = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error al preparar la consulta de actualización: " . $conn->error);
        }
        $stmt->bind_param("siiisi", $fecha_vis, $cod_cli, $cod_emp, $cod_inm, $comenta_vis, $cod_vis_update);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Visita actualizada exitosamente.";
        } else {
            $_SESSION['error_message'] = "Error al actualizar la visita: " . $stmt->error;
        }
        $stmt->close();
    }
    header("Location: visitas.php?action=list");
    exit();
}

// --- LÓGICA PARA ACCIONES GET ---
// ELIMINAR
if ($action === 'delete' && $id_visita) {
    $stmt = $conn->prepare("DELETE FROM visitas WHERE cod_vis = ?");
    if ($stmt === false) {
        die("Error al preparar la consulta de eliminación: " . $conn->error);
    }
    $stmt->bind_param("i", $id_visita);
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Visita eliminada exitosamente.";
    } else {
        $_SESSION['error_message'] = "Error al eliminar la visita: " . $stmt->error;
    }
    $stmt->close();
    header("Location: visitas.php?action=list");
    exit();
}

// CARGAR DATOS PARA EDITAR
if ($action === 'edit' && $id_visita) {
    $stmt = $conn->prepare("SELECT * FROM visitas WHERE cod_vis = ?");
    if ($stmt === false) {
        die("Error al preparar la consulta para editar: " . $conn->error);
    }
    $stmt->bind_param("i", $id_visita);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $visita = $result->fetch_assoc();
    } else {
        $_SESSION['error_message'] = "Visita no encontrada.";
        header("Location: visitas.php?action=list");
        exit();
    }
    $stmt->close();
}

// OBTENER LISTA DE VISITAS (para la acción 'list')
$visitas_list = [];
if ($action === 'list') {
    // Ajusta i.dir_inm al campo descriptivo de tu tabla inmuebles
    $sql = "SELECT v.*, c.nom_cli, e.nom_emp, i.dir_inm as inmueble_desc
            FROM visitas v
            LEFT JOIN clientes c ON v.cod_cli = c.cod_cli
            LEFT JOIN empleados e ON v.cod_emp = e.cod_emp
            LEFT JOIN inmuebles i ON v.cod_inm = i.cod_inm
            ORDER BY v.fecha_vis DESC, v.cod_vis DESC";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $visitas_list[] = $row;
        }
    }
}

// Obtener datos para desplegables (se usan en add y edit)
$clientes_dropdown = obtenerClientes($conn);
$empleados_dropdown = obtenerEmpleados($conn);
$inmuebles_dropdown = obtenerInmuebles($conn);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Visitas</title>
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
    textarea { min-height: 100px; }
    input[type="submit"] { margin-top: 20px; background-color: #007bff; color: #fff; border: none; cursor: pointer; padding: 10px 20px; border-radius: 4px; font-size: 16px; }
    input[type="submit"]:hover { background-color: #0056b3; }
    .message { padding: 10px; margin-bottom: 15px; border-radius: 4px; }
    .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>

<div class="container">
    <h1>Gestión de Visitas</h1>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="message success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="message error"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
    <?php endif; ?>

    <?php if ($action === 'list'): ?>
        <h2>Listado de Visitas</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Empleado</th>
                    <th>Inmueble</th>
                    <th>Comentarios</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($visitas_list)): ?>
                    <tr><td colspan="7">No hay visitas registradas.</td></tr>
                <?php else: ?>
                    <?php foreach ($visitas_list as $v): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($v['cod_vis']); ?></td>
                        <td><?php echo htmlspecialchars(date("d/m/Y", strtotime($v['fecha_vis']))); ?></td>
                        <td><?php echo htmlspecialchars($v['nom_cli'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($v['nom_emp'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($v['inmueble_desc'] ?? 'N/A'); ?> (ID: <?php echo htmlspecialchars($v['cod_inm']);?>)</td>
                        <td><?php echo nl2br(htmlspecialchars(substr($v['comenta_vis'], 0, 100) . (strlen($v['comenta_vis']) > 100 ? '...' : ''))); ?></td>
                        <td class="actions">
                            <a href="visitas.php?action=edit&id=<?php echo $v['cod_vis']; ?>" class="edit">Editar</a>
                            <a href="visitas.php?action=delete&id=<?php echo $v['cod_vis']; ?>" class="delete" onclick="return confirm('¿Está seguro de que desea eliminar esta visita?');">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

    <?php elseif ($action === 'add' || ($action === 'edit' && $visita)): ?>
        <h2><?php echo ($action === 'add') ? 'Registrar Nueva Visita' : 'Editar Visita'; ?></h2>
        <form action="visitas.php?action=<?php echo $action; ?><?php echo ($action==='edit' ? '&id='.$visita['cod_vis'] : ''); ?>" method="POST">
            <?php if ($action === 'edit'): ?>
                <input type="hidden" name="cod_vis" value="<?php echo htmlspecialchars($visita['cod_vis']); ?>">
            <?php endif; ?>

            <label for="fecha_vis">Fecha de la Visita:</label>
            <input type="date" id="fecha_vis" name="fecha_vis" value="<?php echo htmlspecialchars($visita['fecha_vis'] ?? date('Y-m-d')); ?>" required>

            <label for="cod_cli">Cliente:</label>
            <select id="cod_cli" name="cod_cli" required>
              <option value="">-- Seleccione Cliente --</option>
              <?php foreach ($clientes_dropdown as $cli): ?>
                <option value="<?php echo $cli['cod_cli']; ?>" <?php echo (($visita['cod_cli'] ?? '') == $cli['cod_cli']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($cli['nom_cli']); ?> (ID: <?php echo $cli['cod_cli']; ?>)
                </option>
              <?php endforeach; ?>
            </select>

            <label for="cod_emp">Empleado que Atiende:</label>
            <select id="cod_emp" name="cod_emp" required>
              <option value="">-- Seleccione Empleado --</option>
              <?php foreach ($empleados_dropdown as $emp): ?>
                <option value="<?php echo $emp['cod_emp']; ?>" <?php echo (($visita['cod_emp'] ?? '') == $emp['cod_emp']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($emp['nom_emp']); ?> (ID: <?php echo $emp['cod_emp']; ?>)
                </option>
              <?php endforeach; ?>
            </select>

            <label for="cod_inm">Inmueble Visitado:</label>
            <select id="cod_inm" name="cod_inm" required>
              <option value="">-- Seleccione Inmueble --</option>
              <?php foreach ($inmuebles_dropdown as $inm): ?>
                <option value="<?php echo $inm['cod_inm']; ?>" <?php echo (($visita['cod_inm'] ?? '') == $inm['cod_inm']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($inm['dir_inm'] ?? 'Ref: '.$inm['cod_inm']); ?> (ID: <?php echo $inm['cod_inm']; ?>)
                </option>
              <?php endforeach; ?>
            </select>

            <label for="comenta_vis">Comentarios de la Visita:</label>
            <textarea id="comenta_vis" name="comenta_vis"><?php echo htmlspecialchars($visita['comenta_vis'] ?? ''); ?></textarea>

            <input type="submit" value="<?php echo ($action === 'add') ? 'Registrar Visita' : 'Actualizar Visita'; ?>">
            <a href="visitas.php?action=list" style="margin-left: 10px; text-decoration:none; color: #333; padding:10px; background-color:#eee; border-radius:4px; border:1px solid #ccc;">Cancelar</a>
        </form>
    <?php else: ?>
        <p>Acción no válida o visita no encontrada.</p>
        <a href="visitas.php?action=list">Volver al listado</a>
    <?php endif; ?> <br>

    <a href="visitas.php?action=add" class="actions add">Registrar Nueva Visita</a><br>

    <br><input type="button" value="Inicio" onclick="location.href='../dashboard.php'"><br>
</div>

<?php $conn->close(); ?>
</body>
</html>