<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Contratos</title>
    <link rel="stylesheet" href="./style.css">
    <style>
        body { font-family: sans-serif; margin: 20px; }
        form { max-width: 800px; margin: auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; }
        fieldset { margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        legend { font-weight: bold; }
        label { display: block; margin-top: 10px; }
        input, select, button { width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box; }

        /* Botones juntos al final */
        .botones-finales {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-top: 20px;
        }

        .botones-finales button,
        .botones-finales input[type="submit"] {
            width: 100%;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        .botones-finales button:hover,
        .botones-finales input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<form action="/inmobiliaria/crud_contratos/insertar_contratos.php" method="POST" enctype="multipart/form-data">
    <fieldset>
        <legend>Datos del Contrato</legend>

        <label>Cliente:</label>
        <select name="cod_cli" required>
            <option value="">-- Seleccione --</option>
            <?php
                require '/xampp/htdocs/inmobiliaria/conexion.php';
                $clientes = $conn->query("SELECT cod_cli, nom_cli FROM clientes ORDER BY nom_cli");
                while ($row = $clientes->fetch_assoc()) {
                    echo "<option value='{$row["cod_cli"]}'>" . htmlspecialchars($row["nom_cli"]) . "</option>";
                }
            ?>
        </select>

        <label>Fecha del contrato:</label>
        <input type="date" name="fecha_con" required>

        <label>Fecha de inicio:</label>
        <input type="date" name="fecha_ini" required>

        <label>Fecha de fin:</label>
        <input type="date" name="fecha_fin" required>

        <label>Meses:</label>
        <input type="number" name="meses" min="1" required>

        <label>Valor del contrato:</label>
        <input type="text" name="valor_con" required>

        <label>Depósito:</label>
        <input type="text" name="deposito_con" required>

        <label>Método de pago:</label>
        <select name="metodo_pago_con" required>
            <option value="">-- Seleccione --</option>
            <option value="efectivo">Efectivo</option>
            <option value="transferencia">Transferencia</option>
        </select>

        <label>Dato del pago:</label>
        <input type="text" name="dato_pago" maxlength="20">

        <label>Archivo del contrato (PDF):</label>
        <input type="file" name="archivo_con" accept=".pdf">

        <div class="botones-finales">
            <input type="submit" value="Guardar Contrato">
            <button type="button" onclick="location.href='/inmobiliaria/crud_contratos/consultar_contratos.php'">Consultar Contrato</button>
        </div>

    </fieldset>
</form>

<br><button onclick="window.history.back();" style="padding:10px 20px; background-color:#1976d2; color:white; border:none; border-radius:5px; cursor:pointer;">Volver atrás</button><br>
</body>
</html>