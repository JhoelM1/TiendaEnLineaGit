<?php 
require 'config/database.php';
require 'config/config.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header('Location: index.php');
    exit;
}

$db = new Database();
$con = $db->conectarbd();

$FechaH = date('Y-m-d');
$lunes = date('Y-m-d', strtotime('monday this week',strtotime($FechaH)));
$domingo = date('Y-m-d', strtotime('sunday this week',strtotime($FechaH)));

$fechaIni = new DateTime($lunes);
$fechaFin = new DateTime($domingo);

$diasVentas = [];

for($i = $fechaIni; $i <=$fechaFin; $i->modify('+1 day')){
    $diasVentas[] = totalDia($con,$i->format('Y-m-d'));
}

$diasVentas = implode(',', $diasVentas);

// ------

// Obtener los productos más vendidos
$listaProductos = ProductosMV($con, $lunes, $domingo);
// Inicializar arrays para almacenar nombres y cantidades de productos
$nombreProductos = [];
$cantidadProductos = [];

// Recorrer la lista de productos
foreach ($listaProductos as $producto) {
    // Almacenar el nombre del producto
    $nombreProductos[] = $producto['nombre'];

    // Almacenar la cantidad vendida del producto
    $cantidadProductos[] = $producto['cantidad'];
}

// Convertir arrays en cadenas separadas por comas
$nombreProductos = implode("','", $nombreProductos);
$cantidadProductos = implode(',', $cantidadProductos);



function totalDia($con, $fecha){
    $sql = "SELECT IFNULL(SUM(total), 0) AS total FROM venta
    WHERE DATE (fecha) = '$fecha' AND status LIKE 'COMPLETED'";
    $resultado = $con->query($sql);
    $row = $resultado->fetch(PDO:: FETCH_ASSOC);
    
    return $row['total'];
}

function ProductosMV($con, $fechaIni, $fechaFin){
    $sql = "SELECT SUM(dv.cantidad) AS cantidad, dv.nombre FROM detalle_venta AS dv
    INNER JOIN venta AS v ON dv.id_venta = v.id
    WHERE DATE(v.fecha) BETWEEN '$fechaIni' AND '$fechaFin'
    GROUP BY dv.id_producto, dv.nombre
    ORDER BY SUM(dv.cantidad) DESC
    LIMIT 5";
    $resultado = $con->query($sql);
    return $resultado->fetchAll(PDO:: FETCH_ASSOC);
}

include 'header.php';
?>
<main>
    <div class="container-fluid px-4">
            <h1 class="mt-4">Bienvenido <?php echo $_SESSION['user_name'] ?>!</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Resumen semanal rápido</li>
            </ol>

            <div class="row">
                <div class="col-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            Ventas semanales
                        </div>
                        <div class="card-body">
                            <canvas id="myChart"></canvas>
                        </div>
                    
                    </div>
                </div>

                <div class="col-5">
                    <div class="card mb-4">
                        <div class="card-header">
                            Lista de productos más vendidos en la semana
                        </div>
                        <div class="card-body">
                            <canvas id="chart-productos"></canvas>
                        </div>
                    
                    </div>
                </div>
            </div>
    </div>
</main>

<script>
  const ctx = document.getElementById('myChart');

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes','Sábado', 'Domingo'],
      datasets: [{
        label: 'Ingresos en USD',
        data: [<?php echo $diasVentas; ?>],
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });

  const ctxProductos = document.getElementById('chart-productos');

  let chartProd = new Chart(ctxProductos, {
    type: 'pie',
    data: {
      labels: ['<?php echo $nombreProductos; ?>'],
      datasets: [{
        data: [<?php echo $cantidadProductos; ?>],
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });

</script>

<?php include 'footer.php'; ?>
      