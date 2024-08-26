<?php

require '../config/config.php';
require '../config/database.php';
$db = new Database();
$con = $db->conectarbd();

$json = file_get_contents('php://input');
$datos = json_decode($json, true);

echo '<pre>';
print_r($datos);
echo '</pre>';

if(is_array($datos)){

    $id_Cliente = $_SESSION['user_cliente'];
    $sql = $con->prepare("SELECT correo FROM clientes WHERE id=? AND estatus=1");
    $sql->execute([$id_Cliente]);
    $row_cliente = $sql->fetch(PDO::FETCH_ASSOC);

    $id_transac = $datos['detalles']['id'];
    $total = $datos['detalles']['purchase_units'][0]['amount']['value'];
    $status = $datos['detalles']['status'];
    $fecha = $datos['detalles']['update_time'];
    date_default_timezone_set('America/Guayaquil'); // Establece la zona horaria a la de Ecuador
    $fecha_nueva = date('Y-m-d H:i:s', strtotime($fecha));
    $correo = $row_cliente['correo'];
    //$correo = $datos['detalles']['payer']['email_address'];
    //$id_Cliente = $datos['detalles']['payer']['payer_id'];
   

    $sql = $con->prepare("INSERT INTO venta (id_transac, fecha, status, correo, id_cliente, total) VALUES (?,?,?,?,?,?)");
    $sql->execute([$id_transac, $fecha_nueva, $status, $correo, $id_Cliente, $total]);
    $id = $con->lastInsertId();


    if($id >0){
        $productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos']: null;
        if($productos != null){
            foreach($productos as $clave => $cantidad){
                $sql = $con->prepare("SELECT id, nombre, valor FROM productos WHERE id=? AND disponible=1");
                $sql->execute([$clave]);
                $row_prod = $sql->fetch(PDO::FETCH_ASSOC);

                $valor = $row_prod['valor'];

                $sql_insert = $con->prepare("INSERT INTO detalle_venta (id_venta, id_producto, nombre, valor, cantidad) VALUES (?,?,?,?,?)");
                if($sql_insert->execute([$id, $row_prod['id'], $row_prod['nombre'], $valor, $cantidad])) {
                    restarStock($row_prod['id'], $cantidad, $con );
                }

            }
        }
        unset($_SESSION['carrito']);
        header("Location: index.php");

    }
}

function restarStock($id, $cantidad, $con){
    $sql = $con->prepare("UPDATE productos SET stock = stock - ? WHERE id = ? ");
    $sql->execute([$cantidad, $id]);
}