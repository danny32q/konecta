<?php
include("Base_Datos/Conexion.php");

if (isset($_GET['id'])) {
    $producto_id = $_GET['id'];

    // Verificar si se ha enviado un formulario para actualizar el producto
    if (isset($_POST['actualizar'])) {
        $nombre_producto = $_POST['nombre_producto'];
        $referencia = $_POST['referencia'];
        $precio = $_POST['precio'];
        $peso = $_POST['peso'];
        $stock = $_POST['stock'];
        $fecha_creacion = $_POST['fecha_creacion'];
        $idcategoria = $_POST['idcategoria'];

        try {
            $sql = "UPDATE producto SET nombre_producto = :nombre_producto, referencia = :referencia, precio = :precio, peso = :peso, stock = :stock, fecha_creacion = :fecha_creacion, idcategoria = :idcategoria WHERE idproducto = :producto_id";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(':nombre_producto', $nombre_producto, PDO::PARAM_STR);
            $stmt->bindParam(':referencia', $referencia, PDO::PARAM_STR);
            $stmt->bindParam(':precio', $precio, PDO::PARAM_INT);
            $stmt->bindParam(':peso', $peso, PDO::PARAM_INT);
            $stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
            $stmt->bindParam(':fecha_creacion', $fecha_creacion);
            $stmt->bindParam(':idcategoria', $idcategoria, PDO::PARAM_INT);
            $stmt->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
            $stmt->execute();

            header("Location: CrearProducto.php");
            exit();
        } catch (PDOException $e) {
            die("Error al actualizar el producto: " . $e->getMessage());
        }
    }

    // Obtener los datos del producto a editar
    $sql = "SELECT * FROM producto WHERE idproducto = :producto_id";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
    $stmt->execute();
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    // Si no se proporciona un ID válido, redirigir a la página de creación de productos
    header("Location: CrearProducto.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
<link rel="stylesheet" href="css/EditarProducto.css">
</head>

<body>
    <!-- Formulario para editar el producto -->
    <h1>Editar Producto</h1>
    <form action="EditarProducto.php?id=<?php echo $producto_id; ?>" method="POST">
        <label for="nombre_producto">Nombre de producto:</label>
        <input type="text" id="nombre_producto" name="nombre_producto" value="<?php echo $producto['nombre_producto']; ?>" required><br>

        <label for="referencia">Referencia:</label>
        <input type="text" id="referencia" name="referencia" value="<?php echo $producto['referencia']; ?>" required><br>

        <label for="precio">Precio:</label>
        <input type="number" id="precio" name="precio" value="<?php echo $producto['precio']; ?>" required><br>

        <label for="peso">Peso:</label>
        <input type="number" id="peso" name="peso" value="<?php echo $producto['peso']; ?>" required><br>

        <label for="stock">Stock (Cantidad en bodega):</label>
        <input type="number" id="stock" name="stock" value="<?php echo $producto['stock']; ?>" required><br>

        <label for="fecha_creacion">Fecha de creación:</label>
        <input type="date" id="fecha_creacion" name="fecha_creacion" value="<?php echo $producto['fecha_creacion']; ?>" required><br>

        <label for="idcategoria">Categoría:</label>
        <select id="idcategoria" name="idcategoria" required>
            <?php
            $sql = "SELECT * FROM categoria";
            $resultado = $con->query($sql);
            while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $selected = ($fila['idcategoria'] == $producto['idcategoria']) ? 'selected' : '';
                echo "<option value='" . $fila['idcategoria'] . "' $selected>" . htmlspecialchars($fila['nombrecategoria']) . "</option>";
            }
            ?>
        </select><br>

        <input type="submit" name="actualizar" value="Actualizar Producto">
        <a href="CrearProducto.php">Cancelar</a>
    </form>
</body>

</html>