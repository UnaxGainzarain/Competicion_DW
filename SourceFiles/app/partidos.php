<?php
/**
 * @title: Proyecto integrador Ev01 - Página de Partidos
 * @description:  Muestra un listado de todos los partidos de la competición.
 *
 * @version    0.1
 *
 * @author ander_frago@cuatrovientos.org
 */

require_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../persistence/DAO/GenericDAO.php';
require_once __DIR__ . '/../persistence/conf/PersistentManager.php';
require_once __DIR__ . '/../persistence/DAO/EquipoDAO.php';
require_once __DIR__ . '/../persistence/DAO/PartidoDAO.php';

// --- Lógica de la página ---
$partidoDAO = new PartidoDAO();
$equipoDAO = new EquipoDAO();

$mensaje = ""; // Variable para mensajes de feedback al usuario

//Procesar formulario para añadir nuevo partido
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nombreLocal'], $_POST['nombreVisitante'], $_POST['resultado'], $_POST['jornada'])) {
    $idEquipoLocal = $_POST['nombreLocal'];
    $idEquipoVisitante = $_POST['nombreVisitante'];
    $resultado = $_POST['resultado'];
    $jornada = $_POST['jornada'];

    if (!empty($idEquipoLocal) && !empty($idEquipoVisitante) && !empty($resultado) && !empty($jornada)) {
        if ($idEquipoLocal === $idEquipoVisitante) {
            $mensaje = "<div class='alert alert-danger' role='alert'>Error: Un equipo no puede jugar contra sí mismo.</div>";
        } else {
            if ($partidoDAO->checkExistsInJornada($jornada, $idEquipoLocal, $idEquipoVisitante)) {
                $mensaje = "<div class='alert alert-danger' role='alert'>Error: Estos equipos ya se han enfrentado en esta jornada.</div>";
            } else {
                // Si no han jugado, procedemos a insertar el partido
                if ($partidoDAO->insert($idEquipoLocal, $idEquipoVisitante, $jornada, $resultado)) {
                    $mensaje = "<div class='alert alert-success' role='alert'>Partido añadido correctamente. La página se refrescará.</div>";
                    // Refrescar la página para ver el nuevo partido y limpiar el formulario
                    echo '<meta http-equiv="refresh" content="2">';
                } else {
                    $mensaje = "<div class='alert alert-danger' role='alert'>Error al añadir el partido.</div>";
                }
            }
        }
    } else {
        $mensaje = "<div class='alert alert-warning' role='alert'>Todos los campos son obligatorios.</div>";
    }
}

$jornada_seleccionada = $_GET['jornada'] ?? null;

// Obtener los partidos (filtrados o no) usando el DAO
$partidos = $partidoDAO->selectAllWithTeamNames($jornada_seleccionada);

// Obtener todos los equipos para los desplegables del formulario
$equipos = $equipoDAO->selectAll();

// Obtener todas las jornadas para el filtro
$jornadas = $partidoDAO->selectAllJornadas();
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Listado de Partidos <?php echo $jornada_seleccionada ? "- Jornada $jornada_seleccionada" : "" ?></h2>
        <form action="partidos.php" method="GET" class="d-flex">
            <select name="jornada" class="form-select me-2" onchange="this.form.submit()">
                <option value="">Todas las jornadas</option>
                <?php foreach ($jornadas as $j): ?>
                    <option value="<?php echo $j; ?>" <?php echo ($jornada_seleccionada == $j) ? 'selected' : ''; ?>>Jornada <?php echo $j; ?></option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Jornada</th>
                <th>Equipo Local</th>
                <th>Equipo Visitante</th>
                <th>Estadio</th>
                <th>Resultado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($partidos as $partido): ?>
                <tr>
                    <td><?php echo htmlspecialchars($partido['jornada']); ?></td>
                    <td><?php echo htmlspecialchars($partido['nombre_local']); ?></td>
                    <td><?php echo htmlspecialchars($partido['nombre_visitante']); ?></td>
                    <td><?php echo htmlspecialchars($partido['estadio']); ?></td>
                    <td><?php echo htmlspecialchars($partido['resultado']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
      <div class="col-md-8 mx-auto">
            <h2 class="mt-5">Añadir Nuevo Partido</h2>
            <?php echo $mensaje; // Muestra mensajes de éxito o error ?>
            <form action="partidos.php" method="POST">
                <div class="mb-3">
                    <label for="nombreLocal" class="form-label">Equipo Local</label>
                    <select class="form-select" id="nombreLocal" name="nombreLocal" required>
                        <option value="">Selecciona un equipo</option>
                        <?php foreach ($equipos as $equipo): ?>
                            <option value="<?php echo $equipo['id']; ?>"><?php echo htmlspecialchars($equipo['nombre']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="nombreVisitante" class="form-label">Equipo Visitante</label>
                    <select class="form-select" id="nombreVisitante" name="nombreVisitante" required>
                        <option value="">Selecciona un equipo</option>
                        <?php foreach ($equipos as $equipo): ?>
                            <option value="<?php echo $equipo['id']; ?>"><?php echo htmlspecialchars($equipo['nombre']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="resultado" class="form-label">Resultado (1, X, 2)</label>
                    <select class="form-select" id="resultado" name="resultado" required>
                        <option value="1">1</option><option value="X">X</option><option value="2">2</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="jornada" class="form-label">Jornada</label>
                    <input type="text" class="form-control" id="jornada" name="jornada" required>
                </div>
                <button type="submit" class="btn btn-primary">Añadir Partido</button>
            </form>
        </div>
</div>


<script src="<?php echo BASE_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
