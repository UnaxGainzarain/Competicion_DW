<?php
/**
 * @title: Proyecto integrador Ev01 - Página principal
 * @description:  Bienvenida a la aplicación
 *
 * @version    0.1
 *
 * @author ander_frago@cuatrovientos.org
 */

require_once __DIR__ . '/../templates/header.php';
?>

<div class="container text-center">
  <div class="py-5 my-5 bg-light rounded-3">
      <h1 class='display-3'>Bienvenid@ a Competición</h1>
      <p class="lead">Gestiona los equipos y los resultados de los partidos.</p>
  </div>

  <div class="row justify-content-center g-4">
      <div class="col-md-4">
          <a href="<?php echo BASE_URL ?>/app/equipos.php" class="btn btn-primary btn-lg w-100 py-3">
              Gestionar Equipos
          </a>
      </div>
      <div class="col-md-4">
          <a href="<?php echo BASE_URL ?>/app/partidos.php" class="btn btn-secondary btn-lg w-100 py-3">
              Ver Partidos
          </a>
      </div>
  </div>
</div>

<footer class="footer mt-auto py-3 bg-light fixed-bottom">
  <div class="container">
    <span class="text-muted">Desarrollo Web - 2º DAM.</span>
  </div>
</footer>

<script src="<?php echo BASE_URL ?>/assets/js/bootstrap.bundle.min.js"></script>

</body>

</html>
