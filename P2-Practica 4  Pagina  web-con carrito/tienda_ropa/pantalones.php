<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pantalones - Cuidado con el Perro</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: Arial, sans-serif; }
        .top-bar { background: #000; color: #fff; text-align: center; padding: 5px; font-size: 12px; font-weight: bold; }
        header { display: flex; justify-content: space-between; align-items: center; padding: 10px 20px; border-bottom: 2px solid #000; }
        .logo { font-size: 18px; font-weight: bold; text-decoration: none; color: #000; }
        nav { text-align: center; background: #eee; padding: 10px; }
        nav a { padding: 8px 15px; font-weight: bold; border: 1px solid #000; background: #fff; text-decoration: none; color: #000; margin: 0 5px; display: inline-block; }
        .productos { display: flex; justify-content: center; gap: 15px; flex-wrap: wrap; margin: 20px auto; max-width: 1000px; }
        .tarjeta { border: 1px solid #ccc; width: 180px; text-align: center; padding: 10px; margin-bottom: 15px; }
        .tarjeta img { width: 100%; height: 160px; object-fit: cover; }
        .tarjeta h4 { font-size: 13px; margin: 8px 0 4px; }
        .tarjeta p { font-size: 14px; margin-bottom: 8px; }
        .tarjeta button { width: 100%; padding: 6px; background: #000; color: #fff; border: none; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <h2 style="text-align:center; margin-top:20px;">CATALOGO DE PANTALONES</h2>

    <div class="productos">
        <?php
        $res = mysqli_query($conexion, "SELECT * FROM productos WHERE categoria = 'pantalones'");
        if ($res) {
            while ($r = mysqli_fetch_assoc($res)) {
                echo '<div class="tarjeta">';
                echo '<img src="' . $r['imagen'] . '" alt="' . $r['nombre'] . '">';
                echo '<h4>' . $r['nombre'] . '</h4>';
                echo '<p><b>$' . number_format($r['precio'], 2) . '</b></p>';
                echo '<form action="carrito.php" method="POST">';
                echo '<input type="hidden" name="accion" value="agregar">';
                echo '<input type="hidden" name="id" value="' . $r['id'] . '">';
                echo '<input type="hidden" name="nombre" value="' . $r['nombre'] . '">';
                echo '<input type="hidden" name="precio" value="' . $r['precio'] . '">';
                echo '<button type="submit">Agregar al carrito</button>';
                echo '</form>';
                echo '</div>';
            }
        }
        ?>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>