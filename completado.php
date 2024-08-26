<?php

require 'config/config.php';
require 'config/database.php';

//print_r($_SESSION);

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
<body class="d-flex flex-column h-100">

<?php include 'menu.php'; ?>

<main class="flex-shrink-0 d-flex flex-column justify-content-center align-items-center text-center" style="flex-grow: 1;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <p class="display-4 text-success mb-4">¡Gracias por su compra!</p>
                <p class="lead">Su pedido ha sido procesado exitosamente. Puede ver el historial de sus compras en su perfil.</p>
                <p class="text-muted">Si tiene alguna pregunta, no dude en <a href="contacto.php" class="text-decoration-none">contactarnos</a>.</p>
                <a href="index.php" class="btn btn-primary mt-3">Regresar a la tienda</a>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
