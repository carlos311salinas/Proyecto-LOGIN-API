CREATE DATABASE IF NOT EXISTS pruebas_php;

USE pruebas_php;

-- TABLA USUARIOS

CREATE TABLE usuarios (

    id INT AUTO_INCREMENT PRIMARY KEY,

    nombre VARCHAR(100) NOT NULL,

    email VARCHAR(100) NOT NULL UNIQUE,

    telefono VARCHAR(20),

    password_hash VARCHAR(255) NOT NULL,

    rol ENUM('admin', 'usuario') DEFAULT 'usuario',

    estado ENUM('activo', 'inactivo') DEFAULT 'activo',

    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);

-- TABLA CONSULTAS CLIMA

CREATE TABLE consultas_clima (

    id INT AUTO_INCREMENT PRIMARY KEY,

    usuario_id INT NOT NULL,

    ciudad VARCHAR(100),

    temperatura DECIMAL(5,2),

    descripcion VARCHAR(255),

    humedad INT,

    viento DECIMAL(5,2),

    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (usuario_id)
    REFERENCES usuarios(id)

);

-- TABLA LOGS ACCESO

CREATE TABLE logs_acceso (

    id INT AUTO_INCREMENT PRIMARY KEY,

    usuario_id INT,

    accion VARCHAR(255),

    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    ip VARCHAR(100)

);

-- USUARIO ADMIN PRUEBA

INSERT INTO usuarios (

    nombre,
    email,
    telefono,
    password_hash,
    rol,
    estado

)

VALUES

(
    'Administrador',
    'carlos311salinas@gmail.com',
    '3216609166',
    '$2y$10$$2y$10$pzU9bhDZI80YB6H.Xap8A.mzn46o5a6rx5xijlQrCfWpcjJty8doe',
    'admin',
    'activo'
);