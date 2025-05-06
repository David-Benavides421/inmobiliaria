<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Oficinas</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link rel="stylesheet" href="oficinas.css">
</head>
<body>
    <form action="guardar_oficinas.php" method="post" enctype="multipart/form-data">
        <h2>Registro de Oficinas</h2>
        <label for="nombre">Nombre de la oficina:</label><br>
        <input type="text" id="nombre" name="nombre" required><br><br>
        <label for="direccion">Dirección:</label><br>
        <input type="text" id="direccion" name="direccion" required><br><br>

        <label>Ubicación (Latitud y Longitud):</label>
        <input type="text" id="latitud" name="latitud" readonly />
        <input type="text" id="longitud" name="longitud" readonly />

        <div id="map"></div>
        
        <label for="foto">Foto del Almacén:</label>
        <input type="file" id="foto" name="foto" accept="image/*"/><br>
        
        <label for="telefono">Teléfono:</label><br>
        <input type="text" id="telefono" name="telefono" required><br><br>
        <label for="email">Email</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <button type="submit">Guardar Oficinas</button>
        <button type="button" onclick="window.location.href='/inmobiliaria/oficinas/consultar_oficinas.php'">Consultar Oficinas</button>
    </form>
    <script>
        //Iniciar el mapa
        const map= L.map('map').setView([4.6097, -74.0817],13); //Coordenadas de Bogotá, Colombia

        //Agregar Capa De OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { 
          attribution: '&copy; OpenStreetMap contributors'            
        }).addTo(map);

        //Agregar Marcador
        const marker = L.marker([4.6097, -74.0817], { draggable: true}).addTo(map);

        //Actualizar Campos De Latitud y Longitud
        function updateLatLng() {
            const position = marker.getLatLng();
            document.getElementById('latitud').value = position.lat.toFixed(6);
            document.getElementById('longitud').value = position.lng.toFixed(6);
        }
        updateLatLng();

        marker.on('dragend', updateLatLng);
        
        //Obtener Ubicaión Del Usuario
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) =>{
                const { latitude, longitude } = position.coords;
                marker.setLatLng([latitude, longitude]);
                map.setView([latitude, longitude], 13);
                updateLatLng();
            });
        }
    </script>
</body>
</html>