<?php
session_start();

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Crear cuenta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <main class="contenedor">
        <div class="auth-split">
            <div class="auth-left" aria-hidden="true">
                <img src="../img/uni.png" alt="Logotipo de la universidad" loading="lazy">
            </div>
            <div class="auth-right">
                <div class="tarjeta">
                    <h1>Crear cuenta</h1>

                    <form action="../proc/insert_register.php" method="post" autocomplete="off" class="formulario">
                        <label>
                            Correo electrónico
                            <input type="text" id="email" name="email" onblur="validaEmail()">
                            <div id="errorEmail" class="error"></div>
                        </label>
                        <label>
                            Nombre de usuario
                            <input type="text" id="username" name="username" onblur="validaUsuario()">
                            <div id="errorUsuario" class="error"></div>
                        </label>
                        <label>
                            Contraseña
                            <input type="password" id="password" name="password" onblur="validaPassword()">
                            <div id="errorPassword" class="error"></div>
                        </label>
                        <label>
                            Confirmar contraseña
                            <input type="password" id="confirm_password" name="confirm_password" onblur="validaConfirmPassword()">
                            <div id="errorConfirm" class="error"></div>
                        </label>
                        <label>
                            Selecciona tu rol:
                        </label>
                        <fieldset class="roles" aria-describedby="errorRol" style="border:none;padding:0;margin:0 0 1rem 0;">
                            <label for="rol_profesor"><input type="radio" id="rol_profesor" name="rol" value="profesor" onchange="validaRol()" required> Profesor</label>
                            <label for="rol_secretaria"><input type="radio" id="rol_secretaria" name="rol" value="secretaria" onchange="validaRol()"> Secretaria</label>
                            <label for="rol_administrador"><input type="radio" id="rol_administrador" name="rol" value="administrador" onchange="validaRol()"> Administrador</label>
                            <label for="rol_direccion"><input type="radio" id="rol_direccion" name="rol" value="direccion" onchange="validaRol()"> Dirección</label>
                        </fieldset>
                        <div id="errorRol" class="error"></div>

                        <button type="submit">Registrarme</button>
                        <a href="login.php">¿Ya tienes cuenta? Inicia sesión</a>
                    </form>

                    <?php
                    if (!empty($_GET['error'])) {
                        echo "<div class='alerta alerta-error'>" . htmlspecialchars($_GET['error']) . "</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </main>
    <script src="../js/registrar.js"></script>
</body>
</html>