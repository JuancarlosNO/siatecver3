<?php
include 'config.php';

$mensaje = '';
$productos = [];

// Procesar el formulario cuando se envía por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'guardar') {
        $productoId = isset($_POST['productoId']) ? intval($_POST['productoId']) : 0;
        $precio = isset($_POST['precioProducto']) ? floatval($_POST['precioProducto']) : 0;
        $descripcion = isset($_POST['descripcionProducto']) ? trim($_POST['descripcionProducto']) : '';

        if ($productoId > 0) {
            // Actualizar solo precio y descripción usando prepared statement
            $stmt = $conexion->prepare("UPDATE productos SET precio = ?, descripcion = ? WHERE idproducto = ?");
            $stmt->bind_param("dsi", $precio, $descripcion, $productoId);
            if ($stmt->execute()) {
                $mensaje = 'Producto actualizado correctamente.';
                $_POST = []; // Limpiar formulario tras actualización
            } else {
                $mensaje = 'Error al actualizar el producto: ' . $stmt->error;
            }
            $stmt->close();
        } else {
            // Insertar nuevo producto completo usando prepared statement
            $categoria = isset($_POST['categoriaProducto']) ? trim($_POST['categoriaProducto']) : '';
            $nombre = isset($_POST['nombreProducto']) ? trim($_POST['nombreProducto']) : '';

            $stmt = $conexion->prepare("INSERT INTO productos (categoria, producto, precio, descripcion) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssds", $categoria, $nombre, $precio, $descripcion);
            if ($stmt->execute()) {
                $mensaje = 'Producto agregado correctamente.';
                $_POST = []; // Limpiar formulario tras inserción
            } else {
                $mensaje = 'Error al agregar el producto: ' . $stmt->error;
            }
            $stmt->close();
        }
    } elseif ($accion === 'borrar') {
        $productoId = isset($_POST['productoId']) ? intval($_POST['productoId']) : 0;
        if ($productoId > 0) {
            $stmt = $conexion->prepare("DELETE FROM productos WHERE idproducto = ?");
            $stmt->bind_param("i", $productoId);
            if ($stmt->execute()) {
                $mensaje = "Producto borrado correctamente.";
                $_POST = []; // <-- Aquí limpiamos el formulario para nuevo producto
            } else {
                $mensaje = "Error al borrar el producto: " . $stmt->error;
            }
            $stmt->close();
        }
    } elseif ($accion === 'editar') {
        $productoId = isset($_POST['productoId']) ? intval($_POST['productoId']) : 0;
        if ($productoId > 0) {
            $stmt = $conexion->prepare("SELECT * FROM productos WHERE idproducto = ? LIMIT 1");
            $stmt->bind_param("i", $productoId);
            $stmt->execute();
            $resultado = $stmt->get_result();
            if ($resultado && $resultado->num_rows === 1) {
                $producto_editar = $resultado->fetch_assoc();
                // Cargar solo precio y descripción para editar
                $_POST['productoId'] = $producto_editar['idproducto'];
                $_POST['precioProducto'] = $producto_editar['precio'];
                $_POST['descripcionProducto'] = $producto_editar['descripcion'];
                // Para edición solo permitimos cambiar precio y descripción
                $_POST['categoriaProducto'] = '';
                $_POST['nombreProducto'] = '';
            } else {
                $mensaje = "Producto no encontrado para editar.";
            }
            $stmt->close();
        }
    }
}

// Cargar productos para la tabla
$resultado = $conexion->query("SELECT * FROM productos ORDER BY idproducto ASC");
if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $productos[] = $fila;
    }
} else {
    $mensaje = $mensaje ?: 'No hay productos registrados.';
}
?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
    <meta charset="utf-8" />
    <title>Siatec Productos</title>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <script src="js/productos.js"></script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a href="#" class="navbar-brand">Siatec Perú - Productos</a>
    <form class="form-inline ml-auto d-flex" method="get" action="">
        <input
            type="search"
            id="search-product"
            class="form-control mr-sm-2"
            placeholder="Buscar producto"
            name="buscar"
        />
        <!-- Separar el botón de búsqueda con un margen más amplio -->
        <button type="submit" class="btn btn-success ml-3">Eliminar</button>
    </form>
