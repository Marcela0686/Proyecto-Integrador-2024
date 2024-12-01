<?php
session_start();

// Verificar si el usuario es administrador
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Verificar acción de eliminación de usuario
if (isset($_GET['borrarUsuario'])) {
    $usuarioABorrar = $_GET['borrarUsuario'];
    if (isset($_SESSION['usuarios'][$usuarioABorrar])) {
        unset($_SESSION['usuarios'][$usuarioABorrar]);
    }
}
?>

<h2>Gestión de usuarios</h2>

<table>
    <thead>
        <tr>
            <th>Correo</th>
            <th>Nombre</th>
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($_SESSION['usuarios'] as $email => $datos): ?>
            <tr>
                <td><?php echo $email; ?></td>
                <td><?php echo $datos['nombre']; ?></td>
                <td>
                    <a href="?borrarUsuario=<?php echo $email; ?>">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
