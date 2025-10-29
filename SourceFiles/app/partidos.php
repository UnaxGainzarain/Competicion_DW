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
            p.jornada,
            p.resultado,
            equipo_local.nombre AS nombre_local,
            equipo_visitante.nombre AS nombre_visitante
        FROM partidos p
        JOIN equipos AS equipo_local ON p.id_equipo_local = equipo_local.id
        JOIN equipos AS equipo_visitante ON p.id_equipo_visitante = equipo_visitante.id
        ORDER BY p.jornada ASC";

$resultado = $db->query($sql);
$partidos = [];
if ($resultado->num_rows > 0) {
    while($fila = $resultado->fetch_assoc()) {
        $partidos[] = $fila;
    }
}

$db->close();
?>

<div class="container mt-4">
    <h2>Listado de Partidos</h2>
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
        </tbody>
    </table>
</div>

<script src="<?php echo BASE_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
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
            p.jornada,
            p.resultado,
            equipo_local.nombre AS nombre_local,
            equipo_visitante.nombre AS nombre_visitante
        FROM partidos p
        JOIN equipos AS equipo_local ON p.id_equipo_local = equipo_local.id
        JOIN equipos AS equipo_visitante ON p.id_equipo_visitante = equipo_visitante.id
        ORDER BY p.jornada ASC";

$resultado = $db->query($sql);
$partidos = [];
if ($resultado->num_rows > 0) {
    while($fila = $resultado->fetch_assoc()) {
        $partidos[] = $fila;
    }
}

$db->close();
?>

<div class="container mt-4">
    <h2>Listado de Partidos</h2>
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
        </tbody>
    </table>
      <div class="col-md-12">
            <h2>Añadir Nuevo Equipo</h2>
            <?php echo $mensaje; // Muestra mensajes de éxito o error ?>
            <form action="partidos.php" method="POST">
                <div class="mb-3">
                    <label for="nombreLocal" class="form-label">Equipo Local</label>
                    <input type="text" class="form-control" id="nombreLocal" name="nombre" required>
                </div>
                <div class="mb-3">
                    <label for="nombreVisitanq" class="form-label">Equipo Visitante</label>
                    <input type="text" class="form-control" id="nombreVisitante" name="nombre" required>
                </div>
                <div class="mb-3">
                    <label for="resultado" class="form-label">Resultado </label>
                    <input type="text" class="form-control" id="resultado" name="resultado" required>
                </div>
                <button type="submit" class="btn btn-primary">Añadir Equipo</button>
            </form>
        </div>
</div>


<script src="<?php echo BASE_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
