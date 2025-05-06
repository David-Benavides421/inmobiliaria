<?php
include '../conexion.php';

//Recibir Datos
$direccion = $_POST['di_inm'];
$barrio = $_POST['barrio_inm'];
$ciudad = $_POST['ciudad_inm'];
$departamento = $_POST['departamento_inm'];
$latitud = $_POST['latitud'];
$longitud = $_POST['longitud'];
$web_p1 = $_POST['web_p1'];
$web_p2 = $_POST['web_p2'];
$cod_tipoinm = $_POST['cod_tipoinm'];
$num_hab = $_POST['num_hab'];
$precio_alq = $_POST['precio_alq'];
$cod_propietario = intval($_POST['cod_propietarios']);
$caracteristica_inm = $_POST['caracteristica_inm'];
$notas_inm = $_POST['notas_inm'];
$cod_emp = $_POST['cod_emp'];
$cod_ofi = $_POST['cod_ofi'];

//Manejo de foto
$foto = null;
if (!empty($_FILES['foto']['name'])) {
    $foto = "uploads/" . basename($_FILES['foto']['name']);
    move_uploaded_file($_FILES['foto']['tmp_name'], $foto);
}

//Insertar en la base de datos
$sql = "INSERT INTO inmuebles (di_inm, barrio_inm, ciudad_inm, departamento_inm,  latitud, longitud, foto, web_p1, web_p2, cod_tipoinm, num_hab, precio_alq, cod_propietarios, caracteristicas_inm, notas_inm, cod_emp, cod_ofi) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn -> prepare($sql);
$stmt -> bind_param("ssssddsssiiiissii", $direccion, $barrio, $ciudad, $departamento, $latitud, $longitud, $foto, $web_p1, $web_p2, $cod_tipoinm, $num_hab, $precio_alq, $cod_propietario, $caracteristica_inm, $notas_inm, $cod_emp, $cod_ofi);

if ($conn->query($sql) === TRUE) {
    echo "<script>
        alert('Inmueble registrado correctamente');
        alert('Volviendo al formulario');
        window.location.href = 'inmueble_crud.php';
    </script>";
} else {
    echo "<script>
        alert('Error al eliminar: " . addslashes($conn->error) . "');
        window.history.back();
    </script>";
}

$stmt -> close();
$conn -> close();
?>