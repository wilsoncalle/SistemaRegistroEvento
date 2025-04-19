# Manual de Instalación

## Requisitos Previos

Antes de comenzar con la instalación, asegúrate de tener instalados los siguientes componentes:

1. **Node.js** (versión 14 o superior)
   - Descarga e instalación: [https://nodejs.org/](https://nodejs.org/)
   - Verifica la instalación ejecutando:
     ```bash
     node --version
     npm --version
     ```

2. **MySQL** (versión 5.7 o superior)
   - Descarga e instalación: [https://dev.mysql.com/downloads/](https://dev.mysql.com/downloads/)
   - Alternativamente, puedes usar XAMPP que incluye MySQL:
     - Descarga: [https://www.apachefriends.org/](https://www.apachefriends.org/)

3. **Git** (opcional, para clonar el repositorio)
   - Descarga e instalación: [https://git-scm.com/](https://git-scm.com/)

## Pasos de Instalación

### 1. Clonar el Repositorio (opcional)

Si tienes acceso al repositorio Git, puedes clonarlo:
```bash
git clone [URL_DEL_REPOSITORIO]
cd SistemaGestionEventos
```

### 2. Configurar la Base de Datos

1. Inicia tu servidor MySQL (usando XAMPP o el servicio de MySQL)
2. Abre el cliente MySQL (phpMyAdmin o línea de comandos)
3. Importa el esquema de la base de datos:
   ```bash
   mysql -u root -p < database/schema.sql
   ```
   O usa phpMyAdmin para importar el archivo `database/schema.sql`

4. (Opcional) Importa datos de ejemplo:
   ```bash
   mysql -u root -p < database/seed.sql
   ```

### 3. Configurar el Backend

1. Navega al directorio del backend:
   ```bash
   cd backend
   ```

2. Instala las dependencias:
   ```bash
   npm install
   ```

3. Configura las variables de entorno:
   - Copia el archivo `.env.example` a `.env` (si existe)
   - O crea un nuevo archivo `.env` con el siguiente contenido:
     ```
     # Configuración del servidor
     PORT=3000
     NODE_ENV=development

     # Configuración de la base de datos
     DB_HOST=localhost
     DB_USER=root
     DB_PASSWORD=tu_contraseña
     DB_NAME=eventos_academicos
     ```
   - Ajusta los valores según tu configuración local

### 4. Iniciar el Servidor

1. Para desarrollo:
   ```bash
   npm run dev
   ```
   Esto iniciará el servidor con nodemon para reinicio automático al hacer cambios.

2. Para producción:
   ```bash
   npm start
   ```

## Verificación de la Instalación

Para verificar que todo está funcionando correctamente:

1. El servidor debería iniciar sin errores
2. Puedes probar la API usando curl o Postman:
   ```bash
   curl http://localhost:3000/api/eventos
   ```
   Deberías recibir una respuesta JSON con la lista de eventos (vacía si no hay datos)

## Solución de Problemas Comunes

1. **Error de conexión a la base de datos**
   - Verifica que MySQL esté corriendo
   - Comprueba las credenciales en el archivo `.env`
   - Asegúrate de que la base de datos `eventos_academicos` existe

2. **Error de puerto en uso**
   - Verifica que no haya otro proceso usando el puerto 3000
   - Puedes cambiar el puerto en el archivo `.env`

3. **Errores de dependencias**
   - Intenta eliminar la carpeta `node_modules` y el archivo `package-lock.json`
   - Ejecuta `npm install` nuevamente

## Configuración Adicional

### Configuración de la Base de Datos

La base de datos está estructurada con las siguientes tablas:
- `categorias_evento`: Categorías de eventos
- `eventos`: Información de los eventos
- `ponentes`: Información de los ponentes
- `eventos_ponentes`: Relación entre eventos y ponentes
- `participantes`: Información de los participantes
- `registros`: Registros de inscripción a eventos

### Variables de Entorno

El archivo `.env` contiene las siguientes variables configurables:

- `PORT`: Puerto donde correrá el servidor (por defecto: 3000)
- `NODE_ENV`: Entorno de ejecución (development/production)
- `DB_HOST`: Host de la base de datos
- `DB_USER`: Usuario de la base de datos
- `DB_PASSWORD`: Contraseña de la base de datos
- `DB_NAME`: Nombre de la base de datos

## Mantenimiento

### Actualización de Dependencias

Para mantener las dependencias actualizadas:

```bash
npm update
```

### Backup de la Base de Datos

Para realizar un backup de la base de datos:

```bash
mysqldump -u root -p eventos_academicos > backup_$(date +%Y%m%d).sql
```

## Soporte

Si encuentras problemas durante la instalación o el uso del sistema:

1. Revisa los logs del servidor para identificar errores
2. Verifica que todos los requisitos previos estén instalados correctamente
3. Consulta la documentación de la API para verificar los endpoints
4. Si el problema persiste, contacta al equipo de soporte
