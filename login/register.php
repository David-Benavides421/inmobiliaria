<?php
require '/xampp/htdocs/inmobiliaria/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_2 = $_POST['password_2'];
    //$rol = 'usuario'; 

    if ($password !== $password_2) {
        die("Las contraseñas no coinciden.");
    }

    $sql = "INSERT INTO usuarios (EMAIL, CONTRASEÑA)
    VALUES ('$email', '$password')";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: /inmobiliaria/login/login.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/inmobiliaria/styles.css">
    <title>registro</title>
</head>
<body>
    <h1>registro</h1>

    <form method="POST" action="/inmobiliaria/login/register.php">

    <label>Email</label><br>
    <input type="email" name="email" required><br>

    <label>Contraseña</label><br>
    <input type="password" name="password" required><br>

    <label>Confirmar contraseña</label><br>
    <input type="password" name="password_2" required><br>

    <input type="submit" value="Registrate"><br><br>

    </form>

    <a href="/inmobiliaria/login/login.php">¿Ya tienes cuenta? Inicia sesion</a>
</body>
</html>