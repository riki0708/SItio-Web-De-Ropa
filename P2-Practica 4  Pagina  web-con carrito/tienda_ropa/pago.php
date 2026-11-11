<?php
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}
require_once 'config.php';

// Obligar inicio de sesión
if (!isset($_SESSION['usuario_id'])) {
    echo "<script>alert('Debes iniciar sesion o crear una cuenta para proceder al pago.'); window.location.href='index.php#auth';</script>";
    exit();
}

$mensaje = "";

if (isset($_POST['confirmar_pago'])) {
    if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0) {
        $usuario_id = $_SESSION['usuario_id'];
        $titular = mysqli_real_escape_string($conexion, $_POST['titular']);
        $metodo = mysqli_real_escape_string($conexion, $_POST['metodo']);
        
        $total = 0;
        foreach ($_SESSION['carrito'] as $prod) {
            $total += ($prod['precio'] * $prod['cantidad']);
        }

        // Se guarda en la base de datos vinculando el usuario
        $sql_venta = "INSERT INTO ventas (titular, metodo_pago, total) VALUES ('$titular (ID Usuario: $usuario_id)', '$metodo', '$total')";
        
        if (mysqli_query($conexion, $sql_venta)) {
            $id_venta = mysqli_insert_id($conexion);

            foreach ($_SESSION['carrito'] as $prod) {
                $nombre_p = mysqli_real_escape_string($conexion, $prod['nombre']);
                $precio_p = $prod['precio'];
                $cant_p = $prod['cantidad'];
                $subtotal_p = $precio_p * $cant_p;

                $sql_detalle = "INSERT INTO detalle_ventas (id_venta, nombre_producto, precio, cantidad, subtotal) 
                                VALUES ('$id_venta', '$nombre_p', '$precio_p', '$cant_p', '$subtotal_p')";
                mysqli_query($conexion, $sql_detalle);
            }

            unset($_SESSION['carrito']);
            $mensaje = "¡Pago realizado con éxito, " . $_SESSION['usuario_nombre'] . "! Tu compra #" . $id_venta . " ha sido guardada en la base de datos.";
        } else {
            $mensaje = "Error al registrar la venta: " . mysqli_error($conexion);
        }
    } else {
        echo "<script>window.location.href='carrito.php';</script>";
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pasarela de Pago - Cuidado con el Perro</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: Arial, sans-serif; }
        .top-bar { background: #000; color: #fff; text-align: center; padding: 5px; font-size: 12px; font-weight: bold; }
        header { display: flex; justify-content: space-between; align-items: center; padding: 10px 20px; border-bottom: 2px solid #000; }
        .logo { font-size: 18px; font-weight: bold; text-decoration: none; color: #000; }
        nav { text-align: center; background: #eee; padding: 10px; }
        nav a { padding: 8px 15px; font-weight: bold; border: 1px solid #000; background: #fff; text-decoration: none; color: #000; margin: 0 5px; display: inline-block; }
        .contenedor { max-width: 500px; margin: 30px auto; padding: 20px; border: 1px solid #000; background: #f9f9f9; text-align: center; }
        .form-group { margin-bottom: 12px; text-align: left; }
        .form-group label { display: block; font-size: 13px; font-weight: bold; margin-bottom: 4px; }
        .form-group input, .form-group select { width: 100%; padding: 8px; border: 1px solid #000; }
        .btn-pagar { width: 100%; padding: 10px; background: #000; color: #fff; border: none; font-weight: bold; cursor: pointer; margin-top: 10px; }
        .exito { background: #d4edda; color: #155724; padding: 15px; border: 1px solid #c3e6cb; margin-bottom: 15px; font-weight: bold; }
        .btn-volver { display: inline-block; padding: 8px 15px; background: #000; color: #fff; text-decoration: none; font-weight: bold; margin-top: 10px; }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="contenedor">
        <?php if (!empty($mensaje)): ?>
            <div class="exito"><?php echo $mensaje; ?></div>
            <a href="index.php" class="btn-volver">VOLVER AL INICIO</a>
        <?php else: ?>
            <h2>PASARELA DE PAGO</h2>
            <p style="font-size:12px; margin-bottom:15px;">Comprando como: <b><?php echo $_SESSION['usuario_email']; ?></b></p>
            <form method="POST" action="pago.php">
                <div class="form-group">
                    <label>Nombre del Titular:</label>
                    <input type="text" name="titular" value="<?php echo $_SESSION['usuario_nombre']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Número de Tarjeta:</label>
                    <input type="text" maxlength="16" name="tarjeta" placeholder="1234 5678 9012 3456" required>
                </div>
                <div class="form-group">
                    <label>Método de Pago:</label>
                    <select name="metodo" required>
                        <option value="Tarjeta de Credito">Tarjeta de Crédito</option>
                        <option value="Tarjeta de Debito">Tarjeta de Débito</option>
                    </select>
                </div>
                <button type="submit" name="confirmar_pago" class="btn-pagar">CONFIRMAR Y PAGAR</button>
            </form>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>