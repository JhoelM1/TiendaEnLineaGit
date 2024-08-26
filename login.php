<?php

require 'config/config.php';
require 'config/database.php';
require 'clases/clienteFunciones.php';

if (isset($_SESSION['user_type'])) {
  unset($_SESSION['user_id']);
  unset($_SESSION['user_name']);
  unset($_SESSION['user_cliente']);
  unset($_SESSION['user_type']);
  header('Location: login.php');
  exit;
}

$db = new Database();
$con = $db->conectarbd();

$proceso = isset($_GET['pago']) ? 'pago': 'login';

$errors = [];

if(!empty($_POST)){

    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);
    $proceso = $_POST['proceso'] ?? 'login';

    if (esNulo ([$usuario, $password])) {
        $errors[] = "Debe llenar todos los campos";
    }

    if (count($errors) == 0) {
        $errors[] = login($usuario, $password, $con, $proceso);
      }
      
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="css/estilos.css" rel="stylesheet">
    <style>
        body {
            
            background: url('imagenes/fondo/fondo1.png') no-repeat center center fixed;
            background-size: cover;
        }

        .form-login {
            max-width: 400px;
            padding: 2rem;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-floating {
            margin-bottom: 1rem;
        }

        .form-floating label {
            color: #6c757d;
        }

        h2 {
            font-size: 1.75rem;
            color: #333;
            margin-bottom: 1.5rem;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<header data-bs-theme="dark">
  <div class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
      <a href="index.php" class="navbar-brand">
        <img src="imagenes/fondo/logo.png" alt="E-Games Store" style="height: 40px;"> <!-- Ajusta la altura según sea necesario -->
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarHeader">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a href="index.php" class="nav-link active">Catálogo</a>
          </li>
          <li>
            <a href="contacto.php" class="nav-link">Contáctanos</a>
          </li>
        </ul>
        <a href="checkout.php" class="btn btn-primary">CARRITO
          <span id="num_cart" class="badge bg-secondary"><?php echo $num_cart; ?></span>
        </a>
      </div>
    </div>
  </div>
</header>

<!-- CARTAS DEL MENU -->
<br>
<main class="form-login m-auto pt-4 text-center">
    <img src="imagenes/fondo/logo.png" alt="Logo" height="80" class="mb-3">
    <h2>Inicio de sesión</h2>

    <?php mostrarMensajes($errors); ?>

    <form action="login.php" method="post">
        <input type="hidden" name="proceso" value="<?php echo $proceso; ?>">

        <div class="form-floating mb-3">
            <input class="form-control" type="text" name="usuario" id="usuario" placeholder="Usuario" required>
            <label for="usuario">Usuario</label>
        </div>

        <div class="form-floating mb-3">
            <input class="form-control" type="password" name="password" id="password" placeholder="Contraseña" required>
            <label for="password">Contraseña</label>
        </div>

        <button type="submit" class="btn btn-primary mb-3">Iniciar sesión</button>

        <hr>

        <div class="col-12">
            ¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a>
        </div>
    </form>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
