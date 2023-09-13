<?php
include("Base_Datos/Conexion.php");

$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $producto_id = $_POST['producto_id'];
    $cantidad_vendida = $_POST['cantidad_vendida'];

    // Verificar si el producto tiene suficiente stock para la venta
    $sqlStock = "SELECT stock FROM producto WHERE idproducto = :producto_id";
    $stmtStock = $con->prepare($sqlStock);
    $stmtStock->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
    $stmtStock->execute();
    $stock = $stmtStock->fetch(PDO::FETCH_ASSOC)['stock'];

    if ($stock !== false && $cantidad_vendida <= $stock) {
        try {
            // Realizar la venta y actualizar el stock
            $con->beginTransaction();

            $sqlVenta = "INSERT INTO ventas (Idproductovendido, cantidadvendida, fechaventa) 
                         VALUES (:producto_id, :cantidad_vendida, NOW())";
            $stmtVenta = $con->prepare($sqlVenta);
            $stmtVenta->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
            $stmtVenta->bindParam(':cantidad_vendida', $cantidad_vendida, PDO::PARAM_INT);
            $stmtVenta->execute();

            $nuevo_stock = $stock - $cantidad_vendida;
            $sqlActualizarStock = "UPDATE producto SET stock = :nuevo_stock WHERE idproducto = :producto_id";
            $stmtActualizarStock = $con->prepare($sqlActualizarStock);
            $stmtActualizarStock->bindParam(':nuevo_stock', $nuevo_stock, PDO::PARAM_INT);
            $stmtActualizarStock->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
            $stmtActualizarStock->execute();

            $con->commit();
            $mensaje = "Venta realizada con éxito.";
        } catch (PDOException $e) {
            $con->rollBack();
            die("Error al realizar la venta: " . $e->getMessage());
        }
    } else {
        $mensaje = "No hay suficiente stock para la venta.";
    }
}

// Obtener la lista de productos disponibles
$sqlProductosDisponibles = "SELECT idproducto, nombre_producto, stock FROM producto WHERE stock > 0";
$stmtProductosDisponibles = $con->query($sqlProductosDisponibles);
$productos_disponibles = $stmtProductosDisponibles->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo de Ventas</title>
    <link rel="stylesheet" href="css/Ventas.css">
</head>

<body>
    <h1>Módulo de Ventas</h1>

    <form action="crearventas.php" method="POST"> <!-- Cambiar la acción al archivo actual -->
        <label for="producto_id">Selecciona un producto:</label>
        <select id="producto_id" name="producto_id" required>
            <?php foreach ($productos_disponibles as $producto) { ?>
                <option value="<?php echo $producto['idproducto']; ?>">
                    <?php echo $producto['nombre_producto']; ?> (Stock: <?php echo $producto['stock']; ?>)
                </option>
            <?php } ?>
        </select><br>

        <label for="cantidad_vendida">Cantidad vendida:</label>
        <input type="number" id="cantidad_vendida" name="cantidad_vendida" required><br>

        <input type="submit" value="Realizar Venta">
    </form>

    <?php if (!empty($mensaje)) { ?>
        <p style="color: <?php echo strpos($mensaje, 'éxito') ? 'green' : 'red'; ?>">
            <?php echo $mensaje; ?>
        </p>
    <?php } ?>

    <!-- Lista de ventas realizadas (opcional) -->
    <h2>Historial de Ventas</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Producto</th>
            <th>Cantidad Vendida</th>
            <th>Fecha de Venta</th>
        </tr>
        <?php
        $sqlVentasRealizadas = "SELECT ventas.idventa, producto.nombre_producto, ventas.cantidadvendida, ventas.fechaventa 
                                FROM ventas 
                                JOIN producto ON ventas.Idproductovendido = producto.idproducto
                                ORDER BY ventas.idventa DESC";
        $stmtVentasRealizadas = $con->query($sqlVentasRealizadas);
        $ventas_realizadas = $stmtVentasRealizadas->fetchAll(PDO::FETCH_ASSOC);

        foreach ($ventas_realizadas as $venta) { ?>
            <tr>
                <td><?php echo $venta['idventa']; ?></td>
                <td><?php echo $venta['nombre_producto']; ?></td>
                <td><?php echo $venta['cantidadvendida']; ?></td>
                <td><?php echo $venta['fechaventa']; ?></td>
            </tr>
        <?php } ?>
    </table>
</body>

</html>