<?php
require 'config/config.php';
require 'config/database.php';
$db = new Database();
$con = $db->conectarbd();
$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

$lista_carrito = array();

if ($productos != null) {
    foreach ($productos as $clave => $cantidad) {
        $sql = $con->prepare("SELECT id, nombre, valor, $cantidad AS cantidad FROM productos WHERE id=? AND disponible=1");
        $sql->execute([$clave]);
        $lista_carrito[] = $sql->fetch(PDO::FETCH_ASSOC);
    }
} else {
    header("Location: index.php");
    exit;
}
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

<main>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6">
                <h4>Detalles del pago</h4>
                <div id="paypal-button-container"></div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Resumen del Carrito</h5>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>SubTotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($lista_carrito == null): ?>
                                        <tr>
                                            <td colspan="2" class="text-center"><b>Lista vacía</b></td>
                                        </tr>
                                    <?php else:
                                        $total = 0;
                                        foreach ($lista_carrito as $producto):
                                            $_id = $producto['id'];
                                            $nombre = $producto['nombre'];
                                            $valor = $producto['valor'];
                                            $cantidad = $producto['cantidad'];   
                                            $subtotal = $cantidad * $valor;
                                            $total += $subtotal;
                                    ?>
                                        <tr>
                                            <td><?php echo $nombre; ?></td>
                                            <td>
                                                <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]"><?php echo MONEDA . number_format($subtotal, 2, '.', ','); ?></div> 
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                        <tr>
                                            <td class="fw-bold">Total:</td>
                                            <td class="fw-bold"><?php echo MONEDA . number_format($total, 2, '.', ',') ?></td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if ($lista_carrito != null): ?>
                            <div class="text-end mt-3">
                                <div id="paypal-button-container"></div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://www.paypal.com/sdk/js?client-id=<?php echo CLIENT_ID; ?>&currency=<?php echo CURRENCY; ?>"></script>

<script>
    paypal.Buttons({
        style: {
            color: 'blue',
            label: 'pay'
        },
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: <?php echo $total; ?>
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            let URL = 'clases/captura.php';
            actions.order.capture().then(function(detalles) {
                console.log(detalles);
                
                return fetch(URL, {
                    method: 'post',
                    headers: {
                        'content-type': 'application/json'
                    },
                    body: JSON.stringify({
                        detalles: detalles
                    })
                }).then(function(response) {
                    window.location.href = "completado.php";
                });
            });
        },
        onCancel: function(data) {
            alert("El pago se canceló");
            console.log(data);
        }
    }).render('#paypal-button-container');
</script>

</body>
</html>
