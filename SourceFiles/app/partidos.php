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

// Consulta para obtener los partidos con los nombres de los equipos
$sql = "SELECT 
// --- Lógica de la página ---

$jornada_seleccionada = $_GET['jornada'] ?? null;
$where_clause = "";
if ($jornada_seleccionada) {
    $where_clause = "WHERE p.jornada = " . intval($jornada_seleccionada);
}

// Consulta para obtener los partidos (filtrados o no) con los nombres de los equipos y el estadio
$sql_partidos = "SELECT 
            p.jornada,
            p.resultado,
            equipo_local.nombre AS nombre_local,
            equipo_visitante.nombre AS nombre_visitante
            equipo_visitante.nombre AS nombre_visitante,
            equipo_local.estadio AS estadio
        FROM partidos p
        JOIN equipos AS equipo_local ON p.id_equipo_local = equipo_local.id
        JOIN equipos AS equipo_visitante ON p.id_equipo_visitante = equipo_visitante.id
        ORDER BY p.jornada ASC";
        $where_clause
        ORDER BY p.jornada ASC, p.id ASC";

$resultado = $db->query($sql);
$resultado_partidos = $db->query($sql_partidos);
$partidos = [];
if ($resultado->num_rows > 0) {
    while($fila = $resultado->fetch_assoc()) {
if ($resultado_partidos->num_rows > 0) {
    while($fila = $resultado_partidos->fetch_assoc()) {
        $partidos[] = $fila;
    }
}

$mensaje = ""; // Variable para mensajes de feedback al usuario

//Procesar formulario para añadir nuevo partido
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nombreLocal'], $_POST['nombreVisitante'], $_POST['resultado'], $_POST['jornada'])) {
    $nombreLocal = $_POST['nombreLocal'];
    $nombreVisitante = $_POST['nombreVisitante'];
    $idEquipoLocal = $_POST['nombreLocal'];
    $idEquipoVisitante = $_POST['nombreVisitante'];
    $resultado = $_POST['resultado'];
    $jornada = $_POST['jornada'];

    if (!empty($nombreLocal) && !empty($nombreVisitante) && !empty($resultado) && !empty($jornada)) {
        // Usamos sentencias preparadas para obtener los IDs de forma segura
        $idEquipoLocal = null;
        $idEquipoVisitante = null;
    if (!empty($idEquipoLocal) && !empty($idEquipoVisitante) && !empty($resultado) && !empty($jornada)) {
        if ($idEquipoLocal === $idEquipoVisitante) {
            $mensaje = "<div class='alert alert-danger' role='alert'>Error: Un equipo no puede jugar contra sí mismo.</div>";
        } else {
            // Validar que los equipos no hayan jugado previamente en la misma jornada
            $stmt_check = $db->prepare(
                "SELECT id FROM partidos WHERE jornada = ? AND 
                ((id_equipo_local = ? AND id_equipo_visitante = ?) OR (id_equipo_local = ? AND id_equipo_visitante = ?))"
            );
            $stmt_check->bind_param("iiiii", $jornada, $idEquipoLocal, $idEquipoVisitante, $idEquipoVisitante, $idEquipoLocal);
            $stmt_check->execute();
            $res_check = $stmt_check->get_result();

        // 1. Obtener ID del equipo local
        $stmt = $db->prepare("SELECT id FROM equipos WHERE nombre = ?");
        $stmt->bind_param("s", $nombreLocal);
        $stmt->execute();
        $resLocal = $stmt->get_result();
        if ($resLocal->num_rows > 0) {
            $idEquipoLocal = $resLocal->fetch_assoc()['id'];
        }
            if ($res_check->num_rows > 0) {
                $mensaje = "<div class='alert alert-danger' role='alert'>Error: Estos equipos ya se han enfrentado en esta jornada.</div>";
            } else {
                // Si no han jugado, procedemos a insertar el partido
                $stmt_insert = $db->prepare("INSERT INTO partidos (id_equipo_local, id_equipo_visitante, jornada, resultado) VALUES (?, ?, ?, ?)");
                $stmt_insert->bind_param("iiis", $idEquipoLocal, $idEquipoVisitante, $jornada, $resultado);

        // 2. Obtener ID del equipo visitante
        $stmt->bind_param("s", $nombreVisitante);
        $stmt->execute();
        $resVisitante = $stmt->get_result();
        if ($resVisitante->num_rows > 0) {
            $idEquipoVisitante = $resVisitante->fetch_assoc()['id'];
        }

        // 3. Comprobar si ambos equipos existen
        if ($idEquipoLocal !== null && $idEquipoVisitante !== null) {
            // Si existen, procedemos a insertar el partido
            $stmt_insert = $db->prepare("INSERT INTO partidos (id_equipo_local, id_equipo_visitante, jornada, resultado) VALUES (?, ?, ?, ?)");
            $stmt_insert->bind_param("iiis", $idEquipoLocal, $idEquipoVisitante, $jornada, $resultado);

            if ($stmt_insert->execute()) {
                $mensaje = "<div class='alert alert-success' role='alert'>Partido añadido correctamente.</div>";
            } else {
                $mensaje = "<div class='alert alert-danger' role='alert'>Error al añadir el partido: " . $stmt_insert->error . "</div>";
                if ($stmt_insert->execute()) {
                    $mensaje = "<div class='alert alert-success' role='alert'>Partido añadido correctamente. La página se refrescará.</div>";
                    // Refrescar la página para ver el nuevo partido y limpiar el formulario
                    echo '<meta http-equiv="refresh" content="2">';
                } else {
                    $mensaje = "<div class='alert alert-danger' role='alert'>Error al añadir el partido: " . $stmt_insert->error . "</div>";
                }
                $stmt_insert->close();
            }
            $stmt_insert->close();
        } else {
            // Si uno o ambos no existen, mostramos un error
            $mensaje = "<div class='alert alert-danger' role='alert'>Error: Uno o ambos equipos no existen.</div>";
            $stmt_check->close();
        }
        $stmt->close();
    } else {
        $mensaje = "<div class='alert alert-warning' role='alert'>Todos los campos son obligatorios.</div>";
    }
}

// Comprobar conexión
if ($db->connect_error) {
  die("La conexión ha fallado: " . $db->connect_error);
// Obtener todas las jornadas para el desplegable
$jornadas_result = $db->query("SELECT DISTINCT jornada FROM partidos ORDER BY jornada ASC");
$jornadas = [];
while($fila = $jornadas_result->fetch_assoc()) {
    $jornadas[] = $fila['jornada'];
}

//consulta para obtener los partidos con los nombres de los equipos
// Obtener todos los equipos para los desplegables del formulario
$equipos_result = $db->query("SELECT id, nombre FROM equipos ORDER BY nombre ASC");
$equipos = [];
while($fila = $equipos_result->fetch_assoc()) {
    $equipos[] = $fila;
}

$db->close();
?>

<div class="container mt-4">
    <h2>Listado de Partidos</h2>
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
                    <input type="text" class="form-control" id="nombreLocal" name="nombreLocal" required>
                    <select class="form-select" id="nombreLocal" name="nombreLocal" required>
                        <option value="">Selecciona un equipo</option>
                        <?php foreach ($equipos as $equipo): ?>
                            <option value="<?php echo $equipo['id']; ?>"><?php echo htmlspecialchars($equipo['nombre']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="nombreVisitante" class="form-label">Equipo Visitante</label>
                    <input type="text" class="form-control" id="nombreVisitante" name="nombreVisitante" required>
                    <select class="form-select" id="nombreVisitante" name="nombreVisitante" required>
                        <option value="">Selecciona un equipo</option>
                        <?php foreach ($equipos as $equipo): ?>
                            <option value="<?php echo $equipo['id']; ?>"><?php echo htmlspecialchars($equipo['nombre']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="resultado" class="form-label">Resultado (1, X, 2)</label>
                    <input type="text" class="form-control" id="resultado" name="resultado" required>
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
