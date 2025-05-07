<?php
require '/xampp/htdocs/inmobiliaria/conexion.php';

$id = $_GET['id'];
$row = $conn->query("SELECT * FROM propietarios WHERE cod_propietario=$id")->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("UPDATE propietarios SET tipo_empresa=?, tipo_doc=?, num_doc=?, nombre_propietario=?, dir_propietario=?, tel_propietario=?, email_propietario=?, contacto_prop=?, tel_contacto_prop=?, email_contacto_prop=? WHERE cod_propietario=?");
    $stmt->bind_param("ssisssssssi", $_POST['tipo_empresa'], $_POST['tipo_doc'], $_POST['num_doc'], $_POST['nombre_propietario'], $_POST['dir_propietario'], $_POST['tel_propietario'], $_POST['email_propietario'], $_POST['contacto_prop'], $_POST['tel_contacto_prop'], $_POST['email_contacto_prop'], $id);
    $stmt->execute();
    header("Location: propietarios.php");
}
?>

<h2>Editar Propietario</h2>
<form method="POST">
    Tipo Empresa:
    <select name="tipo_empresa">
        <option <?= $row['tipo_empresa'] == 'Persona Natural' ? 'selected' : '' ?>>Persona Natural</option>
        <option <?= $row['tipo_empresa'] == 'Jurídica' ? 'selected' : '' ?>>Jurídica</option>
    </select><br><br>

    Tipo Documento:
    
    <select name="tipo_doc">
        <option <?= $row['tipo_doc'] == 'CC' ? 'selected' : '' ?>>CC</option>
        <option <?= $row['tipo_doc'] == 'NIT' ? 'selected' : '' ?>>NIT</option>
        <option <?= $row['tipo_doc'] == 'CE' ? 'selected' : '' ?>>CE</option>
    </select><br><br>

    Número Documento: <input name="num_doc" value="<?= $row['num_doc'] ?>"><br><br>
    Nombre: <input name="nombre_propietario" value="<?= $row['nombre_propietario'] ?>"><br><br>
    Dirección: <input name="dir_propietario" value="<?= $row['dir_propietario'] ?>"><br><br>
    Teléfono: <input name="tel_propietario" value="<?= $row['tel_propietario'] ?>"><br><br>
    Email: <input name="email_propietario" value="<?= $row['email_propietario'] ?>"><br><br>
    Contacto Propiedad: <input name="contacto_prop" value="<?= $row['contacto_prop'] ?>"><br><br>
    Tel. Contacto Propiedad: <input name="tel_contacto_prop" value="<?= $row['tel_contacto_prop'] ?>"><br><br>
    Email Contacto Propiedad: <input name="email_contacto_prop" value="<?= $row['email_contacto_prop'] ?>"><br><br>
    <button type="submit">Actualizar</button>
</form>
