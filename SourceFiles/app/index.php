<?php
/**
 * @title: Proyecto integrador Ev01 - Página principal
 * @description:  Bienvenida a la aplicación
 *
 * @version    0.1
 *
 * @author ander_frago@cuatrovientos.org
 */

session_start(); // Iniciar la sesión para poder leer sus variables

// Lógica de redirección:
// 1. Si el usuario ha visitado la página de partidos de un equipo, lo llevamos allí.
if (isset($_SESSION['last_visited_team_id'])) {
    $last_team_id = $_SESSION['last_visited_team_id'];
    header("Location: partidosEquipo.php?id=" . $last_team_id);
    exit; // Es importante terminar el script después de una redirección
} 
// 2. Si no, la página principal por defecto es la de equipos.
else {
    header("Location: equipos.php");
    exit; // Terminar el script
}

