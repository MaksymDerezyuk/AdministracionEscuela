<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<main class="contenedor">
        <div class="split-page">
            <div class="split__media">
                <img src="../img/uni.png" alt="Logotipo de la universidad" loading="lazy">
            </div>
            <div class="tarjeta">
                <h1>Inicio Sesión</h1>

                    <form action="../proc/insert_login.php" method="POST" class="formulario" novalidate>
                        <label for="email">Correo Electrónico:</label>
                        <input type="text" name="email" id="email">
                        <div class="error" id="error-email"></div>

                        <label for="contrasena">Contraseña:</label>
                        <input type="password" name="contrasena" id="contrasena">
                        <div class="error" id="error-contrasena"></div>

                        <button type="submit" name="enviado" value="enviado">Iniciar Sesión</button>
                        <a href="./register.php">¿No tienes cuenta? Regístrate Aquí</a>
                    </form>

                    <?php if (!empty($_GET['errorform'])){
                        echo "<div class='alerta alerta-error'>" . $_GET['errorform'] . "</div>";
                    } else if (!empty($_GET['estado'])) {
                        echo "<div class='alerta alerta-exito'>" . $_GET['estado'] . "</div>";
                    }
                    ?>
                </div>
            </div>
    </main>
    </main>
    
    <script src="../proc/login.js"></script>
</body>
</html>