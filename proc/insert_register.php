<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once "../conexion/connection.php";

    // Recogemos los datos, las saneamos y las guardamos en variables
    $username = trim(htmlspecialchars($_POST['username']));
    $email = trim(htmlspecialchars($_POST['email']));
    $password = trim(htmlspecialchars($_POST['password']));
    $confirm_password = trim(htmlspecialchars($_POST['confirm_password']));
    $rol = trim(htmlspecialchars($_POST['rol']));

    // Validaciones básicas
    if (isset($username) && !empty($username)) {
        $username = trim($username);
        if (strlen($username) > 2) {
            if (isset($email) && !empty($email)){
                if (preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $email)) {
                    if (isset($password) && !empty(trim($password))) {
                        if (preg_match('/^(?=.*[A-Z])(?=.*\d).{8,}$/', trim($password))) {
                            if (isset($confirm_password) && !empty(trim($confirm_password)) && trim($confirm_password) === trim($password)) {
                                if (isset($rol) && !empty($rol)) {
                                    $rol = trim(htmlspecialchars($rol));
                                    try {
                                        // Comprobar duplicados
                                        $sqlCheck = "SELECT id FROM tbl_gestores WHERE nombre_completo = :username OR email = :email";
                                        $stmtCheck = $conn->prepare($sqlCheck);
                                        $stmtCheck->bindParam(':username', $username);
                                        $stmtCheck->bindParam(':email', $email);
                                        $stmtCheck->execute();

                                        if ($stmtCheck->fetch()) {
                                            header("Location: ../view/register.php?error=El nombre de usuario o correo ya existen");
                                            exit;
                                        }

                                        // Cifrar la contraseña
                                        $password_hash = password_hash($password, PASSWORD_DEFAULT);
                                    
                                        // Insertar nuevo usuario - CORREGIDO: usar 'password' en lugar de 'password_hash'
                                        $sqlInsert = "INSERT INTO tbl_gestores (nombre_completo, email, password, rol)
                                                      VALUES (:username, :email, :contrasena, :rol)";
                                        $stmtInsert = $conn->prepare($sqlInsert);
                                        $stmtInsert->bindParam(':username', $username);
                                        $stmtInsert->bindParam(':email', $email);
                                        $stmtInsert->bindParam(':contrasena', $password_hash);
                                        $stmtInsert->bindParam(':rol', $rol);
                                        $stmtInsert->execute();
                                    
                                        header("Location: ../view/login.php?estado=Usuario registrado con éxito");
                                        exit;
                                    
                                    } catch (PDOException $e) {
                                        error_log("Register error: " . $e->getMessage());
                                        header("Location: ../view/register.php?error=Error en el registro, inténtalo de nuevo más tarde");
                                        exit;
                                    }

                                } else {
                                    header("Location: ../view/register.php?error=Tienes que seleccionar un rol");
                                    exit;
                                }
                            } else {
                                header("Location: ../view/register.php?error=Las contraseñas no coinciden");
                                exit;
                            }
                        } else {
                            header("Location: ../view/register.php?error=La contraseña debe tener al menos 8 caracteres, una letra mayúscula y un número");
                            exit;
                        }
                    } else {
                        header("Location: ../view/register.php?error=Tienes que completar la contraseña.");
                        exit;
                    }
                } else {
                    header("Location: ../view/register.php?error=El formato del email no es válido");
                    exit;
                }
            } else {
                header("Location: ../view/register.php?error=Tienes que completar el email");
                exit;
            }
        } else {
            header("Location: ../view/register.php?error=El nombre de usuario debe tener más de 2 caracteres");
            exit;
        }
    } else {
        header("Location: ../view/register.php?error=Tienes que completar el nombre de usuario");
        exit;
    }
} else {
    header("Location: ../view/register.php");
    exit;
}
?>