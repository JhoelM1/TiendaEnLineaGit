<?php 
//print_r($_SESSION);
if (isset($_SESSION['user_type'])) {
  unset($_SESSION['user_id']);
  unset($_SESSION['user_name']);
  unset($_SESSION['user_cliente']);
  unset($_SESSION['user_type']);
  header('Location: index.php');
  exit;
}

/*
if (!isset($_SESSION['user_type'])) {
  header('Location: index.php');
  exit;
}
*/
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
  body {
    background-image: url('imagenes/fondo/FondoT3.jpg'); 
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
  }
</style>

<header data-bs-theme="dark">
  <div class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
      <a href="index.php" class="navbar-brand">
        <img src="imagenes/fondo/logo.png" alt="Logo" height="40"> 
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
          <?php if (!isset($_SESSION['user_id'])) { ?>
          <li>
            <a href="registro.php" class="nav-link">Registrarse</a>
          </li>
          <?php } ?>
        </ul>
        
        <form action="index.php" method="get" autocomplete="off">
          <div class="input-group pe-3">
            <input type="text" name="q" id="q" class="form-control form-control-sm" placeholder="Buscar..." aria-describedby="icon-buscar">
            <button type="submit" id="icon-buscar" class="btn btn-outline-info btn-sm">
              <i class="fas fa-search"></i>
            </button>
          </div>                      
        </form>

        <a href="checkout.php" class="btn btn-primary me-2">CARRITO
          <span id="num_cart" class="badge bg-secondary"><?php echo $num_cart; ?></span>
        </a>

        <?php if (isset($_SESSION['user_id'])) { ?>
          <div class="dropdown">
            <button class="btn btn-success dropdown-toggle" type="button" id="btn_session" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-user"></i> <?php echo $_SESSION['user_name']; ?>
            </button>
            <ul class="dropdown-menu" aria-labelledby="btn_session">
              <li><a class="dropdown-item" href="compras.php">Mis compras</a></li>
              <li><a class="dropdown-item" href="logout.php">Cerrar sesión</a></li>
            </ul>
          </div>
        <?php } else { ?>
          <a href="login.php" class="btn btn-success"><i class="fas fa-user"></i> Iniciar sesión</a>
        <?php } ?>
      </div>
    </div>
  </div>
</header>
