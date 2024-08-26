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
    <title>Contáctanos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="css/estilos.css" rel="stylesheet">
</head>
<body class="d-flex flex-column h-100">

<?php include 'menu.php'; ?>

<main class="flex-shrink-0 d-flex flex-column justify-content-center align-items-center text-center" style="flex-grow: 1;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <p class="display-4 text-primary mb-4">Contáctanos</p>
                        <p class="lead">Estamos aquí para ayudarte. Puedes ponerte en contacto con nosotros a través de la siguiente información:</p>
                        <div class="text-start">
                            <p><strong>Dirección:</strong> Calle Falsa 123, Ciudad Ejemplo, País</p>
                            <p><strong>Teléfono:</strong> +1 234 567 890</p>
                            <p><strong>Correo Electrónico:</strong> contacto@tiendaejemplo.com</p>
                            <p><strong>Horario de Atención:</strong> Lunes a Viernes, 9:00 AM - 6:00 PM</p>
                            <p><strong>Ubicación:</strong> <a href="https://www.google.com/maps" target="_blank">Ver en Google Maps</a></p>
                            <p><strong>Redes Sociales:</strong></p>
                            <ul class="list-unstyled">
                                <li><a href="https://www.facebook.com" target="_blank" class="text-decoration-none">Facebook</a></li>
                                <li><a href="https://www.twitter.com" target="_blank" class="text-decoration-none">Twitter</a></li>
                                <li><a href="https://www.instagram.com" target="_blank" class="text-decoration-none">Instagram</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
