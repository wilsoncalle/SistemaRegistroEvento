# Manual Técnico del Sistema

## Arquitectura

### Arquitectura General
El sistema está construido siguiendo una arquitectura cliente-servidor con las siguientes características:

1. **Backend**: API REST desarrollada en Node.js con Express
2. **Base de Datos**: MySQL
3. **Frontend**: Aplicación web (pendiente de implementación)

### Patrones de Diseño
- **MVC (Modelo-Vista-Controlador)**: Separación clara entre modelos, controladores y rutas
- **Pool de Conexiones**: Gestión eficiente de conexiones a la base de datos
- **Middleware**: Procesamiento de solicitudes HTTP con middleware de Express

## Descripción de Componentes

### 1. Backend (Node.js + Express)

#### Estructura de Directorios
```
backend/
├── src/
│   ├── config/         # Configuraciones del sistema
│   ├── controllers/    # Lógica de negocio
│   ├── models/         # Modelos de datos
│   ├── routes/         # Definición de rutas API
│   ├── utils/          # Utilidades y helpers
│   └── index.js        # Punto de entrada de la aplicación
```

#### Componentes Principales

##### Configuración (`config/`)
- **database.js**: Configuración y conexión a la base de datos MySQL
  - Pool de conexiones configurable
  - Manejo de variables de entorno
  - Verificación de conexión

##### Controladores (`controllers/`)
- **eventos.js**: Gestión de eventos académicos
- **ponentes.js**: Administración de ponentes
- **participantes.js**: Gestión de participantes
- **registros.js**: Control de registros e inscripciones
- **categorias.js**: Administración de categorías de eventos

##### Rutas (`routes/`)
- Implementación de endpoints RESTful
- Middleware de autenticación (pendiente)
- Validación de datos
- Manejo de errores

##### Modelos (`models/`)
- Representación de entidades de negocio
- Interacción con la base de datos
- Validaciones de datos

### 2. Base de Datos (MySQL)

#### Estructura de Tablas

##### Tabla: `categorias_evento`
- `categoria_id`: Identificador único
- `nombre`: Nombre de la categoría
- `descripcion`: Descripción detallada
- `created_at`: Fecha de creación

##### Tabla: `eventos`
- `evento_id`: Identificador único
- `titulo`: Título del evento
- `categoria_id`: Referencia a categoría
- `descripcion`: Descripción del evento
- `ubicacion`: Lugar del evento
- `fecha_evento`: Fecha del evento
- `hora_inicio`: Hora de inicio
- `hora_fin`: Hora de finalización
- `max_participantes`: Capacidad máxima
- `tarifa_inscripcion`: Costo de inscripción
- `imagen`: URL de imagen
- `created_at`, `updated_at`: Timestamps

##### Tabla: `ponentes`
- `ponente_id`: Identificador único
- `nombre`, `apellido`: Datos personales
- `email`: Correo electrónico (único)
- `telefono`: Número de contacto
- `especializacion`: Área de especialización
- `biografia`: Información profesional
- `imagen_perfil`: URL de imagen
- `created_at`, `updated_at`: Timestamps

##### Tabla: `eventos_ponentes`
- `evento_id`: Referencia a evento
- `ponente_id`: Referencia a ponente
- `titulo_presentacion`: Título de la presentación
- `hora_presentacion`: Hora programada

##### Tabla: `participantes`
- `participante_id`: Identificador único
- `nombre`, `apellido`: Datos personales
- `email`: Correo electrónico (único)
- `telefono`: Número de contacto
- `institucion`: Institución de procedencia
- `created_at`: Timestamp

##### Tabla: `registros`
- `registro_id`: Identificador único
- `evento_id`: Referencia a evento
- `participante_id`: Referencia a participante
- `fecha_registro`: Fecha de registro
- `estado_pago`: Estado del pago
- `asistencia`: Confirmación de asistencia
- `retroalimentacion`: Comentarios del participante

#### Relaciones
- Un evento pertenece a una categoría
- Un evento puede tener múltiples ponentes
- Un participante puede registrarse en múltiples eventos
- Un registro vincula un participante con un evento

## Flujo de Datos

### 1. Registro de Evento
1. Creación de categoría (si no existe)
2. Creación del evento
3. Asignación de ponentes
4. Configuración de detalles del evento

### 2. Inscripción de Participantes
1. Registro del participante
2. Verificación de cupo disponible
3. Creación de registro
4. Actualización de estado de pago

### 3. Gestión de Asistencia
1. Registro de asistencia
2. Captura de retroalimentación
3. Actualización de estadísticas

## Seguridad

### Medidas Implementadas
1. **Validación de Datos**
   - Verificación de campos requeridos
   - Validación de formatos
   - Prevención de inyección SQL

2. **Manejo de Errores**
   - Logging de errores
   - Respuestas de error estandarizadas
   - Protección de información sensible

3. **CORS**
   - Configuración de orígenes permitidos
   - Restricción de métodos HTTP

### Medidas Pendientes
1. Autenticación de usuarios
2. Autorización basada en roles
3. Encriptación de datos sensibles
4. Rate limiting
5. Validación de tokens JWT

## Rendimiento

### Optimizaciones
1. **Base de Datos**
   - Pool de conexiones
   - Índices en campos de búsqueda
   - Consultas optimizadas

2. **API**
   - Middleware de compresión
   - Caching (pendiente)
   - Paginación de resultados

### Monitoreo
1. Logging de operaciones
2. Métricas de rendimiento
3. Alertas de errores

## Mantenimiento

### Backups
1. Base de datos
   - Backup diario automático
   - Retención de 30 días
   - Verificación de integridad

2. Código
   - Control de versiones
   - Documentación actualizada
   - Pruebas automatizadas

### Actualizaciones
1. Dependencias
   - Actualización mensual
   - Pruebas de compatibilidad
   - Rollback plan

## Consideraciones Técnicas

### Escalabilidad
1. **Horizontal**
   - Balanceo de carga
   - Replicación de base de datos
   - Caché distribuido

2. **Vertical**
   - Optimización de consultas
   - Indexación adecuada
   - Particionamiento de tablas

### Compatibilidad
1. **Navegadores**
   - Chrome (últimas 2 versiones)
   - Firefox (últimas 2 versiones)
   - Safari (últimas 2 versiones)
   - Edge (últimas 2 versiones)

2. **Dispositivos**
   - Desktop
   - Tablet
   - Móvil (responsive design)

## Próximos Pasos

1. Implementación de autenticación
2. Desarrollo del frontend
3. Sistema de notificaciones
4. Integración con pasarela de pagos
5. Panel de administración
6. Reportes y estadísticas
