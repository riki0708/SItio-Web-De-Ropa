<?php
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}
$total_items = 0;
if (isset($_SESSION['carrito']) && is_array($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $prod) {
        if (isset($prod['cantidad'])) {
            $total_items += $prod['cantidad'];
        }
    }
}
?>
<div class="top-bar">3 MSI CON BRADESCARD + ENVIO GRATIS</div>
<header>
    <a href="index.php" class="logo">🐶 CUIDADO CON EL PERRO</a>
    <form action="#" method="GET">
        <input type="text" placeholder="Buscar producto...">
        <button type="submit">Buscar</button>
    </form>
    <div>
        <?php if (isset($_SESSION['usuario_id'])): ?>
            <span style="font-size:13px; font-weight:bold; margin-right:10px;">👤 Hola, <?php echo $_SESSION['usuario_nombre']; ?></span>
            <a href="logout.php" style="font-size:12px; color:red; margin-right:15px; text-decoration:none;">(Salir)</a>
        <?php else: ?>
            <a href="index.php#auth" style="font-size:13px; font-weight:bold; margin-right:15px; color:#000; text-decoration:none;">🔑 INGRESAR</a>
        <?php endif; ?>
        <a href="carrito.php" style="font-weight:bold; color:#000; text-decoration:none;">🛒 CARRITO (<?php echo $total_items; ?>)</a>
    </div>
</header>
<nav>
    <a href="index.php">INICIO</a>
    <a href="camisas.php">CAMISAS</a>
    <a href="pantalones.php">PANTALONES</a>
</nav>