<?php
/* =========================================================
   CONEXIÓN A LA BASE DE DATOS Y CARGA DE DATOS
   ========================================================= */
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sistema_usuarios";

// Conexión en el puerto 3307
$conn = new mysqli($servername,$username, $password,$dbname, 3307);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// 1. Crear tabla de cultivos si no existe e insertar datos iniciales por defecto
$sql_tabla = "CREATE TABLE IF NOT EXISTS cultivos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    cantidad INT NOT NULL,
    cosecha INT NOT NULL,
    estado VARCHAR(20) NOT NULL
)";
$conn->query($sql_tabla);

// Insertar datos de ejemplo si la tabla está vacía
$res_check =$conn->query("SELECT COUNT(*) as total FROM cultivos");
$row_check =$res_check->fetch_assoc();
if ($row_check['total'] == 0) {$conn->query("INSERT INTO cultivos (nombre, cantidad, cosecha, estado) VALUES 
        ('Tomate', 45, 32, 'Bueno'),
        ('Lechuga', 60, 18, 'Excelente'),
        ('Zanahoria', 80, 25, 'Bueno'),
        ('Frutilla', 35, 12, 'Regular')");
}

// 2. Obtener los cultivos desde la base de datos
$cultivos = [];$totalPlantas = 0;
$totalCosecha = 0;

$sql_cultivos = "SELECT * FROM cultivos";
$resultado = $conn->query($sql_cultivos);

if ($resultado &&$resultado->num_rows > 0) {
    while ($row =$resultado->fetch_assoc()) {
        $cultivos[$row['nombre']] = [
            "cantidad" => (int)$row['cantidad'],
            "cosecha" => (int)$row['cosecha'],
            "estado" => $row['estado']
        ];
        $totalPlantas += (int)$row['cantidad'];
        $totalCosecha += (int)$row['cosecha'];
    }
}

$totalCultivos = count($cultivos);

/* Datos ambientales */
$riegoSemanal = 4250;
$temperatura = 24;
$humedad = 68;
$estadoHuerta = "Excelente";

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard - Huerta 🌿</title>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
/* =========================================================
   ESTILO GENERAL (CELESTE)
========================================================= */
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI', sans-serif;
}

