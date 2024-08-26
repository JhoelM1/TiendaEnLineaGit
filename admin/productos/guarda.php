<?php

require '../config/database.php';
require '../config/config.php';


if (!isset($_SESSION['user_type'])) {
    header('Location: ../index.php');
    exit;
}

if ($_SESSION['user_type'] != 'admin') {
    header('Location: ../../index.php');
    exit;
}

//print_r($_SESSION);

$db = new Database();
$con = $db->conectarbd();

$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$valor = $_POST['valor'];
$stock = $_POST['stock'];
$categoria = $_POST['categoria'];

$sql = "INSERT INTO productos (nombre, descripcion, valor, stock, id_categoria, disponible)
VALUES(?, ?, ?, ?, ?, 1)";
$stm = $con->prepare($sql);
if($stm->execute([$nombre, $descripcion, $valor, $stock, $categoria])){
    $id = $con->lastInsertId();

    if ($_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
        $dir = '../../imagenes/Productos/' . $id . '/';
        $permitidos = ['jpeg', 'jpg'];
    
        $arregloImagen = explode('.', $_FILES['imagen']['name']);
        $extension = strtolower(end($arregloImagen));
    
        if (in_array($extension, $permitidos)) {
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
    
            $ruta_img = $dir . 'Prod.jpg';
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_img)) {
                echo "El archivo se cargó correctamente.";
            } else {
                echo "Error al cargar el archivo.";
            }
        } else {
            echo "Archivo no permitido";
        }
    } else {
        echo "No eviaste archivo";
    }
}
                                   
header('Location: index.php');
?>

