<?php

require '../config/config.php';
require '../config/database.php';

if(isset($_POST['action'])){

    $action = $_POST['action'];
    $id = isset($_POST['id']) ? $_POST['id']: 0;

    if($action =='eliminar'){
        $datos['ok'] = eliminar($id);
    } else if ($action =='agregar'){
        $cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : 0;
        $respuesta = agregar($id, $cantidad);
        if($respuesta>0) {
            $_SESSION['carrito']['productos'][$id] = $cantidad;
            $datos['ok'] = true;
        }else{
            $datos['ok'] = false;
            $datos['cantidadAnterior'] = $_SESSION['carrito']['productos'][$id];
        }
        $datos['sub'] = MONEDA . number_format($respuesta,2,'.',',');
    }  else{
        $datos['ok']= false;
    }
}else{
    $datos['ok']= false;
}

echo json_encode($datos);

function agregar($id, $cantidad){

    if($id > 0 && $cantidad > 0 && is_numeric($cantidad) && isset($_SESSION['carrito']['productos'][$id])){
            

            $db = new Database();
            $con = $db->conectarbd();
            $sql = $con->prepare("SELECT valor, stock FROM productos WHERE id=? AND disponible=1 LIMIT 1");
            $sql->execute([$id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $valor = $row['valor'];
            $stock = $row['stock'];

            if($stock>=$cantidad){
            return $cantidad * $valor;
        }
    }
    return 0;
}

function eliminar($id){
    if($id > 0){
        if(isset($_SESSION['carrito']['productos'][$id])){ 
           unset($_SESSION['carrito']['productos'][$id]);
           return true;
        }else{
            return false;
        }
    }
}