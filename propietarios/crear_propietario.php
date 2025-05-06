<?php
require '/xampp/htdocs/inmobiliaria/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("INSERT INTO propietarios (tipo_empresa, tipo_doc, num_doc, nombre_propietario, dir_propietario, tel_propietario, email_propietario, contacto_prop, tel_contacto_prop, email_contacto_prop) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssisssssss", $_POST['tipo_empresa'], $_POST['tipo_doc'], $_POST['num_doc'], $_POST['nombre_propietario'], $_POST['dir_propietario'], $_POST['tel_propietario'], $_POST['email_propietario'], $_POST['contacto_prop'], $_POST['tel_contacto_prop'], $_POST['email_contacto_prop']);
    $stmt->execute();
    header("Location: propietarios.php");
}
?>

<h2>Crear Propietario</h2>
<form method="POST">
    Tipo Empresa:
    <select name="tipo_empresa">
        <option value="none"></option>
        <option value="Persona Natural">Persona Natural</option>
        <option value="Jurídica">Jurídica</option>
    </select><br><br>

    Tipo Documento:
    <select name="tipo_doc">
        <option value="none"></option>
        <option value="CC">CC</option>
        <option value="NIT">NIT</option>
        <option value="CE">CE</option>
    </select><br><br>

    Número Documento: <input name="num_doc"><br><br>
    Nombre: <input name="nombre_propietario"><br><br>
    Dirección: <input name="dir_propietario"><br><br>
    Teléfono: <input name="tel_propietario"><br><br>
    Email: <input name="email_propietario"><br><br>
    Contacto Propiedad: <input name="contacto_prop"><br><br>
    Tel. Contacto Propiedad: <input name="tel_contacto_prop"><br><br>
    Email Contacto Propiedad: <input name="email_contacto_prop"><br><br>
    <button type="submit">Guardar</button>
</form>
