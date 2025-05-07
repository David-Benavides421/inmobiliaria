<?php
session_start();
// Si conexion.php está en /inmobiliaria/conexion.php
// y este archivo está en /inmobiliaria/inspeccion/inspecciones.php
// entonces ../conexion.php es correcto.
require '../conexion.php';

// --- FUNCIONES AUXILIARES PARA DESPLEGABLES ---
function obtenerInmuebles($conn) {
    $items = [];
    // **AJUSTA 'dir_inm' al campo descriptivo real de tu tabla 'inmuebles' (ej. ref_inm, descripcion_inm)**
    $sql = "SELECT cod_inm, dir_inm FROM inmuebles ORDER BY dir_inm";
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

// --- LÓGICA DE ACCIONES ---
$action = $_GET['action'] ?? 'list'; // Acción por defecto es listar
$id_inspeccion = $_GET['id'] ?? null;
$inspeccion = null; // Para guardar datos de la inspección a editar

// --- PROCESAR FORMULARIOS (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha_ins = $_POST['fecha_ins'] ?? null;
    $cod_inm = !empty($_POST['cod_inm']) ? (int)$_POST['cod_inm'] : null;
    $cod_emp = !empty($_POST['cod_emp']) ? (int)$_POST['cod_emp'] : null;
    $comentario = trim($_POST['comentario'] ?? null);

    // Validaciones básicas
    if (empty($fecha_ins) || empty($cod_inm) || empty($cod_emp)) {
        $_SESSION['error_message'] = "Error: Fecha, Inmueble y Empleado son obligatorios.";
        // Las URLs en header Location son relativas al script actual, por lo que no necesitan ../
        $redirect_url = "inspecciones.php?action=" . (isset($_POST['cod_ins']) ? "edit&id=" . $_POST['cod_ins'] : "add");
        header("Location: " . $redirect_url);
        exit();
    }

    // INSERTAR
    if ($action === 'add' && !isset($_POST['cod_ins'])) {
        $sql = "INSERT INTO inspeccion (fecha_ins, cod_inm, cod_emp, comentario) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            // En producción, loguear el error, no mostrarlo directamente
            error_log("Error al preparar la consulta de inserción: " . $conn->error);
            $_SESSION['error_message'] = "Error al procesar la solicitud. Intente más tarde.";
             header("Location: inspecciones.php?action=list");
            exit();
        }
        // s (fecha), i (inm), i (emp), s (comentario)
        $stmt->bind_param("siis", $fecha_ins, $cod_inm, $cod_emp, $comentario);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Inspección registrada exitosamente.";
        } else {
            error_log("Error al registrar la inspección: " . $stmt->error);
            $_SESSION['error_message'] = "Error al registrar la inspección.";
        }
        $stmt->close();
    }
    // ACTUALIZAR
    elseif ($action === 'edit' && isset($_POST['cod_ins'])) {
        $cod_ins_update = (int)$_POST['cod_ins'];
        $sql = "UPDATE inspeccion SET fecha_ins = ?, cod_inm = ?, cod_emp = ?, comentario = ? WHERE cod_ins = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error al preparar la consulta de actualización: " . $conn->error);
            $_SESSION['error_message'] = "Error al procesar la solicitud. Intente más tarde.";
            header("Location: inspecciones.php?action=list");
            exit();
        }
        $stmt->bind_param("siisi", $fecha_ins, $cod_inm, $cod_emp, $comentario, $cod_ins_update);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Inspección actualizada exitosamente.";
        } else {
            error_log("Error al actualizar la inspección: " . $stmt->error);
            $_SESSION['error_message'] = "Error al actualizar la inspección.";
        }
        $stmt->close();
    }
    header("Location: inspecciones.php?action=list"); // Relativa al script actual
    exit();
}

// --- LÓGICA PARA ACCIONES GET ---
// ELIMINAR
if ($action === 'delete' && $id_inspeccion) {
    $stmt = $conn->prepare("DELETE FROM inspeccion WHERE cod_ins = ?");
    if ($stmt === false) {
        error_log("Error al preparar la consulta de eliminación: " . $conn->error);
        $_SESSION['error_message'] = "Error al procesar la solicitud. Intente más tarde.";
    } else {
        $stmt->bind_param("i", $id_inspeccion);
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Inspección eliminada exitosamente.";
        } else {
            error_log("Error al eliminar la inspección: " . $stmt->error);
            $_SESSION['error_message'] = "Error al eliminar la inspección.";
        }
        $stmt->close();
    }
    header("Location: inspecciones.php?action=list"); // Relativa al script actual
    exit();
}

// CARGAR DATOS PARA EDITAR
if ($action === 'edit' && $id_inspeccion) {
    $stmt = $conn->prepare("SELECT * FROM inspeccion WHERE cod_ins = ?");
    if ($stmt === false) {
        error_log("Error al preparar la consulta para editar: " . $conn->error);
        $_SESSION['error_message'] = "Error al cargar datos para edición.";
        header("Location: inspecciones.php?action=list"); // Relativa al script actual
        exit();
    }
    $stmt->bind_param("i", $id_inspeccion);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $inspeccion = $result->fetch_assoc();
    } else {
        $_SESSION['error_message'] = "Inspección no encontrada.";
        header("Location: inspecciones.php?action=list"); // Relativa al script actual
        exit();
    }
    $stmt->close();
}

