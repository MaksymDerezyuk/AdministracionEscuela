<?php

session_start();

if (!isset($_SESSION['logeado']) || $_SESSION['logeado'] !== true) {
    header('Location: ./view/login.php');
    exit();
} else {
    $usuario = $_SESSION['user_nombre'];
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Bienvenido <?php echo $usuario ?></title>
    </head>
    <body>
        
    </body>
    </html>
    <?php
}
?>