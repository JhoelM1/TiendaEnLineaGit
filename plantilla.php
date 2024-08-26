<?php

require 'config/config.php';
require 'config/database.php';
require 'clases/clienteFunciones.php';

$db = new Database();
$con = $db->conectarbd();

$errors = [];



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
<header data-bs-theme="dark">
  
  <div class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
      <a href="index.php" class="navbar-brand ">
        <strong>E-Games Store</strong>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarHeader">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a href="#" class="nav-link active"> Menú</a>

            </li>
            <li>
            <a href="#" class="nav-link "> Información de contacto</a>
            </li>
            <li>
            <a href="#" class="nav-link "> Menú</a>
            </li>

        </ul>
        <a href="checkout.php" class="btn btn-primary">CARRITO
        <span id="num_cart" class="badge bg-secondary"><?php echo $num_cart; ?></span>
        </a>
    </div>
  </div>
</header>
<!-- CARTAS DEL MENU-->
<main>
    <div class="container">
        
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>



</body>
</html>