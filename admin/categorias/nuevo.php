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

require '../header.php';

//print_r($_SESSION);

$db = new Database();
$con = $db->conectarbd();

?>



<link href="../css/styles.css" rel="stylesheet" />


<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Nueva Categoría</h1>
        
        <form action="guarda.php" method="post" autocomplete="off">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" id="nombre" required autofocus>
            </div>

            <button type="submit" class="btn btn-primary">Guarda</button>
        </form>


    </div>
</main>

<?php require '../footer.php'; ?>

