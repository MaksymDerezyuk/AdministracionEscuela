-- =====================================
-- CREACIÓN DE LA BASE DE DATOS
-- =====================================

CREATE DATABASE IF NOT EXISTS db_gestion_notas;
USE db_gestion_notas;

-- =====================================
-- CREACIÓN DE TABLAS
-- =====================================

-- Tabla de gestores (profesores, educadores, secretaría, dirección, administrador)
CREATE TABLE IF NOT EXISTS tbl_gestores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('profesor', 'educador', 'secretaria', 'direccion', 'administrador') DEFAULT 'profesor'
);

-- =====================================
-- DATOS DE EJEMPLO
-- =====================================

INSERT INTO tbl_gestores (nombre_completo, email, password, rol) VALUES
('Administrador del Sistema', 'admin@centro.edu', '$2y$10$5mJbkW4B1C/P4gWUThlWVOETvOoES33/jqAEz5AyZ23Gs2H/5QovK', 'administrador');

INSERT INTO tbl_gestores (nombre_completo, email, password, rol) VALUES
('Pep Jordian', 'pep@centro.edu', '$2y$10$5mJbkW4B1C/P4gWUThlWVOETvOoES33/jqAEz5AyZ23Gs2H/5QovK', 'profesor');