// OBTENER LISTA DE INSPECCIONES (para la acción 'list')
$inspecciones_list = [];
if ($action === 'list') {
    // **AJUSTA inm.dir_inm al campo descriptivo de tu tabla inmuebles**
    $sql = "SELECT insp.*, inm.dir_inm as inmueble_desc, emp.nom_emp
            FROM inspeccion insp
            LEFT JOIN inmuebles inm ON insp.cod_inm = inm.cod_inm
            LEFT JOIN empleados emp ON insp.cod_emp = emp.cod_emp
            ORDER BY insp.fecha_ins DESC, insp.cod_ins DESC";
    $result = $conn->query($sql);
    if ($result === false) {
        error_log("Error al obtener lista de inspecciones: " . $conn->error);
        // Podrías mostrar un error en la página aquí o simplemente dejar la lista vacía
        $inspecciones_list = [];
         $_SESSION['error_message'] = "Error al cargar el listado de inspecciones.";
    } elseif ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $inspecciones_list[] = $row;
        }
    }
}

// Obtener datos para desplegables (se usan en add y edit)
$inmuebles_dropdown = obtenerInmuebles($conn);
$empleados_dropdown = obtenerEmpleados($conn);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Inspecciones</title>
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

<div class="container">
    <h1>Gestión de Inspecciones</h1>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="message success"><?php echo htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="message error"><?php echo htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?></div>
    <?php endif; ?>

    <?php if ($action === 'list'): ?>
        <h2>Listado de Inspecciones</h2>
        <a href="inspecciones.php?action=add" class="actions add">Registrar Nueva Inspección</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Inmueble</th>
                    <th>Empleado</th>
                    <th>Comentario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($inspecciones_list)): ?>
                    <tr><td colspan="6">No hay inspecciones registradas.</td></tr>
                <?php else: ?>
                    <?php foreach ($inspecciones_list as $insp): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($insp['cod_ins']); ?></td>
                        <td><?php echo htmlspecialchars(date("d/m/Y", strtotime($insp['fecha_ins']))); ?></td>
                        <td><?php echo htmlspecialchars($insp['inmueble_desc'] ?? 'N/A'); ?> (ID: <?php echo htmlspecialchars($insp['cod_inm']);?>)</td>
                        <td><?php echo htmlspecialchars($insp['nom_emp'] ?? 'N/A'); ?> (ID: <?php echo htmlspecialchars($insp['cod_emp']);?>)</td>
                        <td><?php echo nl2br(htmlspecialchars(substr($insp['comentario'] ?? '', 0, 100) . (strlen($insp['comentario'] ?? '') > 100 ? '...' : ''))); ?></td>
                        <td class="actions">
                            <a href="inspecciones.php?action=edit&id=<?php echo $insp['cod_ins']; ?>" class="edit">Editar</a>
                            <a href="inspecciones.php?action=delete&id=<?php echo $insp['cod_ins']; ?>" class="delete" onclick="return confirm('¿Está seguro de que desea eliminar esta inspección?');">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

    <?php elseif ($action === 'add' || ($action === 'edit' && $inspeccion)): ?>
        <h2><?php echo ($action === 'add') ? 'Registrar Nueva Inspección' : 'Editar Inspección'; ?></h2>
        <form action="inspecciones.php?action=<?php echo $action; ?><?php echo ($action==='edit' ? '&id='.htmlspecialchars($inspeccion['cod_ins']) : ''); ?>" method="POST">
            <?php if ($action === 'edit'): ?>
                <input type="hidden" name="cod_ins" value="<?php echo htmlspecialchars($inspeccion['cod_ins']); ?>">
            <?php endif; ?>

            <label for="fecha_ins">Fecha de la Inspección:</label>
            <input type="date" id="fecha_ins" name="fecha_ins" value="<?php echo htmlspecialchars($inspeccion['fecha_ins'] ?? date('Y-m-d')); ?>" required>

            <label for="cod_inm">Inmueble Inspeccionado:</label>
            <select id="cod_inm" name="cod_inm" required>
              <option value="">-- Seleccione Inmueble --</option>
              <?php foreach ($inmuebles_dropdown as $inm): ?>
                <option value="<?php echo $inm['cod_inm']; ?>" <?php echo (($inspeccion['cod_inm'] ?? '') == $inm['cod_inm']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($inm['dir_inm'] ?? 'Ref: '.$inm['cod_inm']); ?> (ID: <?php echo $inm['cod_inm']; ?>)
                </option>
              <?php endforeach; ?>
            </select>

            <label for="cod_emp">Empleado que Realiza:</label>
            <select id="cod_emp" name="cod_emp" required>
              <option value="">-- Seleccione Empleado --</option>
              <?php foreach ($empleados_dropdown as $emp): ?>
                <option value="<?php echo $emp['cod_emp']; ?>" <?php echo (($inspeccion['cod_emp'] ?? '') == $emp['cod_emp']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($emp['nom_emp']); ?> (ID: <?php echo $emp['cod_emp']; ?>)
                </option>
              <?php endforeach; ?>
            </select>

            <label for="comentario">Comentarios de la Inspección (máx 255 caracteres):</label>
            <textarea id="comentario" name="comentario" rows="4" maxlength="255"><?php echo htmlspecialchars($inspeccion['comentario'] ?? ''); ?></textarea>

            <input type="submit" value="<?php echo ($action === 'add') ? 'Registrar Inspección' : 'Actualizar Inspección'; ?>">
            <a href="inspecciones.php?action=list" style="margin-left: 10px; text-decoration:none; color: #333; padding:10px; background-color:#eee; border-radius:4px; border:1px solid #ccc;">Cancelar</a>
        </form>
    <?php else: ?>
        <p>Acción no válida o inspección no encontrada.</p>
        <a href="inspecciones.php?action=list">Volver al listado</a>
    <?php endif; ?>

    <br><button onclick="window.history.back();" style="padding:10px 20px; background-color:#1976d2; color:white; border:none; border-radius:5px; cursor:pointer;">Volver atrás</button><br>
</div>

<?php
if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}
?>
</body>
</html>