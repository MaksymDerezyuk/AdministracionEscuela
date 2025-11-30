USE db_gestion_notas;


-- ==========================================================
-- 1. GESTORES (Usuarios: Admin y Profesores)
-- ==========================================================
-- Password hash ejemplo para '1234': $2y$10$e0MYzXyjpJS7Pd0RVvHwHe.i1i1i1i1...
-- (Asegúrate de usar hashes válidos generados por tu sistema si estos no van)

INSERT INTO tbl_gestores (id, nombre, email, password, rol) VALUES
(1, 'Admin Principal', 'admin@escuela.edu', '$2y$10$FXaaKh8lzROXO91xVnocRuXctk2x5SHJAggsbvYLYw1iYlaUpcAC2', 'administrador'),
(2, 'Alberto De Santos', 'alberto@escuela.edu', '$2y$10$FXaaKh8lzROXO91xVnocRuXctk2x5SHJAggsbvYLYw1iYlaUpcAC2', 'profesor'),
(3, 'Maksym Derezuk', 'maksym@escuela.edu', '$2y$10$FXaaKh8lzROXO91xVnocRuXctk2x5SHJAggsbvYLYw1iYlaUpcAC2', 'profesor'),
(4, 'Aaron Suarez', 'aaron@escuela.edu', '$2y$10$FXaaKh8lzROXO91xVnocRuXctk2x5SHJAggsbvYLYw1iYlaUpcAC2', 'profesor'),
(5, 'Sergi Nebot', 'sergi@escuela.edu', '$2y$10$FXaaKh8lzROXO91xVnocRuXctk2x5SHJAggsbvYLYw1iYlaUpcAC2', 'profesor'),
(6, 'Gerard Rodriguez', 'gerard@escuela.edu', '$2y$10$FXaaKh8lzROXO91xVnocRuXctk2x5SHJAggsbvYLYw1iYlaUpcAC2', 'profesor');

-- ==========================================================
-- 2. FACULTADES
-- ==========================================================
INSERT INTO tbl_facultades (id, nombre, campus) VALUES
(1, 'Facultad de Informática y Matemáticas', 'Campus Nord'),
(2, 'Facultad de Economía y Empresa', 'Campus Sud');

-- ==========================================================
-- 3. GRADOS (Carreras)
-- ==========================================================
INSERT INTO tbl_grados (id, nombre, id_facultad) VALUES
(1, 'Grado en Ingeniería Informática', 1),
(2, 'Grado en Diseño y Desarrollo de Videojuegos', 1),
(3, 'Grado en Administración y Dirección de Empresas', 2);

-- ==========================================================
-- 4. ASIGNATURAS
-- ==========================================================
-- Informática (Grado 1)
INSERT INTO tbl_asignaturas (id, nombre, id_grado, curso, creditos) VALUES
(1, 'Fundamentos de Programación', 1, '1', 6),
(2, 'Bases de Datos I', 1, '1', 6),
(3, 'Matemáticas Discretas', 1, '1', 6),
(4, 'Estructura de Datos', 1, '2', 6),
(5, 'Sistemas Operativos', 1, '2', 6),
(6, 'Inteligencia Artificial', 1, '3', 6),
(7, 'Trabajo Final de Grado', 1, '4', 12);

-- Videojuegos (Grado 2)
INSERT INTO tbl_asignaturas (id, nombre, id_grado, curso, creditos) VALUES
(8, 'Diseño de Niveles', 2, '1', 6),
(9, 'Motores Gráficos (Unity/Unreal)', 2, '2', 6),
(10, 'Animación 3D', 2, '3', 6);

-- ADE (Grado 3)
INSERT INTO tbl_asignaturas (id, nombre, id_grado, curso, creditos) VALUES
(11, 'Contabilidad Financiera', 3, '1', 6),
(12, 'Marketing Estratégico', 3, '2', 6),
(13, 'Macroeconomía', 3, '2', 6);

-- ==========================================================
-- 5. RELACIÓN PROFESOR - ASIGNATURA (CORREGIDO)
-- ==========================================================
-- IMPORTANTE: Aquí NO aparece el ID 1 (Admin).

INSERT INTO tbl_profesor_asignatura (id_profesor, id_asignatura) VALUES
-- Profesor Java (ID 2): Programación básica
(2, 1), -- Fundamentos
(2, 2), -- Bases de Datos
(2, 4), -- Estructura de Datos

-- Roberto Matemáticas (ID 6): Toma las que antes tenía el Admin
(6, 3), -- Mates Discretas
(6, 5), -- Sistemas Operativos

-- Sara Sistemas (ID 5): Informática Avanzada
(5, 6), -- IA
(5, 7), -- TFG

-- Prof. Marcos 3D (ID 4): Videojuegos
(4, 8), -- Diseño de Niveles
(4, 9), -- Motores Gráficos
(4, 10), -- Animación 3D

-- Dra. Elena (ID 3): ADE
(3, 11), -- Contabilidad
(3, 12), -- Marketing
(3, 13); -- Macroeconomía

-- ==========================================================
-- 6. MATRÍCULAS
-- ==========================================================
INSERT INTO tbl_matriculas (id_alumno, id_grado, anio_academico) VALUES
(1, 1, '2024/2025'), -- Juan (Info)
(2, 1, '2024/2025'), -- María (Info)
(3, 1, '2024/2025'), 
(4, 1, '2024/2025'), 
(5, 2, '2024/2025'), -- Luis (Videojuegos)
(6, 2, '2024/2025'), 
(7, 3, '2024/2025'), -- Pedro (ADE)
(8, 3, '2024/2025');

-- ==========================================================
-- 7. NOTAS
-- ==========================================================
INSERT INTO tbl_notas (id_alumno, id_asignatura, convocatoria, nota) VALUES
-- Juan Pérez (ID 1)
(1, 1, 'ordinaria', 8.50),  -- Muestra: Profe Java
(1, 2, 'ordinaria', 7.25),  -- Muestra: Profe Java
(1, 3, 'extraordinaria', 5.00), -- Mates (Ahora mostrará: Roberto Matemáticas)

-- María López (ID 2)
(2, 1, 'ordinaria', 9.00),
(2, 2, 'ordinaria', 9.50),
(2, 6, 'ordinaria', 10.00), -- IA (Muestra: Sara Sistemas)

-- Luis Martín (ID 5)
(5, 8, 'ordinaria', 6.75),  -- Muestra: Marcos 3D
(5, 9, 'ordinaria', 8.00),  -- Muestra: Marcos 3D

-- Pedro Díaz (ID 7)
(7, 11, 'ordinaria', 7.50), -- Muestra: Dra. Elena
(7, 12, 'ordinaria', 6.00); -- Muestra: Dra. Elena

-- 