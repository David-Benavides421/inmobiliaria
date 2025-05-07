<?php
require '/xampp/htdocs/inmobiliaria/conexion.php';

// Consultar todos los empleados con sus cargos y oficinas
$resultado = $conn->query("SELECT e.*, c.nom_cargo, o.nom_ofi 
                           FROM empleados e
                           LEFT JOIN cargos c ON e.cod_cargo = c.cod_cargo
                           LEFT JOIN oficinas o ON e.cod_ofi = o.cod_ofi
                           ORDER BY e.nom_emp");

// Verificar si la consulta falló
if (!$resultado) {
    die("Error en la consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consultar Empleados</title>
    <link rel="stylesheet" href="./style.css"> <!-- Añade tu CSS si lo tienes -->
    <style>
        body { font-family: sans-serif; margin: 20px; }
        .contenedor { max-width: 1200px; margin: auto; } /* Ajusta el ancho si es necesario */
        table {
            width: 100%; /* Ocupa el ancho disponible */
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); /* Sombra suave */
        }
        th, td {
            border: 1px solid #ddd; /* Borde más suave */
            padding: 10px 12px; /* Más padding */
            text-align: left; /* Alineación estándar */
            vertical-align: top; /* Alinear arriba por si hay mucho texto */
        }
        th {
            background-color: #f2f2f2; /* Fondo gris claro para cabeceras */
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9; /* Filas pares con fondo ligero */
        }
        tr:hover {
            background-color: #f1f1f1; /* Resaltar fila al pasar el mouse */
        }
        .acciones a, .acciones button { /* Estilo para enlaces/botones de acción */
            text-decoration: none;
            padding: 5px 10px;
            margin-right: 5px;
            border-radius: 3px;
            color: white;
            font-size: 0.9em;
        }
        .boton-editar { background-color: #ffc107; color: black;} /* Amarillo */
        .boton-eliminar { background-color: #dc3545; } /* Rojo */
        .boton-editar:hover { background-color: #e0a800; }
        .boton-eliminar:hover { background-color: #c82333; }

        h2 { text-align: center; color: #333; }
        .acciones-superiores { margin-bottom: 20px; text-align: right;}
        .boton-crear {
             padding: 10px 15px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block;
        }
         .boton-crear:hover { background-color: #218838; }

    </style>
</head>
<body>

<div class="contenedor">
    <h2>Listado de Empleados</h2>
    <table>
        <thead>
            <tr>
                <th>Cédula</th>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>RH</th>
                <th>Fecha Nac.</th>
                <th>Cargo</th>
                <th>Oficina</th>
                <th>Salario</th>
                <th>Ingreso</th>
                <th>Retiro</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultado->num_rows > 0): ?>
                <?php while ($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <!-- Usar htmlspecialchars para prevenir XSS, aunque sea data interna -->
                    <td><?php echo htmlspecialchars($fila['ced_emp']); ?></td>
                    <td><?php echo htmlspecialchars($fila['nom_emp']); ?></td>
                    <td><?php echo htmlspecialchars($fila['dir_emp']); ?></td>
                    <td><?php echo htmlspecialchars($fila['tel_emp'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($fila['email_emp'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($fila['rh_emp']); ?></td>
                    <td><?php echo htmlspecialchars($fila['fecha_nac']); ?></td>
                    <td><?php echo htmlspecialchars($fila['nom_cargo'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($fila['nom_ofi'] ?? 'N/A'); ?></td>
                    <td><?php echo number_format($fila['salario'], 0, ',', '.'); // Formatear salario ?></td>
                    <td><?php echo htmlspecialchars($fila['fecha_ing']); ?></td>
                    <td><?php echo htmlspecialchars($fila['fecha_ret'] ?? '---'); // Usar '---' si es NULL ?></td>
                    <td class="acciones">
                        <!-- ENLACE EDITAR CORREGIDO -->
                        <a href="/inmobiliaria/crud_empleados/editar_empleados.php?cod_emp=<?php echo htmlspecialchars($fila['cod_emp']); ?>" class="boton-editar">Editar</a>
                        
                        <!-- SUGERENCIA: Usar cod_emp también para eliminar si es posible -->
                        <a href="/inmobiliaria/crud_empleados/eliminar_empleados.php?cod_emp=<?php echo htmlspecialchars($fila['cod_emp']); ?>" class="boton-eliminar" onclick="return confirm('¿Estás seguro de eliminar a <?php echo htmlspecialchars(addslashes($fila['nom_emp'])); ?>?')">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="13" style="text-align:center;">No se encontraron empleados registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<div class="acciones-superiores">
        <a href="/inmobiliaria/crud_empleados/empleados.php" class="boton boton-crear">Registrar Nuevo Empleado</a> <!-- Asumiendo que tienes una página para registrar -->
    </div>
</body>
</html>

<?php
$conn->close();
?>