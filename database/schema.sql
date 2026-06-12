-- ============================================================================
-- DSS AGAPROVA - Esquema de Base de Datos
-- Version 1.0 - MySQL 8+
-- ============================================================================

CREATE DATABASE IF NOT EXISTS dss_agaprova
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE dss_agaprova;

-- TABLAS CATALOGO

CREATE TABLE mercados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    ciudad VARCHAR(100) NOT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    CONSTRAINT uq_mercados_nombre UNIQUE (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE estaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    factor DECIMAL(4,2) NOT NULL,
    descripcion VARCHAR(255),
    CONSTRAINT ck_estaciones_factor CHECK (factor >= 0.50 AND factor <= 1.00),
    CONSTRAINT uq_estaciones_nombre UNIQUE (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE condiciones_ganado (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    factor DECIMAL(4,2) NOT NULL,
    descripcion VARCHAR(255),
    CONSTRAINT ck_condiciones_factor CHECK (factor >= 0.50 AND factor <= 1.00),
    CONSTRAINT uq_condiciones_nombre UNIQUE (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT uq_usuarios_username UNIQUE (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE rutas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(10) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    origen VARCHAR(100) NOT NULL,
    destino VARCHAR(100) NOT NULL,
    mercado_id INT NOT NULL,
    tipo_via VARCHAR(50),
    tiempo_horas DECIMAL(4,1) NOT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    CONSTRAINT uq_rutas_codigo UNIQUE (codigo),
    CONSTRAINT ck_rutas_tiempo CHECK (tiempo_horas > 0),
    CONSTRAINT fk_rutas_mercado FOREIGN KEY (mercado_id)
        REFERENCES mercados(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_rutas_mercado (mercado_id),
    INDEX idx_rutas_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE lotes_ganado (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cabezas INT NOT NULL,
    peso_promedio_kg DECIMAL(6,2) NOT NULL,
    condicion_id INT NOT NULL,
    estacion_id INT NOT NULL,
    usuario_id INT DEFAULT NULL,
    ruta_optima_id INT NULL DEFAULT NULL,
    hora_salida TIME NOT NULL DEFAULT '20:00:00',
    fecha_registro DATE NOT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT ck_lotes_cabezas CHECK (cabezas > 0),
    CONSTRAINT ck_lotes_peso CHECK (peso_promedio_kg > 0),
    CONSTRAINT fk_lotes_condicion FOREIGN KEY (condicion_id)
        REFERENCES condiciones_ganado(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_lotes_estacion FOREIGN KEY (estacion_id)
        REFERENCES estaciones(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_lotes_usuario FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_lotes_ruta_optima FOREIGN KEY (ruta_optima_id)
        REFERENCES rutas(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_lotes_fecha (fecha_registro),
    INDEX idx_lotes_activo (activo),
    INDEX idx_lotes_condicion (condicion_id),
    INDEX idx_lotes_estacion (estacion_id),
    INDEX idx_lotes_usuario (usuario_id),
    INDEX idx_lotes_ruta_optima (ruta_optima_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE precios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mercado_id INT NOT NULL,
    precio_kg DECIMAL(10,2) NOT NULL,
    fecha_registro DATE NOT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT ck_precios_positivo CHECK (precio_kg > 0),
    CONSTRAINT fk_precios_mercado FOREIGN KEY (mercado_id)
        REFERENCES mercados(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_precios_fecha (fecha_registro),
    INDEX idx_precios_activo (activo),
    INDEX idx_precios_mercado_fecha (mercado_id, fecha_registro)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE clima (
    id INT AUTO_INCREMENT PRIMARY KEY,
    probabilidad_lluvia DECIMAL(4,2) NOT NULL,
    ubicacion VARCHAR(100) NOT NULL DEFAULT 'Abapo',
    estacion_id INT DEFAULT NULL,
    fecha_registro DATE NOT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT ck_clima_probabilidad CHECK (
        probabilidad_lluvia >= 0 AND probabilidad_lluvia <= 1
    ),
    CONSTRAINT fk_clima_estacion FOREIGN KEY (estacion_id)
        REFERENCES estaciones(id) ON DELETE SET NULL ON UPDATE CASCADE,
    INDEX idx_clima_fecha (fecha_registro),
    INDEX idx_clima_activo (activo),
    INDEX idx_clima_estacion (estacion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE bloqueos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ruta_id INT NOT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 0,
    fecha_inicio DATE,
    fecha_fin DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_bloqueos_ruta FOREIGN KEY (ruta_id)
        REFERENCES rutas(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_bloqueos_ruta (ruta_id),
    INDEX idx_bloqueos_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE costos_flete (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ruta_id INT NOT NULL,
    costo_cabeza DECIMAL(10,2) NOT NULL,
    semana_inicio DATE NOT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT ck_costos_positivo CHECK (costo_cabeza > 0),
    CONSTRAINT fk_costos_ruta FOREIGN KEY (ruta_id)
        REFERENCES rutas(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_costos_ruta (ruta_id),
    INDEX idx_costos_activo (activo),
    INDEX idx_costos_semana (semana_inicio)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE escenarios_simulacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(10) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    estacion_id INT DEFAULT NULL,
    condicion_id INT DEFAULT NULL,
    usuario_id INT DEFAULT NULL,
    precio_sc DECIMAL(10,2) DEFAULT NULL,
    precio_cb DECIMAL(10,2) DEFAULT NULL,
    costo_c1 DECIMAL(10,2) DEFAULT NULL,
    costo_c2 DECIMAL(10,2) DEFAULT NULL,
    costo_c3 DECIMAL(10,2) DEFAULT NULL,
    costo_c4 DECIMAL(10,2) DEFAULT NULL,
    prob_lluvia DECIMAL(4,2) DEFAULT NULL,
    bloqueo_r1 TINYINT(1) DEFAULT 0,
    bloqueo_r2 TINYINT(1) DEFAULT 0,
    bloqueo_r3 TINYINT(1) DEFAULT 0,
    bloqueo_r4 TINYINT(1) DEFAULT 0,
    datos_json JSON NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT uq_escenarios_codigo UNIQUE (codigo),
    CONSTRAINT fk_escenario_estacion FOREIGN KEY (estacion_id)
        REFERENCES estaciones(id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_escenario_condicion FOREIGN KEY (condicion_id)
        REFERENCES condiciones_ganado(id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_escenario_usuario FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id) ON DELETE SET NULL ON UPDATE CASCADE,
    INDEX idx_escenarios_estacion (estacion_id),
    INDEX idx_escenarios_condicion (condicion_id),
    INDEX idx_escenarios_usuario (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE resultados_optimizacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lote_id INT DEFAULT NULL,
    fecha_calculo DATE NOT NULL,
    x1 INT DEFAULT 0,
    x2 INT DEFAULT 0,
    x3 INT DEFAULT 0,
    x4 INT DEFAULT 0,
    ganancia_total DECIMAL(12,2),
    factible TINYINT(1) DEFAULT 0,
    datos_json JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_resultado_lote FOREIGN KEY (lote_id)
        REFERENCES lotes_ganado(id) ON DELETE SET NULL ON UPDATE CASCADE,
    INDEX idx_resultados_fecha (fecha_calculo),
    INDEX idx_resultados_lote (lote_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE reportes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lote_id INT NOT NULL,
    usuario_id INT DEFAULT NULL,
    fecha_generacion DATETIME NOT NULL,
    pdf_path VARCHAR(500) NOT NULL,
    resultado_json LONGTEXT,
    CONSTRAINT fk_reporte_lote FOREIGN KEY (lote_id)
        REFERENCES lotes_ganado(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_reportes_usuario FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id) ON DELETE SET NULL ON UPDATE CASCADE,
    INDEX idx_reportes_lote (lote_id),
    INDEX idx_reportes_usuario (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE semana_operativa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lote_id INT DEFAULT NULL,
    cabezas INT NOT NULL DEFAULT 0,
    peso_promedio_kg DECIMAL(6,2) NOT NULL DEFAULT 0,
    hora_salida TIME DEFAULT '20:00:00',
    fecha_registro DATE DEFAULT NULL,
    condicion VARCHAR(50) DEFAULT NULL,
    factor_condicion DECIMAL(4,2) DEFAULT NULL,
    estacion VARCHAR(50) DEFAULT NULL,
    factor_estacion DECIMAL(4,2) DEFAULT NULL,
    ruta_id INT DEFAULT NULL,
    ruta_codigo VARCHAR(10) DEFAULT NULL,
    ruta_nombre VARCHAR(100) DEFAULT NULL,
    tiempo_horas DECIMAL(4,1) DEFAULT NULL,
    tipo_via VARCHAR(50) DEFAULT NULL,
    mercado_id INT DEFAULT NULL,
    mercado_nombre VARCHAR(100) DEFAULT NULL,
    mercado_ciudad VARCHAR(100) DEFAULT NULL,
    precio_kg DECIMAL(10,2) DEFAULT NULL,
    costo_cabeza DECIMAL(10,2) DEFAULT NULL,
    bloqueado TINYINT(1) DEFAULT 0,
    probabilidad_lluvia DECIMAL(4,2) DEFAULT NULL,
    eficiencia_inv_efectiva DECIMAL(12,4) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_semana_lote FOREIGN KEY (lote_id)
        REFERENCES lotes_ganado(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_semana_ruta FOREIGN KEY (ruta_id)
        REFERENCES rutas(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_semana_mercado FOREIGN KEY (mercado_id)
        REFERENCES mercados(id) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_semana_lote (lote_id),
    INDEX idx_semana_ruta (ruta_id),
    INDEX idx_semana_mercado (mercado_id),
    INDEX idx_semana_fecha (fecha_registro)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- VISTA SEMANA OPERATIVA (sobre tabla física)

CREATE OR REPLACE VIEW v_semana_operativa AS
SELECT * FROM semana_operativa;

-- PROCEDIMIENTO PARA REFRESCAR DATOS DE SEMANA OPERATIVA

DELIMITER //

CREATE PROCEDURE IF NOT EXISTS sp_refresh_semana_operativa()
BEGIN
  DELETE FROM semana_operativa;
  INSERT INTO semana_operativa (
    lote_id, cabezas, peso_promedio_kg, hora_salida, fecha_registro,
    condicion, factor_condicion, estacion, factor_estacion,
    ruta_id, ruta_codigo, ruta_nombre, tiempo_horas, tipo_via,
    mercado_id, mercado_nombre, mercado_ciudad,
    precio_kg, costo_cabeza, bloqueado, probabilidad_lluvia,
    eficiencia_inv_efectiva
  )
  SELECT
    l.id, l.cabezas, l.peso_promedio_kg, l.hora_salida, l.fecha_registro,
    cg.nombre, cg.factor, e.nombre, e.factor,
    r.id, r.codigo, r.nombre, r.tiempo_horas, r.tipo_via,
    m.id, m.nombre, m.ciudad,
    p.precio_kg, cf.costo_cabeza,
    COALESCE((SELECT b.activo FROM bloqueos b WHERE b.ruta_id = r.id AND b.activo = 1 LIMIT 1), 0),
    cl.probabilidad_lluvia,
    l.peso_promedio_kg * cg.factor * e.factor
  FROM lotes_ganado l
  JOIN condiciones_ganado cg ON l.condicion_id = cg.id
  JOIN estaciones e ON l.estacion_id = e.id
  CROSS JOIN rutas r
  JOIN mercados m ON r.mercado_id = m.id
  LEFT JOIN precios p ON p.mercado_id = m.id AND p.activo = 1
  LEFT JOIN costos_flete cf ON cf.ruta_id = r.id AND cf.activo = 1
  LEFT JOIN clima cl ON cl.activo = 1
  WHERE l.activo = 1 AND r.activo = 1;
END //

DELIMITER ;

-- EVENTO PARA REFRESCO AUTOMATICO CADA HORA

CREATE EVENT IF NOT EXISTS ev_refresh_semana_operativa
ON SCHEDULE EVERY 1 HOUR
DO CALL sp_refresh_semana_operativa();
