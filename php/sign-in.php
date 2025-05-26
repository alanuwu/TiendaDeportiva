<?php
session_start();
include "config.php"; // Asegúrate de tener la conexión $conn aquí

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
    $correo = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Puedes hashear la contraseña si lo deseas:
    // $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Verifica si el correo ya existe
    $check = mysqli_query($conn, "SELECT id_usuario FROM usuarios WHERE correo = '$correo'");
    if (mysqli_num_rows($check) > 0) {
        $error = "El correo ya está registrado.";
    } else {
        // Inserta el usuario
        $query = "INSERT INTO usuarios (nombre, correo, contraseña, telefono, fecha_registro) VALUES ('$nombre', '$correo', '$password', '$telefono', NOW())";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $id_usuario = mysqli_insert_id($conn);
            $_SESSION['id_usuario'] = $id_usuario;
            $_SESSION['correo'] = $correo;
            $_SESSION['nombre'] = $nombre;
            $_SESSION['telefono'] = $telefono;
            ?>
            <script>
                localStorage.setItem('usuario', JSON.stringify({
                    id_usuario: "<?php echo $id_usuario; ?>",
                    correo: "<?php echo $correo; ?>",
                    nombre: "<?php echo $nombre; ?>",
                    telefono: "<?php echo $telefono; ?>"
                }));
                window.location.href = "../index.php";
            </script>
            <?php
            exit();
        } else {
            $error = "Error al registrar el usuario.";
        }
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