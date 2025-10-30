<?php
require_once __DIR__ . '/../conf/PersistentManager.php'; // Asegúrate de que la ruta sea correcta

abstract class GenericDAO {

  //Conexión a BD
  protected $conn = null;

  //Constructor de la clase
  public function __construct() {
    $this->conn = PersistentManager::getInstance()->get_connection();
  }

   abstract protected function selectAll();
   abstract protected function selectById($id);
   abstract protected function delete($id);
}
