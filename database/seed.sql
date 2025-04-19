-- Seed para Categorías
INSERT INTO categorias_evento (nombre, descripcion) VALUES
('Congresos', 'Eventos académicos multidisciplinarios con expertos internacionales.'),
('Seminarios', 'Sesiones especializadas sobre temas específicos con enfoque práctico.'),
('Talleres', 'Sesiones interactivas para desarrollar habilidades prácticas.'),
('Simposios', 'Discusiones especializadas con múltiples expertos en un campo.'),
('Conferencias Magistrales', 'Charlas principales con expertos de alto nivel.'),
('Ferias Educativas', 'Eventos para mostrar ofertas académicas y de investigación.');

-- Seed para Eventos (10 eventos)
INSERT INTO eventos (titulo, categoria_id, descripcion, ubicacion, fecha_evento, hora_inicio, hora_fin, max_participantes, tarifa_inscripcion, imagen) VALUES
('Congreso Internacional de Innovación Educativa', 1, 'Evento anual que reúne a los principales expertos en pedagogía moderna', 'Auditorio Principal UNI', '2024-09-15', '08:30:00', '18:00:00', 300, 150.00, 'https://apps.camaralima.org.pe/vipcam1/Marketing/2024/expopostgrados/crm.jpg'),
('Seminario de Inteligencia Artificial Aplicada', 2, 'Deep Learning y sus aplicaciones en la industria 4.0', 'Centro de Convenciones Lima', '2024-10-10', '09:00:00', '13:00:00', 150, 80.00, 'https://aulasimple.ai/blog/wp-content/uploads/2024/01/beneficios-de-la-inteligencia-artificial-en-la-educacion.jpg'),
('Taller de Bioinformática Avanzada', 3, 'Manejo de herramientas computacionales para análisis genómico', 'Laboratorio de Ciencias PUCP', '2024-11-05', '14:00:00', '18:00:00', 30, 200.00, 'https://www.unc.edu.pe/wp-content/uploads/2025/04/Curso-Taller-Introduccion-a-la-Bioinformatica-y-a-la-Genomica-Microbiana-1.jpg'),
('Simposio sobre Cambio Climático', 4, 'Impacto del calentamiento global en ecosistemas andinos', 'Museo de Historia Natural', '2024-09-28', '10:00:00', '16:00:00', 200, 0.00, 'https://cdlima.org.pe/wp-content/uploads/2022/11/Flyer-Cambio-Climatico.jpg'),
('Conferencia Magistral de Neurociencia', 5, 'Avances en el estudio de la conciencia humana', 'Teatro Municipal', '2024-12-02', '18:30:00', '20:30:00', 500, 50.00, 'https://ucsm.edu.pe/wp-content/uploads/2021/11/UCSM-V-CONGRESO-INTERNACIONAL-DE-INNOVACI%C3%93N-INDUSTRIAL-EMPRENDIMIENTO-EN-TIEMPOS-DE-PANDEMIA-Portada.jpg'),
('Feria de Posgrados Internacionales', 6, 'Representantes de las 100 mejores universidades del mundo', 'Centro de Exposiciones Jockey', '2024-10-22', '09:00:00', '20:00:00', 1000, 0.00, 'https://apps.camaralima.org.pe/vipcam1/Marketing/2024/expopostgrados/crm.jpg'),
('Taller de Robótica Educativa', 3, 'Implementación de robots en el aula escolar', 'Colegio de Ingenieros', '2024-11-15', '15:00:00', '19:00:00', 40, 120.00, 'https://aulasimple.ai/blog/wp-content/uploads/2024/01/beneficios-de-la-inteligencia-artificial-en-la-educacion.jpg'),
('Simposio de Economía Circular', 4, 'Nuevos modelos de producción sostenible', 'Cámara de Comercio', '2024-09-30', '11:00:00', '14:00:00', 180, 75.00, 'https://www.unc.edu.pe/wp-content/uploads/2025/04/Curso-Taller-Introduccion-a-la-Bioinformatica-y-a-la-Genomica-Microbiana-1.jpg'),
('Conferencia de Astrofísica Moderna', 5, 'Descubrimientos recientes en cosmología', 'Planetario Nacional', '2024-12-10', '19:00:00', '21:30:00', 250, 30.00, 'https://cdlima.org.pe/wp-content/uploads/2022/11/Flyer-Cambio-Climatico.jpg'),
('Seminario de Derecho Digital', 2, 'Regulaciones para IA y metaverso', 'Facultad de Derecho UNMSM', '2024-11-20', '08:00:00', '12:30:00', 120, 90.00, 'https://ucsm.edu.pe/wp-content/uploads/2021/11/UCSM-V-CONGRESO-INTERNACIONAL-DE-INNOVACI%C3%93N-INDUSTRIAL-EMPRENDIMIENTO-EN-TIEMPOS-DE-PANDEMIA-Portada.jpg');

