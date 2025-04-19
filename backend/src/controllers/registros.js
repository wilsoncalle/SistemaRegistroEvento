const pool = require('../config/database');

// Obtener todos los registros
const getRegistros = async (req, res) => {
    try {
        const [registros] = await pool.query(`
            SELECT r.*, 
                   e.titulo as evento_titulo,
                   CONCAT(p.nombre, ' ', p.apellido) as participante_nombre
            FROM registros r
            JOIN eventos e ON r.evento_id = e.evento_id
            JOIN participantes p ON r.participante_id = p.participante_id
        `);
        res.json(registros);
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

// Obtener un registro por ID
const getRegistroById = async (req, res) => {
    try {
        const [registro] = await pool.query(`
            SELECT r.*, 
                   e.titulo as evento_titulo,
                   e.fecha_evento,
                   e.hora_inicio,
                   e.hora_fin,
                   e.ubicacion,
                   CONCAT(p.nombre, ' ', p.apellido) as participante_nombre,
                   p.email as participante_email
            FROM registros r
            JOIN eventos e ON r.evento_id = e.evento_id
            JOIN participantes p ON r.participante_id = p.participante_id
            WHERE r.registro_id = ?
        `, [req.params.id]);

        if (registro.length === 0) {
            return res.status(404).json({ message: 'Registro no encontrado' });
        }
        res.json(registro[0]);
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

// Crear un nuevo registro
const createRegistro = async (req, res) => {
    const { evento_id, participante_id, estado_pago } = req.body;

    try {
        // Verificar si hay cupo disponible
        const [[{ total_registros }]] = await pool.query(
            'SELECT COUNT(*) as total_registros FROM registros WHERE evento_id = ?',
            [evento_id]
        );

        const [[evento]] = await pool.query(
            'SELECT max_participantes FROM eventos WHERE evento_id = ?',
            [evento_id]
        );

        if (total_registros >= evento.max_participantes) {
            return res.status(400).json({ message: 'El evento ha alcanzado su capacidad máxima' });
        }

        // Verificar si el participante ya está registrado en el evento
        const [[registro_existente]] = await pool.query(
            'SELECT registro_id FROM registros WHERE evento_id = ? AND participante_id = ?',
            [evento_id, participante_id]
        );

        if (registro_existente) {
            return res.status(400).json({ message: 'El participante ya está registrado en este evento' });
        }

        const [result] = await pool.query(
            `INSERT INTO registros (
                evento_id, participante_id, estado_pago
            ) VALUES (?, ?, ?)`,
            [evento_id, participante_id, estado_pago || 'pendiente']
        );

        res.status(201).json({
            id: result.insertId,
            evento_id,
            participante_id,
            estado_pago: estado_pago || 'pendiente'
        });
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

// Actualizar un registro
const updateRegistro = async (req, res) => {
    const { estado_pago, asistencia, retroalimentacion } = req.body;

    try {
        const [result] = await pool.query(
            `UPDATE registros SET 
                estado_pago = ?,
                asistencia = ?,
                retroalimentacion = ?
            WHERE registro_id = ?`,
            [estado_pago, asistencia, retroalimentacion, req.params.id]
        );

        if (result.affectedRows === 0) {
            return res.status(404).json({ message: 'Registro no encontrado' });
        }
        res.json({ message: 'Registro actualizado exitosamente' });
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

// Eliminar un registro
const deleteRegistro = async (req, res) => {
    try {
        const [result] = await pool.query('DELETE FROM registros WHERE registro_id = ?', [req.params.id]);
        if (result.affectedRows === 0) {
            return res.status(404).json({ message: 'Registro no encontrado' });
        }
        res.json({ message: 'Registro eliminado exitosamente' });
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

module.exports = {
    getRegistros,
    getRegistroById,
    createRegistro,
    updateRegistro,
    deleteRegistro
};
