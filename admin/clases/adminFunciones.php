<?php

function esNulo(array $parametros)
{
    foreach ($parametros as $parametro) {
        if (strlen(trim($parametro)) < 1) {
            return true;
        }
    }
    return false;
}

function validaPassword($password, $repassword)
{
    if (strcmp($password, $repassword) == 0) {
        return true;
    }
    return false;
}

function registraUsuario(array $datos, $con)
{
    $sql = $con->prepare("INSERT INTO usuarios (usuario, password, token, id_cliente) VALUES(?,?,?,?)");
    if($sql->execute($datos)){
        return true;
    }
    return false;
}

function usuarioExiste ($usuario, $con)
{
    $sql = $con->prepare("SELECT id FROM usuarios WHERE usuario LIKE ? LIMIT 1");
    $sql->execute([$usuario]);
    if ($sql->fetchColumn() > 0) {           
        return true;
    }
    return false;

}

function emailExiste ($correo, $con)
{
    $sql = $con->prepare("SELECT id FROM clientes WHERE correo LIKE ? LIMIT 1");
    $sql->execute([$correo]);
    if ($sql->fetchColumn() > 0) {           
        return true;
     }
    return false;
}

function mostrarMensajes($errors)
{
    if (count($errors) > 0) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert"><ul>';
        foreach ($errors as $error) {
            echo '<li>' . $error . '</li>';
        }
        echo '</ul>';
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
}

function login($usuario, $password, $con) {
    $sql = $con->prepare ("SELECT id, usuario, password, nombre FROM admin WHERE usuario LIKE ? AND activo = 1 LIMIT 1");
    $sql->execute([$usuario]);
    if ($row = $sql->fetch (PDO::FETCH_ASSOC)) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['nombre'];
                $_SESSION['user_type'] = 'admin';     
                header('Location: inicio.php');
                exit;
            }     
    }
    return 'El usuario y/o contraseña son incorrectos.';
}

function actualizaPassword($user_id, $password, $con){
    $sql = $con->prepare("UPDATE usuarios SET password=?, token_password = '', password_request = 0 WHERE id = ?");
    if($sql->execute([$password, $user_id])){
        return true;
    }
    return false;
}


