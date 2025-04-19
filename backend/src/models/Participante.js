const pool = require('../config/database');

class Participante {
    constructor(data) {
        this.participante_id = data.participante_id;
        this.nombre = data.nombre;
        this.apellido = data.apellido;
        this.email = data.email;
        this.telefono = data.telefono;
        this.institucion = data.institucion;
        this.created_at = data.created_at;
    }

    static async findAll() {
        const [rows] = await pool.query('SELECT * FROM participantes');
        return rows.map(row => new Participante(row));
    }

    static async findById(id) {
        const [rows] = await pool.query('SELECT * FROM participantes WHERE participante_id = ?', [id]);
        if (rows.length === 0) return null;

        const participante = new Participante(rows[0]);

        // Obtener los eventos registrados del participante
        const [eventos] = await pool.query(`
            SELECT e.*, r.fecha_registro, r.estado_pago, r.asistencia, r.retroalimentacion
            FROM eventos e 
            JOIN registros r ON e.evento_id = r.evento_id 
            WHERE r.participante_id = ?
        `, [id]);

        participante.eventos_registrados = eventos;
        return participante;
    }

    async save() {
        if (this.participante_id) {
            // Update
            const [result] = await pool.query(
                `UPDATE participantes SET 
                    nombre = ?, apellido = ?, email = ?, 
                    telefono = ?, institucion = ?
                WHERE participante_id = ?`,
                [
                    this.nombre, this.apellido, this.email,
                    this.telefono, this.institucion,
                    this.participante_id
                ]
            );
            return result.affectedRows > 0;
        } else {
            // Insert
            const [result] = await pool.query(
                `INSERT INTO participantes (
                    nombre, apellido, email, telefono, institucion
                ) VALUES (?, ?, ?, ?, ?)`,
                [
                    this.nombre, this.apellido, this.email,
                    this.telefono, this.institucion
                ]
            );
            this.participante_id = result.insertId;
            return true;
        }
    }

    static async delete(id) {
        const [result] = await pool.query('DELETE FROM participantes WHERE participante_id = ?', [id]);
        return result.affectedRows > 0;
    }
}

module.exports = Participante;
