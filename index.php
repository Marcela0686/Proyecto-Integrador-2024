<?php
session_start();

// Arreglo de usuarios predeterminados
$usuarios = array(
    "admin@example.com" => array("password" => 'admin123', "nombre" => 'Administrador', "rol" => 'admin')
);

// Inicializar sesión de usuarios y archivos si no existen
if (!isset($_SESSION['usuarios'])) {
    $_SESSION['usuarios'] = $usuarios;
}

if (!isset($_SESSION['archivosSubidos'])) {
    $_SESSION['archivosSubidos'] = array();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['email'];
    $pass = $_POST['password'];
    
    // Login
    if (isset($_POST['login'])) {
        if (isset($_SESSION['usuarios'][$user]) && $_SESSION['usuarios'][$user]['password'] === $pass) {
            $_SESSION['userValido'] = true;
            $_SESSION['user'] = $user;
            $_SESSION['nombre'] = $_SESSION['usuarios'][$user]['nombre'];
            $_SESSION['rol'] = $_SESSION['usuarios'][$user]['rol'];
            header("Location: revela.php");
            exit();
        } else {
            echo '<p>Correo o contraseña incorrectos.</p>';
        }
    }

    // Registro de usuario
    if (isset($_POST['register'])) {
        $newNombre = $_POST['nombre'];
        if (!isset($_SESSION['usuarios'][$user])) {
            $_SESSION['usuarios'][$user] = array("password" => $pass, "nombre" => $newNombre, "rol" => 'usuario');
            echo '<p>Usuario registrado correctamente.</p>';
        } else {
            echo '<p>El correo electrónico ya está registrado.</p>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de usuarios</title>
    <style>
        /* Reset de estilos básicos */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Estilo para el cuerpo */
        body {
            font-family: Arial, sans-serif;
            background-color: #111;
            color: #f1f1f1;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        /* Contenedor principal */
        .formLogin {
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 400px;
        }

        .formLogin h2 {
            color: #f1f1f1;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .barra {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #444;
            background-color: #222;
            color: #f1f1f1;
            font-size: 16px;
        }

        .barra:focus {
            border-color: #6a8f29; /* Verde militar */
            outline: none;
        }

        .boton {
            background-color: #6a8f29; /* Verde militar */
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            margin-top: 10px;
        }

        .boton:hover {
            background-color: #4f6f1d;
        }

        /* Estilo para los mensajes */
        p {
            color: red;
            margin-top: 10px;
        }

    </style>
</head>
<body>

    <form class="formLogin" action="index.php" method="post" enctype="multipart/form-data">
        <h2>Inicio de sesión</h2>
        <label for="email">Correo Electrónico:</label>
        <input class="barra" type="email" id="email" name="email" required>
        <br>
        <label for="password">Contraseña:</label>
        <input class="barra" type="password" name="password" id="password" required>
        <br>
        <label for="nombre">Nombre (solo para nuevos usuarios):</label>
        <input class="barra" type="text" name="nombre">
        <br><br>
        <input class="boton" type="submit" name="login" value="Iniciar Sesión">
        <input class="boton" type="submit" name="register" value="Registrar Usuario">
    </form>

</body>
</html>
