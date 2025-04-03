# Registro Diario

El proyecto **Registro Diario** es una aplicación web diseñada para que los usuarios puedan registrar, gestionar y visualizar sus tareas diarias. Incluye funcionalidades como la creación de tareas, visualización de un historial, generación de reportes en PDF y gestión de usuarios.

## Características

- **Registro de tareas**: Los usuarios pueden agregar tareas con detalles como fecha, actividad, tiempo dedicado, observaciones y la semana correspondiente.
- **Historial de tareas**: Visualización de todas las tareas registradas en una tabla organizada.
- **Generación de PDF**: Descarga de un reporte en formato PDF con las tareas registradas.
- **Gestión de usuarios**: Cada usuario tiene su propio espacio para registrar y gestionar sus tareas.
- **Edición y eliminación de tareas**: Los usuarios pueden modificar o eliminar tareas existentes.

---

## Requisitos previos

Antes de ejecutar el proyecto, asegúrate de tener instalado lo siguiente:

- **Servidor web**: Apache, Nginx o cualquier servidor compatible con PHP.
- **PHP**: Versión 7.4 o superior.
- **Base de datos**: MySQL o MariaDB.
- **Composer** (opcional): Para gestionar dependencias de PHP.

---

## Instalación

1. **Clona el repositorio**:
   ```bash
   git clone https://github.com/KenMastters/Registro-Diario.git
   cd Registro-Diario
   ```

2. **Configura la base de datos**:
   - Crea una base de datos en MySQL llamada `registro_diario`.
   - Importa el archivo `database.sql` incluido en el proyecto para crear las tablas necesarias:
     
     mysql -u tu_usuario -p registro_diario < database.sql
     

3. **Configura la conexión a la base de datos**:
   - Edita el archivo `php/db.php` y actualiza las credenciales de la base de datos:
     <?php
     $host = 'localhost';
     $dbname = 'registro_diario';
     $username = 'tu_usuario';
     $password = 'tu_contraseña';
     ?>

4. **Configura el entorno local**:
   - Asegúrate de que el proyecto esté en el directorio raíz de tu servidor web (por ejemplo, `htdocs` en XAMPP o `www` en WAMP).

5. **Accede a la aplicación**:
   - Abre tu navegador y accede a `http://localhost/registro-diario`.

---

## Uso

### **1. Registro de tareas**
- Ve a la página de creación de tareas (`php/add_record.php`).
- Completa el formulario con los detalles de la tarea y haz clic en "Guardar tarea en el registro".

### **2. Historial de tareas**
- Ve a la página de historial (`historial.php`) para ver todas las tareas registradas.
- Desde esta página, puedes editar o eliminar tareas.

### **3. Generación de PDF**
- Haz clic en el botón "Descargar PDF" en el historial para generar un reporte en formato PDF con las tareas registradas.

### **4. Gestión de usuarios**
- Cada usuario debe iniciar sesión para acceder a sus tareas. Si no estás logueado, serás redirigido a la página de inicio de sesión.

---

## Estructura del proyecto

```
registro-diario/
├── css/
│   └── styles.css          # Estilos CSS para la aplicación
├── php/
│   ├── add_record.php      # Página para agregar tareas
│   ├── db.php              # Configuración de la base de datos
│   ├── delete_record.php   # Página para eliminar tareas
│   ├── edit_record.php     # Página para editar tareas
│   ├── login.php           # Página de inicio de sesión
│   ├── logout.php          # Página para cerrar sesión
│   └── register.php        # Página de registro de usuarios
├── download_pdf.php        # Generación de PDF con las tareas
├── historial.php           # Página de historial de tareas
├── index.php               # Página principal
└── README.md               # Documentación del proyecto
```
## Tablas SQL

CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE `tasks` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `fecha` DATE NOT NULL,
    `actividad` VARCHAR(255) NOT NULL,
    `tiempo` TIME NOT NULL,
    `observaciones` TEXT,
    `semana` VARCHAR(100) NOT NULL,
    `user_id` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
);

INSERT INTO `users` (`username`, `password`, `email`) VALUES
('admin', '$2y$10$eImiTXuWVxfM37uY4JANjQ==', 'admin@example.com'); -- Contraseña: admin

Nota:La contraseña debe estar encryptada con un algoritmo como bcrypt. El valor del eejmplo es ilustrativo.


INSERT INTO `tasks` (`fecha`, `actividad`, `tiempo`, `observaciones`, `semana`, `user_id`) VALUES
('2025-04-01', 'Revisión de código', '02:30:00', 'Se revisaron los cambios en el repositorio.', 'Semana 14, periodo del 31 de marzo al 6 de abril', 1);

## Tecnologías utilizadas

- **Frontend**:
  - HTML5
  - CSS3
  - JavaScript (opcional para interactividad)

- **Backend**:
  - PHP 7.4+
  - MySQL/MariaDB

- **Librerías**:
  - [TCPDF](https://tcpdf.org/) para la generación de PDFs.

---

## Contribuciones

¡Las contribuciones son bienvenidas! Si deseas colaborar en este proyecto:

1. Haz un fork del repositorio.
2. Crea una rama para tu funcionalidad (`git checkout -b nueva-funcionalidad`).
3. Realiza tus cambios y haz un commit (`git commit -m "Añadir nueva funcionalidad"`).
4. Envía un pull request.

---

## Licencia

Este proyecto está bajo la licencia MIT. Consulta el archivo `LICENSE` para más detalles.

---

## Autores

Desarrollado por ** https://github.com/KenMastters, https://github.com/Antojo02 , https://github.com/YasminaBBh ,https://github.com/RubenSM9 **. Si tienes preguntas o sugerencias, no dudes en contactarnos.






   