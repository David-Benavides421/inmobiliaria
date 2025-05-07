<?php
include '../conexion.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Inmuebles</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link rel="stylesheet" href="inmuebles.css">
    <style>
        #map { height: 400px; width: 100%; }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        label { display: block; margin-top: 10px;}
        input, select, button, textarea { width: 100%; padding: 10px; margin-top: 5px; }
        textarea { height: 100px; }
    </style>
</head>
<body>
    <form action="guardar_inmueble.php" method="post" enctype="multipart/form-data">
        <h2>Registro de inmueble</h2>
        
        <!-- Campo oculto para modo edición (si es necesario) -->
        <input type="hidden" id="cod_inm" name="cod_inm" value="">
        
        <label for="dir_inm">Dirección del Inmueble:</label>
        <input type="text" id="dir_inm" name="dir_inm" required />

        <label for="barrio_inm">Barrio:</label>
        <input type="text" id="barrio_inm" name="barrio_inm" required />

        <label for="ciudad_inm">Ciudad:</label>
        <input type="text" id="ciudad_inm" name="ciudad_inm" required />

        <label for="departamento_inm">Departamento:</label>
        <input type="text" id="departamento_inm" name="departamento_inm" required />

        <label>Ubicación (Latitud y Longitud):</label>
        <input type="text" id="latitud" name="latitud" placeholder="Latitud" readonly />
        <input type="text" id="longitud" name="longitud" placeholder="Longitud" readonly />

        <div id="map"></div>

        <label for="foto">Foto del Inmueble:</label>
        <input type="file" id="foto" name="foto" accept="image/*" />

        <label for="web_p1">Enlace Web 1:</label>
        <input type="url" id="web_p1" name="web_p1" placeholder="https://..." />

        <label for="web_p2">Enlace Web 2:</label>
        <input type="url" id="web_p2" name="web_p2" placeholder="https://..." />

        <label for="cod_tipoinm">Tipo de inmueble:</label>
        <select id="cod_tipoinm" name="cod_tipoinm" required>
            <option value="">Seleccione un tipo de inmueble</option>
            <?php
                $sql_tipo_inm = "SELECT cod_tipoinm, nom_tipoinm FROM tipo_inmueble";
                $result_tipo_inm = $conn->query($sql_tipo_inm);
                while ($row_tipo_inm = $result_tipo_inm->fetch_assoc()){
                    echo "<option value='".$row_tipo_inm['cod_tipoinm']."'>".$row_tipo_inm['nom_tipoinm']."</option>";
                }
            ?>
        </select>

        <label for="num_hab">Número de habitaciones:</label>
        <input type="number" id="num_hab" name="num_hab" min="0" required />

        <label for="precio_alq">Precio mensual del alquiler:</label>
        <input type="number" id="precio_alq" name="precio_alq" min="0" required />

        <label for="cod_propietario">Propietario:</label>
        <select id="cod_propietario" name="cod_propietario" required>
            <option value="">Seleccione el propietario</option>
            <?php
                $sql_prop = "SELECT cod_propietario, nombre_propietario FROM propietarios";
                $result_prop = $conn->query($sql_prop);
                while ($row_prop = $result_prop->fetch_assoc()){
                    echo "<option value='".$row_prop['cod_propietario']."'>".$row_prop['nombre_propietario']."</option>";
                }
            ?>
        </select>

        <label for="caracteristica_inm">Características del inmueble:</label>
        <select id="caracteristica_inm" name="caracteristica_inm" required>
            <option value="conjunto">Conjunto</option>
            <option value="Urbanizacion">Urbanización</option>
        </select>

        <label for="notas_inm">Notas del Inmueble:</label>
        <textarea id="notas_inm" name="notas_inm"></textarea>

        <label for="cod_emp">Empleado responsable:</label>
        <select id="cod_emp" name="cod_emp" required>
            <option value="">Seleccione el empleado</option>
            <?php
                $sql_emp = "SELECT cod_emp, nom_emp FROM empleados";
                $result_emp = $conn->query($sql_emp);
                while ($row_emp = $result_emp->fetch_assoc()){
                    echo "<option value='".$row_emp['cod_emp']."'>".$row_emp['nom_emp']."</option>";
                }
            ?>
        </select>

        <label for="cod_ofi">Oficina:</label>
        <select id="cod_ofi" name="cod_ofi" required>
            <option value="">Seleccione la oficina</option>
            <?php
                $sql_ofi = "SELECT cod_ofi, nom_ofi FROM oficinas";
                $result_ofi = $conn->query($sql_ofi);
                while ($row_ofi = $result_ofi->fetch_assoc()){
                    echo "<option value='".$row_ofi['cod_ofi']."'>".$row_ofi['nom_ofi']."</option>";
                }
            ?>
        </select>

        <button type="submit">Guardar Inmueble</button>
        <input type="button" value="Consultar" onclick="location.href='consultar_inmueble.php'">
    </form>

    <script>
        // Inicializar el mapa
        const map = L.map('map').setView([4.6097, -74.0817], 13); // Coordenadas de Bogotá, Colombia
        
        // Agregar capa de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Agregar marcador
        const marker = L.marker([4.6097, -74.0817], { draggable: true }).addTo(map);

        // Actualizar campos de latitud y longitud
        function updateLatLng() {
            const position = marker.getLatLng();
            document.getElementById('latitud').value = position.lat.toFixed(6);
            document.getElementById('longitud').value = position.lng.toFixed(6);
        }
        updateLatLng();

        marker.on('dragend', updateLatLng);

        // Obtener ubicación del usuario
        if (navigator.geolocation){
            navigator.geolocation.getCurrentPosition((position) => {
                const { latitude, longitude } = position.coords;
                marker.setLatLng([ latitude, longitude]);
                map.setView([latitude, longitude], 13);
                updateLatLng();
            });
        }
    </script>
</body>
</html>

<br><button onclick="window.history.back();" style="padding:10px 20px; background-color:#1976d2; color:white; border:none; border-radius:5px; cursor:pointer;">Volver atrás</button><br>