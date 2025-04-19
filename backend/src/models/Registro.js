const pool = require('../config/database');

class Registro {
    constructor(data) {
        this.registro_id = data.registro_id;
        this.evento_id = data.evento_id;
        this.participante_id = data.participante_id;
        this.fecha_registro = data.fecha_registro;
        this.estado_pago = data.estado_pago;
        this.asistencia = data.asistencia;
        this.retroalimentacion = data.retroalimentacion;
    }

    static async findAll() {
        const [rows] = await pool.query(`
            SELECT r.*, 
                   e.titulo as evento_titulo,
                   CONCAT(p.nombre, ' ', p.apellido) as participante_nombre
            FROM registros r
            JOIN eventos e ON r.evento_id = e.evento_id
            JOIN participantes p ON r.participante_id = p.participante_id
        `);
        return rows.map(row => new Registro(row));
    }

    static async findById(id) {
        const [rows] = await pool.query(`
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
        `, [id]);

        if (rows.length === 0) return null;
        return new Registro(rows[0]);
    }

    async save() {
        if (this.registro_id) {
            // Update
            const [result] = await pool.query(
                `UPDATE registros SET 
                    estado_pago = ?,
                    asistencia = ?,
                    retroalimentacion = ?
                WHERE registro_id = ?`,
                [
                    this.estado_pago,
                    this.asistencia,
                    this.retroalimentacion,
                    this.registro_id
                ]
            );
            return result.affectedRows > 0;
        } else {
            // Verificar si hay cupo disponible
            const [[{ total_registros }]] = await pool.query(
                'SELECT COUNT(*) as total_registros FROM registros WHERE evento_id = ?',
                [this.evento_id]
            );

            const [[evento]] = await pool.query(
                'SELECT max_participantes FROM eventos WHERE evento_id = ?',
                [this.evento_id]
            );

            if (total_registros >= evento.max_participantes) {
                throw new Error('El evento ha alcanzado su capacidad máxima');
            }

            // Verificar si el participante ya está registrado
            const [[registro_existente]] = await pool.query(
                'SELECT registro_id FROM registros WHERE evento_id = ? AND participante_id = ?',
                [this.evento_id, this.participante_id]
            );

            if (registro_existente) {
                throw new Error('El participante ya está registrado en este evento');
            }

            // Insert
            const [result] = await pool.query(
                `INSERT INTO registros (
                    evento_id, participante_id, estado_pago
                ) VALUES (?, ?, ?)`,
                [
                    this.evento_id,
                    this.participante_id,
                    this.estado_pago || 'pendiente'
                ]
            );
            this.registro_id = result.insertId;
            return true;
        }
    }

    static async delete(id) {
        const [result] = await pool.query('DELETE FROM registros WHERE registro_id = ?', [id]);
        return result.affectedRows > 0;
    }
}

module.exports = Registro;
