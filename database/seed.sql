USE dss_agaprova;

-- Mercados destino
INSERT INTO mercados (nombre, ciudad) VALUES
('Mercado Mayorista Santa Cruz', 'Santa Cruz'),
('Feria de Punata - Cochabamba', 'Cochabamba');

-- Estaciones climaticas
INSERT INTO estaciones (nombre, factor, descripcion) VALUES
('Seca', 1.00, 'Temporada seca (mayo-agosto). Mayor rendimiento.'),
('Lluviosa', 0.70, 'Temporada lluviosa (diciembre-marzo). Menor rendimiento.'),
('Transicion', 0.85, 'Transicion (abril, septiembre-noviembre). Rendimiento moderado.');

-- Condiciones de ganado
INSERT INTO condiciones_ganado (nombre, factor, descripcion) VALUES
('Buena', 1.00, 'Condicion optima, peso estandar.'),
('Regular', 0.90, 'Condicion media, rendimiento al 90%.'),
('Invernal', 0.75, 'Vaca flaca en invierno, rendimiento al 75%.');

-- Rutas de despacho (4 rutas)
INSERT INTO rutas (codigo, nombre, origen, destino, mercado_id, tipo_via, tiempo_horas) VALUES
('R01', 'Santa Cruz via Samaipata', 'Vallegrande', 'Santa Cruz', 1, 'Asfalto', 6.5),
('R02', 'Cochabamba via Comarapa', 'Vallegrande', 'Cochabamba', 2, 'Asfalto', 9.0),
('R03', 'Santa Cruz via Ipati-Abapo', 'Vallegrande', 'Santa Cruz', 1, 'Mixto (tierra/asfalto)', 11.0),
('R04', 'Cochabamba via Aiquile', 'Vallegrande', 'Cochabamba', 2, 'Asfalto', 10.0);

-- Usuario operador (password: admin123, hash bcrypt)
INSERT INTO usuarios (username, password_hash, nombre) VALUES
('operador', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Operador AGAPROVA');

-- Precios iniciales
INSERT INTO precios (mercado_id, precio_kg, fecha_registro, activo) VALUES
(1, 32.00, CURDATE(), 1),
(2, 34.00, CURDATE(), 1);

-- Clima inicial
INSERT INTO clima (probabilidad_lluvia, ubicacion, fecha_registro, activo) VALUES
(0.15, 'Abapo', CURDATE(), 1);

-- Costos de flete iniciales
INSERT INTO costos_flete (ruta_id, costo_cabeza, semana_inicio, activo) VALUES
(1, 420.00, CURDATE(), 1),
(2, 510.00, CURDATE(), 1),
(3, 390.00, CURDATE(), 1),
(4, 480.00, CURDATE(), 1);

-- Escenarios de simulacion A-F
INSERT INTO escenarios_simulacion (codigo, nombre, descripcion, datos_json) VALUES
('A', 'Operacion Optima (Clima Soleado)',
 'Clima soleado, sin bloqueos, condicion buena, estacion seca.',
 '{"estacion_id":1,"condicion_id":1,"precio_sc":32,"precio_cb":34,"costo_c1":420,"costo_c2":510,"costo_c3":390,"costo_c4":480,"prob_lluvia":0.10,"bloqueo_r1":0,"bloqueo_r2":0,"bloqueo_r3":0,"bloqueo_r4":0}'),
('B', 'Contingencia Climatica (Lluvia en Abapo)',
 'Alta probabilidad de lluvia en Abapo que bloquea ruta Ipati.',
 '{"estacion_id":2,"condicion_id":2,"precio_sc":30,"precio_cb":32,"costo_c1":420,"costo_c2":510,"costo_c3":390,"costo_c4":480,"prob_lluvia":0.65,"bloqueo_r1":0,"bloqueo_r2":0,"bloqueo_r3":0,"bloqueo_r4":0}'),
('C', 'Estacionalidad Invernal (Vaca Flaca)',
 'Invierno con ganado en condicion invernal y precios bajos.',
 '{"estacion_id":1,"condicion_id":3,"precio_sc":28,"precio_cb":30,"costo_c1":420,"costo_c2":510,"costo_c3":390,"costo_c4":480,"prob_lluvia":0.10,"bloqueo_r1":0,"bloqueo_r2":0,"bloqueo_r3":0,"bloqueo_r4":0}'),
('D', 'Fluctuacion de Mercado (Alza en Cochabamba)',
 'Incremento del precio en Cochabamba para evaluar sensibilidad.',
 '{"estacion_id":1,"condicion_id":1,"precio_sc":32,"precio_cb":38,"costo_c1":420,"costo_c2":510,"costo_c3":390,"costo_c4":480,"prob_lluvia":0.10,"bloqueo_r1":0,"bloqueo_r2":0,"bloqueo_r3":0,"bloqueo_r4":0}'),
('E', 'Restriccion de Ruta (Bloqueo Total)',
 'Bloqueos en rutas alternativas para probar asignacion forzada.',
 '{"estacion_id":1,"condicion_id":1,"precio_sc":32,"precio_cb":34,"costo_c1":420,"costo_c2":510,"costo_c3":390,"costo_c4":480,"prob_lluvia":0.10,"bloqueo_r1":0,"bloqueo_r2":1,"bloqueo_r3":0,"bloqueo_r4":1}'),
('F', 'Crisis Logistica (Bloqueo + Clima + Invierno)',
 'Combinado: bloqueo en Samaipata, clima adverso, condicion invernal.',
 '{"estacion_id":2,"condicion_id":3,"precio_sc":28,"precio_cb":30,"costo_c1":420,"costo_c2":510,"costo_c3":390,"costo_c4":480,"prob_lluvia":0.50,"bloqueo_r1":1,"bloqueo_r2":0,"bloqueo_r3":0,"bloqueo_r4":0}');
