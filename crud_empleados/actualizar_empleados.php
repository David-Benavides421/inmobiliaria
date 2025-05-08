<?php
// Requerir el archivo de conexión - Ajusta la ruta si es necesario
require '../conexion.php';

// Verificar si el formulario fue enviado usando el método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- Validación básica y recuperación de datos ---
    // Obtener el ID del empleado (crucial para la cláusula WHERE)
    // Usamos intval para asegurarnos de que sea un número entero
    $cod_emp = isset($_POST['cod_emp']) ? intval($_POST['cod_emp']) : 0;

    // Obtener los demás campos del formulario
    // Usar el operador de fusión de null (??) para asignar null si no está definido
    // Se recomienda añadir validaciones más robustas (formatos, longitudes, etc.)
    $ced_emp = $_POST['ced_emp'] ?? null;
    $tipo_doc = $_POST['tipo_doc'] ?? null;
    $nom_emp = $_POST['nom_emp'] ?? null;
    $dir_emp = $_POST['dir_emp'] ?? null;
    // Permitir valores vacíos para campos no obligatorios, pero convertirlos a NULL si están vacíos
    $tel_emp = !empty($_POST['tel_emp']) ? $_POST['tel_emp'] : null;
    $email_emp = !empty($_POST['email_emp']) ? $_POST['email_emp'] : null;
    $rh_emp = $_POST['rh_emp'] ?? null;
    $fecha_nac = $_POST['fecha_nac'] ?? null;
    $cod_cargo = isset($_POST['cod_cargo']) && $_POST['cod_cargo'] !== '' ? intval($_POST['cod_cargo']) : null;
    $cod_ofi = isset($_POST['cod_ofi']) && $_POST['cod_ofi'] !== '' ? intval($_POST['cod_ofi']) : null;
    // Convertir a entero o null si está vacío/no es numérico (ajustar según necesidad)
    $salario = isset($_POST['salario']) && is_numeric($_POST['salario']) ? intval($_POST['salario']) : null;
    $gastos = isset($_POST['gastos']) && is_numeric($_POST['gastos']) ? intval($_POST['gastos']) : null;
    $comision = isset($_POST['comision']) && is_numeric($_POST['comision']) ? intval($_POST['comision']) : null;
    $fecha_ing = $_POST['fecha_ing'] ?? null;
    // Manejar fecha de retiro vacía - establecer a NULL si está vacía
    $fecha_ret = !empty($_POST['fecha_ret']) ? $_POST['fecha_ret'] : null;

    // Campos de Contacto de Emergencia (permitir vacíos/NULL)
    $nom_contacto = !empty($_POST['nom_contacto']) ? $_POST['nom_contacto'] : null;
    $dir_contacto = !empty($_POST['dir_contacto']) ? $_POST['dir_contacto'] : null;
    $tel_contacto = !empty($_POST['tel_contacto']) ? $_POST['tel_contacto'] : null;
    $email_contacto = !empty($_POST['email_contacto']) ? $_POST['email_contacto'] : null;
    $relacion_contacto = !empty($_POST['relacion_contacto']) ? $_POST['relacion_contacto'] : null;

    // --- Verificar datos esenciales ---
    if ($cod_emp <= 0) {
        die("Error: Código de empleado inválido para actualizar.");
    }
    // Validar campos requeridos que no deben ser NULL en la base de datos
    if (is_null($ced_emp) || is_null($tipo_doc) || is_null($nom_emp) || is_null($dir_emp) ||
        is_null($rh_emp) || is_null($fecha_nac) || is_null($cod_cargo) || is_null($cod_ofi) ||
        is_null($salario) || is_null($gastos) || is_null($comision) || is_null($fecha_ing)) {
         die("Error: Faltan datos requeridos para la actualización.");
    }


    // --- Preparar la sentencia SQL UPDATE ---
    // Asegúrate de que los nombres de columna coincidan exactamente con tu tabla 'empleados'
    $sql = "UPDATE empleados SET
                ced_emp = ?, tipo_doc = ?, nom_emp = ?, dir_emp = ?, tel_emp = ?,
                email_emp = ?, rh_emp = ?, fecha_nac = ?, cod_cargo = ?, salario = ?,
                gastos = ?, comision = ?, fecha_ing = ?, fecha_ret = ?, nom_contacto = ?,
                dir_contacto = ?, tel_contacto = ?, email_contacto = ?, relacion_contacto = ?, cod_ofi = ?
            WHERE cod_emp = ?"; // La cláusula WHERE es fundamental

    $stmt = $conn->prepare($sql);

    // Verificar si la preparación de la consulta falló
    if (!$stmt) {
        die("Error al preparar la actualización: " . $conn->error);
    }

    // --- Vincular Parámetros ---
    // Los tipos deben coincidir con el orden y tipo de datos de los '?' en la consulta SQL
    // s: string, i: integer, d: double, b: blob
    // 20 signos de interrogación para SET y 1 para WHERE = 21 parámetros
    $stmt->bind_param(
        "isssssssiiiissssssssi", // 1 int (cedula?), 7 strings, 3 int, 1 date (string), 1 date/null (string), 5 strings, 1 int (ofi) + 1 int (WHERE)
                                 // Ajustar tipos si ced_emp es string: "ssssssssiiiissssssssi"
                                 // Si ced_emp es INT: "isssssssiiiissssssssi" (como está ahora)
                                 // ¡Verifica los tipos de tus columnas! Asumiendo ced_emp es INT(11) basado en tu imagen.
        $ced_emp, $tipo_doc, $nom_emp, $dir_emp, $tel_emp,
        $email_emp, $rh_emp, $fecha_nac, $cod_cargo, $salario,
        $gastos, $comision, $fecha_ing, $fecha_ret, $nom_contacto,
        $dir_contacto, $tel_contacto, $email_contacto, $relacion_contacto, $cod_ofi,
        $cod_emp // Este último es para el WHERE cod_emp = ?
    );

    // --- Ejecutar y Verificar ---
    if ($stmt->execute()) {
        // Éxito: Redirigir de vuelta al formulario de edición o a una página de confirmación
        // Puedes añadir un mensaje de éxito usando sesiones o parámetros GET
        // Redirigiendo de vuelta al formulario de edición para ver los cambios inmediatamente:
        // Ajusta la URL según tu estructura
        header("Location: /inmobiliaria/crud_empleados/editar_empleados.php?cod_emp=" . $cod_emp . "&estado=exito");
        exit; // Importante llamar a exit después de header() para detener la ejecución
    } else {
        // Error: Redirigir de vuelta con un mensaje de error o mostrar el error
        // Redirigiendo de vuelta al formulario con una bandera de error:
        // header("Location: /inmobiliaria/crud_empleados/editar_empleado.php?cod_emp=" . $cod_emp . "&estado=error&msg=" . urlencode($stmt->error));
        // O simplemente mostrar el error para depuración:
         die("Error al actualizar el empleado: " . $stmt->error);
    }

    // Cerrar la sentencia preparada
    $stmt->close();

} else {
    // No es una solicitud POST, redirigir o mostrar un error
    echo "Acceso inválido al script de actualización.";
    // Considera redirigir a la página de consulta:
    // header("Location: /inmobiliaria/crud_empleados/consultar_empleados.php");
    // exit;
}

// Cerrar la conexión a la base de datos
$conn->close();
?>