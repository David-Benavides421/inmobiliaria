<?php
// Este archivo debe establecer tu conexión a la base de datos y hacer que el objeto $conn esté disponible.
require '../conexion.php';

// Verifica si la conexión a la base de datos fue exitosa
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Verifica si el método de la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $cod_cli = $_POST['cod_cli'] ?? '';
    $fecha_con = $_POST['fecha_con'] ?? '';
    $fecha_ini = $_POST['fecha_ini'] ?? '';
    $fecha_fin = $_POST['fecha_fin'] ?? '';
    $meses = $_POST['meses'] ?? '';
    $valor_con = $_POST['valor_con'] ?? '';
    $deposito_con = $_POST['deposito_con'] ?? '';
    $metodo_pago_con = $_POST['metodo_pago_con'] ?? '';
    $dato_pago = $_POST['dato_pago'] ?? '';

    // --- Manejo de la subida del archivo ---
    $archivo_con = ''; // Inicializamos la variable para el nombre del archivo

    if (isset($_FILES['archivo_con']) && $_FILES['archivo_con']['error'] === UPLOAD_ERR_OK) {
        $nombre_archivo = $_FILES['archivo_con']['name'];
        $archivo_temporal = $_FILES['archivo_con']['tmp_name'];
        $ruta_destino = '/xampp/htdocs/inmobiliaria/archivos_contratos/' . $nombre_archivo; // Ruta donde guardar el archivo

        // Mover el archivo subido a la ubicación deseada
        if (move_uploaded_file($archivo_temporal, $ruta_destino)) {
            $archivo_con = $nombre_archivo; // Guarda solo el nombre del archivo en la base de datos
        } else {
            echo "Error al guardar el archivo.";
            // Considera registrar este error en un log
        }
    } elseif (isset($_FILES['archivo_con']) && $_FILES['archivo_con']['error'] !== UPLOAD_ERR_NO_FILE) {
        // Hubo un error en la subida (diferente a no seleccionar archivo)
        echo "Error al subir el archivo: " . $_FILES['archivo_con']['error'];
        // Considera manejar diferentes códigos de error de subida
    }
    // Si no se subió ningún archivo (UPLOAD_ERR_NO_FILE), $archivo_con permanecerá vacío, lo cual es correcto.

    // --- Validación básica de otros campos ---
    if (empty($cod_cli) || empty($fecha_con) || empty($fecha_ini) || empty($fecha_fin) || empty($valor_con) || empty($metodo_pago_con)) {
        echo "Error: Faltan campos obligatorios.";
        $conn->close();
        exit();
    }

    // --- Sentencia SQL INSERT ---
    $query = "INSERT INTO contratos (cod_cli, fecha_con, fecha_ini, fecha_fin, meses, valor_con, deposito_con, metodo_pago_con, dato_pago, archivo_con)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("isssiiisss", $cod_cli, $fecha_con, $fecha_ini, $fecha_fin, $meses, $valor_con, $deposito_con, $metodo_pago_con, $dato_pago, $archivo_con);

        if ($stmt->execute()) {
            echo "Contrato registrado exitosamente.";
            // Puedes redirigir aquí a una página de éxito
        } else {
            echo "Error al registrar el contrato: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Acceso no válido.";
}
?>