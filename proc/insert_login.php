<?php
session_start();

if (!isset($_POST['enviado'])) {
    header('Location: ../view/login.php?errorform=Tienes que enviar el formulario');
    exit();
}

    if (isset($_POST['email']) && !empty(trim($_POST['email']))){
        if (preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', trim($_POST['email']))) {
            
            $email = trim($_POST['email']);

            if (isset($_POST['contrasena']) && !empty(trim($_POST['contrasena']))) {
            $contrasena = trim($_POST['contrasena']);

                require_once('./../conexion/connection.php');

                // Buscar usuario por email
                $stmt = $conn->prepare("SELECT * FROM tbl_gestores WHERE email = ?");
                $stmt->bindParam(1, $email);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Si se encontró el usuario
                if ($user) {
                    // Verificar la contraseña (usando password_verify si están cifradas)
                    if (password_verify($contrasena, $user['password'])) {
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['user_email'] = $user['email'];
                        $_SESSION['user_nombre'] = $user['nombre'];
                        $_SESSION['user_rol'] = $user['rol'];
                        $_SESSION['logeado'] = true;
                    
                        header('Location: ../index.php');
                        exit();
                    } else {
                        header('Location: ../view/login.php?errorform=Email o contraseña incorrectos');
                        exit();
                    }
                } else {
                    header('Location: ../view/login.php?errorform=Email o contraseña incorrectos');
                    exit();
                }
        } else {
            header('Location: ../view/login.php?errorform=Tienes que poner la contraseña');
            exit();
        }
    } else {
        header('Location: ../view/login.php?errorform=El formato del email no es valido');
        exit();
    }
} else {
    header('Location: ../view/login.php?errorform=El email esta vacio');
    exit();
}
?>
