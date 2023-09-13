<?php
include("Base_Datos/Conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mensaje = "";

    // Recoger los valores del formulario
    $nombre_producto = $_POST['nombre_producto'];
    $referencia = $_POST['referencia'];
    $precio = $_POST['precio'];
    $peso = $_POST['peso'];
    $stock = $_POST['stock'];
    $fecha_creacion = $_POST['fecha_creacion'];
    $categoria_option = $_POST['categoria_option'];
    $idcategoria = $_POST['idcategoria'];
    $nombre_categoria = $_POST['nombre_categoria'];

    // Verificar si la referencia ya existe en la base de datos
    $sql = "SELECT COUNT(*) FROM producto WHERE referencia = :referencia";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':referencia', $referencia, PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        // La referencia ya existe, muestra una alerta
        $mensaje = "La referencia ya existe, por favor cambia la referencia.";
    } else {
        // Si la referencia no existe, se procede con la inserción
        if ($categoria_option === 'nueva' && !empty($nombre_categoria)) {
            // Verificar si la categoría ya existe
            $sqlCheckCategoria = "SELECT idcategoria FROM categoria WHERE nombrecategoria = :nombre_categoria";
            $stmtCheckCategoria = $con->prepare($sqlCheckCategoria);
            $stmtCheckCategoria->bindParam(':nombre_categoria', $nombre_categoria, PDO::PARAM_STR);
            $stmtCheckCategoria->execute();
            $categoriaExistente = $stmtCheckCategoria->fetch(PDO::FETCH_ASSOC);

            if (!$categoriaExistente) {
                // Insertar una nueva categoría si no existe
                try {
                    $sql = "INSERT INTO categoria (nombrecategoria) VALUES (:nombre_categoria)";
                    $stmt = $con->prepare($sql);
                    $stmt->bindParam(':nombre_categoria', $nombre_categoria, PDO::PARAM_STR);
                    $stmt->execute();
                    $idcategoria = $con->lastInsertId();
                } catch (PDOException $e) {
                    die("Error al insertar la nueva categoría: " . $e->getMessage());
                }
            } else {
                // La categoría ya existe, puedes mostrar una alerta o simplemente omitir la inserción
                $mensaje = "La categoría ya existe.";
            }
        }

        if (empty($mensaje)) {
            try {
                // Insertar el producto en la base de datos
                $sql = "INSERT INTO producto (nombre_producto, referencia, precio, peso, stock, fecha_creacion, idcategoria) VALUES (:nombre_producto, :referencia, :precio, :peso, :stock, :fecha_creacion, :idcategoria)";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(':nombre_producto', $nombre_producto, PDO::PARAM_STR);
                $stmt->bindParam(':referencia', $referencia, PDO::PARAM_STR);
                $stmt->bindParam(':precio', $precio, PDO::PARAM_INT);
                $stmt->bindParam(':peso', $peso, PDO::PARAM_INT);
                $stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
                $stmt->bindParam(':fecha_creacion', $fecha_creacion);
                $stmt->bindParam(':idcategoria', $idcategoria, PDO::PARAM_INT);
                $stmt->execute();

                header("Location: CrearProducto.php?registrado=1");
                exit();
            } catch (PDOException $e) {
                die("Error al insertar el producto: " . $e->getMessage());
            }
        }
    }
} else {
    $mensaje = '';
}

// Obtener la lista de productos desde la base de datos
$sql = "SELECT producto.*, categoria.nombrecategoria FROM producto JOIN categoria ON producto.idcategoria = categoria.idcategoria";
$resultado = $con->query($sql);
$productos = $resultado->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konecta</title>
    <link rel="stylesheet" href="css/CrearProducto.css">
</head>

<body>
    <form action="CrearProducto.php" method="POST">
        <h1>Formulario de Producto</h1> <br>
        <?php if (!empty($mensaje)) { ?>
            <p style="color: red;"><?php echo $mensaje; ?></p>
        <?php } ?>
        <label for="nombre_producto">Nombre de producto:</label>
        <input type="text" id="nombre_producto" name="nombre_producto" required><br>

        <label for="referencia">Referencia:</label>
        <input type="text" id="referencia" name="referencia" required><br>

        <label for="precio">Precio:</label>
        <input type="number" id="precio" name="precio" required><br>

        <label for="peso">Peso:</label>
        <input type="number" id="peso" name="peso" required><br>

        <label for="stock">Stock (Cantidad en bodega):</label>
        <input type="number" id="stock" name="stock" required><br>

        <label for="fecha_creacion">Fecha de creación:</label>
        <input type="date" id="fecha_creacion" name="fecha_creacion" required><br>

        <label for="categoria_option">Elige una opción:</label><br>
        <input type="radio" id="categoria_existente" name="categoria_option" value="existente" required>
        <label for="categoria_existente">Seleccionar categoría existente</label><br>
        <input type="radio" id="categoria_nueva" name="categoria_option" value="nueva" required>
        <label for="categoria_nueva">Agregar nueva categoría</label><br>

        <label for="nombre_categoria">Nombre de categoría (si es nueva):</label>
        <input type="text" id="nombre_categoria" name="nombre_categoria"><br>

        <select id="idcategoria" name="idcategoria" required>
            <?php
            $sql = "SELECT * FROM categoria";
            $resultado = $con->query($sql);
            while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='" . $fila['idcategoria'] . "'>" . htmlspecialchars($fila['nombrecategoria']) . "</option>";
            }
            ?>
        </select><br>

        <input type="submit" value="Guardar Producto">
    </form>
    <!-- Tabla para mostrar la lista de productos -->
    <h2>Lista de Productos</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Referencia</th>
            <th>Precio</th>
            <th>Peso</th>
            <th>Stock</th>
            <th>Fecha de Creación</th>
            <th>Categoria</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($productos as $producto) { ?>
            <tr>
                <td><?php echo $producto['idproducto']; ?></td>
                <td><?php echo $producto['nombre_producto']; ?></td>
                <td><?php echo $producto['referencia']; ?></td>
                <td><?php echo $producto['precio']; ?></td>
                <td><?php echo $producto['peso']; ?></td>
                <td><?php echo $producto['stock']; ?></td>
                <td><?php echo $producto['fecha_creacion']; ?></td>
                <td><?php echo $producto['nombrecategoria']; ?></td>
                <td>
                    <a href="EditarProducto.php?id=<?php echo $producto['idproducto']; ?>">Editar</a>
                    <a href="EliminarProducto.php?id=<?php echo $producto['idproducto']; ?>">Eliminar</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>

</html>
