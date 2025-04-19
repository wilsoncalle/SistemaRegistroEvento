const mysql = require('mysql2/promise');
require('dotenv').config();

const pool = mysql.createPool({
    host: process.env.DB_HOST || 'localhost',
    user: process.env.DB_USER || 'root',
    password: process.env.DB_PASSWORD || '',
    database: process.env.DB_NAME || 'eventos_academicos',
    waitForConnections: true,
    connectionLimit: 10,
    queueLimit: 0
});

// Probar la conexión
pool.getConnection()
    .then(connection => {
        console.log('Base de datos conectada exitosamente');
        connection.release();
    })
    .catch(error => {
        console.error('Error al conectar con la base de datos:', error.message);
    });

module.exports = pool;
