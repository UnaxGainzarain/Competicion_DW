<?php
/**
 * @title: Proyecto integrador Ev01 - Cabecera y barrra de navegación.
 * @description:  Cabecera y barra de navegación principal de la aplicación.
 *
 * @version    0.2
 *
 * @author     Ander Frago & Miguel Goyena <miguel_goyena@cuatrovientos.org>
 */

// Definimos la constante BASE_URL para construir las rutas correctamente.
// En un proyecto más grande, esto estaría en un archivo de configuración central.
define('BASE_URL', '/Competicion/SourceFiles');
?>
<head>
    <meta charset="utf-8">
    <title>Competición</title>
    <meta name="viewport"
          content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo BASE_URL ?>/assets/css/bootstrap.css">
</head>


<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo BASE_URL ?>/app/index.php">Competición</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL ?>/app/index.php">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL ?>/app/equipos.php">Equipos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL ?>/app/partidos.php">Partidos</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
