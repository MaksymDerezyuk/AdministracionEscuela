-- Crear base de datos
CREATE DATABASE IF NOT EXISTS db_gestion_notas;
USE db_gestion_notas;

-- =====================================
-- CREACIÓN DE TABLAS
-- =====================================

-- Tabla de gestores (profesores, administración, dirección)
CREATE TABLE IF NOT EXISTS tbl_gestores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('profesor','secretaria','direccion','administrador') DEFAULT 'profesor'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de alumnos
CREATE TABLE IF NOT EXISTS tbl_alumnos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dni VARCHAR(15) NOT NULL UNIQUE,
    nombre VARCHAR(50) NOT NULL,
    apellido1 VARCHAR(50) NOT NULL,
    apellido2 VARCHAR(50),
    email VARCHAR(100) UNIQUE,
    fecha_nacimiento DATE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de facultades (propio de universidad)
CREATE TABLE IF NOT EXISTS tbl_facultades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    campus VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de grados (carreras universitarias)
CREATE TABLE IF NOT EXISTS tbl_grados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    id_facultad INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de asignaturas
CREATE TABLE IF NOT EXISTS tbl_asignaturas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    id_grado INT NOT NULL,
    curso ENUM('1','2','3','4') NOT NULL,
    creditos INT DEFAULT 6
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla matrícula alumnos → grados
CREATE TABLE IF NOT EXISTS tbl_matriculas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_alumno INT NOT NULL,
    id_grado INT NOT NULL,
    anio_academico VARCHAR(9) NOT NULL   -- Ej: 2024/2025
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla profesor imparte asignatura
CREATE TABLE IF NOT EXISTS tbl_profesor_asignatura (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_profesor INT NOT NULL,
    id_asignatura INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla notas
CREATE TABLE IF NOT EXISTS tbl_notas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_alumno INT NOT NULL,
    id_asignatura INT NOT NULL,
    convocatoria ENUM('ordinaria','extraordinaria') NOT NULL,
    nota DECIMAL(4,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla asistencias
CREATE TABLE IF NOT EXISTS tbl_asistencias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_alumno INT NOT NULL,
    id_asignatura INT NOT NULL,
    fecha DATE NOT NULL,
    estado ENUM('presente','ausente','tarde','justificado') DEFAULT 'presente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================
-- RELACIONES (FOREIGN KEYS)
-- =====================================

-- Grados → facultades
ALTER TABLE tbl_grados
    ADD CONSTRAINT fk_grado_facultad
    FOREIGN KEY (id_facultad) REFERENCES tbl_facultades(id);

-- Asignaturas → grados
ALTER TABLE tbl_asignaturas
    ADD CONSTRAINT fk_asignatura_grado
    FOREIGN KEY (id_grado) REFERENCES tbl_grados(id);

-- Matrículas → alumnos
ALTER TABLE tbl_matriculas
    ADD CONSTRAINT fk_matricula_alumno
    FOREIGN KEY (id_alumno) REFERENCES tbl_alumnos(id);

-- Matrículas → grados
ALTER TABLE tbl_matriculas
    ADD CONSTRAINT fk_matricula_grado
    FOREIGN KEY (id_grado) REFERENCES tbl_grados(id);

-- Profesor-imparte → gestor
ALTER TABLE tbl_profesor_asignatura
    ADD CONSTRAINT fk_profesor
    FOREIGN KEY (id_profesor) REFERENCES tbl_gestores(id);

-- Profesor-imparte → asignatura
ALTER TABLE tbl_profesor_asignatura
    ADD CONSTRAINT fk_profesor_asignatura
    FOREIGN KEY (id_asignatura) REFERENCES tbl_asignaturas(id);

-- Notas → alumnos
ALTER TABLE tbl_notas
    ADD CONSTRAINT fk_notas_alumno
    FOREIGN KEY (id_alumno) REFERENCES tbl_alumnos(id);

-- Notas → asignaturas
ALTER TABLE tbl_notas
    ADD CONSTRAINT fk_notas_asignatura
    FOREIGN KEY (id_asignatura) REFERENCES tbl_asignaturas(id);

-- Asistencias → alumnos
ALTER TABLE tbl_asistencias
    ADD CONSTRAINT fk_asistencia_alumno
    FOREIGN KEY (id_alumno) REFERENCES tbl_alumnos(id);

-- Asistencias → asignaturas
ALTER TABLE tbl_asistencias
    ADD CONSTRAINT fk_asistencia_asignatura
    FOREIGN KEY (id_asignatura) REFERENCES tbl_asignaturas(id);
