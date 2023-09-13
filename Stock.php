<?php
include("Base_Datos/Conexion.php");

// Consulta para conocer el producto con más stock
$sqlStock = "SELECT nombre_producto, stock
            FROM producto
            ORDER BY stock DESC
            LIMIT 1";

$stmtStock = $con->query($sqlStock);
$producto_stock = $stmtStock->fetch(PDO::FETCH_ASSOC);

// Consulta para conocer el producto más vendido
$sqlMasVendido = "SELECT p.nombre_producto, SUM(v.cantidadvendida) AS TotalVendido
                FROM producto p
                JOIN ventas v ON p.idproducto = v.idproductovendido
                GROUP BY p.nombre_producto
                ORDER BY SUM(v.cantidadvendida) DESC
                LIMIT 1";

$stmtMasVendido = $con->query($sqlMasVendido);
$producto_mas_vendido = $stmtMasVendido->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="stylesheet" href="css/Stock.css">
</head>

<body>
    <h1>Productos</h1>

    <h2>Producto con Más Stock</h2>
    <p>El producto con más stock es: <?php echo $producto_stock['nombre_producto']; ?></p>
    <p>Cantidad en Stock: <?php echo $producto_stock['stock']; ?></p>

    <h2>Producto Más Vendido</h2>
    <?php if (!empty($producto_mas_vendido['nombre_producto'])) { ?>
        <p>El producto más vendido es: <?php echo $producto_mas_vendido['nombre_producto']; ?></p>
        <?php if (isset($producto_mas_vendido['TotalVendido'])) { ?>
            <p>Total Vendido: <?php echo $producto_mas_vendido['TotalVendido']; ?></p>
        <?php } else { ?>
            <p>Total Vendido: 0</p>
        <?php } ?>
    <?php } else { ?>
        <p>No se han realizado ventas aún.</p>
    <?php } ?>
</body>

</html>
