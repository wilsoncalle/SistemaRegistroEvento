const pool = require('../config/database');

// Obtener todas las categorías
const getCategorias = async (req, res) => {
    try {
        const [categorias] = await pool.query('SELECT * FROM categorias_evento');
        res.json(categorias);
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

// Obtener una categoría por ID
const getCategoriaById = async (req, res) => {
    try {
        const [categoria] = await pool.query('SELECT * FROM categorias_evento WHERE categoria_id = ?', [req.params.id]);
        if (categoria.length === 0) {
            return res.status(404).json({ message: 'Categoría no encontrada' });
        }
        res.json(categoria[0]);
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

// Crear una nueva categoría
const createCategoria = async (req, res) => {
    const { nombre, descripcion } = req.body;
    try {
        const [result] = await pool.query(
            'INSERT INTO categorias_evento (nombre, descripcion) VALUES (?, ?)',
            [nombre, descripcion]
        );
        res.status(201).json({ 
            id: result.insertId,
            nombre,
            descripcion
        });
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

// Actualizar una categoría
const updateCategoria = async (req, res) => {
    const { nombre, descripcion } = req.body;
    try {
        const [result] = await pool.query(
            'UPDATE categorias_evento SET nombre = ?, descripcion = ? WHERE categoria_id = ?',
            [nombre, descripcion, req.params.id]
        );
        if (result.affectedRows === 0) {
            return res.status(404).json({ message: 'Categoría no encontrada' });
        }
        res.json({ message: 'Categoría actualizada exitosamente' });
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

// Eliminar una categoría
const deleteCategoria = async (req, res) => {
    try {
        const [result] = await pool.query('DELETE FROM categorias_evento WHERE categoria_id = ?', [req.params.id]);
        if (result.affectedRows === 0) {
            return res.status(404).json({ message: 'Categoría no encontrada' });
        }
        res.json({ message: 'Categoría eliminada exitosamente' });
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

module.exports = {
    getCategorias,
    getCategoriaById,
    createCategoria,
    updateCategoria,
    deleteCategoria
};
