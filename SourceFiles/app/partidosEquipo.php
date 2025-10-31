<?php
/**
 * @title: Proyecto integrador Ev01 - Página de Partidos por Equipo
 * @description:  Muestra los partidos de un equipo específico.
 *
 * @version    0.1
 *
 * @author ander_frago@cuatrovientos.org
 */

session_start(); // Iniciar la sesión al principio de todo

require_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../persistence/DAO/GenericDAO.php';
require_once __DIR__ . '/../persistence/conf/PersistentManager.php';
require_once __DIR__ . '/../persistence/DAO/EquipoDAO.php';
require_once __DIR__ . '/../persistence/DAO/PartidoDAO.php';

// --- Lógica de Base de Datos ---
$equipoDAO = new EquipoDAO();
$partidoDAO = new PartidoDAO();
// Obtener el ID del equipo de la URL. Si no existe, redirigir.
$equipo_id = $_GET['id'] ?? null;
if (!$equipo_id) {
    header('Location: equipos.php');
    exit;
}

// Guardar el equipo visitado en la sesión para futuras visitas
$_SESSION['last_visited_team_id'] = $equipo_id;

// Obtener la información del equipo usando el DAO
$equipo = $equipoDAO->selectById($equipo_id);

if (!$equipo) {
    echo "<div class='container mt-4'><h2>Equipo no encontrado</h2></div>";
    require_once __DIR__ . '/../templates/footer.php';
    exit;
}

$nombre_equipo = $equipo['nombre'];

// Obtener los partidos del equipo usando el Dao
$partidos = $partidoDAO->selectPartidosByEquipo($equipo_id);
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
