# Sistema de Gestión de Eventos Académicos

![Estado del Proyecto](https://img.shields.io/badge/Estado-En%20Desarrollo-yellow)
![Versión](https://img.shields.io/badge/Versión-1.0.0-blue)
![Licencia](https://img.shields.io/badge/Licencia-MIT-green)

Sistema de gestión integral para eventos académicos que permite la administración de eventos, ponentes, participantes y registros.

## 🚀 Características Principales

- Gestión completa de eventos académicos
- Administración de ponentes y sus presentaciones
- Registro y seguimiento de participantes
- Sistema de inscripciones y pagos
- Categorización de eventos
- Gestión de asistencia y retroalimentación

## 📋 Requisitos Previos

- Node.js (v14 o superior)
- MySQL (v5.7 o superior) o XAMPP
- Git (opcional)

## 🔧 Instalación

1. Clonar el repositorio:
```bash
git clone [URL_DEL_REPOSITORIO]
cd SistemaGestionEventos
```

2. Configurar la base de datos:
```bash
mysql -u root -p < database/schema.sql
```

3. Instalar dependencias del backend:
```bash
cd backend
npm install
```

4. Configurar variables de entorno:
```bash
cp .env.example .env
# Editar .env con tus configuraciones
```

5. Iniciar el servidor:
```bash
npm run dev
```

## 📚 Documentación

- [Manual de Instalación](docs/instalacion.md)
- [Manual Técnico](docs/manual_tecnico.md)
- [Documentación de la API](docs/api_docs.md)

## 🛠️ Tecnologías Utilizadas

### Backend
- Node.js
- Express.js
- MySQL
- JWT (pendiente)
- Bcrypt (pendiente)

### Frontend (Pendiente)
- React.js
- Material-UI
- Redux

## 📁 Estructura del Proyecto

```
SistemaGestionEventos/
├── backend/
│   ├── src/
│   │   ├── config/         # Configuraciones
│   │   ├── controllers/    # Controladores
│   │   ├── models/        # Modelos
│   │   ├── routes/        # Rutas API
│   │   └── utils/         # Utilidades
│   └── package.json
├── database/
│   ├── schema.sql         # Esquema de la base de datos
│   └── seed.sql          # Datos de ejemplo
├── docs/
│   ├── api_docs.md       # Documentación API
│   ├── instalacion.md    # Manual de instalación
│   └── manual_tecnico.md # Manual técnico
└── README.md
```

## 📝 Endpoints de la API

### Eventos
- `GET /api/eventos` - Listar eventos
- `GET /api/eventos/:id` - Obtener evento específico
- `POST /api/eventos` - Crear evento
- `PUT /api/eventos/:id` - Actualizar evento
- `DELETE /api/eventos/:id` - Eliminar evento

### Participantes
- `GET /api/participantes` - Listar participantes
- `GET /api/participantes/:id` - Obtener participante
- `POST /api/participantes` - Crear participante
- `PUT /api/participantes/:id` - Actualizar participante
- `DELETE /api/participantes/:id` - Eliminar participante

### Ponentes
- `GET /api/ponentes` - Listar ponentes
- `GET /api/ponentes/:id` - Obtener ponente
- `POST /api/ponentes` - Crear ponente
- `PUT /api/ponentes/:id` - Actualizar ponente
- `DELETE /api/ponentes/:id` - Eliminar ponente

### Registros
- `GET /api/registros` - Listar registros
- `GET /api/registros/:id` - Obtener registro
- `POST /api/registros` - Crear registro
- `PUT /api/registros/:id` - Actualizar registro
- `DELETE /api/registros/:id` - Eliminar registro

### Categorías
- `GET /api/categorias` - Listar categorías
- `GET /api/categorias/:id` - Obtener categoría
- `POST /api/categorias` - Crear categoría
- `PUT /api/categorias/:id` - Actualizar categoría
- `DELETE /api/categorias/:id` - Eliminar categoría


## 🙏 Agradecimientos

- [Express.js](https://expressjs.com/)
- [MySQL](https://www.mysql.com/)
- [Node.js](https://nodejs.org/) 