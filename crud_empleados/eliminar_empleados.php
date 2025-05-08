<?php
// Requerir archivo de conexión
require '../conexion.php';

// Verificar si el CÓDIGO del empleado está presente y es numérico
// Usamos cod_emp (clave primaria) en lugar de ced_emp
if (!isset($_GET['cod_emp']) || !is_numeric($_GET['cod_emp'])) {
    // Mensaje de error si falta el código o no es un número
    die("Error: Código de empleado no válido o no proporcionado en la URL.");
}

// Obtener el código como entero para seguridad
$codigo_empleado = intval($_GET['cod_emp']);

// Verificar conexión (buena práctica añadirla aquí también)
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Iniciar una transacción (opcional pero recomendado para operaciones de borrado)
$conn->begin_transaction();

try {
    // Usar sentencia preparada con cod_emp (clave primaria)
    $stmt = $conn->prepare("DELETE FROM empleados WHERE cod_emp = ?");

    // Verificar si la preparación falló
    if (!$stmt) {
        // Lanzar excepción si falla la preparación
        throw new Exception("Error al preparar la consulta de eliminación: " . $conn->error);
    }

    // Vincular el parámetro (i para entero)
    $stmt->bind_param("i", $codigo_empleado);

    // Ejecutar la consulta de eliminación
    if (!$stmt->execute()) {
        // Lanzar excepción si falla la ejecución
        throw new Exception("Error al ejecutar la eliminación: " . $stmt->error);
    }

    // Verificar si alguna fila fue afectada (opcional, pero útil)
    if ($stmt->affected_rows === 0) {
        // Lanzar excepción si no se encontró el empleado (o ya fue borrado)
        throw new Exception("No se encontró ningún empleado con el código proporcionado o no se pudo eliminar.");
    }

    // Si todo fue bien, confirmar la transacción
    $conn->commit();

    // Cerrar la sentencia antes de redirigir
    $stmt->close();
    $conn->close();

    // Éxito: Redirigir de vuelta a la lista con un mensaje de éxito
    // El mensaje se puede mostrar en consultar_empleados.php
    header("Location: /inmobiliaria/crud_empleados/consultar_empleados.php?status=deleted_success");
    exit(); // Detener ejecución después de la redirección

} catch (Exception $e) {
    // Si hubo algún error (preparación, ejecución, etc.), revertir la transacción
    $conn->rollback();

    // Cerrar sentencia si existe y conexión
    if (isset($stmt) && $stmt) {
        $stmt->close();
    }
    $conn->close();

    // Mostrar mensaje de error detallado o redirigir con error
    // die("Error al eliminar el empleado: " . $e->getMessage());

    // O redirigir a la lista con un mensaje de error
     header("Location: /inmobiliaria/crud_empleados/consultar_empleados.php?status=delete_error&msg=" . urlencode($e->getMessage()));
     exit();
}

?>