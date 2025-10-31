<?php

/**
 * @title: Proyecto integrador Ev01 - Página principal
 * @description:  Bienvenida a la aplicación
 *
 * @version    0.1
 *
 * @author ander_frago@cuatrovientos.org
 */

require_once __DIR__ . '/../utils/SessionHelper.php';
SessionHelper::startSessionIfNotStarted();

// Lógica de redirección:
// 1. Si el usuario ha visitado la página de partidos de un equipo, lo llevamos allí.
$last_team_id = SessionHelper::getLastVisitedTeam();
if ($last_team_id !== null) {
  header("Location: partidosEquipo.php?id=" . $last_team_id);
  exit; // Es importante terminar el script después de una redirección
}
// 2. Si no, la página principal por defecto es la de equipos.
else {
  header("Location: equipos.php");
  exit; // Terminar el script
}
