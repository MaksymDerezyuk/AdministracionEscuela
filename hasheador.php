<?php
// IMPORTANTE: Escribe aquí la contraseña que quieres usar para entrar
?>
<form method="POST">
    <input type='text' name="contrasena" value='' style='width:300px;' placeholder="Escribe la contraseña a hashear">
    <button type='submit'>Generar Hash Seguro</button>
</form>

<hr>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Comprobar que la contraseña no está vacía
    if (!empty(trim($_POST['contrasena']))) {
        
        $contrasena_plana = trim($_POST['contrasena']);
        
        // Usar PASSWORD_DEFAULT es la práctica recomendada
        $hash_seguro = password_hash($contrasena_plana, PASSWORD_DEFAULT);
        
        echo "<h3>¡Hash Generado!</h3>";
        echo "<b>Contraseña Plana:</b> " . htmlspecialchars($contrasena_plana) . "<br><br>";
        
        echo "<b>Copia este Hash Seguro y pégalo en tu base de datos:</b><br>";
        // Ponerlo en un <textarea> hace fácil copiarlo
        echo "<textarea style='width:500px; height:80px;'>" . $hash_seguro . "</textarea>";
        
    } else {
        echo "<p style='color:red;'>Por favor, escribe una contraseña.</p>";
    }
}
?>