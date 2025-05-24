<?php
session_start();
include "config.php"; // Asegúrate de tener la conexión $conn aquí

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Busca el usuario por correo
    $query = "SELECT id_usuario, nombre , correo, contraseña , telefono FROM usuarios WHERE correo = '$correo' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) === 1) {
        $usuario = mysqli_fetch_assoc($result);
        // Si las contraseñas están hasheadas usa password_verify, si no, compara directo
        if (password_verify($password, $usuario['contraseña']) || $password === $usuario['contraseña']) {
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['correo'] = $usuario['correo'];
             $_SESSION['nombre'] = $usuario['nombre'];
             $_SESSION['telefono'] = $usuario['telefono'];
            // Guardar usuario en localStorage y redirigir
            ?>
            <script>
                localStorage.setItem('usuario', JSON.stringify({
                    id_usuario: "<?php echo $usuario['id_usuario']; ?>",
                    correo: "<?php echo $usuario['correo']; ?>",
                     nombre: "<?php echo $usuario['nombre']; ?>",
                       telefono: "<?php echo $usuario['telefono']; ?>"
                }));
                window.location.href = "../index.php";
            </script>
            <?php
            exit();
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Usuario no encontrado.";
    }
}
?>

<!-- Puedes mostrar el error en tu modal si lo deseas -->
<?php if (isset($error)): ?>
<script>
  alert("<?php echo $error; ?>");
  window.location.href = "../index.php";
</script>
<?php endif; ?>