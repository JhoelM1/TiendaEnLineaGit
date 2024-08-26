<?php

require 'config/config.php';
require 'config/database.php';
require 'clases/clienteFunciones.php';

$token_session = $_SESSION['token'];
$orden = $_GET['orden'] ?? null;
$token = $_GET['token'] ?? null;


if ($orden == null || $token == null || $token != $token_session) {
    header("Location: compras.php");
    exit;
}

$db = new Database();
$con = $db->conectarbd();

$sqlVenta = $con->prepare("SELECT id, id_transac, fecha, total FROM venta WHERE id_transac = ? LIMIT 1");
$sqlVenta->execute([$orden]);
$rowVenta = $sqlVenta->fetch (PDO:: FETCH_ASSOC);
$idVenta = $rowVenta['id'];

// Consultar los detalles de la compra
$sqlDetalle = $con->prepare("SELECT id, nombre, valor, cantidad FROM detalle_venta WHERE id_venta = ?");
$sqlDetalle->execute([$idVenta]);

$errors = [];
$idCliente = $_SESSION['user_cliente'];


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
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-4">                                                                    
                <div class="card mb-3">
                    <div class="card-header">         
                        <strong>Detalle de la compra</strong>
                    </div>                     
                    <div class="card-body">   
                        <p><strong>Fecha de la compra: </strong> <?php echo $rowVenta['fecha']; ?></p>
                        <p><strong>Pedido: </strong> <?php echo $rowVenta['id_transac']; ?>
                        <p><strong>Total: </strong>
                            <?php echo MONEDA . ' ' . number_format($rowVenta ['total'], 2, '.', ','); ?>
                        </p>

                        <a href="compras.php" class="btn btn-primary">Regresar</a>
                    </div>                          
                </div>
            </div>
              <div class="col-12 col-md-8">
                <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>

        <tbody>
            <?php
            while ($row = $sqlDetalle->fetch(PDO::FETCH_ASSOC)) {
                $producto = $row['nombre'];
                $valor = $row['valor'];
                $cantidad = $row['cantidad'];
                $subtotal = $valor * $cantidad;
            ?>
            <tr>
                <td><?php echo $producto; ?></td>
                <td><?php echo $valor; ?></td>
                <td><?php echo $cantidad; ?></td>
                <td><?php echo $subtotal; ?></td>
            </tr>
            <?php } ?>
        </tbody>
</table>
              </div>                         
            </div>
</div>  
</div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>



</body>
</html>