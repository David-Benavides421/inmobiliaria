<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consulta de Contratos</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .acciones { text-align: center; }
        .acciones a { margin: 0 5px; text-decoration: none; padding: 5px 10px; border-radius: 4px; }
        .editar { background-color: #007bff; color: white; }
        .eliminar { background-color: #dc3545; color: white; }
        .mensaje { margin-top: 15px; font-weight: bold; text-align: center; }
        .exito { color: green; }
        .error { color: red; }
    </style>
</head>
<body>

<h2>Listado de Contratos</h2>

<?php
require '/xampp/htdocs/inmobiliaria/conexion.php';

if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Mostrar mensajes si existen
if (isset($_GET['mensaje'])) {
    $mensaje = $_GET['mensaje'];
    echo "<p class='mensaje " . (strpos($mensaje, 'exito') !== false ? 'exito' : 'error') . "'>" . htmlspecialchars(str_replace('_', ' ', $mensaje)) . "</p>";
}

$sql = "SELECT
            c.cod_con,
            cl.nom_cli AS nombre_cliente,
            c.fecha_con,
            c.fecha_ini,
            c.fecha_fin,
            c.meses,
            c.valor_con,
            c.deposito_con,
            c.metodo_pago_con,
            c.dato_pago,
            c.archivo_con
        FROM contratos c
        INNER JOIN clientes cl ON c.cod_cli = cl.cod_cli
        ORDER BY c.fecha_con DESC"; // Puedes ajustar el ORDER BY según tus preferencias

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Código</th>";
    echo "<th>Cliente</th>";
    echo "<th>Fecha Contrato</th>";
    echo "<th>Fecha Inicio</th>";
    echo "<th>Fecha Fin</th>";
    echo "<th>Meses</th>";
    echo "<th>Valor</th>";
    echo "<th>Depósito</th>";
    echo "<th>Método Pago</th>";
    echo "<th>Dato Pago</th>";
    echo "<th>Archivo</th>";
    echo "<th class='acciones'>Acciones</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row["cod_con"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["nombre_cliente"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["fecha_con"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["fecha_ini"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["fecha_fin"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["meses"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["valor_con"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["deposito_con"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["metodo_pago_con"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["dato_pago"]) . "</td>";
        echo "<td>";
        if (!empty($row["archivo_con"])) {
            $ruta_base_archivos = '/xampp/htdocs/inmobiliaria/archivos_contratos/';
            $ruta_completa = $ruta_base_archivos . htmlspecialchars($row["archivo_con"]);
            echo "<a href='" . $ruta_completa . "' target='_blank'>Ver Archivo</a>";
            echo "<br>";
            echo "<strong>Ruta generada:</strong> " . htmlspecialchars($ruta_completa);
        } else {
            echo "No disponible";
        }
        echo "</td>";
        echo "<td class='acciones'>";
        echo "<a href='actualizar_contratos.php?cod_con=" . htmlspecialchars($row["cod_con"]) . "' class='editar'>Editar</a>";
        echo "<a href='eliminar_contratos.php?cod_con=" . htmlspecialchars($row["cod_con"]) . "' class='eliminar' onclick='return confirm(\"¿Estás seguro de eliminar este contrato?\")'>Eliminar</a>";
        echo "</td>";
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
} else {
    echo "<p>No se encontraron contratos registrados.</p>";
}

$conn->close();
?>

<p><a href="/inmobiliaria/crud_contratos/contratos.php">Volver al formulario de registro</a></p>

</body>
</html>