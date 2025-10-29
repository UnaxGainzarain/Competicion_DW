<?php
/**
 * @title: Proyecto integrador Ev01 - Página de Equipos
 * @description:  Muestra los equipos y permite añadir nuevos.
 *
 * @version    0.1
 *
 * @author ander_frago@cuatrovientos.org
 */

require_once __DIR__ . '/../templates/header.php';

// --- Lógica de Base de Datos ---

// En una aplicación real, estos datos estarían en un fichero de configuración.
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

$mensaje = "";

// Procesar el formulario para añadir un nuevo equipo
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nombre']) && isset($_POST['estadio'])) {
    $nombre = $_POST['nombre'];
    $estadio = $_POST['estadio'];

    if (!empty($nombre) && !empty($estadio)) {
        // Usamos sentencias preparadas para evitar inyección SQL
        $stmt = $db->prepare("INSERT INTO equipos (nombre, estadio) VALUES (?, ?)");
        $stmt->bind_param("ss", $nombre, $estadio);

        if ($stmt->execute()) {
            $mensaje = "<div class='alert alert-success' role='alert'>Equipo añadido correctamente.</div>";
        } else {
            // El nombre de equipo es UNIQUE, así que puede dar error si se repite
            $mensaje = "<div class='alert alert-danger' role='alert'>Error al añadir el equipo: " . $stmt->error . "</div>";
        }
        $stmt->close();
    } else {
        $mensaje = "<div class='alert alert-warning' role='alert'>El nombre y el estadio no pueden estar vacíos.</div>";
    }
}

// Obtener todos los equipos para mostrarlos en la tabla
$equipos = [];
$resultado = $db->query("SELECT * FROM equipos ORDER BY nombre ASC");
if ($resultado->num_rows > 0) {
    while($fila = $resultado->fetch_assoc()) {
        $equipos[] = $fila;
    }
}

$db->close();
?>

<div class="container mt-4">
    <div class="row">
        <!-- Columna para la lista de equipos -->
        <div class="col-md-8">
            <h2>Listado de Equipos</h2>
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Nombre del Equipo</th>
                        <th>Estadio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($equipos as $equipo): ?>
                        <tr>
                            <!-- TODO: El enlace a PartidosEquipo.php no está implementado todavía -->
                            <td><a href="PartidosEquipo.php?id=<?php echo $equipo['id']; ?>"><?php echo htmlspecialchars($equipo['nombre']); ?></a></td>
                            <td><?php echo htmlspecialchars($equipo['estadio']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($equipos)): ?>
                        <tr>
                            <td colspan="2" class="text-center">No hay equipos registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Columna para el formulario de añadir equipo -->
        <div class="col-md-4">
            <h2>Añadir Nuevo Equipo</h2>
            <?php echo $mensaje; // Muestra mensajes de éxito o error ?>
            <form action="equipos.php" method="POST">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre del Equipo</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="mb-3">
                    <label for="estadio" class="form-label">Estadio</label>
                    <input type="text" class="form-control" id="estadio" name="estadio" required>
                </div>
                <button type="submit" class="btn btn-primary">Añadir Equipo</button>
            </form>
        </div>
    </div>
</div>

<script src="<?php echo BASE_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
