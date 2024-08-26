<?php

require 'config/config.php';
require 'config/database.php';
$db = new Database();
$con = $db->conectarbd();
$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos']: null;

//print_r($_SESSION);

$lista_carrito = array();

if($productos != null){
    foreach($productos as $clave => $cantidad){
        $sql = $con->prepare("SELECT id, nombre, valor, $cantidad AS cantidad FROM productos WHERE id=? AND disponible=1");
        $sql->execute([$clave]);
        $lista_carrito[] = $sql->fetch(PDO::FETCH_ASSOC);
    }
}



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
<body>

<?php include 'menu.php'; ?>

<main>
        <div class="container mt-4">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($lista_carrito == null): ?>
                            <tr>
                                <td colspan="5" class="text-center"><b>Carrito vacío</b></td>
                            </tr>
                        <?php else:
                            $total = 0;
                            foreach($lista_carrito as $producto):
                                $_id = $producto['id'];
                                $nombre = $producto['nombre'];
                                $valor = $producto['valor'];
                                $cantidad = $producto['cantidad'];
                                $subtotal = $cantidad * $valor;
                                $total += $subtotal;
                        ?>
                        <tr>
                            <td><?php echo $nombre; ?></td>
                            <td><?php echo MONEDA . number_format($valor, 2, '.', ','); ?></td>
                            <td>
                                <input type="number" min="1" max="15" step="1" value="<?php echo $cantidad ?>" size="5" id="cantidad_<?php echo $_id; ?>" onchange="actualizaCantidad(this.value, <?php echo $_id;?>)" class="form-control">
                            </td>
                            <td>
                                <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]" class="fw-bold"><?php echo MONEDA . number_format($subtotal, 2, '.', ','); ?></div>
                            </td>
                            <td>
                                <a id="eliminar" class="btn btn-danger btn-sm" data-bs-id="<?php echo $_id; ?>" data-bs-toggle="modal" data-bs-target="#eliminaModal">Eliminar</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="3"></td>
                            <td colspan="2">
                                <p class="h3 fw-bold">Total: <?php echo MONEDA . number_format($total, 2, '.', ',') ?></p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($lista_carrito != null): ?>
            <div class="row mt-3">
                <div class="col-md-5 offset-md-7">
                    <?php if (isset($_SESSION['user_cliente'])): ?>
                    <a href="pago.php" class="btn btn-primary btn-lg d-block">Realizar pago</a>
                    <?php else: ?>
                    <a href="login.php?pago" class="btn btn-primary btn-lg d-block">Realizar pago</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>
<!-- Modal -->
<div class="modal fade" id="eliminaModal" tabindex="-1" aria-labelledby="eliminaModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="eliminaModalLabel">Aviso</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ¿Quiere quitar el producto del carrito de compras?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Regresar</button>
        <button id="btn-elimina" type="button" class="btn btn-danger" onclick="eliminar()">Quitar</button>
      </div>
    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script> 
let eliminaModal= document.getElementById('eliminaModal')
eliminaModal.addEventListener('show.bs.modal',function(event){
    let button = event.relatedTarget
    let id= button.getAttribute('data-bs-id')
    let buttonElimina = eliminaModal.querySelector('.modal-footer #btn-elimina')
    buttonElimina.value= id
})


function actualizaCantidad(cantidad, id){
    let url = 'clases/actualizar_cart.php'
    let formData = new FormData()
    formData.append('action','agregar')
    formData.append('id',id)
    formData.append('cantidad',cantidad)

    fetch(url, {
        method: 'POST',
        body: formData,
        mode: 'cors'
    }).then(response => response.json())
    .then(data => {
        if(data.ok){
            let divsubtotal = document.getElementById('subtotal_' + id)
            divsubtotal.innerHTML = data.sub

            let total = 0.00
            let list = document.getElementsByName('subtotal[]')

            for(let i = 0; i< list.length; i++){
                total += parseFloat(list[i].innerHTML.replace(/[<?php echo MONEDA; ?>,]/g, ''))
            }
            total = new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 2
            }).format(total)
            document.getElementById('total').innerHTML = '<?php echo MONEDA; ?>' + total
        }else{
            let inputCantidad = document.getElementById('cantidad_' + id);
            inputCantidad.value = data.cantidadAnterior;
            alert("No hay suficiente stock")
        }
    })
}


function eliminar(){

    let botonElimina = document.getElementById('btn-elimina')
    let id= botonElimina.value
    let url = 'clases/actualizar_cart.php'
    let formData = new FormData()
    formData.append('action','eliminar')
    formData.append('id',id)


    fetch(url, {
        method: 'POST',
        body: formData,
        mode: 'cors'
    }).then(response => response.json())
    .then(data => {
        if(data.ok){
            location.reload()
        }
    })
}
</script>
</body>
</html>