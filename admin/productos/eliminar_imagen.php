<?php

require '../config/config.php';


if (!isset($_SESSION['user_type'])) {
    header('Location: ../index.php');
    exit;
}

if ($_SESSION['user_type'] != 'admin') {
    header('Location: ../../index.php');
    exit;
}

// Se obtiene la URL de la imagen del formulario
$urlImagen = $_POST['urlImagen'] ?? '';

// Se verifica si la URL de la imagen no está vacía y si el archivo existe
if ($urlImagen !== '' && file_exists($urlImagen)) {
    // Se elimina el archivo de la imagen
    unlink($urlImagen);
}

?>