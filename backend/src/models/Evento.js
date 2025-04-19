const pool = require('../config/database');

class Evento {
    constructor(data) {
        this.evento_id = data.evento_id;
        this.titulo = data.titulo;
        this.categoria_id = data.categoria_id;
        this.descripcion = data.descripcion;
        this.ubicacion = data.ubicacion;
        this.fecha_evento = data.fecha_evento;
        this.hora_inicio = data.hora_inicio;
        this.hora_fin = data.hora_fin;
        this.max_participantes = data.max_participantes;
        this.tarifa_inscripcion = data.tarifa_inscripcion;
        this.imagen = data.imagen;
        this.created_at = data.created_at;
        this.updated_at = data.updated_at;
    }

    static async findAll() {
        const [rows] = await pool.query(`
            SELECT e.*, c.nombre as categoria_nombre 
            FROM eventos e 
            LEFT JOIN categorias_evento c ON e.categoria_id = c.categoria_id
        `);
        return rows.map(row => new Evento(row));
    }

    static async findById(id) {
        const [rows] = await pool.query(`
            SELECT e.*, c.nombre as categoria_nombre 
            FROM eventos e 
            LEFT JOIN categorias_evento c ON e.categoria_id = c.categoria_id 
            WHERE e.evento_id = ?
        `, [id]);
        
        if (rows.length === 0) return null;

        const evento = new Evento(rows[0]);

        // Obtener los ponentes del evento
        const [ponentes] = await pool.query(`
            SELECT p.*, ep.titulo_presentacion, ep.hora_presentacion 
            FROM ponentes p 
            JOIN eventos_ponentes ep ON p.ponente_id = ep.ponente_id 
            WHERE ep.evento_id = ?
        `, [id]);

        evento.ponentes = ponentes;
        return evento;
    }

    async save() {
        const connection = await pool.getConnection();
        await connection.beginTransaction();

        try {
            if (this.evento_id) {
                // Update
                const [result] = await connection.query(
                    `UPDATE eventos SET 
                        titulo = ?, categoria_id = ?, descripcion = ?, 
                        ubicacion = ?, fecha_evento = ?, hora_inicio = ?, 
                        hora_fin = ?, max_participantes = ?, 
                        tarifa_inscripcion = ?, imagen = ?
                    WHERE evento_id = ?`,
                    [
                        this.titulo, this.categoria_id, this.descripcion,
                        this.ubicacion, this.fecha_evento, this.hora_inicio,
                        this.hora_fin, this.max_participantes,
                        this.tarifa_inscripcion, this.imagen,
                        this.evento_id
                    ]
                );

                if (this.ponentes) {
                    // Eliminar ponentes actuales
                    await connection.query(
                        'DELETE FROM eventos_ponentes WHERE evento_id = ?',
                        [this.evento_id]
                    );

                    // Insertar nuevos ponentes
                    for (const ponente of this.ponentes) {
                        await connection.query(
                            `INSERT INTO eventos_ponentes (
                                evento_id, ponente_id, titulo_presentacion, hora_presentacion
                            ) VALUES (?, ?, ?, ?)`,
                            [this.evento_id, ponente.ponente_id, ponente.titulo_presentacion, ponente.hora_presentacion]
                        );
                    }
                }
            } else {
                // Insert
                const [result] = await connection.query(
                    `INSERT INTO eventos (
                        titulo, categoria_id, descripcion, ubicacion, 
                        fecha_evento, hora_inicio, hora_fin, 
                        max_participantes, tarifa_inscripcion, imagen
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
                    [
                        this.titulo, this.categoria_id, this.descripcion,
                        this.ubicacion, this.fecha_evento, this.hora_inicio,
                        this.hora_fin, this.max_participantes,
                        this.tarifa_inscripcion, this.imagen
                    ]
                );

                this.evento_id = result.insertId;

                if (this.ponentes) {
                    for (const ponente of this.ponentes) {
                        await connection.query(
                            `INSERT INTO eventos_ponentes (
                                evento_id, ponente_id, titulo_presentacion, hora_presentacion
                            ) VALUES (?, ?, ?, ?)`,
                            [this.evento_id, ponente.ponente_id, ponente.titulo_presentacion, ponente.hora_presentacion]
                        );
                    }
                }
            }

            await connection.commit();
            return true;
        } catch (error) {
            await connection.rollback();
            throw error;
        } finally {
            connection.release();
        }
    }

    static async delete(id) {
        const [result] = await pool.query('DELETE FROM eventos WHERE evento_id = ?', [id]);
        return result.affectedRows > 0;
    }
}

module.exports = Evento;