-- Seed para Ponentes (20 ponentes)
INSERT INTO ponentes (nombre, apellido, email, telefono, especializacion, biografia, imagen_perfil) VALUES
('Carlos', 'Mendoza', 'cmendoza@ciencia.pe', '+51987654321', 'Inteligencia Artificial', 'PhD en Computer Science con 15 años de experiencia en ML', 'https://img.freepik.com/vector-premium/perfil-avatar-hombre-icono-redondo_24640-14044.jpg'),
('Ana', 'Guerrero', 'aguerrero@educacion.edu', '+51911223344', 'Pedagogía Digital', 'Directora del Centro de Innovación Educativa UC', 'https://img.freepik.com/vector-premium/perfil-avatar-mujer-icono-redondo_24640-14047.jpg'),
('Luis', 'Vasquez', 'lvasquez@bio.pe', '+51944556677', 'Genómica', 'Investigador principal en proyectos de secuenciación ADN', 'https://img.freepik.com/vector-premium/perfil-avatar-hombre-icono-redondo_24640-14044.jpg'),
('María', 'Fernández', 'mfernandez@clima.org', '+51977889900', 'Cambio Climático', 'Autora líder en informes IPCC sobre ecosistemas andinos', 'https://img.freepik.com/vector-premium/perfil-avatar-mujer-icono-redondo_24640-14047.jpg'),
('Jorge', 'Ramírez', 'jramirez@neuro.edu', '+51900112233', 'Neurociencia Cognitiva', 'Premio Nacional de Investigación en Neurociencias 2023', 'https://img.freepik.com/vector-premium/perfil-avatar-hombre-icono-redondo_24640-14044.jpg'),
('Laura', 'Espinoza', 'lespinoza@posgrados.pe', '+51955443322', 'Educación Superior', 'Directora de Relaciones Internacionales en Universia', 'https://img.freepik.com/vector-premium/perfil-avatar-mujer-icono-redondo_24640-14047.jpg'),
('Roberto', 'Silva', 'rsilva@robotica.pe', '+51966778899', 'Robótica', 'Fundador de la Liga Nacional de Robótica Educativa', 'https://img.freepik.com/vector-premium/perfil-avatar-hombre-icono-redondo_24640-14044.jpg'),
('Carmen', 'Ríos', 'crios@economia.pe', '+51922334455', 'Economía Sostenible', 'Consultora senior en proyectos de economía circular', 'https://img.freepik.com/vector-premium/perfil-avatar-mujer-icono-redondo_24640-14047.jpg'),
('Pablo', 'Gómez', 'pgomez@astro.pe', '+51911223366', 'Cosmología', 'Investigador del Observatorio de Cerro Tololo', 'https://img.freepik.com/vector-premium/perfil-avatar-hombre-icono-redondo_24640-14044.jpg'),
('Daniela', 'Castro', 'dcastro@derecho.pe', '+51977889911', 'Derecho Tecnológico', 'Experta en regulación de IA y protección de datos', 'https://img.freepik.com/vector-premium/perfil-avatar-mujer-icono-redondo_24640-14047.jpg'),
('Diego', 'Herrera', 'dherrera@ia.pe', '+51900112244', 'Deep Learning', 'Líder en desarrollo de redes neuronales profundas', 'https://img.freepik.com/vector-premium/perfil-avatar-hombre-icono-redondo_24640-14044.jpg'),
('Sofía', 'Linares', 'slinares@edu.pe', '+51955443355', 'Tecnología Educativa', 'Pionera en implementación de aulas virtuales en Perú', 'https://img.freepik.com/vector-premium/perfil-avatar-mujer-icono-redondo_24640-14047.jpg'),
('Ricardo', 'Morales', 'rmorales@genoma.pe', '+51966778822', 'Bioinformática', 'Desarrollador de herramientas de análisis genético', 'https://img.freepik.com/vector-premium/perfil-avatar-hombre-icono-redondo_24640-14044.jpg'),
('Valeria', 'Quispe', 'vquispe@ambiente.pe', '+51922334466', 'Ecología', 'Investigadora de ecosistemas de alta montaña', 'https://img.freepik.com/vector-premium/perfil-avatar-mujer-icono-redondo_24640-14047.jpg'),
('Arturo', 'Díaz', 'adiaz@neuro.pe', '+51911223377', 'Neurociencia', 'Especialista en mapeo cerebral y neuroimagen', 'https://img.freepik.com/vector-premium/perfil-avatar-hombre-icono-redondo_24640-14044.jpg'),
('Lucía', 'Torres', 'ltorres@posgrados.pe', '+51977889933', 'Educación Global', 'Coordinadora de programas de doble titulación', 'https://img.freepik.com/vector-premium/perfil-avatar-mujer-icono-redondo_24640-14047.jpg'),
('Fernando', 'Ruiz', 'fruiz@robotica.pe', '+51900112288', 'Automatización', 'Ingeniero en sistemas de automatización industrial', 'https://img.freepik.com/vector-premium/perfil-avatar-hombre-icono-redondo_24640-14044.jpg'),
('Gabriela', 'Vega', 'gvega@eco.pe', '+51955443399', 'Sostenibilidad', 'Consultora en certificaciones ambientales', 'https://img.freepik.com/vector-premium/perfil-avatar-mujer-icono-redondo_24640-14047.jpg'),
('Héctor', 'Salazar', 'hsalazar@astro.pe', '+51966778844', 'Astrofísica', 'Estudioso de formación estelar y galáxias tempranas', 'https://img.freepik.com/vector-premium/perfil-avatar-hombre-icono-redondo_24640-14044.jpg'),
('Alejandra', 'Paredes', 'aparedes@legal.pe', '+51922334477', 'Derecho Digital', 'Especialista en ciberdelitos y propiedad intelectual', 'https://img.freepik.com/vector-premium/perfil-avatar-mujer-icono-redondo_24640-14047.jpg');

