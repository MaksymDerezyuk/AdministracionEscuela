USE db_gestion_notas;

-- =============================================
-- 1. GESTORES (Usuarios para entrar al sistema)
-- =============================================
-- Password para ambos: '1234'
-- El hash es: $2y$10$e0MYzXyjpJS7Pd0RVvHwHe.i1i1i1i1... (simulado para el ejemplo)
-- TE RECOMIENDO USAR TU 'hasheador.php' para generar tu propia clave si esta no va.
INSERT INTO tbl_gestores (nombre, email, password, rol) VALUES
('Admin Principal', 'admin@escuela.edu', '$2y$10$uMB/d.w.w.w.w.w.w.w.w.w.w.w.w.w.w.w.w.w.w.w.w.w.w.', 'administrador'),
('Profesor Java', 'profe@escuela.edu', '$2y$10$uMB/d.w.w.w.w.w.w.w.w.w.w.w.w.w.w.w.w.w.w.w.w.w.w.', 'profesor');

-- =============================================
-- 2. FACULTADES
-- =============================================
INSERT INTO tbl_facultades (nombre, campus) VALUES
('Facultad de Informática y Matemáticas', 'Campus Nord'),  -- ID 1
('Facultad de Economía y Empresa', 'Campus Sud');          -- ID 2

-- =============================================
-- 3. GRADOS (Carreras)
-- =============================================
INSERT INTO tbl_grados (nombre, id_facultad) VALUES
('Grado en Ingeniería Informática', 1),                -- ID 1 (Pertenece a Facultad 1)
('Grado en Diseño y Desarrollo de Videojuegos', 1),    -- ID 2 (Pertenece a Facultad 1)
('Grado en Administración y Dirección de Empresas', 2); -- ID 3 (Pertenece a Facultad 2)

-- =============================================
-- 4. ASIGNATURAS
-- =============================================

-- Asignaturas de Ingeniería Informática (ID Grado 1)
INSERT INTO tbl_asignaturas (nombre, id_grado, curso, creditos) VALUES
('Fundamentos de Programación', 1, '1', 6),   -- ID 1
('Bases de Datos I', 1, '1', 6),              -- ID 2
('Matemáticas Discretas', 1, '1', 6),         -- ID 3
('Estructura de Datos', 1, '2', 6),           -- ID 4
('Sistemas Operativos', 1, '2', 6),           -- ID 5
('Inteligencia Artificial', 1, '3', 6),       -- ID 6
('Trabajo Final de Grado', 1, '4', 12);       -- ID 7

-- Asignaturas de Videojuegos (ID Grado 2)
INSERT INTO tbl_asignaturas (nombre, id_grado, curso, creditos) VALUES
('Diseño de Niveles', 2, '1', 6),             -- ID 8
('Motores Gráficos (Unity/Unreal)', 2, '2', 6),-- ID 9
('Animación 3D', 2, '3', 6);                  -- ID 10

-- Asignaturas de ADE (ID Grado 3)
INSERT INTO tbl_asignaturas (nombre, id_grado, curso, creditos) VALUES
('Contabilidad Financiera', 3, '1', 6),       -- ID 11
('Marketing Estratégico', 3, '2', 6),         -- ID 12
('Macroeconomía', 3, '2', 6);                 -- ID 13

-- =============================================
-- 5. MATRÍCULAS (Vinculamos los alumnos que subiste antes)
-- =============================================
-- Asumiendo que ya ejecutaste 'inserts_alumnos.sql' y tienes IDs del 1 al 30

-- Alumnos matriculados en Informática (Grado 1)
INSERT INTO tbl_matriculas (id_alumno, id_grado, anio_academico) VALUES
(1, 1, '2024/2025'), -- Juan Pérez
(2, 1, '2024/2025'), -- María López
(3, 1, '2024/2025'), -- Carlos García
(4, 1, '2024/2025');

-- Alumnos matriculados en Videojuegos (Grado 2)
INSERT INTO tbl_matriculas (id_alumno, id_grado, anio_academico) VALUES
(5, 2, '2024/2025'), -- Luis Martín
(6, 2, '2024/2025');

-- Alumnos matriculados en ADE (Grado 3)
INSERT INTO tbl_matriculas (id_alumno, id_grado, anio_academico) VALUES
(7, 3, '2024/2025'), -- Pedro Díaz
(8, 3, '2024/2025');

-- =============================================
-- 6. NOTAS (Ejemplos)
-- =============================================

INSERT INTO tbl_notas (id_alumno, id_asignatura, convocatoria, nota) VALUES
-- Notas de Juan Pérez (ID 1) en Informática
(1, 1, 'ordinaria', 8.50),  -- Fundamentos
(1, 2, 'ordinaria', 7.25),  -- BBDD
(1, 3, 'extraordinaria', 5.00), -- Mates (Recuperación)

-- Notas de María López (ID 2) en Informática
(2, 1, 'ordinaria', 9.00),
(2, 2, 'ordinaria', 9.50),

-- Notas de Luis Martín (ID 5) en Videojuegos
(5, 8, 'ordinaria', 6.75); -- Diseño Niveles