<?php
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

// Procesar acciones de agregar, eliminar o vaciar antes del HTML
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    if ($_POST['accion'] === 'agregar') {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];

        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = array();
        }

        if (isset($_SESSION['carrito'][$id])) {
            $_SESSION['carrito'][$id]['cantidad'] += 1;
        } else {
            $_SESSION['carrito'][$id] = array(
                'id' => $id,
                'nombre' => $nombre,
                'precio' => $precio,
                'cantidad' => 1
            );
        }
    } elseif ($_POST['accion'] === 'eliminar') {
        $id = $_POST['id'];
        unset($_SESSION['carrito'][$id]);
    } elseif ($_POST['accion'] === 'vaciar') {
        unset($_SESSION['carrito']);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras - Cuidado con el Perro</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: Arial, sans-serif; }
        .top-bar { background: #000; color: #fff; text-align: center; padding: 5px; font-size: 12px; font-weight: bold; }
        header { display: flex; justify-content: space-between; align-items: center; padding: 10px 20px; border-bottom: 2px solid #000; }
        .logo { font-size: 18px; font-weight: bold; text-decoration: none; color: #000; }
        nav { text-align: center; background: #eee; padding: 10px; }
        nav a { padding: 8px 15px; font-weight: bold; border: 1px solid #000; background: #fff; text-decoration: none; color: #000; margin: 0 5px; display: inline-block; }
        .contenedor { max-width: 800px; margin: 30px auto; padding: 0 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 10px; text-align: center; }
        th { background: #eee; }
        .btn-del { background: #ff0000; color: #fff; border: none; padding: 5px 10px; cursor: pointer; font-weight: bold; }
        .btn-vaciar { background: #555; color: #fff; border: none; padding: 8px 15px; cursor: pointer; font-weight: bold; }
        .btn-pagar { display: inline-block; background: #000; color: #fff; text-decoration: none; padding: 8px 15px; font-weight: bold; border: 1px solid #000; }
        .acciones-bar { display: flex; justify-content: space-between; align-items: center; margin-top: 20px; }
        .total-box { text-align: right; font-size: 18px; font-weight: bold; margin-top: 15px; }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="contenedor">
        <h2>CARRITO DE COMPRAS</h2>

        <?php if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Accion</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_pagar = 0;
                    foreach ($_SESSION['carrito'] as $prod):
                        $subtotal = $prod['precio'] * $prod['cantidad'];
                        $total_pagar += $subtotal;
                    ?>
                    <tr>
                        <td><?php echo $prod['nombre']; ?></td>
                        <td>$<?php echo number_format($prod['precio'], 2); ?></td>
                        <td><?php echo $prod['cantidad']; ?></td>
                        <td>$<?php echo number_format($subtotal, 2); ?></td>
                        <td>
                            <form method="POST" action="carrito.php">
                                <input type="hidden" name="accion" value="eliminar">
                                <input type="hidden" name="id" value="<?php echo $prod['id']; ?>">
                                <button type="submit" class="btn-del">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="total-box">
                TOTAL A PAGAR: $<?php echo number_format($total_pagar, 2); ?>
            </div>

            <div class="acciones-bar">
                <form method="POST" action="carrito.php">
                    <input type="hidden" name="accion" value="vaciar">
                    <button type="submit" class="btn-vaciar">Vaciar Carrito</button>
                </form>
                <a href="pago.php" class="btn-pagar">PROCEDER AL PAGO</a>
            </div>
        <?php else: ?>
            <p style="margin-top:20px;">El carrito está vacío.</p>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>