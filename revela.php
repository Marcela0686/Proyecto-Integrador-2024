<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['userValido']) || $_SESSION['userValido'] !== true) {
    header("Location: index.php");
    exit();
}

$usuarioActual = $_SESSION['user'];
$imagenes = $_SESSION['archivosSubidos']; // Solo imágenes del usuario actual

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subirImagen'])) {
    if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0) {
        $nombreArchivo = $_FILES['archivo']['name'];
        $rutaDestino = "uploads/" . $nombreArchivo;

        if (move_uploaded_file($_FILES['archivo']['tmp_name'], $rutaDestino)) {
            $_SESSION['archivosSubidos'][] = $nombreArchivo;
            echo '<p>Archivo subido con éxito.</p>';
        } else {
            echo '<p>Error al subir el archivo.</p>';
        }
    }
}

// Eliminar imagen
if (isset($_GET['borrar'])) {
    $imagenABorrar = $_GET['borrar'];
    if (($key = array_search($imagenABorrar, $_SESSION['archivosSubidos'])) !== false) {
        unset($_SESSION['archivosSubidos'][$key]);
        unlink("uploads/" . $imagenABorrar); // Eliminar archivo físicamente
        echo '<p>Archivo eliminado.</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galería de Imágenes</title>
    <style>
        body {
            background-color: #000; /* Fondo negro */
            color: #fff; /* Texto blanco */
            font-family: Arial, sans-serif;
        }

        h2, h3 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table, th, td {
            border: 1px solid #fff;
        }

        th, td {
            padding: 8px;
            text-align: center;
        }

        button {
            padding: 8px 15px;
            margin: 5px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .container {
            width: 90%;
            margin: 0 auto;
        }

        .galeriaImagenes {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .galeriaImagenes img {
            width: 200px;
            margin: 10px;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
        }

        footer {
            text-align: center;
            padding: 10px;
            margin-top: 20px;
            background-color: #333;
            color: #fff;
        }
    </style>
</head>
<body>

<h2>Bienvenido, <?php echo $_SESSION['nombre']; ?></h2>

<h3>Galería de imágenes</h3>

<!-- Tabla de archivos -->
<table>
    <thead>
        <tr>
            <th>Imagen</th>
            <th>Peso (KB)</th>
            <th>Tipo de Documento</th>
            <th>Subido por</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($imagenes as $imagen): ?>
            <?php
            $rutaImagen = "uploads/$imagen";
            $tamanoArchivo = round(filesize($rutaImagen) / 1024, 2); // Tamaño en KB
            $tipoDocumento = mime_content_type($rutaImagen); // Obtener tipo MIME
            ?>
            <tr>
                <td><img src="<?php echo $rutaImagen; ?>" alt="Imagen" width="100"></td>
                <td><?php echo $tamanoArchivo; ?> KB</td>
                <td><?php echo $tipoDocumento; ?></td>
                <td><?php echo $_SESSION['nombre']; ?></td>
                <td>
                    <!-- Botón para mostrar -->
                    <a href="<?php echo $rutaImagen; ?>" target="_blank"><button>Mostrar</button></a>
                    <!-- Botón para eliminar -->
                    <a href="?borrar=<?php echo $imagen; ?>"><button>Eliminar</button></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<form action="revela.php" method="post" enctype="multipart/form-data" style="text-align: center;">
    <label for="archivo">Subir imagen:</label>
    <input type="file" name="archivo" required>
    <input type="submit" name="subirImagen" value="Subir" style="padding: 8px 15px; margin-top: 10px; background-color: #28a745; color: #fff; border: none; cursor: pointer; border-radius: 5px;">
</form>

<div class="container">
    <div class="galeriaImagenes">
        <?php
        // Mostrar las imágenes de la galería
        foreach ($imagenes as $imagen) {
            echo "<img src='uploads/$imagen' alt='$imagen'>";
        }
        ?>
    </div>
</div>

<!-- Footer -->
<footer>
    <p>&copy; Gracias por su visita</p>
</footer>

</body>
</html>
