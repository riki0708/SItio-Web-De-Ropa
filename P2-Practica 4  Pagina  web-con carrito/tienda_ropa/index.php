<?php
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}
require_once 'config.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion_login'])) {
        $email = mysqli_real_escape_string($conexion, $_POST['email']);
        $password = mysqli_real_escape_string($conexion, $_POST['password']);

        $res = mysqli_query($conexion, "SELECT * FROM usuarios WHERE email = '$email' AND password = '$password'");
        if ($res && mysqli_num_rows($res) > 0) {
            $u = mysqli_fetch_assoc($res);
            $_SESSION['usuario_id'] = $u['id'];
            $_SESSION['usuario_nombre'] = $u['nombre'];
            $_SESSION['usuario_email'] = $u['email'];
            echo "<script>window.location.href='index.php';</script>";
            exit();
        } else {
            $error = "Correo o contraseña incorrectos";
        }
    } elseif (isset($_POST['accion_registro'])) {
        $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
        $email = mysqli_real_escape_string($conexion, $_POST['email']);
        $password = mysqli_real_escape_string($conexion, $_POST['password']);

        $check = mysqli_query($conexion, "SELECT id FROM usuarios WHERE email = '$email'");
        if (mysqli_num_rows($check) > 0) {
            $error = "El correo ya esta registrado";
        } else {
            $sql = "INSERT INTO usuarios (nombre, email, password) VALUES ('$nombre', '$email', '$password')";
            if (mysqli_query($conexion, $sql)) {
                $_SESSION['usuario_id'] = mysqli_insert_id($conexion);
                $_SESSION['usuario_nombre'] = $nombre;
                $_SESSION['usuario_email'] = $email;
                echo "<script>window.location.href='index.php';</script>";
                exit();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cuidado con el Perro - Inicio</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: Arial, sans-serif; }
        .top-bar { background: #000; color: #fff; text-align: center; padding: 5px; font-size: 12px; font-weight: bold; }
        header { display: flex; justify-content: space-between; align-items: center; padding: 10px 20px; border-bottom: 2px solid #000; }
        .logo { font-size: 18px; font-weight: bold; text-decoration: none; color: #000; }
        nav { text-align: center; background: #eee; padding: 10px; }
        nav a { padding: 8px 15px; font-weight: bold; border: 1px solid #000; background: #fff; text-decoration: none; color: #000; margin: 0 5px; display: inline-block; }
        .intro { max-width: 800px; margin: 30px auto; text-align: center; padding: 0 20px; }
        .intro h1 { font-size: 28px; margin-bottom: 10px; }
        .intro p { color: #444; line-height: 1.6; }
        .formularios { display: flex; justify-content: center; gap: 20px; margin: 30px 20px; background: #f9f9f9; padding: 20px; }
        .form-box { border: 1px solid #000; padding: 15px; background: #fff; width: 240px; }
        .form-box input { width: 100%; margin: 5px 0 10px; padding: 5px; border: 1px solid #000; }
        .form-box button { width: 100%; padding: 6px; background: #000; color: #fff; border: none; font-weight: bold; cursor: pointer; }
        .error-msg { text-align:center; color:red; font-weight:bold; margin-top:10px; }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <section class="intro">
        <h1>HOLA LETICIA, BIENVENIDO A CUIDADO CON EL PERRO</h1>
        <p>Somos una marca mexicana inspirada en el estilo urbano y juvenil. Explora nuestra nueva coleccion con los mejores disenos en ropa formal y casual. Registrate para obtener promociones exclusivas.</p>
        <?php if(!empty($error)): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>
    </section>

    <?php if(!isset($_SESSION['usuario_id'])): ?>
    <div class="formularios" id="auth">
        <form class="form-box" method="POST" action="index.php">
            <input type="hidden" name="accion_login" value="1">
            <h3>Ingresar</h3>
            <label>Correo:</label><input type="email" name="email" required>
            <label>Contraseña:</label><input type="password" name="password" required>
            <button type="submit">ENTRAR</button>
        </form>
        
        <form class="form-box" method="POST" action="index.php">
            <input type="hidden" name="accion_registro" value="1">
            <h3>Registrarse</h3>
            <label>Nombre:</label><input type="text" name="nombre" required>
            <label>Correo:</label><input type="email" name="email" required>
            <label>Contraseña:</label><input type="password" name="password" required>
            <button type="submit">GUARDAR</button>
        </form>
    </div>
    <?php else: ?>
        <p style="text-align:center; font-weight:bold; color:green; margin-bottom:30px;">¡Has iniciado sesión como <?php echo $_SESSION['usuario_nombre']; ?>! Ya puedes realizar compras.</p>
    <?php endif; ?>

    <?php include 'footer.php'; ?>
</body>
</html>