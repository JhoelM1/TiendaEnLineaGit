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

$id = $_GET['id'];

$sql = $con->prepare("SELECT id, nombre, descripcion, valor, stock, id_categoria FROM productos WHERE id= ? AND disponible = 1");
$sql->execute([$id]);
$producto = $sql->fetch(PDO::FETCH_ASSOC);

$sql = "SELECT id, nombre FROM categorias WHERE activo = 1";
$resultado = $con->query($sql);
$categorias = $resultado->fetchAll(PDO::FETCH_ASSOC);

$rutaImagenes = '../../imagenes/productos/'.$id. '/';
$imagenPrincipal = $rutaImagenes . 'Prod.jpg';
$imagenPrincipalE = $rutaImagenes . 'Prod.jpeg';

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
        <h1 class="mt-4">Moficar producto</h1>
        
        <form method="get" action="index.php">
            <button type="submit" class="btn btn-warning">Regresar</button>
        </form>

        <form action="actualiza.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($producto['id'], ENT_QUOTES); ?>">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" class="form-control" name="nombre" id="nombre" value="<?php echo $producto['nombre']; ?>" required autofocus>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción:</label>
                <textarea class="form-control" name="descripcion" id="editor" autofocus><?php echo $producto['descripcion']; ?></textarea>
            </div>

            <div class="row mb-2">
                <div class="col">
                        <label for="imagen" class="form-label">Imagen:</label>
                        <input type="file" class="form-control" name="imagen" id="imagen" accept="image/jpeg">
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-12 col-md-6">
                    <?php if (file_exists($imagenPrincipal)) { ?>
                    <img src="<?php echo $imagenPrincipal . '?id=' . time(); ?>" class="img-thumbnail my-3"><br>
                    <button class="btn btn-danger btn-sm" onclick="eliminaImagen('<?php echo $imagenPrincipal; ?>')">Eliminar imagen</button>
                    <?php } ?>
                    <?php if (file_exists($imagenPrincipalE)) { ?>
                    <img src="<?php echo $imagenPrincipalE; ?>" class="img-thumbnail my-3"><br>
                    <button class="btn btn-danger btn-sm" onclick="eliminaImagen('<?php echo $imagenPrincipalE; ?>')">Eliminar imagen</button>
                    <?php } ?>
                    
                </div>
            </div>

            
            <div class="row">
                <div class="col mb-3">
                    <label for="valor" class="form-label">Precio:</label>
                    <input type="number" class="form-control" name="valor" id="valor" value="<?php echo $producto['valor']; ?>" required autofocus>
                </div>

                <div class="col mb-3">
                    <label for="stock" class="form-label">Stock:</label>
                    <input type="number" class="form-control" name="stock" id="stock" value="<?php echo $producto['stock']; ?>" required autofocus>
                </div>
            </div>

            <div class="row">
                <div class="col-4 mb-3">
                    <label for="categoria" class="form-label">Categoría</label>
                        <select class="form-select" name="categoria" id="categoria" required>
                            <option selected>Seleccionar</option>
                                <?php foreach ($categorias as $categoria) { ?>
                                <option value="<?php echo $categoria['id']; ?>" <?php if ($categoria['id'] == $producto['id_categoria']) echo 'selected'; ?>><?php echo $categoria['nombre']; ?></option>
                                <?php } ?>
                        </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Guardar cambios</button>

        </form>
        <br>
        <form method="get" action="index.php">
            <button type="submit" class="btn btn-warning">Regresar</button>
        </form>


    </div>
</main>

<script>
    ClassicEditor
        .create( document.querySelector( '#editor' ) )
        .catch( error => {
            console.error( error );
        } );

    function eliminaImagen(urlImagen) {
    let url = 'eliminar_imagen.php';
    let formData = new FormData();
    formData.append('urlImagen', urlImagen);

    fetch(url, {
        method: 'POST',
        body: formData
    }).then((response) => {
        if (response.ok) {
        location.reload();
        }
    });
    }

</script>


<?php require '../footer.php'; ?>

