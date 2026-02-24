CREATE DATABASE IF NOT EXISTS obra_control CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE obra_control;

CREATE TABLE clientes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  telefono VARCHAR(30) DEFAULT NULL,
  email VARCHAR(120) DEFAULT NULL,
  cif_nif VARCHAR(30) DEFAULT NULL,
  notas TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME DEFAULT NULL,
  INDEX idx_cliente_nombre (nombre)
);

CREATE TABLE proyectos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cliente_id INT NOT NULL,
  nombre VARCHAR(150) NOT NULL,
  descripcion TEXT DEFAULT NULL,
  localizacion VARCHAR(180) DEFAULT NULL,
  fecha_inicio_prevista DATE DEFAULT NULL,
  fecha_fin_prevista DATE DEFAULT NULL,
  fecha_inicio_real DATE DEFAULT NULL,
  fecha_fin_real DATE DEFAULT NULL,
  estado ENUM('Próximo','En curso','Pausado','Finalizado','Facturado','Cobrado','Cerrado') NOT NULL DEFAULT 'Próximo',
  notas_generales TEXT DEFAULT NULL,
  presupuesto_estimado DECIMAL(12,2) DEFAULT NULL,
  etiquetas VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME DEFAULT NULL,
  CONSTRAINT fk_proyecto_cliente FOREIGN KEY (cliente_id) REFERENCES clientes(id),
  INDEX idx_proyecto_estado (estado),
  INDEX idx_proyecto_cliente (cliente_id)
);

CREATE TABLE trabajadores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  rol VARCHAR(80) DEFAULT NULL,
  coste_hora DECIMAL(10,2) NOT NULL DEFAULT 0,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME DEFAULT NULL,
  INDEX idx_trabajador_nombre (nombre)
);

CREATE TABLE materiales (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  unidad_medida ENUM('ud','m','m²','m³','kg','L') NOT NULL,
  coste_unitario DECIMAL(10,2) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME DEFAULT NULL,
  INDEX idx_material_nombre (nombre)
);

CREATE TABLE maquinaria (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  modelo VARCHAR(120) DEFAULT NULL,
  coste_hora DECIMAL(10,2) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME DEFAULT NULL,
  INDEX idx_maquinaria_nombre (nombre)
);

CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  username VARCHAR(60) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  rol ENUM('administrador','operario') NOT NULL,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME DEFAULT NULL
);

CREATE TABLE partes_diarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  proyecto_id INT NOT NULL,
  fecha DATE NOT NULL,
  tarea VARCHAR(160) DEFAULT NULL,
  notas TEXT DEFAULT NULL,
  creado_por INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  deleted_at DATETIME DEFAULT NULL,
  CONSTRAINT fk_parte_proyecto FOREIGN KEY (proyecto_id) REFERENCES proyectos(id),
  CONSTRAINT fk_parte_usuario FOREIGN KEY (creado_por) REFERENCES usuarios(id),
  INDEX idx_parte_fecha (fecha),
  INDEX idx_parte_proyecto (proyecto_id)
);

CREATE TABLE parte_trabajadores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  parte_id INT NOT NULL,
  trabajador_id INT NOT NULL,
  horas DECIMAL(8,2) NOT NULL,
  coste_hora_snapshot DECIMAL(10,2) NOT NULL DEFAULT 0,
  CONSTRAINT fk_pt_parte FOREIGN KEY (parte_id) REFERENCES partes_diarios(id) ON DELETE CASCADE,
  CONSTRAINT fk_pt_trabajador FOREIGN KEY (trabajador_id) REFERENCES trabajadores(id),
  INDEX idx_pt_trabajador (trabajador_id)
);

CREATE TABLE parte_materiales (
  id INT AUTO_INCREMENT PRIMARY KEY,
  parte_id INT NOT NULL,
  material_id INT NOT NULL,
  cantidad DECIMAL(10,2) NOT NULL,
  coste_unitario_snapshot DECIMAL(10,2) NOT NULL DEFAULT 0,
  CONSTRAINT fk_pm_parte FOREIGN KEY (parte_id) REFERENCES partes_diarios(id) ON DELETE CASCADE,
  CONSTRAINT fk_pm_material FOREIGN KEY (material_id) REFERENCES materiales(id),
  INDEX idx_pm_material (material_id)
);

CREATE TABLE parte_maquinaria (
  id INT AUTO_INCREMENT PRIMARY KEY,
  parte_id INT NOT NULL,
  maquinaria_id INT NOT NULL,
  horas DECIMAL(8,2) NOT NULL,
  coste_hora_snapshot DECIMAL(10,2) NOT NULL DEFAULT 0,
  CONSTRAINT fk_pq_parte FOREIGN KEY (parte_id) REFERENCES partes_diarios(id) ON DELETE CASCADE,
  CONSTRAINT fk_pq_maquinaria FOREIGN KEY (maquinaria_id) REFERENCES maquinaria(id),
  INDEX idx_pq_maquinaria (maquinaria_id)
);

-- Datos base mínimos
INSERT INTO usuarios (nombre, username, password_hash, rol) VALUES
('Admin', 'admin', '$2y$10$QfXqF2lA3xKlFKd2kxXtX.RV3gljEmM4x4dL2E2wFfUEX6F44Pq2K', 'administrador'),
('Operario Demo', 'operario', '$2y$10$fxNMcYfQ8QHUBYj42zx4ouql6JROn8fS8dD0fIkHkyl4u0x9IIByW', 'operario');

INSERT INTO clientes (nombre, telefono, email, cif_nif, notas) VALUES
('Construcciones Norte SL', '600000001', 'info@norte.es', 'B12345678', 'Cliente prioritario');

INSERT INTO proyectos (cliente_id, nombre, descripcion, localizacion, fecha_inicio_prevista, fecha_fin_prevista, estado, presupuesto_estimado, etiquetas)
VALUES (1, 'Reforma Nave Industrial', 'Adecuación integral de nave', 'Madrid', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 90 DAY), 'En curso', 120000.00, 'reforma,industrial');

INSERT INTO trabajadores (nombre, rol, coste_hora, activo) VALUES
('Juan Pérez', 'Oficial 1ª', 24.50, 1),
('Ana Gómez', 'Peón', 18.00, 1);

INSERT INTO materiales (nombre, unidad_medida, coste_unitario) VALUES
('Cemento CEM II', 'kg', 0.25),
('Ladrillo hueco', 'ud', 0.48);

INSERT INTO maquinaria (nombre, modelo, coste_hora) VALUES
('Mini excavadora', 'CAT 301.7', 28.00),
('Generador', 'Honda EG3600', 6.50);
