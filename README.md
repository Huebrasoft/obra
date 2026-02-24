# Obra Control (PHP + MySQL + JS/AJAX)

Aplicación web simple para control de tiempo, materiales y costes por proyecto en obra.

## Estructura

- `config/` configuración y conexión
- `models/` consultas a base de datos
- `controllers/` lógica de flujo
- `views/` interfaz HTML
- `assets/css` estilos responsive
- `assets/js` interacciones dinámicas
- `sql/schema.sql` script completo de base de datos

## Requisitos

- PHP 8.1+
- MySQL 8+

## Instalación rápida

1. Crear base de datos ejecutando `sql/schema.sql`.
2. Ajustar credenciales en `config/config.php`.
3. Servir proyecto con Apache/Nginx o `php -S localhost:8000`.
4. Abrir `http://localhost:8000/index.php`.

## Credenciales demo

- Administrador: `admin / admin123`
- Operario: `operario / operario123`

## Módulos funcionales incluidos

- Login y roles simples.
- Dashboard y proyectos activos.
- Módulo **Nuevo Parte Diario** (trabajadores, materiales, maquinaria, notas) con AJAX.
- Informe mensual por trabajador + export CSV.
- Informe completo por proyecto + export CSV.

## Nota seguridad

Se usan `prepared statements`, validaciones básicas frontend/backend y soft delete en tablas principales.

## Solución rápida si sale HTTP 500 en login

- Verifica versión de PHP (recomendado 8.1+).
- Activa extensiones `pdo` y `pdo_mysql`.
- Revisa el log de errores de PHP del hosting.
- Vuelve a importar `sql/schema.sql` para cargar hashes válidos de usuarios demo.
