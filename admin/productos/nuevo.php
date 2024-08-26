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

$sql = "SELECT id, nombre FROM categorias WHERE activo = 1";
$resultado = $con->query($sql);
$categorias = $resultado->fetchAll(PDO::FETCH_ASSOC);

?>

<script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>
<link href="../css/styles.css" rel="stylesheet" />

<style>
  .ck-editor_editable[role="textbox"] {
    min-height: 150px;
  }
</style>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Nuevo producto</h1>
        
        <form action="guarda.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" id="nombre" required autofocus>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" name="descripcion" id="editor" autofocus></textarea>
            </div>

            <div class="row mb-2">
                <div class="col">
                        <label for="imagen" class="form-label">Imagen</label>
                        <input type="file" class="form-control" name="imagen" id="imagen" accept="image/jpeg" required>
                </div>
            </div>
            
            <div class="row">
                <div class="col mb-3">
                    <label for="valor" class="form-label">Precio</label>
                    <input type="number" class="form-control" name="valor" id="valor" required autofocus>
                </div>

                <div class="col mb-3">
                    <label for="stock" class="form-label">Stock</label>
                    <input type="number" class="form-control" name="stock" id="stock" required autofocus>
                </div>
            </div>

            <div class="row">
                <div class="col-4 mb-3">
                    <label for="categoria" class="form-label">Categoría</label>
                        <select class="form-select" name="categoria" id="categoria" required>
                            <option selected>Seleccionar</option>
                            <?php foreach ($categorias as $categoria) { ?>
                                <option value="<?php echo $categoria['id']; ?>"><?php echo $categoria['nombre']; ?></option>
                            <?php } ?>
                        </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Guarda</button>
        </form>


    </div>
</main>

<script>
    ClassicEditor
        .create( document.querySelector( '#editor' ) )
        .catch( error => {
            console.error( error );
        } );
</script>


<?php require '../footer.php'; ?>

