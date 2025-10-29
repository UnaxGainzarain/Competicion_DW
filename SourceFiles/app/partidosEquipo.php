<?php
/**
 * @title: Proyecto integrador Ev01 - Página de Partidos por Equipo
 * @description:  Muestra los partidos de un equipo específico.
 *
 * @version    0.1
 *
 * @author ander_frago@cuatrovientos.org
 */

require_once __DIR__ . '/../templates/header.php';

// --- Lógica de Base de Datos ---

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "competicion";

// Crear conexión
$db = new mysqli($servername, $username, $password, $dbname);

// Comprobar conexión
if ($db->connect_error) {
  die("La conexión ha fallado: " . $db->connect_error);
}

// Obtener el ID del equipo de la URL. Si no existe, redirigir.
$equipo_id = $_GET['id'] ?? null;
if (!$equipo_id) {
    header('Location: equipos.php');
    exit;
}

// Usar sentencias preparadas para seguridad
$stmt = $db->prepare("SELECT nombre FROM equipos WHERE id = ?");
$stmt->bind_param("i", $equipo_id);
$stmt->execute();
$resultado_equipo = $stmt->get_result();
$equipo = $resultado_equipo->fetch_assoc();

if (!$equipo) {
    echo "<div class='container mt-4'><h2>Equipo no encontrado</h2></div>";
    exit;
}

$nombre_equipo = $equipo['nombre'];

// Consulta para obtener los partidos del equipo (local o visitante)
$stmt = $db->prepare("SELECT 
            p.jornada,
            p.resultado,
            equipo_local.nombre AS nombre_local,
            equipo_visitante.nombre AS nombre_visitante
        FROM partidos p
        JOIN equipos AS equipo_local ON p.id_equipo_local = equipo_local.id
        JOIN equipos AS equipo_visitante ON p.id_equipo_visitante = equipo_visitante.id
        WHERE p.id_equipo_local = ? OR p.id_equipo_visitante = ?
        ORDER BY p.jornada ASC");
$stmt->bind_param("ii", $equipo_id, $equipo_id);
$stmt->execute();
$resultado_partidos = $stmt->get_result();

$partidos = [];
while($fila = $resultado_partidos->fetch_assoc()) {
    $partidos[] = $fila;
}

$stmt->close();
$db->close();
?>

<div class="container mt-4">
    <h2>Partidos de <?php echo htmlspecialchars($nombre_equipo); ?></h2>
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Jornada</th>
                <th>Equipo Local</th>
                <th>Equipo Visitante</th>
                <th>Resultado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($partidos as $partido): ?>
                <tr>
                    <td><?php echo htmlspecialchars($partido['jornada']); ?></td>
                    <td><?php echo htmlspecialchars($partido['nombre_local']); ?></td>
                    <td><?php echo htmlspecialchars($partido['nombre_visitante']); ?></td>
                    <td><?php echo htmlspecialchars($partido['resultado']); ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($partidos)): ?>
                <tr>
                    <td colspan="4" class="text-center">Este equipo no tiene partidos registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="equipos.php" class="btn btn-secondary mt-3">Volver a Equipos</a>
</div>

<script src="<?php echo BASE_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
