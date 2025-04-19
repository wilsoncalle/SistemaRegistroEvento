const pool = require('../config/database');

class Ponente {
    constructor(data) {
        this.ponente_id = data.ponente_id;
        this.nombre = data.nombre;
        this.apellido = data.apellido;
        this.email = data.email;
        this.telefono = data.telefono;
        this.especializacion = data.especializacion;
        this.biografia = data.biografia;
        this.imagen_perfil = data.imagen_perfil;
        this.created_at = data.created_at;
        this.updated_at = data.updated_at;
    }

    static async findAll() {
        const [rows] = await pool.query('SELECT * FROM ponentes');
        return rows.map(row => new Ponente(row));
    }

    static async findById(id) {
        const [rows] = await pool.query('SELECT * FROM ponentes WHERE ponente_id = ?', [id]);
        if (rows.length === 0) return null;

        const ponente = new Ponente(rows[0]);

        // Obtener los eventos del ponente
        const [eventos] = await pool.query(`
            SELECT e.*, ep.titulo_presentacion, ep.hora_presentacion 
            FROM eventos e 
            JOIN eventos_ponentes ep ON e.evento_id = ep.evento_id 
            WHERE ep.ponente_id = ?
        `, [id]);

        ponente.eventos = eventos;
        return ponente;
    }

    async save() {
        if (this.ponente_id) {
            // Update
            const [result] = await pool.query(
                `UPDATE ponentes SET 
                    nombre = ?, apellido = ?, email = ?, 
                    telefono = ?, especializacion = ?, 
                    biografia = ?, imagen_perfil = ?, 
                    updated_at = CURRENT_TIMESTAMP
                WHERE ponente_id = ?`,
                [
                    this.nombre, this.apellido, this.email,
                    this.telefono, this.especializacion,
                    this.biografia, this.imagen_perfil,
                    this.ponente_id
                ]
            );
            return result.affectedRows > 0;
        } else {
            // Insert
            const [result] = await pool.query(
                `INSERT INTO ponentes (
                    nombre, apellido, email, telefono, 
                    especializacion, biografia, imagen_perfil
                ) VALUES (?, ?, ?, ?, ?, ?, ?)`,
                [
                    this.nombre, this.apellido, this.email,
                    this.telefono, this.especializacion,
                    this.biografia, this.imagen_perfil
                ]
            );
            this.ponente_id = result.insertId;
            return true;
        }
    }

    static async delete(id) {
        const [result] = await pool.query('DELETE FROM ponentes WHERE ponente_id = ?', [id]);
        return result.affectedRows > 0;
    }
}

module.exports = Ponente;
