# Proyecto de Gestión de Competición Deportiva

Aplicación web simple desarrollada en PHP puro para gestionar los equipos y partidos de una competición deportiva. Permite visualizar, añadir y filtrar la información de manera intuitiva.

## Características Principales

- **Gestión de Equipos**:
  - Listar todos los equipos registrados con su nombre y estadio.
  - Añadir nuevos equipos a la competición.
  - Enlace directo desde cada equipo a su página de partidos.

- **Gestión de Partidos**:
  - Listar todos los partidos jugados.
  - Filtrar partidos por jornada.
  - Añadir nuevos partidos con validaciones para evitar que un equipo juegue contra sí mismo o que se dupliquen enfrentamientos en la misma jornada.

- **Navegación y Experiencia de Usuario**:
  - Uso de sesiones PHP para recordar el último equipo visitado y redirigir automáticamente al usuario a esa página para una navegación más fluida.
  - Interfaz limpia y responsiva gracias a Bootstrap 5.

## Tecnologías Utilizadas

- **Backend**: PHP 8.x
- **Base de Datos**: MySQL / MariaDB
- **Frontend**: HTML5, CSS3, Bootstrap 5

## Requisitos

Para ejecutar este proyecto, necesitarás un entorno de desarrollo local que incluya:

- [XAMPP](https://www.apachefriends.org/es/index.html), WAMP, MAMP o similar.
- PHP 8.0 o superior.
- MySQL o MariaDB.

## Instalación y Puesta en Marcha

Sigue estos pasos para configurar el proyecto en tu entorno local.

### 1. Clonar o Descargar el Proyecto

Coloca los ficheros del proyecto en el directorio `htdocs` de tu instalación de XAMPP. Por ejemplo: `C:/xampp/htdocs/Competicion/`.

### 2. Configurar la Base de Datos

1.  Abre HeidiSQL.
2.  Crea una nueva base de datos. Puedes llamarla `competicion_db`.
3.  Selecciona la base de datos recién creada y ve a la pestaña **SQL**.
4.  Copia y ejecuta el siguiente código SQL para crear las tablas `equipos` y `partidos`:
5.  Tambíen puedo hacer inserts para añadir datos a la base de datos.


```sql
CREATE TABLE `equipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `estadio` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `equipos` (`nombre`, `estadio`) VALUES
('Real Madrid', 'Santiago Bernabéu'),
('FC Barcelona', 'Camp Nou'),
('Atlético de Madrid', 'Wanda Metropolitano'),
('Sevilla FC', 'Ramón Sánchez-Pizjuán');


INSERT INTO `partidos` (`id_equipo_local`, `id_equipo_visitante`, `jornada`, `resultado`) VALUES
(1, 2, 1, '1'),
(3, 4, 1, 'X'),
(2, 3, 2, '2'),
(4, 1, 2, '1');

```

### 3. Configurar las Credenciales de Conexión

La aplicación lee las credenciales de la base de datos desde un fichero `credentials.json`.

1.  Navega a la carpeta `SourceFiles/persistence/conf/`.
2.  Midifica el fichero llamado `credentials.json`.
3.  Reemplaza el siguiente contenido por los valores de tu configuración local:

```json
{
  "host": "localhost",
  "name": "competicion_db",
  "user": "root",
  "password": ""
}
```

### 4. ¡Listo para Usar!

Abre tu navegador y accede a la URL correspondiente a tu configuración. Por ejemplo:

`http://localhost/Competicion/SourceFiles/app/`

## Estructura del Proyecto

```
/Competicion
├── SourceFiles/
│   ├── app/            # Ficheros principales de la aplicación (vistas/controladores)
│   ├── assets/         # Recursos como CSS y JS (Bootstrap)
│   ├── persistence/    # Lógica de acceso a datos (DAO, PersistentManager)
│   ├── templates/      # Partes reutilizables de la interfaz (header, footer)
│   └── utils/          # Clases de utilidad (SessionHelper)
└── README.md           # Este fichero
```