</nav>

<div class="container mt-4">

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-info">
            <?php echo htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <form method="post" action="">
        <input
            type="hidden"
            name="productoId"
            value="<?php echo isset($_POST['productoId']) ? (int)$_POST['productoId'] : 0; ?>"
        />

        <?php if (empty($_POST['productoId'])): ?>
            <div class="form-group mb-2">
                <select
                    id="categoriaProducto"
                    name="categoriaProducto"
                    class="form-control"
                    required
                >
                    <option value="" disabled <?php echo empty($_POST['categoriaProducto']) ? 'selected' : ''; ?>>
                        Seleccione categoría
                    </option>
                    <?php
                    $categorias = ['Electrónica', 'Ropa', 'Alimentos', 'Hogar', 'Deportes'];
                    foreach ($categorias as $cat) {
                        $selected = (isset($_POST['categoriaProducto']) && $_POST['categoriaProducto'] === $cat) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($cat, ENT_QUOTES, 'UTF-8') . '" ' . $selected . '>' . htmlspecialchars($cat, ENT_QUOTES, 'UTF-8') . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="form-group mb-2">
                <input
                    type="text"
                    id="nombreProducto"
                    name="nombreProducto"
                    class="form-control"
                    placeholder="Nombre del producto"
                    required
                    value="<?php echo isset($_POST['nombreProducto']) ? htmlspecialchars($_POST['nombreProducto'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                />
            </div>
        <?php else: ?>
            <div class="form-group mb-2">
                <input
                    type="text"
                    class="form-control"
                    value="Edición solo precio y descripción"
                    readonly
                />
            </div>
        <?php endif; ?>

        <div class="form-group mb-2">
            <input
                type="number"
                id="precioProducto"
                name="precioProducto"
                class="form-control"
                placeholder="Precio"
                min="0"
                step="0.01"
                required
                value="<?php echo isset($_POST['precioProducto']) ? htmlspecialchars($_POST['precioProducto'], ENT_QUOTES, 'UTF-8') : ''; ?>"
            />
        </div>

        <div class="form-group mb-3">
            <textarea
                id="descripcionProducto"
                name="descripcionProducto"
                rows="5"
                class="form-control"
                placeholder="Descripción del producto"
                required
            ><?php echo isset($_POST['descripcionProducto']) ? htmlspecialchars($_POST['descripcionProducto'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary btn-block" id="submit-producto" name="accion" value="guardar">
            Guardar Producto
        </button>
    </form>

    <table class="table table-bordered table-sm mt-4">
        <thead class="thead-light">
            <tr>
                <th>ID Producto</th>
                <th>Categoría</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="productos">
            <?php if (!empty($productos)): ?>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($producto['idproducto'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($producto['categoria'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($producto['producto'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo number_format((float)$producto['precio'], 2, '.', ''); ?></td>
                        <td><?php echo htmlspecialchars($producto['descripcion'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td>
                            <form method="post" action="" style="display:inline-block;">
                                <input type="hidden" name="productoId" value="<?php echo (int)$producto['idproducto']; ?>">
                                <button type="submit" name="accion" value="editar" class="btn btn-sm btn-warning">
                                    Editar
                                </button>
                            </form>

                            <form method="post" action="" style="display:inline-block;" onsubmit="return confirm('¿Seguro que deseas borrar este producto?');">
                                <input type="hidden" name="productoId" value="<?php echo (int)$producto['idproducto']; ?>">
                                <button type="submit" name="accion" value="borrar" class="btn btn-sm btn-danger">
                                    Borrar
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No hay productos para mostrar.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script
    src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
    crossorigin="anonymous"
></script>
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
    crossorigin="anonymous"
></script>
</body>
</html>
