<?php
session_start();

// Si se ha pedido cerrar sesión
if (isset($_GET['logout']) && $_GET['logout'] == '1') {
    // Destruir las variables de sesión
    $_SESSION = array();

    // Destruir la sesión
    session_destroy();

    // Redirigir a index
    header('Location: index.php');
    exit;
}
?>