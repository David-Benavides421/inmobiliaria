
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesion</title>
    <style>
        /* Estilos CSS Embebidos para Login */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5; /* Un fondo suave */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .auth-container {
            background-color: #ffffff;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px; /* Ancho máximo del contenedor */
        }

        .auth-container h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
            font-weight: 600;
        }

        .input-group {
            margin-bottom: 15px;
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: 500;
            font-size: 0.9em;
        }

        .input-group input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1em;
            transition: border-color 0.2s ease;
        }

        .input-group input:focus {
            outline: none;
            border-color: #007bff; /* Resalta el borde al enfocar */
        }

        .auth-button {
            width: 100%;
            padding: 12px;
            background-color: #0056b3; /* Azul corporativo */
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.2s ease;
        }

        .auth-button:hover {
            background-color: #004494; /* Azul un poco más oscuro */
        }

        .switch-link {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9em;
        }

        .switch-link a {
            color: #007bff;
            cursor: pointer;
            text-decoration: none;
        }

        .switch-link a:hover {
            text-decoration: underline;
        }
    </style>
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