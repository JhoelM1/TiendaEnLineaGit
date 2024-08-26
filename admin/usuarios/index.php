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
$sql = "SELECT usuarios.id, CONCAT(clientes.nombres, ' ', clientes.apellidos) AS cliente, usuarios.usuario, usuarios.activacion,
CASE
WHEN usuarios.activacion = 1 THEN 'Activo'
WHEN usuarios.activacion = 0 THEN 'No activado'
ELSE 'Deshabilitado'
END AS estatus
FROM usuarios
INNER JOIN clientes ON usuarios.id_cliente = clientes.id ";
$resultado=$con->query($sql);


require '../header.php';

?>

<main>
    <div class="container">
        <h4>Listado de usuarios </h4>
        <hr>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>usuario</th>
                        <th>Estatus</th>
                        <th>Detalles</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) { ?>
                        <tr>
                            <td><?php echo $row['cliente']; ?></td>
                            <td><?php echo $row['usuario']; ?></td>
                            <td><?php echo $row['estatus']; ?></td>
                            <td>
                                <a href="cambiar_password.php?user_id=<?php echo $row['id']; ?>"
                                class="btn btn-warning btn-sm">
                                Cambiar contraseña
                                </a>
                                <?php if ($row['activacion'] == 1): ?>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#eliminaModal" data-bs-user="<?php echo $row['id']; ?>">
                                      Deshabilitar
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                    data-bs-target="#activaModal" data-bs-user="<?php echo $row['id']; ?>">
                                      Activa
                                    </button>
                                <?php endif; ?>  
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</main>


<!-- Modal -->
<div class="modal fade" id="eliminaModal" tabindex="-1" aria-labelledby="detalleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="detalleModalLabel">Alerta</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Quiere desactivar este usuario?
      </div>
      <div class="modal-footer">
        <form action="deshabilita.php" method="post">
            <input type="hidden" name="id">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-danger">Deshabilitar</button>

        </form>

      </div>
    </div>
  </div>
</div>

<!-- Modal 2 -->
<div class="modal fade" id="activaModal" tabindex="-1" aria-labelledby="detalleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="detalleModalLabel">Alerta</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Quiere activar este usuario?
      </div>
      <div class="modal-footer">
        <form action="activa.php" method="post">
            <input type="hidden" name="id">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-success">Activar</button>

        </form>

      </div>
    </div>
  </div>
</div>

<script>

const eliminaModal = document.getElementById('eliminaModal')
eliminaModal.addEventListener('show.bs.modal', event => {
  const button = event.relatedTarget
  const user = button.getAttribute('data-bs-user')
  const inputId = eliminaModal.querySelector('.modal-footer input')

  inputId.value = user

})

</script>

<script>

const activaModal = document.getElementById('activaModal')
activaModal.addEventListener('show.bs.modal', event => {
  const button = event.relatedTarget
  const user = button.getAttribute('data-bs-user')
  const inputId = activaModal.querySelector('.modal-footer input')

  inputId.value = user
})

</script>


<?php require '../footer.php'; ?>