-- Asignación de Ponentes a Eventos (3 por evento)
INSERT INTO eventos_ponentes (evento_id, ponente_id, titulo_presentacion, hora_presentacion) VALUES
(1, 1, 'Innovación en la Educación Superior', '09:00:00'),
(1, 2, 'Tendencias en Investigación Académica', '10:30:00'),
(1, 3, 'Gestión Universitaria en el Siglo XXI', '12:00:00'),
(2, 4, 'Inteligencia Artificial Aplicada a la Medicina', '10:00:00'),
(2, 5, 'Deep Learning para Procesamiento de Imágenes', '11:30:00'),
(2, 6, 'Ética en el Desarrollo de IA', '13:00:00'),
(3, 7, 'Herramientas para Análisis Genómico', '14:30:00'),
(3, 8, 'Bioinformática en la Investigación Oncológica', '16:00:00'),
(3, 9, 'Big Data en Estudios Genéticos', '17:30:00'),
(4, 10, 'Impacto del Deshielo Glaciar', '11:00:00'),
(4, 11, 'Políticas Públicas Ambientales', '12:30:00'),
(4, 12, 'Energías Renovables en los Andes', '14:00:00'),
(5, 13, 'Misterios de la Conciencia Humana', '19:00:00'),
(5, 14, 'Avances en Neuroimagen', '20:00:00'),
(5, 15, 'Sueño y Memoria', '21:00:00'),
(6, 16, 'Oportunidades de Posgrado en Europa', '10:00:00'),
(6, 17, 'Becas Internacionales', '11:30:00'),
(6, 18, 'Cómo Elegir un Programa de Posgrado', '13:00:00'),
(7, 19, 'Robótica en Educación Primaria', '15:30:00'),
(7, 20, 'Diseño de Robots Educativos', '17:00:00'),
(7, 1, 'Programación para Niños', '18:30:00'),
(8, 2, 'Modelos de Economía Circular', '11:30:00'),
(8, 3, 'Casos de Éxito en Sostenibilidad', '13:00:00'),
(8, 4, 'Legislación para la Economía Verde', '14:30:00'),
(9, 5, 'Agujeros Negros Supermasivos', '19:30:00'),
(9, 6, 'Materia Oscura y Energía Oscura', '20:15:00'),
(9, 7, 'Telescopios del Futuro', '21:00:00'),
(10, 8, 'Regulación del Metaverso', '09:00:00'),
(10, 9, 'Criptoderechos y NFTs', '10:30:00'),
(10, 10, 'Privacidad en la Era Digital', '12:00:00');

