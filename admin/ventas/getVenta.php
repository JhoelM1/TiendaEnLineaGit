<?php

require '../config/config.php';
require '../config/database.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header('Location: ../../index.php');
    exit;
}

$orden = $_POST['orden'] ?? null;

if($orden == null){
    exit;
}

$db = new Database();
$con = $db->conectarbd();


$sqlVenta = $con->prepare("SELECT venta.id, id_transac, fecha, total, CONCAT(nombres,' ',apellidos) AS cliente 
FROM venta 
INNER JOIN clientes ON venta.id_cliente = clientes.id
WHERE id_transac = ? LIMIT 1");
$sqlVenta->execute([$orden]);
$rowVenta = $sqlVenta->fetch (PDO:: FETCH_ASSOC);

if(!$rowVenta){
    exit;
}

$idVenta = $rowVenta['id'];
$fecha = new DateTime($rowVenta['fecha']);
$fecha = $fecha->format('d-m-Y H:i');
// Consultar los detalles de la compra
$sqlDetalle = $con->prepare("SELECT id, nombre, valor, cantidad FROM detalle_venta WHERE id_venta = ?");
$sqlDetalle->execute([$idVenta]);

$html = '<p><strong>Fecha: </strong>' . $fecha . '</p>';
$html .= '<p><strong>Orden: </strong>' . $rowVenta['id_transac'] . '</p>';
$html .= '<p><strong>Total: </strong>$' . number_format($rowVenta['total'], 2, '.', ',') . '</p>';

$html .= '<table class="table">
<thead>
<tr>
<th>Producto</th>
<th>Precio</th>
<th>Cantidad</th>
<th>Subtotal</th>
</tr>
</thead>';


$html .='<tbody>';
            while ($row = $sqlDetalle->fetch(PDO::FETCH_ASSOC)) {
                $valor = $row['valor'];
                $cantidad = $row['cantidad'];
                $subtotal = $valor * $cantidad;
                $html .='<tr>';
                $html .='<td>'.$row['nombre'].'</td>';       
                $html .='<td>'. $valor.'</td>';      
                $html .='<td>'. $cantidad.'</td>';
                $html .='<td>'. $subtotal.'</td>';                   
                $html .=' </tr>';
            }
            $html .='</tbody></table>';


echo json_encode($html, JSON_UNESCAPED_UNICODE);

?>