const pool = require('../config/database');

class Categoria {
    constructor(data) {
        this.categoria_id = data.categoria_id;
        this.nombre = data.nombre;
        this.descripcion = data.descripcion;
        this.created_at = data.created_at;
    }

    static async findAll() {
        const [rows] = await pool.query('SELECT * FROM categorias_evento');
        return rows.map(row => new Categoria(row));
    }

    static async findById(id) {
        const [rows] = await pool.query('SELECT * FROM categorias_evento WHERE categoria_id = ?', [id]);
        if (rows.length === 0) return null;
        return new Categoria(rows[0]);
    }

    async save() {
        if (this.categoria_id) {
            // Update
            const [result] = await pool.query(
                'UPDATE categorias_evento SET nombre = ?, descripcion = ? WHERE categoria_id = ?',
                [this.nombre, this.descripcion, this.categoria_id]
            );
            return result.affectedRows > 0;
        } else {
            // Insert
            const [result] = await pool.query(
                'INSERT INTO categorias_evento (nombre, descripcion) VALUES (?, ?)',
                [this.nombre, this.descripcion]
            );
            this.categoria_id = result.insertId;
            return true;
        }
    }

    static async delete(id) {
        const [result] = await pool.query('DELETE FROM categorias_evento WHERE categoria_id = ?', [id]);
        return result.affectedRows > 0;
    }
}

module.exports = Categoria;
