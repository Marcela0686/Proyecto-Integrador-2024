<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Archivo</title>
    <link rel="stylesheet" href="sesion_cookie.css">
</head>
<body>

<?php
    if (isset($_SESSION['userValido'])) {
        // Verifica si el formulario fue enviado
        if (isset($_POST['submit'])) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["archivo"]["name"]);
            $uploadOk = 1;
            $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Validaciones de archivo
            if (file_exists($target_file)) {
                echo "<p>El archivo ya existe.</p>";
                $uploadOk = 0;
            }

            if ($_FILES["archivo"]["size"] > 500000) {
                echo "<p>Archivo demasiado grande.</p>";
                $uploadOk = 0;
            }

            if ($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg" && $fileType != "gif") {
                echo "<p>Solo se permiten archivos JPG, JPEG, PNG y GIF.</p>";
                $uploadOk = 0;
            }

            if ($uploadOk == 1) {
                if (move_uploaded_file($_FILES["archivo"]["tmp_name"], $target_file)) {
                    echo "<p>El archivo ". htmlspecialchars(basename($_FILES["archivo"]["name"])) . " ha sido subido.</p>";
                    
                    // Guardar el archivo subido en la sesión para mostrarlo después
                    $_SESSION['archivosSubidos'][] = basename($_FILES["archivo"]["name"]);
                } else {
                    echo "<p>Error al subir el archivo.</p>";
                }
            }
        }
    } else {
        echo "<p>No has iniciado sesión. Por favor, inicia sesión para subir archivos.</p>";
    }
?>

<form action="subir.php" method="post" enctype="multipart/form-data">
    Selecciona el archivo a subir:
    <input type="file" name="archivo" id="archivo">
    <input type="submit" value="Subir Archivo" name="submit">
</form>

</body>
</html>
