-- ============================================================
-- Obra: Esquema base (Fase 1)
-- Stack: MySQL 8+
-- ============================================================

CREATE DATABASE IF NOT EXISTS huebraso_obra_app
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE huebraso_obra_app;

-- =========================
-- Tabla: usuarios
-- =========================
CREATE TABLE usuarios (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  usuario VARCHAR(60) NOT NULL,
  email VARCHAR(150) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  rol ENUM('admin', 'operario') NOT NULL DEFAULT 'operario',
  activo TINYINT(1) NOT NULL DEFAULT 1,
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  actualizado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_usuarios_usuario (usuario),
  UNIQUE KEY uq_usuarios_email (email)
) ENGINE=InnoDB;

-- =========================
-- Tabla: clientes
-- =========================
CREATE TABLE clientes (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(150) NOT NULL,
  nif VARCHAR(20) NULL,
  telefono VARCHAR(30) NULL,
  email VARCHAR(150) NULL,
  direccion VARCHAR(255) NULL,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  actualizado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_clientes_nif (nif),
  KEY idx_clientes_nombre (nombre)
) ENGINE=InnoDB;

-- =========================
-- Tabla: proyectos
-- =========================
CREATE TABLE proyectos (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  cliente_id BIGINT UNSIGNED NOT NULL,
  nombre VARCHAR(180) NOT NULL,
  codigo VARCHAR(40) NULL,
  descripcion TEXT NULL,
  fecha_inicio DATE NULL,
  fecha_fin_estimada DATE NULL,
  estado ENUM('Proximo', 'En curso', 'Pausado', 'Finalizado', 'Facturado', 'Cobrado', 'Cerrado')
    NOT NULL DEFAULT 'Proximo',
  activo TINYINT(1) NOT NULL DEFAULT 1,
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  actualizado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_proyectos_codigo (codigo),
  KEY idx_proyectos_cliente_id (cliente_id),
  KEY idx_proyectos_estado (estado),
  CONSTRAINT fk_proyectos_cliente
    FOREIGN KEY (cliente_id) REFERENCES clientes(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB;

-- =========================
-- Tabla: trabajadores
-- =========================
CREATE TABLE trabajadores (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(130) NOT NULL,
  documento VARCHAR(30) NULL,
  telefono VARCHAR(30) NULL,
  categoria VARCHAR(80) NULL,
  coste_hora DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  actualizado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_trabajadores_documento (documento),
  KEY idx_trabajadores_nombre (nombre)
) ENGINE=InnoDB;

-- =========================
-- Tabla: materiales
-- =========================
CREATE TABLE materiales (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(150) NOT NULL,
  unidad VARCHAR(20) NOT NULL DEFAULT 'ud',
  precio_referencia DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  actualizado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY idx_materiales_nombre (nombre)
) ENGINE=InnoDB;

-- =========================
-- Tabla: maquinaria
-- =========================
CREATE TABLE maquinaria (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(150) NOT NULL,
  matricula VARCHAR(30) NULL,
  coste_hora_referencia DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  actualizado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_maquinaria_matricula (matricula),
  KEY idx_maquinaria_nombre (nombre)
) ENGINE=InnoDB;

-- =========================
-- Tabla opcional recomendada: tareas_proyecto
-- =========================
CREATE TABLE tareas_proyecto (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  proyecto_id BIGINT UNSIGNED NOT NULL,
  nombre VARCHAR(160) NOT NULL,
  descripcion TEXT NULL,
  estado ENUM('pendiente', 'en_progreso', 'hecha') NOT NULL DEFAULT 'pendiente',
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  actualizado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY idx_tareas_proyecto_id (proyecto_id),
  KEY idx_tareas_estado (estado),
  CONSTRAINT fk_tareas_proyecto
    FOREIGN KEY (proyecto_id) REFERENCES proyectos(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================
-- Tabla: partes_diarios
-- =========================
CREATE TABLE partes_diarios (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  proyecto_id BIGINT UNSIGNED NOT NULL,
  tarea_id BIGINT UNSIGNED NULL,
  fecha DATE NOT NULL,
  notas TEXT NULL,
  creado_por_usuario_id BIGINT UNSIGNED NULL,
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  actualizado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY idx_partes_fecha (fecha),
  KEY idx_partes_proyecto_id (proyecto_id),
  KEY idx_partes_tarea_id (tarea_id),
  KEY idx_partes_usuario_id (creado_por_usuario_id),
  UNIQUE KEY uq_partes_proyecto_fecha_tarea (proyecto_id, fecha, tarea_id),
  CONSTRAINT fk_partes_proyecto
    FOREIGN KEY (proyecto_id) REFERENCES proyectos(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  CONSTRAINT fk_partes_tarea
    FOREIGN KEY (tarea_id) REFERENCES tareas_proyecto(id)
    ON UPDATE CASCADE
    ON DELETE SET NULL,
  CONSTRAINT fk_partes_usuario
    FOREIGN KEY (creado_por_usuario_id) REFERENCES usuarios(id)
    ON UPDATE CASCADE
    ON DELETE SET NULL
) ENGINE=InnoDB;

-- =========================
-- Tabla: parte_trabajadores
-- Guarda horas y coste snapshot por trabajador
-- =========================
CREATE TABLE parte_trabajadores (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  parte_diario_id BIGINT UNSIGNED NOT NULL,
  trabajador_id BIGINT UNSIGNED NOT NULL,
  horas DECIMAL(6,2) NOT NULL,
  coste_hora_snapshot DECIMAL(10,2) NOT NULL,
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_pt_parte_diario_id (parte_diario_id),
  KEY idx_pt_trabajador_id (trabajador_id),
  CONSTRAINT fk_pt_parte
    FOREIGN KEY (parte_diario_id) REFERENCES partes_diarios(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT fk_pt_trabajador
    FOREIGN KEY (trabajador_id) REFERENCES trabajadores(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB;

-- =========================
-- Tabla: parte_materiales
-- Guarda cantidad y precio snapshot
-- =========================
CREATE TABLE parte_materiales (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  parte_diario_id BIGINT UNSIGNED NOT NULL,
  material_id BIGINT UNSIGNED NOT NULL,
  cantidad DECIMAL(10,2) NOT NULL,
  precio_unitario_snapshot DECIMAL(10,2) NOT NULL,
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_pm_parte_diario_id (parte_diario_id),
  KEY idx_pm_material_id (material_id),
  CONSTRAINT fk_pm_parte
    FOREIGN KEY (parte_diario_id) REFERENCES partes_diarios(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT fk_pm_material
    FOREIGN KEY (material_id) REFERENCES materiales(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB;

-- =========================
-- Tabla: parte_maquinaria
-- Guarda horas y coste snapshot
-- =========================
CREATE TABLE parte_maquinaria (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  parte_diario_id BIGINT UNSIGNED NOT NULL,
  maquinaria_id BIGINT UNSIGNED NOT NULL,
  horas DECIMAL(6,2) NOT NULL,
  coste_hora_snapshot DECIMAL(10,2) NOT NULL,
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_pmaq_parte_diario_id (parte_diario_id),
  KEY idx_pmaq_maquinaria_id (maquinaria_id),
  CONSTRAINT fk_pmaq_parte
    FOREIGN KEY (parte_diario_id) REFERENCES partes_diarios(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT fk_pmaq_maquinaria
    FOREIGN KEY (maquinaria_id) REFERENCES maquinaria(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB;


-- Usuario inicial para primer acceso
-- Usuario: admin | Email: admin@obra.local | Contraseña: Admin1234
INSERT INTO usuarios (nombre, usuario, email, password_hash, rol, activo)
VALUES ('Administrador', 'admin', 'admin@obra.local', '$2y$12$FBGuyPwC/xVqdmG3bdLYq.GXMivp16IRmtwPiXzutc1EcCkRELHGe', 'admin', 1);
