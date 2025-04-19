-- Script para crear la base de datos y sus tablas con nombres en español

-- 1. Creación de la base de datos
CREATE DATABASE IF NOT EXISTS eventos_academicos;
USE eventos_academicos;

-- 2. Tabla de Categorías de Eventos (categorias_evento)
CREATE TABLE IF NOT EXISTS categorias_evento (
    categoria_id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. Tabla de Eventos (eventos)
CREATE TABLE IF NOT EXISTS eventos (
    evento_id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    categoria_id INT,
    descripcion TEXT,
    ubicacion VARCHAR(200),
    fecha_evento DATE NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    max_participantes INT NOT NULL,
    tarifa_inscripcion DECIMAL(10,2),
    imagen VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias_evento(categoria_id) ON DELETE SET NULL
);

-- 4. Tabla de Ponentes (ponentes)
CREATE TABLE IF NOT EXISTS ponentes (
    ponente_id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    telefono VARCHAR(20),
    especializacion VARCHAR(100),
    biografia TEXT,
    imagen_perfil VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 5. Tabla intermedia para asignar Ponentes a Eventos (eventos_ponentes)
CREATE TABLE IF NOT EXISTS eventos_ponentes (
    evento_id INT NOT NULL,
    ponente_id INT NOT NULL,
    titulo_presentacion VARCHAR(200) NOT NULL,
    hora_presentacion TIME NOT NULL,
    PRIMARY KEY (evento_id, ponente_id),
    FOREIGN KEY (evento_id) REFERENCES eventos(evento_id) ON DELETE CASCADE,
    FOREIGN KEY (ponente_id) REFERENCES ponentes(ponente_id) ON DELETE CASCADE
);

-- 6. Tabla de Participantes (participantes)
CREATE TABLE IF NOT EXISTS participantes (
    participante_id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    telefono VARCHAR(20),
    institucion VARCHAR(150),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 7. Tabla de Registros de Inscripción a Eventos (registros)
CREATE TABLE IF NOT EXISTS registros (
    registro_id INT AUTO_INCREMENT PRIMARY KEY,
    evento_id INT NOT NULL,
    participante_id INT NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado_pago ENUM('pendiente', 'pagado', 'cancelado') DEFAULT 'pendiente',
    asistencia BOOLEAN DEFAULT FALSE,
    retroalimentacion TEXT,
    FOREIGN KEY (evento_id) REFERENCES eventos(evento_id) ON DELETE CASCADE,
    FOREIGN KEY (participante_id) REFERENCES participantes(participante_id) ON DELETE CASCADE
);
