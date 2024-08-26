<?php
require '../config/config.php';
require '../config/database.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

$db = new Database();
$con = $db->conectarbd();

$id = $_POST['id'];

$sql = $con->prepare("UPDATE usuarios SET activacion = 1 WHERE id = ?");

// Ejecución de la consulta SQL
$sql->execute([$id]);

// Redirección a la página de inicio
header("Location: index.php");