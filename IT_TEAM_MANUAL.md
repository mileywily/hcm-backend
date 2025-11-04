# Manual para el Equipo de TI - Proyecto HCM-Back

Este manual está diseñado para proporcionar al equipo de TI toda la información necesaria para el despliegue, mantenimiento, solución de problemas y desarrollo continuo del proyecto HCM-Back.

## Tabla de Contenidos

- [1. Introducción](#1-introducción)
- [2. Arquitectura del Sistema](#2-arquitectura-del-sistema)
- [3. Tecnologías Utilizadas](#3-tecnologías-utilizadas)
- [4. Estructura del Proyecto](#4-estructura-del-proyecto)
- [5. Configuración del Entorno de Desarrollo](#5-configuración-del-entorno-de-desarrollo)
- [6. Base de Datos](#6-base-de-datos)
- [7. Funcionalidades Clave](#7-funcionalidades-clave)
  - [7.1. Gestión de Usuarios](#71-gestión-de-usuarios)
  - [7.2. Gestión de Empleados](#72-gestión-de-empleados)
  - [7.3. Gestión de Departamentos y Puestos](#73-gestión-de-departamentos-y-puestos)
  - [7.4. Autenticación y Autorización](#74-autenticación-y-autorización)
  - [7.5. Reportes y Exportación de Datos](#75-reportes-y-exportación-de-datos)
  - [7.6. Logs y Auditoría](#76-logs-y-auditoría)
  - [7.7. Integraciones (si aplica)](#77-integraciones-si-aplica)
- [8. Despliegue](#8-despliegue)
- [9. Solución de Problemas (Troubleshooting) y Mantenimiento](#9-solución-de-problemas-troubleshooting-y-mantenimiento)
- [10. Seguridad](#10-seguridad)
- [11. API (Interfaz de Programación de Aplicaciones)](#11-api-interfaz-de-programación-de-aplicaciones)
- [12. Contribuciones y Desarrollo](#12-contribuciones-y-desarrollo)
- [13. Descripción de Endpoints (app/route) y Modelos (app/model)](#13-descripción-de-endpoints-approute-y-modelos-appmodel)

---

## 1. Introducción

HCM-Back es el componente de backend para la aplicación de Gestión de Capital Humano. Se encarga de la lógica de negocio, la persistencia de datos y la exposición de la API para el frontend u otras integraciones. Este documento cubre los aspectos técnicos esenciales para el equipo de TI.

## 2. Arquitectura del Sistema

El proyecto HCM-Back sigue una arquitectura [ej: Monolítica, de Microservicios, en capas].

*   **Componentes Principales:**
    *   **Servidor Web:** [ej: Apache, Nginx] - Encargado de servir las peticiones HTTP.
    *   **Aplicación Backend:** Desarrollada en [Lenguaje/Framework] - Contiene la lógica de negocio y la API REST.
    *   **Base de Datos:** [ej: MySQL, PostgreSQL] - Almacena todos los datos del sistema.
*   **Diagrama de Flujo de Datos (ejemplo):**
    ```mermaid
    graph TD;
        A[Frontend/Cliente] -->|Petición HTTP| B(Servidor Web);
        B -->|Redirección/Proxy| C(Aplicación Backend);
        C -->|Consulta/Actualización| D[Base de Datos];
        D -->|Datos| C;
        C -->|Respuesta HTTP| B;
        B -->|Respuesta HTTP| A;
    ```

## 3. Tecnologías Utilizadas

HCM-Back ha sido desarrollado utilizando las siguientes tecnologías principales:

*   **Lenguaje de Programación:** [ej: PHP 8.x]
*   **Framework:** [ej: Laravel 9.x, Symfony 6.x] - Utilizado para la estructura MVC, ORM, ruteo, etc.
*   **Gestor de Base de Datos:** [ej: MySQL 8.0+]
*   **Servidor Web:** [ej: Apache 2.4, Nginx]
*   **Gestor de Dependencias:** [ej: Composer 2.x]
*   **Otros:** [ej: Redis para caché, Beanstalkd para colas, PHPUnit para testing]

## 4. Estructura del Proyecto

La estructura del proyecto sigue una convención estándar de [ej: Laravel/Symfony]. Los directorios más relevantes son:

*   `app/`: Contiene el código fuente principal de la aplicación (modelos, controladores, servicios, etc.).
*   `src/`: [Si aplica, para código fuente específico de un módulo o dominio].
*   `public/`: Punto de entrada de la aplicación web, archivos públicos (ej: `index.php`).
*   `vendor/`: Librerías de terceros instaladas via Composer.
*   `config/`: Archivos de configuración de la aplicación.
*   `database/`: Migraciones, seeders y factorías de la base de datos.
*   `routes/`: Definición de las rutas de la API.
*   `resources/`: Vistas (si el backend genera alguna vista) y assets.
*   `storage/`: Archivos generados por la aplicación (logs, caché, subidas de usuario).
*   `tests/`: Tests unitarios y de integración.
*   `logs/`: Archivos de registro de la aplicación.

## 5. Configuración del Entorno de Desarrollo

Para configurar un entorno de desarrollo funcional, sigue estos pasos:

1.  **Clonar el repositorio:**
    ```bash
    git clone <URL_DEL_REPOSITORIO>
    cd hcm-back
    ```

2.  **Instalar dependencias de PHP:**
    ```bash
    composer install
    ```

3.  **Configurar variables de entorno:**
    Copia el archivo `.env.example` a `.env` y edita las variables según tu configuración local:
    ```bash
    cp .env.example .env
    # Editar .env con: APP_ENV=local, DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD, etc.
    ```
    Genera la clave de la aplicación (solo para frameworks como Laravel):
    ```bash
    php artisan key:generate
    ```

4.  **Configuración del servidor web:**
    *   **Apache:** Asegúrate de que tu `DocumentRoot` apunte al directorio `public/` del proyecto y que las reescrituras de URL estén habilitadas (mod_rewrite).
    *   **Nginx:** Configura un bloque `server` para el proyecto, apuntando a `public/index.php`.

5.  **Configuración de la base de datos:**
    *   Crea una base de datos [nombre_db] en tu servidor MySQL/PostgreSQL.
    *   Ejecuta las migraciones para crear las tablas de la base de datos:
        ```bash
        php artisan migrate
        ```
    *   (Opcional) Ejecuta los seeders para poblar la base de datos con datos de prueba:
        ```bash
        php artisan db:seed
        ```

6.  **Iniciar el servidor de desarrollo (ej: Laravel Development Server):**
    ```bash
    php artisan serve
    ```
    La aplicación estará disponible en `http://localhost:8000` por defecto.

## 6. Base de Datos

La base de datos utilizada es [ej: MySQL]. El esquema de la base de datos está definido por las migraciones ubicadas en `database/migrations/`. El archivo `db.sql` en la raíz del proyecto contiene un dump completo de la base de datos o un esquema inicial.

*   **Nombre de la Base de Datos:** [A completar, ej: `hcm_db`]
*   **Credenciales:** Definidas en el archivo `.env`.
*   **Gestión de Migraciones:**
    *   Crear una nueva migración: `php artisan make:migration create_nombre_tabla_table`
    *   Ejecutar migraciones pendientes: `php artisan migrate`
    *   Revertir la última migración: `php artisan migrate:rollback`
    *   Refrescar la base de datos (rollback y migrate): `php artisan migrate:refresh`
    *   Refrescar y seed (útil en desarrollo): `php artisan migrate:fresh --seed`
*   **Acceso Directo a la Base de Datos:**
    Se puede usar un cliente SQL como MySQL Workbench, DBeaver, DataGrip, o la línea de comandos `mysql -u usuario -p -h host base_de_datos`.

## 7. Funcionalidades Clave

Aquí se describen las principales funcionalidades que implementa el backend HCM-Back. Cada una expone una serie de endpoints API para ser consumidos por el frontend.

### 7.1. Gestión de Usuarios

*   **Descripción:** Permite la administración de cuentas de usuario, incluyendo roles y permisos.
*   **Endpoints Típicos:**
    *   `POST /api/users` - Crear un nuevo usuario.
    *   `GET /api/users` - Obtener lista de usuarios.
    *   `GET /api/users/{id}` - Obtener detalles de un usuario específico.
    *   `PUT /api/users/{id}` - Actualizar un usuario.
    *   `DELETE /api/users/{id}` - Eliminar un usuario.
*   **Modelos/Entidades:** `User`, `Role`, `Permission`

### 7.2. Gestión de Empleados

*   **Descripción:** Maneja la información detallada de los empleados de la organización (datos personales, contacto, departamento, puesto, historial laboral).
*   **Endpoints Típicos:**
    *   `POST /api/employees` - Registrar un nuevo empleado.
    *   `GET /api/employees` - Listar empleados (con filtros, paginación).
    *   `GET /api/employees/{id}` - Ver perfil de un empleado.
    *   `PUT /api/employees/{id}` - Actualizar datos del empleado.
    *   `DELETE /api/employees/{id}` - Eliminar registro de empleado.
*   **Modelos/Entidades:** `Employee`, `ContactInfo`, `EmploymentHistory`

### 7.3. Gestión de Departamentos y Puestos

*   **Descripción:** Permite definir la estructura organizacional (departamentos) y los diferentes puestos de trabajo dentro de la empresa.
*   **Endpoints Típicos:**
    *   `GET /api/departments` - Obtener lista de departamentos.
    *   `POST /api/departments` - Crear departamento.
    *   `GET /api/departments/{id}` - Detalles de departamento.
    *   `PUT /api/departments/{id}` - Actualizar departamento.
    *   `GET /api/positions` - Obtener lista de puestos.
    *   `POST /api/positions` - Crear puesto.
*   **Modelos/Entidades:** `Department`, `Position`

### 7.4. Autenticación y Autorización

*   **Descripción:** Implementa el sistema de inicio de sesión, registro de usuarios, y control de acceso basado en roles y permisos.
*   **Endpoints Típicos:**
    *   `POST /api/auth/login` - Iniciar sesión (retorna token).
    *   `POST /api/auth/register` - Registrar nuevo usuario (si aplica).
    *   `POST /api/auth/logout` - Cerrar sesión (invalidar token).
    *   `GET /api/user` - Obtener usuario autenticado (requiere token).
*   **Tecnología:** [ej: JWT (JSON Web Tokens), Laravel Passport, Sanctum]

### 7.5. Reportes y Exportación de Datos

*   **Descripción:** Funcionalidad para generar reportes sobre diferentes aspectos del personal (ej: empleados por departamento, historial de puestos) y exportar datos en varios formatos.
*   **Endpoints Típicos:**
    *   `GET /api/reports/employees-by-department` - Reporte de empleados por departamento.
    *   `GET /api/reports/export/employees?format=csv` - Exportar lista de empleados.
*   **Tecnología:** [ej: librerías de generación de PDF/Excel, Laravel Excel]

### 7.6. Logs y Auditoría

*   **Descripción:** El sistema registra eventos importantes y acciones de los usuarios para auditoría y depuración.
*   **Ubicación de Logs:** `storage/logs/laravel.log` (para Laravel) o `logs/` (general)
*   **Niveles de Log:** DEBUG, INFO, NOTICE, WARNING, ERROR, CRITICAL, ALERT, EMERGENCY.
*   **Monitorización:** Es crucial monitorear estos logs para identificar problemas y actividades sospechosas.

### 7.7. Integraciones (si aplica)

*   **Descripción:** Detalla cualquier integración con sistemas externos (ej: sistemas de nómina, calendarios, plataformas de comunicación).
*   **Ejemplo:** Integración con [Nombre del Sistema Externo] para [Propósito].
*   **Configuración:** Las credenciales y URLs de los servicios externos se configuran en el archivo `.env`.

## 8. Despliegue

Para desplegar la aplicación en un entorno de producción (o staging), se recomienda seguir estos pasos:

1.  **Preparar el servidor:**
    *   Instalar [ej: PHP, Composer, MySQL, Nginx/Apache].
    *   Configurar firewall (permitir puertos 80/443, 22).
    *   Instalar Certificado SSL (Let's Encrypt, etc.).

2.  **Obtener el código:**
    ```bash
    git clone <URL_DEL_REPOSITORIO> /var/www/hcm-back
    cd /var/www/hcm-back
    ```

3.  **Instalar dependencias:**
    ```bash
    composer install --no-dev --optimize-autoloader
    ```

4.  **Configurar `.env` para producción:**
    Asegúrate de que `APP_ENV=production` y que todas las credenciales de base de datos y servicios estén correctas y seguras.

5.  **Generar clave de aplicación (si no se hizo):**
    ```bash
    php artisan key:generate
    ```

6.  **Configurar permisos de directorios:**
    Asegúrate de que el servidor web tenga permisos de escritura en `storage/` y `bootstrap/cache/`.
    ```bash
    sudo chown -R www-data:www-data /var/www/hcm-back
    sudo chmod -R 775 /var/www/hcm-back/storage
    sudo chmod -R 775 /var/www/hcm-back/bootstrap/cache
    ```

7.  **Ejecutar migraciones de base de datos:**
    ```bash
    php artisan migrate --force
    ```

8.  **Configurar el servidor web (Nginx/Apache):**
    Crear un archivo de configuración para el virtual host/server block apuntando al directorio `public/`.

9.  **Optimización:**
    *   Limpiar caché: `php artisan cache:clear`, `php artisan config:clear`, `php artisan route:clear`, `php artisan view:clear`.
    *   Caché de configuración: `php artisan config:cache`.
    *   Caché de rutas: `php artisan route:cache`.
    *   Caché de vistas: `php artisan view:cache`.

10. **Monitoreo:**
    Configurar herramientas de monitoreo para la aplicación y la infraestructura (logs, rendimiento).

## 9. Solución de Problemas (Troubleshooting) y Mantenimiento

### Problemas Comunes y Soluciones:

*   **`500 Server Error`:**
    *   Verificar logs de Nginx/Apache y logs de la aplicación (`storage/logs/laravel.log`).
    *   Revisar permisos de directorios (`storage/`, `bootstrap/cache/`).
    *   Asegurarse de que el `.env` esté correctamente configurado.
*   **`composer install` falla:**
    *   Verificar la versión de PHP (`php -v`).
    *   Asegurarse de que las extensiones de PHP requeridas estén instaladas (`php -m`).
*   **Errores de base de datos:**
    *   Verificar credenciales en `.env`.
    *   Asegurarse de que el servicio de base de datos esté corriendo.
    *   Revisar si las migraciones se ejecutaron correctamente.

### Mantenimiento:

*   **Actualizaciones:** Mantener PHP, Composer, y las dependencias del framework actualizadas.
*   **Backups:** Implementar una estrategia de backup regular para la base de datos.
*   **Monitorización:** Monitorizar constantemente la salud de la aplicación (CPU, memoria, uso de disco, latencia de API, errores).
*   **Limpieza de Caché y Logs:** Programar tareas para limpiar logs antiguos y caché.

## 10. Seguridad

*   **Variables de Entorno:** Todas las credenciales sensibles (claves API, contraseñas de DB) deben estar en el archivo `.env` y no en el código fuente.
*   **HTTPS:** Forzar el uso de HTTPS en producción.
*   **Validación de Entrada:** Validar y sanear rigurosamente todas las entradas de usuario para prevenir ataques como XSS, inyección SQL.
*   **Protección CSRF:** Asegurarse de que el framework tenga protección CSRF habilitada para las solicitudes POST/PUT/DELETE.
*   **Control de Acceso:** Implementar control de acceso basado en roles y permisos de forma granular.
*   **Actualizaciones:** Mantener todas las dependencias y el sistema operativo actualizados para parchear vulnerabilidades conocidas.
*   **Logs de Auditoría:** Utilizar los logs para detectar actividades sospechosas.

## 11. API (Interfaz de Programación de Aplicaciones)

El backend HCM-Back expone una API RESTful. La documentación detallada de la API se puede encontrar en [ej: un archivo Swagger/OpenAPI, Postman Collection, o en una sección de este manual si es breve].

*   **Base URL:** `http://your-domain.com/api/`
*   **Autenticación:** [ej: Token Bearer JWT en el header `Authorization`].
*   **Convenciones:** Uso de verbos HTTP estándar (GET, POST, PUT, DELETE) y códigos de estado HTTP apropiados.
*   **Ejemplos de Endpoints Clave:**
    *   `GET /api/employees` - Obtener todos los empleados.
    *   `POST /api/employees` - Crear un empleado.
    *   `GET /api/employees/{id}` - Obtener un empleado por ID.

## 12. Contribuciones y Desarrollo

Para realizar contribuciones al proyecto o para el desarrollo continuo, se recomienda seguir el siguiente flujo de trabajo:

1.  **Clonar el repositorio y crear una rama:** `git checkout -b feature/nombre-de-la-feature`
2.  **Realizar cambios y commits significativos.**
3.  **Ejecutar tests:** Asegurarse de que todos los tests pasen (`php artisan test` o `vendor/bin/phpunit`).
4.  **Crear un Pull Request (PR):** Abrir un PR en GitHub/GitLab/Bitbucket contra la rama `main` o `develop`.
5.  **Revisión de Código:** El código será revisado por otro miembro del equipo.

---

**Nota:** Este manual es un documento vivo y debe ser actualizado regularmente a medida que el proyecto evolucione.

## 13. Descripción de Endpoints (app/route) y Modelos (app/model)

El proyecto `hcm-back` organiza sus funcionalidades de API y modelos de datos de forma modular. Cada archivo `hcm-[nombre_entidad].php` en las carpetas `app/route` y `app/model` corresponde a una entidad o funcionalidad específica del sistema.

### 13.1. Endpoints (app/route)

Los archivos en `app/route/` definen las rutas de la API y mapean las solicitudes HTTP a las acciones correspondientes en los controladores (o directamente en las funciones si el framework lo permite). Un archivo de ruta típico para una entidad `[nombre_entidad]` contendrá rutas para operaciones CRUD (Crear, Leer, Actualizar, Eliminar) sobre esa entidad, como:

*   `GET /api/[entidad]` - Obtener una lista de `[entidad]es`.
*   `GET /api/[entidad]/{id}` - Obtener los detalles de una `[entidad]` específica por su ID.
*   `POST /api/[entidad]` - Crear una nueva `[entidad]`.
*   `PUT /api/[entidad]/{id}` - Actualizar una `[entidad]` existente.
*   `DELETE /api/[entidad]/{id}` - Eliminar una `[entidad]`.

**Ejemplos de Archivos de Ruta:**

*   **`hcm-atencion-examen.php`:** Define los endpoints API para la gestión de las atenciones y exámenes médicos. Esto incluiría rutas para registrar una atención-examen, consultar las atenciones-examen existentes, actualizar su estado o detalles, etc.
*   **`hcm-empleado.php` (asumiendo que existe o es parte de otro como `usuario-route.php`):** Contendría las rutas para gestionar la información de los empleados, como la creación, consulta, actualización y eliminación de perfiles de empleado.
*   **`hcm-solicitud.php`:** Define las rutas relacionadas con la gestión de solicitudes, permitiendo crear nuevas solicitudes, listar las solicitudes existentes, ver detalles de una solicitud específica, o cambiar su estado.
*   **`usuario-route.php`:** Probablemente gestiona las rutas para la autenticación, registro, y la administración de perfiles de usuario (login, logout, obtener perfil de usuario, etc.).

### 13.2. Modelos (app/model)

Los archivos en `app/model/` representan las estructuras de datos (entidades) del sistema y encapsulan la lógica de negocio relacionada con la interacción con la base de datos. Cada modelo generalmente corresponde a una tabla en la base de datos. Estos modelos son los encargados de:

*   **Definir la estructura de la tabla:** Columnas, tipos de datos, claves primarias y foráneas.
*   **Manejar las relaciones:** Definir cómo se relacionan con otros modelos (ej: un empleado tiene un departamento).
*   **Implementar la lógica de negocio:** Métodos para crear, leer, actualizar, eliminar registros, así como validaciones y cualquier otra regla de negocio.

**Ejemplos de Archivos de Modelo:**

*   **`hcm-atencion-examen.php`:** Representa la entidad `AtencionExamen` en la base de datos. Contendría la definición de la tabla `atenciones_examenes`, sus campos (ID de atención, ID de examen, fecha, resultados, estado, etc.), y los métodos para interactuar con estos datos (ej: `guardarAtencionExamen()`, `obtenerAtencionExamenPorId()`).
*   **`hcm-beneficiario.php`:** Representa la entidad `Beneficiario`. Incluiría los campos para los datos de un beneficiario (nombre, relación con el empleado, etc.) y los métodos para su gestión.
*   **`hcm-solicitud.php`:** Representa la entidad `Solicitud`. Contendría los campos relacionados con una solicitud (ID, tipo de solicitud, estado, fecha, ID de empleado asociado, etc.) y la lógica para crear, consultar, actualizar y eliminar solicitudes.
*   **`usuario-model.php`:** Representa la entidad `Usuario`. Este modelo manejaría la información del usuario, incluyendo el hash de la contraseña, roles, y métodos para autenticación y autorización.

En resumen, los archivos de `route` actúan como la "puerta de entrada" de la API, dirigiendo las peticiones. Los archivos de `model` son el "corazón" del negocio, manejando los datos y la lógica asociada a cada entidad. Ambos trabajan en conjunto para ofrecer la funcionalidad completa del backend HCM-Back.

---
