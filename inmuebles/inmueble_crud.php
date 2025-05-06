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
    <link rel="stylesheet" href="../estilos.css">
    <style>
        #map { height: 400px; width: 100%; }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        label { display: block; margin-top: 10px;}
        input, select, button { width: 100%; padding: 10px; margin-top: 5px; }
    </style>
</head>
<body>
 
    <form action="guardar_inmueble.php" method="post" enctype="multipart/form-data">
    <h2>Registro de inmueble</h2>
        <label for="di_inm">Dirección del Inmueble:</label>
        <input type="text" id="di_inm" name="di_inm" required />

        <label for="barrio_inm">Barrio:</label>
        <input type="text" id="barrio_inm" name="barrio_inm" required />

        <label for="ciudad_inm">Ciudad:</label>
        <input type="text" id="ciudad_inm" name="ciudad_inm" required />

        <label for="departamento_inm">Departamento:</label>
        <input type="text" id="departamento_inm" name="departamento_inm" required />

        <label>Ubicación (Latitud y Longitud):</label>
        <input type="text" id="latitud" name="latitud" readonly />
        <input type="text" id="longitud" name="longitud" readonly />

        <div id="map"></div>

        <label for="foto">Foto del Inmueble:</label>
        <input type="file" id="foto" name="foto" />

        <label for="web_p1">web 1:</label>
        <input type="text" id="web_p1" name="web_p1" />

        <label for="web_p2">web 2:</label>
        <input type="text" id="web_p2" name="web_p2" />

        <label>Tipo de inmueble:</label>
        <select name="cod_tipoinm" required>
            <option value="">Seleccione un tipo de inmueble</option>
            <?php
                // Obtener tipos de inmueble
                $sqlCat = "SELECT cod_tipoinm, nom_tipoinm FROM tipo_inmueble";
                $resultCat = $conn->query($sqlCat);
                while ($rowCat = $resultCat->fetch_assoc()){
                    echo "<option value='".$rowCat['cod_tipoinm']."'>".$rowCat['nom_tipoinm']."</option>";
                }
            ?>
        </select>

        <label for="num_hab">Número de habitaciones:</label>
        <input type="text" id="num_hab" name="num_hab" required />

        <label for="precio_alq">Precio mensual del alquiler:</label>
        <input type="text" id="precio_alq" name="precio_alq" required />

        <label>Nombre del propietario:</label>
        <select name="cod_propietarios" required>
                <option value="">Seleccione el código del propietario</option>
            <?php

                //Obtener codigo del propietario
                    $sql = "SELECT * FROM propietarios";

                $sqlCat = "SELECT * FROM propietarios";
                $resultCat = $conn->query($sqlCat);
                while ($rowCat = $resultCat->fetch_assoc()){
                    echo "<option value='".$rowCat['cod_propietarios']."'>".$rowCat['nomb_propietario']."</option>";
                }
            ?>
        </select>

        <label>Características del inmueble:</label>
            <select name="caracteristica_inm" required>
                <option value="conjunto">Conjunto</option>
                <option value="urbanizacion">Urbanización</option>
            </select>

        <label>Notas del Inmueble:</label>
        <textarea name="notas_inm"></textarea>

        <label>Nombre del empleado:</label>
        <select name="cod_emp" required>
                <option value="">Seleccione el código del empleado</option>
            <?php
            //Obtener codigo del empleado
                $sql = "SELECT * FROM empleados";

            $sqlCat = "SELECT * FROM empleados";
            $resultCat = $conn->query($sqlCat);
            while ($rowCat = $resultCat->fetch_assoc()){
                echo "<option value='".$rowCat['cod_emp']."'>".$rowCat['nom_emp']."</option>";
            }
            ?>
        </select>

        <label>Código de la Oficina:</label>
        <select name="cod_ofi" required>
                <option value="">Seleccione el código de la oficina</option>
            <?php
            //Obtener codigo dela oficina
                $sql = "SELECT o.cod_ofi
                FROM oficinas o
                JOIN inmuebles i ON o.cod_ofi = o.cod_ofi";
                $result = $conn->query($sql);

            $sqlCat = "SELECT * FROM oficinas";
            $resultCat = $conn->query($sqlCat);
            while ($rowCat = $resultCat->fetch_assoc()){
                echo "<option value='".$rowCat['cod_ofi']."'>".$rowCat['nom_ofi']."</option>";
            }
            ?>
        </select>

        <button type="submit">Guardar Inmueble</button>
        <input type="button" value="Consultar" onclick="location.href='consultar_inmueble.php'">
    </form>

   

    <script>
        //Inicializar el mapa
        const map = L.map('map').setView([4.6097, -74.0817], 13); //Coordenadas de Bogota, Colombia
        
        //Agregar capa de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        //Agregar marcador
        const marker = L.marker([4.6097, -74.0817], { draggable: true }).addTo(map);

        //Actualizar campos de latitud y longitud
        function updateLatLng() {
            const position = marker.getLatLng();
            document.getElementById('latitud').value = position.lat.toFixed(6);
            document.getElementById('longitud').value = position.lng.toFixed(6);
        }
        updateLatLng();

        marker.on('dragend', updateLatLng);

        //Obtener ubicacion del usuario
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