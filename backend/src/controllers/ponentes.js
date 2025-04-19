const pool = require('../config/database');

// Obtener todos los ponentes
const getPonentes = async (req, res) => {
    try {
        const [ponentes] = await pool.query('SELECT * FROM ponentes');
        res.json(ponentes);
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

// Obtener un ponente por ID
const getPonenteById = async (req, res) => {
    try {
        const [ponente] = await pool.query('SELECT * FROM ponentes WHERE ponente_id = ?', [req.params.id]);
        if (ponente.length === 0) {
            return res.status(404).json({ message: 'Ponente no encontrado' });
        }

        // Obtener los eventos del ponente
        const [eventos] = await pool.query(`
            SELECT e.*, ep.titulo_presentacion, ep.hora_presentacion 
            FROM eventos e 
            JOIN eventos_ponentes ep ON e.evento_id = ep.evento_id 
            WHERE ep.ponente_id = ?
        `, [req.params.id]);

        ponente[0].eventos = eventos;
        res.json(ponente[0]);
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

// Crear un nuevo ponente
const createPonente = async (req, res) => {
    const { 
        nombre, 
        apellido, 
        email, 
        telefono, 
        especializacion, 
        biografia, 
        imagen_perfil 
    } = req.body;

    try {
        const [result] = await pool.query(
            `INSERT INTO ponentes (
                nombre, apellido, email, telefono, 
                especializacion, biografia, imagen_perfil
            ) VALUES (?, ?, ?, ?, ?, ?, ?)`,
            [nombre, apellido, email, telefono, especializacion, biografia, imagen_perfil]
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

// Actualizar un ponente
const updatePonente = async (req, res) => {
    const { 
        nombre, 
        apellido, 
        email, 
        telefono, 
        especializacion, 
        biografia, 
        imagen_perfil 
    } = req.body;

    try {
        const [result] = await pool.query(
            `UPDATE ponentes SET 
                nombre = ?, apellido = ?, email = ?, 
                telefono = ?, especializacion = ?, 
                biografia = ?, imagen_perfil = ?, 
                updated_at = CURRENT_TIMESTAMP
            WHERE ponente_id = ?`,
            [nombre, apellido, email, telefono, especializacion, biografia, imagen_perfil, req.params.id]
        );

        if (result.affectedRows === 0) {
            return res.status(404).json({ message: 'Ponente no encontrado' });
        }
        res.json({ message: 'Ponente actualizado exitosamente' });
    } catch (error) {
        if (error.code === 'ER_DUP_ENTRY') {
            return res.status(400).json({ message: 'El email ya está registrado' });
        }
        res.status(500).json({ message: error.message });
    }
};

// Eliminar un ponente
const deletePonente = async (req, res) => {
    try {
        const [result] = await pool.query('DELETE FROM ponentes WHERE ponente_id = ?', [req.params.id]);
        if (result.affectedRows === 0) {
            return res.status(404).json({ message: 'Ponente no encontrado' });
        }
        res.json({ message: 'Ponente eliminado exitosamente' });
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

module.exports = {
    getPonentes,
    getPonenteById,
    createPonente,
    updatePonente,
    deletePonente
};
