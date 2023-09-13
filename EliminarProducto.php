<?php
include("Base_Datos/Conexion.php");

if (isset($_GET['id'])) {
    $producto_id = $_GET['id'];

    // Verificar si se ha enviado una solicitud de eliminación
    if (isset($_POST['eliminar'])) {
        try {
            // Eliminar las ventas relacionadas primero
            $sqlEliminarVentas = "DELETE FROM ventas WHERE idproductovendido = :producto_id";

            $stmtEliminarVentas = $con->prepare($sqlEliminarVentas);
            $stmtEliminarVentas->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
            $stmtEliminarVentas->execute();

            // Luego, eliminar el producto
            $sqlEliminarProducto = "DELETE FROM producto WHERE idproducto = :producto_id";
            $stmtEliminarProducto = $con->prepare($sqlEliminarProducto);
            $stmtEliminarProducto->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
            $stmtEliminarProducto->execute();

            header("Location: CrearProducto.php");
            exit();
        } catch (PDOException $e) {
            die("Error al eliminar el producto: " . $e->getMessage());
        }
    }
} else {
    // Si no se proporciona un ID válido, redirigir a la página de creación de productos
    header("Location: CrearProducto.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="stylesheet" href="css/EliminarProducto.css">
</head>

<body>
    <!-- Confirmación de eliminación del producto -->
    <h2>¿Estás seguro de que deseas eliminar este producto?</h2>
    <form action="EliminarProducto.php?id=<?php echo $producto_id; ?>" method="POST">
        <input type="submit" name="eliminar" value="Eliminar">
        <a href="CrearProducto.php">Cancelar</a>
    </form>
</body>

</html>
