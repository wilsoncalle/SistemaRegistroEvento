const pool = require('../config/database');

// Obtener todos los eventos
const getEventos = async (req, res) => {
    try {
        const [eventos] = await pool.query(`
            SELECT e.*, c.nombre as categoria_nombre 
            FROM eventos e 
            LEFT JOIN categorias_evento c ON e.categoria_id = c.categoria_id
        `);
        res.json(eventos);
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

// Obtener un evento por ID
const getEventoById = async (req, res) => {
    try {
        const [evento] = await pool.query(`
            SELECT e.*, c.nombre as categoria_nombre 
            FROM eventos e 
            LEFT JOIN categorias_evento c ON e.categoria_id = c.categoria_id 
            WHERE e.evento_id = ?
        `, [req.params.id]);
        
        if (evento.length === 0) {
            return res.status(404).json({ message: 'Evento no encontrado' });
        }

        // Obtener los ponentes del evento
        const [ponentes] = await pool.query(`
            SELECT p.*, ep.titulo_presentacion, ep.hora_presentacion 
            FROM ponentes p 
            JOIN eventos_ponentes ep ON p.ponente_id = ep.ponente_id 
            WHERE ep.evento_id = ?
        `, [req.params.id]);

        evento[0].ponentes = ponentes;
        res.json(evento[0]);
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

// Crear un nuevo evento
const createEvento = async (req, res) => {
    const {
        titulo,
        categoria_id,
        descripcion,
        ubicacion,
        fecha_evento,
        hora_inicio,
        hora_fin,
        max_participantes,
        tarifa_inscripcion,
        imagen,
        ponentes
    } = req.body;

    try {
        const connection = await pool.getConnection();
        await connection.beginTransaction();

        try {
            // Insertar el evento
            const [result] = await connection.query(
                `INSERT INTO eventos (
                    titulo, categoria_id, descripcion, ubicacion, 
                    fecha_evento, hora_inicio, hora_fin, 
                    max_participantes, tarifa_inscripcion, imagen
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
                [
                    titulo, categoria_id, descripcion, ubicacion,
                    fecha_evento, hora_inicio, hora_fin,
                    max_participantes, tarifa_inscripcion, imagen
                ]
            );

            const eventoId = result.insertId;

            // Insertar los ponentes del evento si se proporcionaron
            if (ponentes && ponentes.length > 0) {
                for (const ponente of ponentes) {
                    await connection.query(
                        `INSERT INTO eventos_ponentes (
                            evento_id, ponente_id, titulo_presentacion, hora_presentacion
                        ) VALUES (?, ?, ?, ?)`,
                        [eventoId, ponente.ponente_id, ponente.titulo_presentacion, ponente.hora_presentacion]
                    );
                }
            }

            await connection.commit();
            res.status(201).json({
                id: eventoId,
                message: 'Evento creado exitosamente'
            });
        } catch (error) {
            await connection.rollback();
            throw error;
        } finally {
            connection.release();
        }
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

// Actualizar un evento
const updateEvento = async (req, res) => {
    const {
        titulo,
        categoria_id,
        descripcion,
        ubicacion,
        fecha_evento,
        hora_inicio,
        hora_fin,
        max_participantes,
        tarifa_inscripcion,
        imagen,
        ponentes
    } = req.body;

    try {
        const connection = await pool.getConnection();
        await connection.beginTransaction();

        try {
            // Actualizar el evento
            const [result] = await connection.query(
                `UPDATE eventos SET 
                    titulo = ?, categoria_id = ?, descripcion = ?, 
                    ubicacion = ?, fecha_evento = ?, hora_inicio = ?, 
                    hora_fin = ?, max_participantes = ?, 
                    tarifa_inscripcion = ?, imagen = ?
                WHERE evento_id = ?`,
                [
                    titulo, categoria_id, descripcion,
                    ubicacion, fecha_evento, hora_inicio,
                    hora_fin, max_participantes,
                    tarifa_inscripcion, imagen,
                    req.params.id
                ]
            );

            if (result.affectedRows === 0) {
                await connection.rollback();
                return res.status(404).json({ message: 'Evento no encontrado' });
            }

            // Actualizar ponentes si se proporcionaron
            if (ponentes) {
                // Eliminar ponentes actuales
                await connection.query(
                    'DELETE FROM eventos_ponentes WHERE evento_id = ?',
                    [req.params.id]
                );

                // Insertar nuevos ponentes
                for (const ponente of ponentes) {
                    await connection.query(
                        `INSERT INTO eventos_ponentes (
                            evento_id, ponente_id, titulo_presentacion, hora_presentacion
                        ) VALUES (?, ?, ?, ?)`,
                        [req.params.id, ponente.ponente_id, ponente.titulo_presentacion, ponente.hora_presentacion]
                    );
                }
            }

            await connection.commit();
            res.json({ message: 'Evento actualizado exitosamente' });
        } catch (error) {
            await connection.rollback();
            throw error;
        } finally {
            connection.release();
        }
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

// Eliminar un evento
const deleteEvento = async (req, res) => {
    try {
        const [result] = await pool.query('DELETE FROM eventos WHERE evento_id = ?', [req.params.id]);
        if (result.affectedRows === 0) {
            return res.status(404).json({ message: 'Evento no encontrado' });
        }
        res.json({ message: 'Evento eliminado exitosamente' });
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

module.exports = {
    getEventos,
    getEventoById,
    createEvento,
    updateEvento,
    deleteEvento
};
