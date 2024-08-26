<?php

require 'config/config.php';
require 'config/database.php';
$db = new Database();
$con = $db->conectarbd();

$id = isset($_GET['id']) ? $_GET['id'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';

if($id== '' || $token == ''){
    echo 'Error de petición, regrese a la página anterior y vuelva a intentarlo';
    exit;
} else{
    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);

    if($token == $token_tmp){

        $sql = $con->prepare("SELECT count(id)FROM productos WHERE id=? AND disponible=1");
        $sql->execute([$id]);
        if($sql->fetchColumn() > 0){

            $sql = $con->prepare("SELECT nombre, descripcion, valor FROM productos WHERE id=? AND disponible=1 LIMIT 1");
            $sql->execute([$id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $nombre = $row['nombre'];
            $descripcion = $row['descripcion'];
            $valor = $row['valor'];
        }
       

    }else{
        echo 'Error de petición, regrese a la página anterior y vuelva a intentarlo';
    exit;
    }
}

//print_r($_SESSION);
//print_r($id);
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
<body>

<?php include 'menu.php'; ?>

<!-- CARTAS DEL MENU-->
<main>
    <div class='container'> 
        <div class='row'> 
            <div class="col-md-6 order-md-1">
            <?php 
                //$id = $row['id'];
                $imagen = "imagenes/Productos/" . $id . "/Prod.jpg";
                if(!file_exists($imagen)){
                    $imagen = "imagenes/Productos/" . $id . "/Prod.jpeg";
                }
                if(!file_exists($imagen)){
                    $imagen = "imagenes/vacio.jpg";
                }
                ?>
                <img src="<?php echo $imagen; ?>"> </img>
            </div>
            <div class="col-md-6 order-md-2">
                <h2><?php echo $nombre ?> </h2>
                <h2><?php echo MONEDA . $valor; ?> </h2>
                <p class="lead">
                    <?php echo $descripcion; ?>
                </p>

                <div class="d-grid gap-3 col-10 mx-auto">
                    <button class="btn btn-primary" type="button" onclick="window.location.href = 'checkout.php';"> Proceder al pago </button>
                    <button class="btn btn-outline-primary" type="button" onclick="addProducto(<?php echo $id; ?>, '<?php echo $token_tmp; ?>')"> Agregar al carrito </button>
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
</script>

</body>
</html>