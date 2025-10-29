-- /sql/BBDD.sql

CREATE DATABASE IF NOT EXISTS `competicion` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `competicion`;


-- Estructura de tabla para la tabla `equipos`


CREATE TABLE `equipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `estadio` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_unico` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `equipos`
--

INSERT INTO `equipos` (`nombre`, `estadio`) VALUES
('Real Madrid', 'Santiago Bernabéu'),
('FC Barcelona', 'Camp Nou'),
('Atlético de Madrid', 'Wanda Metropolitano'),
('Sevilla FC', 'Ramón Sánchez-Pizjuán');


-- Estructura de tabla para la tabla `partidos`


CREATE TABLE `partidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_equipo_local` int(11) NOT NULL,
  `id_equipo_visitante` int(11) NOT NULL,
  `jornada` int(11) NOT NULL,
  `resultado` enum('1','X','2') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_equipo_local` (`id_equipo_local`),
  KEY `fk_equipo_visitante` (`id_equipo_visitante`),
  CONSTRAINT `fk_equipo_local` FOREIGN KEY (`id_equipo_local`) REFERENCES `equipos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_equipo_visitante` FOREIGN KEY (`id_equipo_visitante`) REFERENCES `equipos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- Volcado de datos para la tabla `partidos`


INSERT INTO `partidos` (`id_equipo_local`, `id_equipo_visitante`, `jornada`, `resultado`) VALUES
(1, 2, 1, '1'),
(3, 4, 1, 'X'),
(2, 3, 2, '2'),
(4, 1, 2, '1');
