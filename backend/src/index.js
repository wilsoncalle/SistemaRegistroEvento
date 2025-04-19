const express = require('express');
const cors = require('cors');
const dotenv = require('dotenv');

// Importar rutas
const eventosRoutes = require('./routes/eventos');
const ponentesRoutes = require('./routes/ponentes');
const participantesRoutes = require('./routes/participantes');
const registrosRoutes = require('./routes/registros');
const categoriasRoutes = require('./routes/categorias');

// Cargar variables de entorno
dotenv.config();

// Definir puerto
const PORT = process.env.PORT || 3000;

const app = express();

// Middlewares
app.use(cors());
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Ruta de prueba
app.get('/', (req, res) => {
    res.json({ message: 'API de Sistema de Gestión de Eventos Académicos' });
});

// Rutas API
app.use('/api/eventos', eventosRoutes);
app.use('/api/ponentes', ponentesRoutes);
app.use('/api/participantes', participantesRoutes);
app.use('/api/registros', registrosRoutes);
app.use('/api/categorias', categoriasRoutes);

// Manejo de ruta no encontrada
app.use((req, res, next) => {
    res.status(404).json({
        success: false,
        message: 'Ruta no encontrada'
    });
});

// Manejo de errores
app.use((err, req, res, next) => {
    console.error(err.stack);
    res.status(500).json({
        success: false,
        message: 'Error interno del servidor',
        error: process.env.NODE_ENV === 'development' ? err.message : {}
    });
});

// Iniciar servidor
app.listen(PORT, () => {
    console.log(`Servidor ejecutándose en puerto ${PORT}`);
});
