<?php

/**
 * class SessionUtils
 *
 * Contains util methods to deal with SESSIONS.
 *
 * @version    0.2
 *
 * @author     Ander Frago & Miguel Goyena <miguel_goyena@cuatrovientos.org>
 */
class SessionHelper {


  static function startSessionIfNotStarted() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start([
          'cookie_lifetime' => 86400,
        ]);
    }
  }

  static function destroySession() {
    self::startSessionIfNotStarted();
    $_SESSION = array();

    if (session_id() != "" || isset($_COOKIE[session_name()]))
      setcookie(session_name(), '', time() - 2592000, '/');

    session_destroy();
  }

  static function setSession($user) {
    self::startSessionIfNotStarted();
    $_SESSION['user'] = $user;
    if (!isset($_SESSION['CREATED'])) {
      $_SESSION['CREATED'] = time();
    } else if (time() - $_SESSION['CREATED'] > 1800) {
      session_regenerate_id(true);   
      $_SESSION['CREATED'] = time(); 
    }
  }

  static function loggedIn() {
    self::startSessionIfNotStarted();
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
     
      session_unset();    
      session_destroy();   
    }
    $_SESSION['LAST_ACTIVITY'] = time();
    if (isset($_SESSION['user'])) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * Guarda el ID del último equipo visitado en la sesión.
   * @param int $team_id El ID del equipo.
   */
  static function setLastVisitedTeam($team_id) {
    self::startSessionIfNotStarted();
    $_SESSION['last_visited_team_id'] = $team_id;
  }

  /**
   * Obtiene el ID del último equipo visitado desde la sesión.
   * @return int|null El ID del equipo o null si no existe.
   */
  static function getLastVisitedTeam() {
    self::startSessionIfNotStarted();
    return $_SESSION['last_visited_team_id'] ?? null;
  }

}