body{
    background: linear-gradient(135deg, #e1f5fe, #f0f4f8, #e0f7fa);
    min-height:100vh;
    padding:25px;
}

/* =========================================================
   CONTENEDOR
========================================================= */
.dashboard{
    width:100%;
    max-width:1200px;
    margin:auto;
}

/* =========================================================
   ENCABEZADO
========================================================= */
.header{
    background:white;
    padding:25px 30px;
    border-radius:25px;
    border:3px solid #81d4fa;
    box-shadow: 0 10px 30px rgba(2,119,189,0.12);
    display:flex;
    justify-space:space-between;
    align-items:center;
    margin-bottom:25px;
}

.header h1{ color:#0277bd; font-size:32px; }
.header p{ color:#666; margin-top:6px; font-size:16px; }

.btn-volver{
    text-decoration:none;
    background:#29b6f6;
    color:white;
    padding:13px 20px;
    border-radius:14px;
    font-weight:bold;
    transition:0.3s;
}

.btn-volver:hover{
    background:#0288d1;
    transform:scale(1.05);
}

/* =========================================================
   TARJETAS PRINCIPALES
========================================================= */
.cards{
    display:grid;
    grid-template-columns: repeat(4,1fr);
    gap:20px;
    margin-bottom:25px;
}

.card{
    background:white;
    padding:25px;
    border-radius:22px;
    border:2px solid #b3e5fc;
    box-shadow: 0 8px 25px rgba(2,119,189,0.08);
    transition:0.3s;
}

.card:hover{
    transform:translateY(-6px);
    box-shadow: 0 12px 30px rgba(2,119,189,0.16);
}

.card-icon{ font-size:38px; margin-bottom:12px; }
.card h3{ color:#555; font-size:15px; margin-bottom:8px; }
.numero{ color:#0277bd; font-size:32px; font-weight:bold; }
.card span{ color:#888; font-size:13px; }

/* =========================================================
   GRÁFICOS
========================================================= */
.charts{
    display:grid;
    grid-template-columns: 1fr 1fr;
    gap:25px;
    margin-bottom:25px;
}

.chart-container{
    background:white;
    padding:25px;
    border-radius:22px;
    border:2px solid #b3e5fc;
    box-shadow: 0 8px 25px rgba(2,119,189,0.08);
}

.chart-container h2{ color:#0277bd; font-size:21px; margin-bottom:20px; }
.chart-box{ height:300px; }

/* =========================================================
   TABLA
========================================================= */
.tabla-container{
    background:white;
    padding:25px;
    border-radius:22px;
    border:2px solid #b3e5fc;
    box-shadow: 0 8px 25px rgba(2,119,189,0.08);
}

.tabla-container h2{ color:#0277bd; margin-bottom:20px; }
table{ width:100%; border-collapse:collapse; }
th{ background:#0277bd; color:white; padding:14px; text-align:left; }
td{ padding:14px; border-bottom:1px solid #e1f5fe; color:#555; }
tr:hover{ background:#e1f5fe; }

/* =========================================================
   ESTADOS
========================================================= */
.estado{
    display:inline-block;
    padding:6px 13px;
    border-radius:20px;
    font-size:13px;
    font-weight:bold;
}

.excelente{ background:#b3e5fc; color:#01579b; }
.bueno{ background:#e1f5fe; color:#0277bd; }
.regular{ background:#fff0c7; color:#856404; }

/* =========================================================
   INFORMACIÓN AMBIENTAL
========================================================= */
.ambiente{
    display:grid;
    grid-template-columns: repeat(3,1fr);
    gap:20px;
    margin-top:25px;
}

.ambiente-card{
    background:white;
    padding:25px;
    border-radius:22px;
    border:2px solid #b3e5fc;
    text-align:center;
    box-shadow: 0 8px 25px rgba(2,119,189,0.08);
}

.ambiente-icon{ font-size:40px; margin-bottom:8px; }
.ambiente-card h3{ color:#555; font-size:15px; }
.ambiente-card strong{ display:block; color:#0277bd; font-size:28px; margin-top:6px; }

/* RESPONSIVE */
@media(max-width:950px){
    .cards{ grid-template-columns: repeat(2,1fr); }
    .charts{ grid-template-columns:1fr; }
}

@media(max-width:600px){
    body{ padding:15px; }
    .header{ flex-direction:column; text-align:center; gap:20px; }
    .header h1{ font-size:26px; }
    .cards{ grid-template-columns:1fr; }
    .ambiente{ grid-template-columns:1fr; }
    table{ font-size:13px; }
    th,td{ padding:9px; }
}
</style>
</head>

<body>

<div class="dashboard">

<div class="header">
    <div>
        <h1>🌿 Dashboard de la Huerta</h1>
        <p>Escuela Experimental ProA</p>
    </div>
    <a href="index.php" class="btn-volver">🏠 Volver al inicio</a>
</div>

<div class="cards">
    <div class="card">
        <div class="card-icon">🌱</div>
        <h3>Tipos de cultivos</h3>
        <div class="numero"><?php echo $totalCultivos; ?></div>
        <span>Cultivos registrados</span>
    </div>

    <div class="card">
        <div class="card-icon">🌿</div>
        <h3>Total de plantas</h3>
        <div class="numero"><?php echo $totalPlantas; ?></div>
        <span>Plantas en la huerta</span>
    </div>

    <div class="card">
        <div class="card-icon">🧺</div>
        <h3>Cosecha total</h3>
        <div class="numero"><?php echo $totalCosecha; ?> kg</div>
        <span>Producción registrada</span>
    </div>

    <div class="card">
        <div class="card-icon">💧</div>
        <h3>Riego semanal</h3>
        <div class="numero"><?php echo $riegoSemanal; ?> L</div>
        <span>Agua utilizada</span>
    </div>
</div>

<div class="charts">
    <div class="chart-container">
        <h2>🌱 Cantidad de plantas</h2>
        <div class="chart-box">
            <canvas id="plantasChart"></canvas>
        </div>
    </div>

    <div class="chart-container">
        <h2>🧺 Cosecha por cultivo</h2>
        <div class="chart-box">
            <canvas id="cosechaChart"></canvas>
        </div>
    </div>
</div>

<div class="tabla-container">
    <h2>📋 Estado de los cultivos</h2>

    <table>
        <thead>
            <tr>
                <th>Cultivo</th>
                <th>Plantas</th>
                <th>Cosecha</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($cultivos)): ?>
            <?php foreach($cultivos as $nombre =>$datos): ?>
            <tr>
                <td>🌱 <?php echo htmlspecialchars($nombre); ?></td>
                <td><?php echo $datos["cantidad"]; ?></td>
                <td><?php echo $datos["cosecha"]; ?> kg</td>
                <td>
                    <span class="estado <?php echo strtolower($datos["estado"]); ?>">
                        <?php echo $datos["estado"]; ?>
                    </span>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4" style="text-align:center;">No hay datos de cultivos cargados en la base de datos.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="ambiente">
    <div class="ambiente-card">
        <div class="ambiente-icon">🌡️</div>
        <h3>Temperatura</h3>
        <strong><?php echo $temperatura; ?> °C</strong>
    </div>

    <div class="ambiente-card">
        <div class="ambiente-icon">💧</div>
        <h3>Humedad</h3>
        <strong><?php echo $humedad; ?>%</strong>
    </div>

    <div class="ambiente-card">
        <div class="ambiente-icon">🌱</div>
        <h3>Estado de la huerta</h3>
        <strong><?php echo $estadoHuerta; ?></strong>
    </div>
</div>

</div>

<script>
/* =========================================================
   GRÁFICO DE PLANTAS (TONOS CELESTES)
========================================================= */
const plantasChart = document.getElementById('plantasChart');

new Chart(plantasChart, {
    type:'bar',
    data:{
        labels: <?php echo json_encode(array_keys($cultivos)); ?>,
        datasets:[{
            label: 'Cantidad de plantas',
            data: <?php echo json_encode(array_column($cultivos, 'cantidad')); ?>,
            backgroundColor:[
                '#0288d1',
                '#29b6f6',
                '#4fc3f7',
                '#81d4fa'
            ],
            borderRadius:12
        }]
    },
    options:{
        responsive:true,
        maintainAspectRatio:false,
        plugins:{ legend:{ display:false } },
        scales:{ y:{ beginAtZero:true } }
    }
});

/* =========================================================
   GRÁFICO DE COSECHA (TONOS CELESTES)
========================================================= */
const cosechaChart = document.getElementById('cosechaChart');

new Chart(cosechaChart, {
    type:'doughnut',
    data:{
        labels: <?php echo json_encode(array_keys($cultivos)); ?>,
        datasets:[{
            data: <?php echo json_encode(array_column($cultivos, 'cosecha')); ?>,
            backgroundColor:[
                '#0277bd',
                '#0288d1',
                '#29b6f6',
                '#81d4fa'
            ],
            borderColor:'#ffffff',
            borderWidth:3
        }]
    },
    options:{
        responsive:true,
        maintainAspectRatio:false,
        plugins:{ legend:{ position:'bottom' } }
    }
});
</script>

</body>
</html>