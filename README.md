# DSS AGAPROVA (V6)

Sistema de Soporte de Decisiones (DSS) para la optimización de transporte y logística de ganado.

## Descripción General
Este sistema provee herramientas para la gestión de lotes de ganado, monitoreo del clima, evaluación de bloqueos en rutas y cálculo de costos de flete, todo respaldado por un sistema de simulación basado en el algoritmo Simplex para la optimización de rutas.

## Guía de Instalación

Sigue estos pasos para instalar y configurar el proyecto en un entorno local (por ejemplo, usando XAMPP, WAMP o un servidor Apache/MySQL independiente).

### Requisitos Previos
- **Servidor Web**: Apache (recomendado) o Nginx.
- **PHP**: Versión 7.4 o superior (se recomienda 8.x).
- **Base de Datos**: MySQL o MariaDB.
- **Composer**: Gestor de dependencias de PHP.

### Paso 1: Clonar el Repositorio
Clona este repositorio en el directorio raíz de tu servidor web (por ejemplo, `htdocs` en XAMPP/Apache o `www` en WAMP).
```bash
git clone https://github.com/KROWNDFORD/V6-AGAPROVA.DSS.git agaprova-dss
cd agaprova-dss
```

### Paso 2: Instalar Dependencias
Abre una terminal en el directorio del proyecto y ejecuta Composer para instalar las librerías necesarias:
```bash
composer install
```

### Paso 3: Configurar la Base de Datos
1. Inicia tu servidor MySQL.
2. Crea una base de datos vacía (por ejemplo, `agaprova_db`).
3. Importa la estructura de la base de datos usando el archivo `database/schema.sql`.
4. (Opcional) Importa los datos de prueba iniciales usando el archivo `database/seed.sql`.

### Paso 4: Configurar la Aplicación
1. Ve a la carpeta `config/` y edita el archivo de configuración correspondiente (usualmente `config.php` o renombra un `config.example.php`).
2. Actualiza las credenciales de conexión a la base de datos:
   ```php
   // Ejemplo de configuración
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', ''); // Tu contraseña de MySQL
   define('DB_NAME', 'agaprova_db');
   ```

### Paso 5: Configurar el Servidor Web (Apache)
El proyecto utiliza URLs amigables mediante el archivo `.htaccess`. Asegúrate de que el módulo `mod_rewrite` esté habilitado en tu servidor Apache.
Si instalaste el proyecto en una subcarpeta (ej. `localhost/agaprova-dss`), asegúrate de que el archivo `.htaccess` esté configurado correctamente para esa ruta base (en caso de usar `RewriteBase`).

### Paso 6: Verificación
Abre tu navegador web y navega a la URL del proyecto:
```
http://localhost/agaprova-dss
```
Deberías ver la pantalla de inicio de sesión o el panel de control (Dashboard).

---

## Estructura Principal
- `api/`: Endpoints para peticiones asíncronas.
- `app/`: Núcleo de la aplicación (Controladores, Modelos, Servicios, etc.).
- `assets/`: Recursos estáticos (CSS, JS, imágenes).
- `config/`: Archivos de configuración del sistema.
- `database/`: Scripts SQL para estructura y datos.
- `logs/`: Registros de errores y eventos del sistema.
- `views/`: Plantillas HTML y vistas del sistema.

## Soporte
Para consultas o problemas de instalación, por favor abre un _Issue_ en este repositorio.
