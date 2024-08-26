<?php

require_once '../config/database.php';
require_once 'clienteFunciones.php';

$datos = [];

if(isset($_POST['action'])){
    $action = $_POST['action'];

    $db = new Database();
    $con = $db->conectarbd();

    if($action == 'existeUsuario'){
        $datos['ok'] = usuarioExiste ($_POST['usuario'], $con);
    }elseif($action == 'existeEmail'){
        $datos['ok'] = emailExiste ($_POST['correo'], $con);
    }
}

echo json_encode($datos);
