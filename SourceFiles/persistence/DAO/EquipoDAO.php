<?php
require_once 'GenericDAO.php';

class EquipoDAO extends GenericDAO
{
  //Se define una constante con el nombre de la tabla
  const USER_TABLE = 'equipos';
  //Funcion para seleccionar todos los equipos de la tabla
  public function selectAll() // La visibilidad debe ser public para usarlo fuera
  {
    $query = "SELECT * FROM " . self::USER_TABLE;
    $result = mysqli_query($this->conn, $query);
    $equipos = array();
    while ($equipoBD = mysqli_fetch_array($result)) {
      $equipo = array(
        'id' => $equipoBD["id"],
        'nombre' => $equipoBD["nombre"],
        'estadio' => $equipoBD["estadio"],
      );
      array_push($equipos, $equipo);
    }
    return $equipos;
  }
  public function selectById($id) // La visibilidad debe ser public
  {
    $query = "SELECT * FROM " . self::USER_TABLE . " WHERE id = ?";
    $stmt = mysqli_prepare($this->conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $equipo = array();
    if ($result && mysqli_num_rows($result) > 0) {
      $equipoBD = mysqli_fetch_assoc($result);
      $equipo = array(
        'id' => $equipoBD["id"],
        'nombre' => $equipoBD["nombre"],
        'estadio' => $equipoBD["estadio"],
      );
    }
    mysqli_stmt_close($stmt);
    return $equipo;
  }

  public function insert($nombre, $estadio)
  {
    $query = "INSERT INTO " . self::USER_TABLE . " (nombre, estadio) VALUES (?, ?)";
    $stmt = mysqli_prepare($this->conn, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $nombre, $estadio);
    return mysqli_stmt_execute($stmt);
  }

  public function delete($id)
  {
    $query = "DELETE FROM " . self::USER_TABLE . " WHERE id = ?";
    $stmt = mysqli_prepare($this->conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    return mysqli_stmt_execute($stmt);
  }
}
