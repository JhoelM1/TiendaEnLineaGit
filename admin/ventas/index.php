<?php
require '../config/config.php';
require '../config/database.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

$db = new Database();
$con = $db->conectarbd();

// Prepara una consulta SQL para seleccionar todas las transacciones para el cliente actual, ordenadas por fecha descendente
$sql = "SELECT id_transac, fecha, status, total, CONCAT(nombres,' ',apellidos) AS cliente
FROM venta 
INNER JOIN clientes ON venta.id_cliente = clientes.id
ORDER BY DATE (fecha) DESC";
$resultado=$con->query($sql);


require '../header.php';

?>

<main>
    <div class="container">
        <h4>Listado de ventas </h4>
        <a href="reportesGV.php" class="btn btn-success btn-sm">
            Reportes de ventas
        </a>
        <hr>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Código de venta</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Fecha</th>
                        <th>Detalles</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) { ?>
                        <tr>
                            <td><?php echo $row['id_transac']; ?></td>
                            <td><?php echo $row['cliente']; ?></td>
                            <td><?php echo $row['total']; ?></td>
                            <td><?php echo $row['fecha']; ?></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#detalleModal" data-bs-orden="<?php echo $row['id_transac']; ?>">
                                    Ver detalles
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</main>


<!-- Modal -->
<div class="modal fade" id="detalleModal" tabindex="-1" aria-labelledby="detalleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="detalleModalLabel">Detalle de la venta</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script>

const detalleModal = document.getElementById('detalleModal')
detalleModal.addEventListener('show.bs.modal', event => {
  const button = event.relatedTarget
  const orden = button.getAttribute('data-bs-orden')
  const modalBody = detalleModal.querySelector('.modal-body')
  const url = '<?php echo ADMIN_URL; ?>ventas/getVenta.php'

    let formData = new FormData()
    formData.append('orden', orden)

    fetch(url, {
            method: 'post',
            body: formData,
        })
        .then((resp)=> resp.json())
        .then(function(data){
            modalBody.innerHTML = data
        })
})
detalleModal.addEventListener('hide.bs.modal', event => {
    const modalBody = detalleModal.querySelector('.modal-body')
    modalBody.innerHTML = ''
})

</script>


<?php require '../footer.php'; ?>