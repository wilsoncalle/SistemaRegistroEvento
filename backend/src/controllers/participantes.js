const pool = require('../config/database'); 
// Implementar funciones de controlador para participantes 

// Obtener todos los participantes
const getParticipantes = async (req, res) => {
    try {
        const [participantes] = await pool.query('SELECT * FROM participantes');
        res.json(participantes);
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

// Obtener un participante por ID
const getParticipanteById = async (req, res) => {
    try {
        const [participante] = await pool.query('SELECT * FROM participantes WHERE participante_id = ?', [req.params.id]);
        if (participante.length === 0) {
            return res.status(404).json({ message: 'Participante no encontrado' });
        }

        // Obtener los eventos registrados del participante
        const [eventos] = await pool.query(`
            SELECT e.*, r.fecha_registro, r.estado_pago, r.asistencia, r.retroalimentacion
            FROM eventos e 
            JOIN registros r ON e.evento_id = r.evento_id 
            WHERE r.participante_id = ?
        `, [req.params.id]);

        participante[0].eventos_registrados = eventos;
        res.json(participante[0]);
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

// Crear un nuevo participante
const createParticipante = async (req, res) => {
    const { nombre, apellido, email, telefono, institucion } = req.body;

    try {
        const [result] = await pool.query(
            `INSERT INTO participantes (
                nombre, apellido, email, telefono, institucion
            ) VALUES (?, ?, ?, ?, ?)`,
            [nombre, apellido, email, telefono, institucion]
        );

        res.status(201).json({
            id: result.insertId,
            nombre,
            apellido,
            email
        });
    } catch (error) {
        if (error.code === 'ER_DUP_ENTRY') {
            return res.status(400).json({ message: 'El email ya está registrado' });
        }
        res.status(500).json({ message: error.message });
    }
};

// Actualizar un participante
const updateParticipante = async (req, res) => {
    const { nombre, apellido, email, telefono, institucion } = req.body;

    try {
        const [result] = await pool.query(
            `UPDATE participantes SET 
                nombre = ?, apellido = ?, email = ?, 
                telefono = ?, institucion = ?
            WHERE participante_id = ?`,
            [nombre, apellido, email, telefono, institucion, req.params.id]
        );

        if (result.affectedRows === 0) {
            return res.status(404).json({ message: 'Participante no encontrado' });
        }
        res.json({ message: 'Participante actualizado exitosamente' });
    } catch (error) {
        if (error.code === 'ER_DUP_ENTRY') {
            return res.status(400).json({ message: 'El email ya está registrado' });
        }
        res.status(500).json({ message: error.message });
    }
};

// Eliminar un participante
const deleteParticipante = async (req, res) => {
    try {
        const [result] = await pool.query('DELETE FROM participantes WHERE participante_id = ?', [req.params.id]);
        if (result.affectedRows === 0) {
            return res.status(404).json({ message: 'Participante no encontrado' });
        }
        res.json({ message: 'Participante eliminado exitosamente' });
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

module.exports = {
    getParticipantes,
    getParticipanteById,
    createParticipante,
    updateParticipante,
    deleteParticipante
};
