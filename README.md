# Obra App (interna)

Aplicación web interna y simple para control de obra:
- Clientes
- Proyectos
- Trabajadores
- Partes diarios (horas, materiales, maquinaria)
- Informes básicos

Stack actual:
- PHP estructurado (sin framework)
- MySQL
- HTML/CSS/JS vanilla

---

## 1) Requisitos

- PHP 8.1+
- MySQL 8+
- Servidor web local (Apache/Nginx) o servidor embebido de PHP

---

## 2) Instalación rápida

1. Clonar/copiar el proyecto en tu servidor.
2. Crear la base de datos e importar el esquema:

```bash
mysql -u huebraso_administrador -p huebraso_obra_app < database/schema.sql
```

> Si la base ya existe, revisa primero que `schema.sql` no sobrescriba información productiva.

3. Levantar servidor en local (modo desarrollo):

```bash
php -S 0.0.0.0:8000
```

4. Abrir en navegador:

```text
http://localhost:8000/login.php
```

---

## 3) Configuración

La conexión está en:

- `config/db.php`

Valores actuales por defecto:

- Host: `localhost`
- Base de datos: `huebraso_obra_app`
- Usuario: `huebraso_administrador`
- Contraseña: `Hector1990`
- Charset: `utf8mb4`

También puedes sobrescribir con variables de entorno:

- `DB_HOST`
- `DB_NAME`
- `DB_USER`
- `DB_PASS`
- `DB_CHARSET`

---

## 4) Usuario y contraseña por defecto

El esquema `database/schema.sql` ya deja creado un usuario inicial de prueba para entrar al sistema.

Credenciales iniciales:
- Usuario: `admin`
- Email: `admin@obra.local`
- Contraseña: `Admin1234`

El login acepta **usuario o email** indistintamente.

> Recomendado: cambiar la contraseña justo después del primer inicio de sesión.

### Si tu tabla `usuarios` ya existía sin columna `usuario`

Ejecuta esta migración manual:

```sql
ALTER TABLE usuarios
  ADD COLUMN usuario VARCHAR(60) NULL AFTER nombre;

UPDATE usuarios
SET usuario = CONCAT('user_', id)
WHERE usuario IS NULL OR usuario = '';

ALTER TABLE usuarios
  MODIFY usuario VARCHAR(60) NOT NULL,
  ADD UNIQUE KEY uq_usuarios_usuario (usuario);
```


---

## 5) Flujo básico de uso (fase actual)

1. Entrar en `login.php`.
2. Validar credenciales.
3. Acceder al dashboard por `index.php?page=dashboard`.
4. Cerrar sesión desde `index.php?page=logout`.

---

## 6) Estructura del proyecto

```text
/config
/models
/controllers
/views
/includes
/assets/css
/assets/js
/ajax
```

Archivos principales:
- `index.php`: router simple por `?page=`
- `login.php`: acceso al sistema
- `includes/session_middleware.php`: control de sesión/roles
- `config/db.php`: conexión PDO

---

## 7) Seguridad mínima aplicada

- Prepared statements (PDO)
- Verificación de contraseña con `password_verify`
- Sesión para autenticación
- Control básico de roles (`admin`, `operario`)

