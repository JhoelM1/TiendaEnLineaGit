<?php
require '../config/config.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

require '../header.php';

?>

<main class="flex-shrink-0">
    <div class="container mt-3">
        <h3>Reporte de ventas </h3>
  
        <form action="reporte_ventas.php" method="post" autocomplete="off" onsubmit="return validarFechas()">

        <div class="row mb-2">
            <div class="col-12 col-md-4">
            <label for="fecha_i" class="form-label">Fecha inicial:</label>
            <input type="date" class="form-control" name="fecha_i" id="fecha_i" required autofocus>
        </div>

            <div class="col-12 col-md-4">

            <label for="fecha_f" class="form-label">Fecha final:</label>
            <input type="date" class="form-control" name="fecha_f" id="fecha_f" required>

            </div>
        </div>

        <button type="submit" class="btn btn-primary">Generar reporte</button>

        </form>
    </div>
</main>


<script>
function validarFechas() {
    var fechaInicio = document.getElementById('fecha_i').value;
    var fechaFin = document.getElementById('fecha_f').value;

    var fechaInicioObj = new Date(fechaInicio);
    var fechaFinObj = new Date(fechaFin);

    // Verificar si la fecha de inicio es posterior a la fecha de fin
    if (fechaInicioObj > fechaFinObj) {
        alert('La fecha inicial no puede ser posterior a la fecha final.');
        return false; // Detener el envío del formulario
    }

    return true; // Permitir el envío del formulario
}
</script>


<?php require '../footer.php'; ?>