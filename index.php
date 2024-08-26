<?php

require 'config/config.php';
require 'config/database.php';



$db = new Database();
$con = $db->conectarbd();


$idCategoria = $_GET['cat'] ?? '';
$orden = $_GET['orden'] ?? '';
$buscar = $_GET['q'] ?? '';



$orders = [
    'asc' => 'nombre ASC',
    'desc' => 'nombre DESC',
    'precio_alto' => 'valor DESC',
    'precio_bajo' => 'valor ASC'
];

$order = $orders[$orden] ?? '';

if (!empty($orden)) {
    $order = "ORDER BY $order";
}
if ($buscar != '') {
    $filtro = "AND nombre LIKE '%$buscar%'";
} else {
    $filtro = '';
}

if (!empty($idCategoria)) {
    $comando = $con->prepare("SELECT id, nombre, valor FROM productos WHERE disponible=1 $filtro AND id_categoria = ? $order");
    $comando->execute([$idCategoria]);
} else {
    $comando = $con->prepare("SELECT id, nombre, valor FROM productos WHERE disponible=1 $filtro $order");
    $comando->execute();
}

$resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

$sqlCategorias = $con->prepare("SELECT id, nombre FROM categorias WHERE activo=1");
$sqlCategorias->execute();
$categorias = $sqlCategorias->fetchAll(PDO::FETCH_ASSOC);

//print_r($_SESSION);

//session_destroy();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="css/estilos.css" rel="stylesheet">
</head>
<body class="d-flex flex-column h-100">

<?php include 'menu.php'; ?>

<main class="flex-shrink-0">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-header">
                        Categorías
                    </div>
                    <div class="list-group">
                        <a href="index.php" class="list-group-item list-group-item-action">
                            Todos los productos
                        </a>
                        <?php foreach ($categorias as $categoria) { ?>
                            <a href="index.php?cat=<?php echo $categoria['id']; ?>" class="list-group-item list-group-item-action <?php if ($idCategoria == $categoria['id']) echo 'active'; ?> ">
                                <?php echo $categoria['nombre']; ?>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-md-9">  
                <div class="row justify-content-end mb-2">
                    <div class="col-auto">
                        <form action="index.php" id="ordenForm" method="get">
                        <input type="hidden" name="cat" id="cat" value="<?php echo $idCategoria; ?>">
                        <select name="orden" id="orden" class="form-select form-select-sm" onchange="submitForm()">
                            <option value="">Filtrar por:</option>
                            <option value="precio_alto" <?php echo ($orden === 'precio_alto') ? 'selected' : ''; ?>>Precios más altos</option>
                            <option value="precio_bajo" <?php echo ($orden === 'precio_bajo') ? 'selected': ''; ?>>Precios más bajo</option>
                            <option value="asc" <?php echo ($orden === 'asc') ? 'selected': ''; ?>>Nombre A-Z</option>
                            <option value="desc" <?php echo ($orden === 'desc') ? 'selected' : ''; ?>>Nombre Z-A</option>
                        </select>

                        </form>
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-md-3 g-3">
                    <?php 
                    // Encontrar la altura máxima de las imágenes
                    $max_height = 0;
                    foreach($resultado as $row){ 
                        $id = $row['id'];
                        $imagen = "imagenes/Productos/" . $id . "/Prod.jpg";
                        if(!file_exists($imagen)){
                            $imagen = "imagenes/Productos/" . $id . "/Prod.jpeg";
                        }
                        if(!file_exists($imagen)){
                            $imagen = "imagenes/vacio.jpg";
                        }
                        list($width, $height) = getimagesize($imagen);
                        $max_height = max($max_height, $height);
                    }
                    ?>
                    <?php foreach($resultado as $row){ ?>
                        <div class="col mb-3">
                            <div class="card shadow-sm h-100">
                                <?php 
                                $id = $row['id'];
                                $imagen = "imagenes/Productos/" . $id . "/Prod.jpg";
                                if(!file_exists($imagen)){
                                    $imagen = "imagenes/Productos/" . $id . "/Prod.jpeg";
                                }
                                if(!file_exists($imagen)){
                                    $imagen = "imagenes/vacio.jpg";
                                }
                                ?>
                                <img src="<?php echo $imagen; ?>" class="card-img-top img-fluid" style="object-fit: cover; max-height: <?php echo $max_height; ?>px;" alt="Producto">
                                <div class="card-body d-flex flex-column">   
                                    <div class="mt-auto">
                                        <div class="card-title h5 mb-1 pb-1 border-bottom"><?php echo $row['nombre']; ?></div>
                                        <p class="card-text mb-3 border-bottom">$<?php echo $row['valor']; ?></p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="btn-group">
                                                <a href="detalles.php?id=<?php echo $row['id'];?>&token=<?php echo hash_hmac('sha1',$row['id'], KEY_TOKEN); ?>" class="btn btn-primary">Detalles</a>
                                            </div>
                                            <button class="btn btn-outline-success" type="button" onclick="addProducto(<?php echo $row['id']; ?>, '<?php echo hash_hmac('sha1',$row['id'], KEY_TOKEN); ?>')">Agregar al carrito</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</main>







<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script> 
function addProducto(id, token){
    let url = 'clases/carrito.php'
    let formData = new FormData()
    formData.append('id',id)
    formData.append('token',token)

    fetch(url, {
        method: 'POST',
        body: formData,
        mode: 'cors'
    }).then(response => response.json())
    .then(data => {
        if(data.ok){
           let elemento = document.getElementById("num_cart")
           elemento.innerHTML = data.numero
        }else{
            alert("No hay suficiente stock")
        }
    })
}

function submitForm() {
  document.getElementById('ordenForm').submit();
}

</script>
</body>
</html>