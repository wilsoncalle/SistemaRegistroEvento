# Documentación de la API

## Endpoints

### Eventos

#### Obtener todos los eventos
- **GET** `/api/eventos`
- **Descripción**: Retorna la lista de todos los eventos con sus categorías
- **Respuesta exitosa**: 200 OK
- **Ejemplo de respuesta**:
```json
[
  {
    "evento_id": 1,
    "titulo": "Conferencia de Tecnología",
    "categoria_id": 1,
    "categoria_nombre": "Tecnología",
    "descripcion": "Descripción del evento",
    "ubicacion": "Sala Principal",
    "fecha_evento": "2024-04-20",
    "hora_inicio": "09:00",
    "hora_fin": "17:00",
    "max_participantes": 100,
    "tarifa_inscripcion": 50.00,
    "imagen": "url_imagen"
  }
]
```

#### Obtener un evento por ID
- **GET** `/api/eventos/:id`
- **Descripción**: Retorna los detalles de un evento específico incluyendo sus ponentes
- **Respuesta exitosa**: 200 OK
- **Respuesta de error**: 404 Not Found
- **Ejemplo de respuesta**:
```json
{
  "evento_id": 1,
  "titulo": "Conferencia de Tecnología",
  "categoria_id": 1,
  "categoria_nombre": "Tecnología",
  "descripcion": "Descripción del evento",
  "ubicacion": "Sala Principal",
  "fecha_evento": "2024-04-20",
  "hora_inicio": "09:00",
  "hora_fin": "17:00",
  "max_participantes": 100,
  "tarifa_inscripcion": 50.00,
  "imagen": "url_imagen",
  "ponentes": [
    {
      "ponente_id": 1,
      "nombre": "Juan",
      "apellido": "Pérez",
      "titulo_presentacion": "Inteligencia Artificial",
      "hora_presentacion": "10:00"
    }
  ]
}
```

#### Crear un nuevo evento
- **POST** `/api/eventos`
- **Descripción**: Crea un nuevo evento
- **Cuerpo de la petición**:
```json
{
  "titulo": "Nuevo Evento",
  "categoria_id": 1,
  "descripcion": "Descripción del evento",
  "ubicacion": "Sala Principal",
  "fecha_evento": "2024-04-20",
  "hora_inicio": "09:00",
  "hora_fin": "17:00",
  "max_participantes": 100,
  "tarifa_inscripcion": 50.00,
  "imagen": "url_imagen",
  "ponentes": [
    {
      "ponente_id": 1,
      "titulo_presentacion": "Tema de la presentación",
      "hora_presentacion": "10:00"
    }
  ]
}
```
- **Respuesta exitosa**: 201 Created
- **Respuesta de error**: 500 Internal Server Error

#### Actualizar un evento
- **PUT** `/api/eventos/:id`
- **Descripción**: Actualiza un evento existente
- **Cuerpo de la petición**: Igual que crear evento
- **Respuesta exitosa**: 200 OK
- **Respuesta de error**: 404 Not Found, 500 Internal Server Error

#### Eliminar un evento
- **DELETE** `/api/eventos/:id`
- **Descripción**: Elimina un evento
- **Respuesta exitosa**: 200 OK
- **Respuesta de error**: 404 Not Found, 500 Internal Server Error

### Participantes

#### Obtener todos los participantes
- **GET** `/api/participantes`
- **Descripción**: Retorna la lista de todos los participantes
- **Respuesta exitosa**: 200 OK

#### Obtener un participante por ID
- **GET** `/api/participantes/:id`
- **Descripción**: Retorna los detalles de un participante incluyendo sus eventos registrados
- **Respuesta exitosa**: 200 OK
- **Respuesta de error**: 404 Not Found

#### Crear un nuevo participante
- **POST** `/api/participantes`
- **Descripción**: Crea un nuevo participante
- **Cuerpo de la petición**:
```json
{
  "nombre": "Juan",
  "apellido": "Pérez",
  "email": "juan@example.com",
  "telefono": "123456789",
  "institucion": "Universidad XYZ"
}
```
- **Respuesta exitosa**: 201 Created
- **Respuesta de error**: 400 Bad Request (email duplicado), 500 Internal Server Error

#### Actualizar un participante
- **PUT** `/api/participantes/:id`
- **Descripción**: Actualiza un participante existente
- **Cuerpo de la petición**: Igual que crear participante
- **Respuesta exitosa**: 200 OK
- **Respuesta de error**: 404 Not Found, 400 Bad Request, 500 Internal Server Error

#### Eliminar un participante
- **DELETE** `/api/participantes/:id`
- **Descripción**: Elimina un participante
- **Respuesta exitosa**: 200 OK
- **Respuesta de error**: 404 Not Found, 500 Internal Server Error

### Ponentes

#### Obtener todos los ponentes
- **GET** `/api/ponentes`
- **Descripción**: Retorna la lista de todos los ponentes
- **Respuesta exitosa**: 200 OK

#### Obtener un ponente por ID
- **GET** `/api/ponentes/:id`
- **Descripción**: Retorna los detalles de un ponente incluyendo sus eventos
- **Respuesta exitosa**: 200 OK
- **Respuesta de error**: 404 Not Found

