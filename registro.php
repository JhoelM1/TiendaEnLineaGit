<?php

require 'config/config.php';
require 'config/database.php';
require 'clases/clienteFunciones.php';

$db = new Database();
$con = $db->conectarbd();

$errors = [];

if(!empty($_POST)){

    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $correo = trim($_POST['correo']);
    $telefono = trim($_POST['telefono']);
    $cedula = trim($_POST['cedula']);
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);
    $repassword = trim($_POST['repassword']);


    if (esNulo ([$nombres, $apellidos, $correo, $telefono, $cedula, $usuario, $password, $repassword])) {
        $errors[] = "Debe llenar todos los campos";
    }
    
    if (!esEmail($correo)) {                 
        $errors[] = "La dirección de correo no es válida";
    }
    
    if(!validaPassword($password, $repassword)){
        $errors[] = "Las contraseñas no coinciden";
    }

    if(usuarioExiste($usuario, $con)){
        $errors[] = "El nombre de usuario $usuario ya existe";
    }

    if(emailExiste($correo, $con)){
        $errors[] = "El correo $correo ya fue usado";
    }

    if(count($errors) == 0){
        $id = registraCliente([$nombres, $apellidos, $correo, $telefono, $cedula], $con);

        if($id > 0){
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);
            $token = generarToken();
            if(!registraUsuario([$usuario, $pass_hash, $token, $id], $con)) {
                $errors[] = "error al registrar el usuario";
            }
        }else{
            $errors[] = "error al registrar el cliente";
        }
        
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<style>
  body {
    background-image: url('imagenes/fondo/FondoT3.jpg'); 
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
  }
</style>

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
    <a href="index.php" class="navbar-brand">
        <img src="imagenes/fondo/logo.png" alt="Logo" height="40"> 
    </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarHeader">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a href="index.php" class="nav-link">Catálogo</a>
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
</header>
<!-- CARTAS DEL MENU-->
<main class="mt-5 mb-5">
    <div class="container d-flex justify-content-center align-items-center" style="min-height: calc(100vh - 200px);">
        <div class="col-lg-6 col-md-8 col-sm-10 bg-light p-5 rounded shadow">
            <h2 class="mb-4 text-center">Datos del cliente</h2>

            <?php if(!empty($errors)) { ?>
            <div class="alert alert-danger">
                <?php foreach($errors as $error) { echo "<p>$error</p>"; } ?>
            </div>
            <?php } ?>

            <form class="row g-3" action="registro.php" method="post" autocomplete="off">
                <div class="form-floating col-12 mb-3">
                    <input type="text" name="nombres" id="nombres" class="form-control" placeholder="Nombres" required>
                    <label for="nombres"><span class="text-danger">*</span> Nombres</label>
                </div>

                <div class="form-floating col-12 mb-3">
                    <input type="text" name="apellidos" id="apellidos" class="form-control" placeholder="Apellidos" required>
                    <label for="apellidos"><span class="text-danger">*</span> Apellidos</label>
                </div>

                <div class="form-floating col-12 mb-3">
                    <input type="email" name="correo" id="correo" class="form-control" placeholder="Correo electrónico" required>
                    <label for="correo"><span class="text-danger">*</span> Correo electrónico</label>
                    <div id="validaCorreo" class="form-text text-danger"></div>
                </div>

                <div class="form-floating col-12 mb-3">
                    <input type="tel" name="telefono" id="telefono" class="form-control" placeholder="Teléfono" required>
                    <label for="telefono"><span class="text-danger">*</span> Teléfono</label>
                </div>

                <div class="form-floating col-12 mb-3">
                    <input type="text" name="cedula" id="cedula" class="form-control" placeholder="Cédula" required>
                    <label for="cedula"><span class="text-danger">*</span> Cédula</label>
                </div>

                <div class="form-floating col-12 mb-3">
                    <input type="text" name="usuario" id="usuario" class="form-control" placeholder="Nombre de Usuario" required>
                    <label for="usuario"><span class="text-danger">*</span> Nombre de Usuario</label>
                    <div id="validaUsuario" class="form-text text-danger"></div>
                </div>

                <div class="form-floating col-12 mb-3">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña" required>
                    <label for="password"><span class="text-danger">*</span> Contraseña</label>
                </div>

                <div class="form-floating col-12 mb-3">
                    <input type="password" name="repassword" id="repassword" class="form-control" placeholder="Repetir Contraseña" required>
                    <label for="repassword"><span class="text-danger">*</span> Repetir Contraseña</label>
                </div>

                <div class="form-text text-muted col-12 mb-3">
                    <b>Nota:</b> Los campos con asteriscos son obligatorios
                </div>

                <div class="col-12 d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary btn-lg">Registrarse</button>
                </div>
            </form>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>




<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>
  let txtUsuario = document.getElementById('usuario');
  txtUsuario.addEventListener("blur", function() {
    existeUsuario(txtUsuario.value);
  }, false)

  let txtCorreo = document.getElementById('correo');
  txtCorreo.addEventListener("blur", function() {
    existeEmail(txtCorreo.value);
  }, false)

  function existeUsuario(usuario) {
    let url = "clases/clienteAjax.php"
    let formData = new FormData()
    formData.append("action", "existeUsuario")
    formData.append("usuario", usuario)

    fetch(url, {
        method: 'POST',
        body: formData
    }).then(response => response.json())
      .then(data => {
        console.log(data);
        if (data.ok) {
            document.getElementById('usuario').value = ''
            document.getElementById('validaUsuario').innerHTML = 'Usuario no disponible'
        } else {
        document.getElementById('validaUsuario').innerHTML = ''
        }

    })
  }
  function existeEmail(correo) {
    let url = "clases/clienteAjax.php"
    let formData = new FormData()
    formData.append("action", "existeEmail")
    formData.append("correo", correo)

    fetch(url, {
        method: 'POST',
        body: formData
    }).then(response => response.json())
      .then(data => {
        console.log(data);
        if (data.ok) {
            document.getElementById('correo').value = ''
            document.getElementById('validaCorreo').innerHTML = 'Correo no disponible'
        } else {
        document.getElementById('validaCorreo').innerHTML = ''
        }

    })
  }
</script>


</body>
</html>