-- Seed para Participantes (100 participantes)
INSERT INTO participantes (nombre, apellido, email, telefono, institucion) VALUES
('Juan', 'Pérez', 'juan.perez@email.com', '+51987654321', 'Universidad Nacional de Ingeniería'),
('María', 'García', 'maria.garcia@email.com', '+51911223344', 'Pontificia Universidad Católica del Perú'),
('Carlos', 'Rodríguez', 'carlos.rodriguez@email.com', '+51944556677', 'Universidad Nacional Mayor de San Marcos'),
('Ana', 'Martínez', 'ana.martinez@email.com', '+51977889900', 'Universidad del Pacífico'),
('Luis', 'López', 'luis.lopez@email.com', '+51900112233', 'Universidad de Lima'),
('Laura', 'Sánchez', 'laura.sanchez@email.com', '+51955443322', 'Universidad Peruana Cayetano Heredia'),
('Pedro', 'Gómez', 'pedro.gomez@email.com', '+51966778899', 'Universidad ESAN'),
('Sofía', 'Díaz', 'sofia.diaz@email.com', '+51922334455', 'Universidad San Ignacio de Loyola'),
('Jorge', 'Hernández', 'jorge.hernandez@email.com', '+51911223366', 'Universidad Privada del Norte'),
('Lucía', 'Torres', 'lucia.torres@email.com', '+51977889911', 'Universidad Ricardo Palma'),
('Miguel', 'Flores', 'miguel.flores@email.com', '+51900112244', 'Universidad Nacional Agraria La Molina'),
('Elena', 'Vargas', 'elena.vargas@email.com', '+51955443355', 'Universidad Nacional de San Agustín'),
('Diego', 'Ruiz', 'diego.ruiz@email.com', '+51966778822', 'Universidad Científica del Sur'),
('Carmen', 'Castro', 'carmen.castro@email.com', '+51922334466', 'Universidad Peruana de Ciencias Aplicadas'),
('Andrés', 'Morales', 'andres.morales@email.com', '+51911223377', 'Universidad de Piura'),
('Fernanda', 'Ortiz', 'fernanda.ortiz@email.com', '+51977889933', 'Universidad Antonio Ruiz de Montoya'),
('Roberto', 'Gutiérrez', 'roberto.gutierrez@email.com', '+51900112288', 'Universidad Nacional de Trujillo'),
('Valeria', 'Rojas', 'valeria.rojas@email.com', '+51955443399', 'Universidad Nacional de Cajamarca'),
('Javier', 'Mendoza', 'javier.mendoza@email.com', '+51966778844', 'Universidad Nacional del Altiplano'),
('Daniela', 'Silva', 'daniela.silva@email.com', '+51922334477', 'Universidad Nacional de la Amazonía Peruana');

-- Seed para Registros (50 registros por evento)
INSERT INTO registros (evento_id, participante_id, estado_pago, asistencia, retroalimentacion)
SELECT 
  e.evento_id,
  p.participante_id,
  ELT(FLOOR(1 + RAND() * 3), 'pendiente', 'pagado', 'cancelado'),
  RAND() > 0.5,
  CASE WHEN RAND() > 0.7 THEN 'Excelente organización y contenido relevante' ELSE NULL END
FROM eventos e
CROSS JOIN participantes p
WHERE p.participante_id BETWEEN 1 AND 100
ORDER BY RAND()
LIMIT 50;