#### Crear un nuevo ponente
- **POST** `/api/ponentes`
- **Descripción**: Crea un nuevo ponente
- **Cuerpo de la petición**:
```json
{
  "nombre": "María",
  "apellido": "González",
  "email": "maria@example.com",
  "telefono": "987654321",
  "especializacion": "Inteligencia Artificial",
  "biografia": "Experta en IA...",
  "imagen_perfil": "url_imagen"
}
```
- **Respuesta exitosa**: 201 Created
- **Respuesta de error**: 400 Bad Request (email duplicado), 500 Internal Server Error

#### Actualizar un ponente
- **PUT** `/api/ponentes/:id`
- **Descripción**: Actualiza un ponente existente
- **Cuerpo de la petición**: Igual que crear ponente
- **Respuesta exitosa**: 200 OK
- **Respuesta de error**: 404 Not Found, 400 Bad Request, 500 Internal Server Error

#### Eliminar un ponente
- **DELETE** `/api/ponentes/:id`
- **Descripción**: Elimina un ponente
- **Respuesta exitosa**: 200 OK
- **Respuesta de error**: 404 Not Found, 500 Internal Server Error

### Registros

#### Obtener todos los registros
- **GET** `/api/registros`
- **Descripción**: Retorna la lista de todos los registros con información de eventos y participantes
- **Respuesta exitosa**: 200 OK

#### Obtener un registro por ID
- **GET** `/api/registros/:id`
- **Descripción**: Retorna los detalles de un registro específico
- **Respuesta exitosa**: 200 OK
- **Respuesta de error**: 404 Not Found

#### Crear un nuevo registro
- **POST** `/api/registros`
- **Descripción**: Crea un nuevo registro de participación en un evento
- **Cuerpo de la petición**:
```json
{
  "evento_id": 1,
  "participante_id": 1,
  "estado_pago": "pendiente"
}
```
- **Respuesta exitosa**: 201 Created
- **Respuesta de error**: 400 Bad Request (cupo lleno o registro duplicado), 500 Internal Server Error

#### Actualizar un registro
- **PUT** `/api/registros/:id`
- **Descripción**: Actualiza un registro existente
- **Cuerpo de la petición**:
```json
{
  "estado_pago": "pagado",
  "asistencia": true,
  "retroalimentacion": "Excelente evento"
}
```
- **Respuesta exitosa**: 200 OK
- **Respuesta de error**: 404 Not Found, 500 Internal Server Error

#### Eliminar un registro
- **DELETE** `/api/registros/:id`
- **Descripción**: Elimina un registro
- **Respuesta exitosa**: 200 OK
- **Respuesta de error**: 404 Not Found, 500 Internal Server Error

### Categorías

#### Obtener todas las categorías
- **GET** `/api/categorias`
- **Descripción**: Retorna la lista de todas las categorías de eventos
- **Respuesta exitosa**: 200 OK

#### Obtener una categoría por ID
- **GET** `/api/categorias/:id`
- **Descripción**: Retorna los detalles de una categoría específica
- **Respuesta exitosa**: 200 OK
- **Respuesta de error**: 404 Not Found

#### Crear una nueva categoría
- **POST** `/api/categorias`
- **Descripción**: Crea una nueva categoría
- **Cuerpo de la petición**:
```json
{
  "nombre": "Tecnología",
  "descripcion": "Eventos relacionados con tecnología"
}
```
- **Respuesta exitosa**: 201 Created
- **Respuesta de error**: 500 Internal Server Error

#### Actualizar una categoría
- **PUT** `/api/categorias/:id`
- **Descripción**: Actualiza una categoría existente
- **Cuerpo de la petición**: Igual que crear categoría
- **Respuesta exitosa**: 200 OK
- **Respuesta de error**: 404 Not Found, 500 Internal Server Error

#### Eliminar una categoría
- **DELETE** `/api/categorias/:id`
- **Descripción**: Elimina una categoría
- **Respuesta exitosa**: 200 OK
- **Respuesta de error**: 404 Not Found, 500 Internal Server Error

## Códigos de Estado

- **200 OK**: La petición se ha completado exitosamente
- **201 Created**: El recurso se ha creado exitosamente
- **400 Bad Request**: La petición es inválida (datos incorrectos, duplicados, etc.)
- **404 Not Found**: El recurso solicitado no existe
- **500 Internal Server Error**: Error interno del servidor

## Ejemplos de Uso

### Crear un nuevo evento con ponentes
```bash
curl -X POST http://localhost:3000/api/eventos \
  -H "Content-Type: application/json" \
  -d '{
    "titulo": "Conferencia de IA",
    "categoria_id": 1,
    "descripcion": "Evento sobre Inteligencia Artificial",
    "ubicacion": "Auditorio Principal",
    "fecha_evento": "2024-05-15",
    "hora_inicio": "09:00",
    "hora_fin": "17:00",
    "max_participantes": 200,
    "tarifa_inscripcion": 100.00,
    "imagen": "https://example.com/ia-conference.jpg",
    "ponentes": [
      {
        "ponente_id": 1,
        "titulo_presentacion": "El futuro de la IA",
        "hora_presentacion": "10:00"
      }
    ]
  }'
```

### Registrar un participante en un evento
```bash
curl -X POST http://localhost:3000/api/registros \
  -H "Content-Type: application/json" \
  -d '{
    "evento_id": 1,
    "participante_id": 1,
    "estado_pago": "pendiente"
  }'
```
