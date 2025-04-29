
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesion</title>
    <link rel="stylesheet" href="/inmobiliaria/styles.css">
</head>
<body>
    <h1>Iniciar sesion</h1>

    <form method="POST" action="/inmobiliaria/login/login.php">

    <label>Email</label><br>
    <input type="email" name="email" required><br>

    <label>Contraseña</label><br>
    <input type="password" name="password" required><br><br>

    <input type="submit" value="Ingresa"><br><br>

    </form>

    <a href="/inmobiliaria/login/register.php">¿No tienes usuario? Registrate</a>
</body>
</html>


<?php
require '/xampp/htdocs/inmobiliaria/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Buscar usuario en la base de datos (sin incluir CATEGORIA)
    $sql = "SELECT CONTRASEÑA FROM usuarios WHERE EMAIL = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($password_db);
        $stmt->fetch();

        // Verificar la contraseña
        if ($password === $password_db) {
            session_start();
            $_SESSION['email'] = $email;
            header("Location: /inmobiliaria/dashboard.php"); // Redirige al usuario
            exit();
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Usuario no encontrado.";
    }

    $stmt->close();
    $conn->close();
}
?>