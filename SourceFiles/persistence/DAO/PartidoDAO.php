<?php
require_once 'GenericDAO.php';

class PartidoDAO extends GenericDAO
{
  //Se define una constante con el nombre de la tabla
  const PARTIDO_TABLE = 'partidos';

  /**
   * Inserta un nuevo partido en la base de datos.
   */
  public function insert($idEquipoLocal, $idEquipoVisitante, $jornada, $resultado)
  {
    $query = "INSERT INTO " . self::PARTIDO_TABLE . " (id_equipo_local, id_equipo_visitante, jornada, resultado) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($this->conn, $query);
    mysqli_stmt_bind_param($stmt, 'iiis', $idEquipoLocal, $idEquipoVisitante, $jornada, $resultado);
    return mysqli_stmt_execute($stmt);
  }

  /**
   * Comprueba si dos equipos ya se han enfrentado en una jornada específica.
   * @return bool True si ya existe, false en caso contrario.
   */
  public function checkExistsInJornada($jornada, $idEquipoLocal, $idEquipoVisitante)
  {
    $query = "SELECT id FROM " . self::PARTIDO_TABLE . " WHERE jornada = ? AND 
              ((id_equipo_local = ? AND id_equipo_visitante = ?) OR (id_equipo_local = ? AND id_equipo_visitante = ?))";
    $stmt = mysqli_prepare($this->conn, $query);
    mysqli_stmt_bind_param($stmt, "iiiii", $jornada, $idEquipoLocal, $idEquipoVisitante, $idEquipoVisitante, $idEquipoLocal);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return $result->num_rows > 0;
  }

  /**
   * Selecciona todos los partidos, uniendo con la tabla de equipos para obtener los nombres.
   * Puede filtrar por jornada si se proporciona una.
   * @param int|null $jornada_seleccionada La jornada para filtrar, o null para obtener todos.
   * @return array Lista de partidos.
   */
  public function selectAllWithTeamNames($jornada_seleccionada = null)
  {
    $query = "SELECT 
                p.jornada,
                p.resultado,
                equipo_local.nombre AS nombre_local,
                equipo_visitante.nombre AS nombre_visitante,
                equipo_local.estadio AS estadio
              FROM " . self::PARTIDO_TABLE . " p
              JOIN equipos AS equipo_local ON p.id_equipo_local = equipo_local.id
              JOIN equipos AS equipo_visitante ON p.id_equipo_visitante = equipo_visitante.id";

    if ($jornada_seleccionada) {
      $query .= " WHERE p.jornada = ?";
    }

    $query .= " ORDER BY p.jornada ASC, p.id ASC";

    $stmt = mysqli_prepare($this->conn, $query);

    if ($jornada_seleccionada) {
      mysqli_stmt_bind_param($stmt, 'i', $jornada_seleccionada);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $partidos = [];
    if ($result->num_rows > 0) {
      while ($fila = $result->fetch_assoc()) {
        $partidos[] = $fila;
      }
    }
    mysqli_stmt_close($stmt);
    return $partidos;
  }

  /**
   * Obtiene todas las jornadas únicas que tienen partidos.
   * @return array Lista de números de jornada.
   */
  public function selectAllJornadas()
  {
    $query = "SELECT DISTINCT jornada FROM " . self::PARTIDO_TABLE . " ORDER BY jornada ASC";
    $result = mysqli_query($this->conn, $query);
    $jornadas = [];
    while ($fila = $result->fetch_assoc()) {
      $jornadas[] = $fila['jornada'];
    }
    return $jornadas;
  }

  /**
   * Selecciona todos los partidos de un equipo específico.
   * @param int $equipo_id El ID del equipo.
   * @return array Lista de partidos del equipo.
   */
  public function selectPartidosByEquipo($equipo_id)
  {
    $query = "SELECT 
                p.jornada,
                p.resultado,
                equipo_local.nombre AS nombre_local,
                equipo_visitante.nombre AS nombre_visitante
              FROM " . self::PARTIDO_TABLE . " p
              JOIN equipos AS equipo_local ON p.id_equipo_local = equipo_local.id
              JOIN equipos AS equipo_visitante ON p.id_equipo_visitante = equipo_visitante.id
              WHERE p.id_equipo_local = ? OR p.id_equipo_visitante = ?
              ORDER BY p.jornada ASC";

    $stmt = mysqli_prepare($this->conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $equipo_id, $equipo_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $partidos = [];
    while ($fila = $result->fetch_assoc()) {
      $partidos[] = $fila;
    }
    mysqli_stmt_close($stmt);
    return $partidos;
  }

  // --- Métodos abstractos de GenericDAO ---

  public function selectAll()
  {
    // Implementación simple, aunque es más útil selectAllWithTeamNames
    $query = "SELECT * FROM " . self::PARTIDO_TABLE;
    $result = mysqli_query($this->conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
  }

  public function selectById($id)
  {
    // No implementado ya que no se usa en la lógica actual. 
  }

  public function delete($id)
  {
    // No implementado ya que no se usa en la lógica actual.
  }
}
