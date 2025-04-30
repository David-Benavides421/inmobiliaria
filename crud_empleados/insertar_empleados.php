<?php
// Este archivo debe establecer tu conexión a la base de datos y hacer que el objeto $conn esté disponible.
// Asegúrate de que se conecta a la base de datos correcta ('inmobiliaria' según tus mensajes de error).
// Verifica que los detalles de conexión (servidor, usuario, contraseña, nombre de la base de datos) en conexion.php sean correctos.
require '/xampp/htdocs/inmobiliaria/conexion.php';

// Verifica si la conexión a la base de datos fue exitosa
if ($conn->connect_error) {
    // Maneja los errores de conexión explícitamente
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Verifica si el método de la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // --- Recupera los datos de la solicitud POST de forma segura ---
    // Usamos ?? '' para evitar advertencias si alguna clave de $_POST no existe.
    // Asegúrate de que los atributos 'name' en tu formulario HTML coincidan exactamente con estas claves.
    $ced_emp = $_POST['ced_emp'] ?? '';
    $tipo_doc = $_POST['tipo_doc'] ?? ''; // Nombre de columna/variable confirmado
    $nom_emp = $_POST['nom_emp'] ?? '';
    $dir_emp = $_POST['dir_emp'] ?? '';
    $tel_emp = $_POST['tel_emp'] ?? '';
    $email_emp = $_POST['email_emp'] ?? '';
    $rh_emp = $_POST['rh_emp'] ?? '';
    $fecha_nac = $_POST['fecha_nac'] ?? '';

    $cod_cargo = $_POST['cod_cargo'] ?? '';
    $cod_ofi = $_POST['cod_ofi'] ?? '';
    $salario = $_POST['salario'] ?? '';
    $gastos = $_POST['gastos'] ?? '';
    $comision = $_POST['comision'] ?? '';
    $fecha_ing = $_POST['fecha_ing'] ?? '';
    $fecha_ret = $_POST['fecha_ret'] ?? '';

    $nom_contacto = $_POST['nom_contacto'] ?? '';
    $dir_contacto = $_POST['dir_contacto'] ?? '';
    $tel_contacto = $_POST['tel_contacto'] ?? '';
    $email_contacto = $_POST['email_contacto'] ?? '';
    $relacion_contacto = $_POST['relacion_contacto'] ?? '';

    // --- Validación básica para campos requeridos (al inicio) ---
    // Esto evita intentar procesar datos incompletos y detiene la ejecución temprano.
    // Agrega o elimina campos de esta validación según cuáles sean obligatorios en tu formulario.
    if (empty($ced_emp) || empty($tipo_doc) || empty($nom_emp) || empty($dir_emp) || empty($fecha_nac) || empty($cod_cargo) || empty($cod_ofi) || empty($fecha_ing)) {
        echo "Error: Faltan campos obligatorios. Revise que todos los campos marcados como requeridos estén llenos.";
        $conn->close(); // Cierra la conexión antes de salir
        exit(); // Detiene la ejecución si la validación falla
    }

    // Opcional: Validar que los campos numéricos sean realmente números si son obligatorios
    // if (!is_numeric($salario) || !is_numeric($gastos) || !is_numeric($comision)) {
    //     echo "Error: Salario, Gastos y Comisión deben ser valores numéricos.";
    //     $conn->close();
    //     exit();
    // }


    // --- Sentencia SQL INSERT usando prepared statements ---
    // Nombre de tabla confirmado como 'empleados'
    // Listando las 20 columnas a insertar - ASEGÚRATE DE QUE ESTOS NOMBRES COINCIDAN EXACTAMENTE CON TUS COLUMNAS EN LA BASE DE DATOS
    // Proporcionando exactamente 20 placeholders (?) para los valores - ASEGÚRATE DE QUE ESTE CONTEO SEA EXACTAMENTE 20
    $query = "INSERT INTO empleados (
                ced_emp, tipo_doc, nom_emp, dir_emp, tel_emp, email_emp, rh_emp, fecha_nac,
                cod_cargo, cod_ofi, salario, gastos, comision, fecha_ing, fecha_ret,
                nom_contacto, dir_contacto, tel_contacto, email_contacto, relacion_contacto
              )
              VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
              )"; // <--- Cuenta estos signos de interrogación: DEBEN SER EXACTAMENTE 20

    // Prepara la consulta SQL
    if ($stmt = $conn->prepare($query)) {

 
        $stmt->bind_param("isssssssiiiiisssssss", // <--- CUENTA LOS CARACTERES EN ESTA CADENA: DEBEN SER EXACTAMENTE 20
            $ced_emp,           // i - INT (asumiendo que ced_emp es INT como lo era cod_emp)
            $tipo_doc,          // s - STRING (ENUM o VARCHAR)
            $nom_emp,           // s - STRING (VARCHAR)
            $dir_emp,           // s - STRING (VARCHAR)
            $tel_emp,           // s - STRING (VARCHAR)
            $email_emp,         // s - STRING (VARCHAR)
            $rh_emp,            // s - STRING (VARCHAR)
            $fecha_nac,         // s - STRING (DATE se enlaza como string 'YYYY-MM-DD')
            $cod_cargo,         // i - INT
            $cod_ofi,           // i - INT
            $salario,           // i - INT
            $gastos,            // i - INT
            $comision,          // i - INT
            $fecha_ing,         // s - STRING (DATE se enlaza como string 'YYYY-MM-DD')
            $fecha_ret,         // s - STRING (DATE se enlaza como string 'YYYY-MM-DD')
            $nom_contacto,      // s - STRING (VARCHAR)
            $dir_contacto,      // s - STRING (VARCHAR)
            $tel_contacto,      // s - STRING (VARCHAR)
            $email_contacto,    // s - STRING (VARCHAR)
            $relacion_contacto  // s - STRING (VARCHAR)
        ); // <--- CUENTA LAS VARIABLES LISTADAS AQUÍ: DEBEN SER EXACTAMENTE 20

        // Ejecuta la sentencia preparada
        if ($stmt->execute()) {
            echo "Empleado registrado exitosamente.";
            // Considera redirigir a una página de éxito después de la inserción:
            // header("Location: /inmobiliaria/success.php");
            // exit(); // Siempre sal después de una redirección con header
        } else {
            // Muestra errores de ejecución específicos de la base de datos
            echo "Error al registrar el empleado: " . $stmt->error;
        }

        // Cierra la sentencia
        $stmt->close();
    } else {
        // Muestra errores de preparación si la consulta SQL en sí está mal formada
        echo "Error en la preparación de la consulta: " . $conn->error;
    }

    // Cierra la conexión a la base de datos
    $conn->close();
} else {
    // Maneja los casos en que el script no se accede a través de POST (ej: alguien escribe la URL directamente)
    echo "Acceso no válido. Este script solo procesa formularios enviados por POST.";
}
?>