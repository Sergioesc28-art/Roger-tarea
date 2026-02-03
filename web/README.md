# Sistema de Control Escolar - Frontend

Aplicación web desarrollada en Angular 20 para gestionar un sistema de control escolar. Incluye módulos para administradores, docentes y estudiantes.

## 🚀 Características

### Módulo de Estudiante (Alumno)
- ✅ Visualizar cursos disponibles
- ✅ Inscripción a clases
- ✅ Ver kardex con calificaciones
- ✅ Consultar historial de pagos
- ✅ Gestión de cuenta

### Módulo de Docente
- ✅ Ver horario de clases
- ✅ Gestionar calificaciones
- ✅ Ver lista de estudiantes por clase

### Módulo de Administrador
- ✅ Registrar nuevos estudiantes
- ✅ Crear clases/cursos
- ✅ Asignar profesores y salones
- ✅ Gestionar periodos académicos

## 📋 Requisitos Previos

- Node.js 18+ 
- npm 9+
- Angular CLI 20+

## 🛠️ Instalación

1. Clonar el repositorio o descargar los archivos

2. Instalar dependencias:
```bash
npm install
```

3. Configurar la URL de la API:

Edita el archivo `src/environments/environment.ts`:

```typescript
export const environment = {
  production: false,
  apiUrl: 'http://localhost:8000/api' // Cambia por la URL de tu API
};
```

## 🚀 Ejecución

### Modo desarrollo:
```bash
npm start
# o
ng serve
```

La aplicación estará disponible en `http://localhost:4200/`

### Modo producción:
```bash
npm run build
# o
ng build --configuration production
```

Los archivos compilados estarán en la carpeta `dist/`

## 🔐 Credenciales de Prueba

Según la documentación de la API:

**Administrador:**
- Email: admin@sistema.com
- Password: password123

## 📱 Estructura de la Aplicación

```
src/
├── app/
│   ├── components/
│   │   ├── login/                    # Login
│   │   ├── layout/                   # Layout principal con navegación
│   │   ├── student/                  # Componentes de estudiante
│   │   │   ├── student-courses/      # Inscripción a cursos
│   │   │   ├── student-kardex/       # Kardex
│   │   │   └── student-payments/     # Pagos
│   │   ├── teacher/                  # Componentes de docente
│   │   │   └── teacher-schedule/     # Horario
│   │   └── admin/                    # Componentes de admin
│   ├── services/
│   │   ├── auth.service.ts           # Autenticación
│   │   ├── student.service.ts        # API estudiante
│   │   ├── teacher.service.ts        # API docente
│   │   └── admin.service.ts          # API admin
│   ├── guards/
│   │   └── auth.guard.ts             # Protección de rutas
│   ├── interceptors/
│   │   └── auth.interceptor.ts       # Interceptor HTTP
│   ├── models/
│   │   └── api.models.ts             # Interfaces TypeScript
│   ├── app.routes.ts                 # Configuración de rutas
│   └── app.config.ts                 # Configuración de la app
├── environments/                      # Configuración de entornos
└── styles.css                        # Estilos globales
```

## 🔄 Rutas de la Aplicación

### Públicas
- `/login` - Inicio de sesión

### Estudiante (requiere rol: alumno)
- `/student/courses` - Cursos disponibles e inscripción
- `/student/kardex` - Historial académico
- `/student/payments` - Estado de pagos

### Docente (requiere rol: docente)
- `/teacher` - Horario de clases
- `/teacher/grades` - Gestión de calificaciones

### Administrador (requiere rol: admin)
- `/admin/students` - Registro de estudiantes
- `/admin/courses` - Creación de clases

## 🌐 API Endpoints Utilizados

La aplicación consume los siguientes endpoints de la API:

### Autenticación
- `POST /api/login` - Iniciar sesión

### Estudiante
- `GET /api/courses` - Listar cursos disponibles
- `POST /api/student/enroll` - Inscribirse a un curso
- `GET /api/student/kardex` - Obtener kardex
- `GET /api/student/payments` - Listar pagos

### Docente
- `GET /api/teacher/schedule` - Obtener horario
- `POST /api/teacher/grades` - Registrar calificación

### Administrador
- `POST /api/admin/register/student` - Registrar estudiante
- `POST /api/admin/courses` - Crear clase

## 🎨 Tecnologías Utilizadas

- **Angular 20** - Framework principal
- **TypeScript** - Lenguaje de programación
- **RxJS** - Programación reactiva
- **Signals** - Gestión de estado reactivo
- **Standalone Components** - Arquitectura modular
- **CSS3** - Estilos y diseño responsivo

## 🔒 Seguridad

- Autenticación mediante tokens JWT
- Guards para protección de rutas
- Interceptor HTTP para agregar tokens
- Validación de roles en el frontend

## 📦 Scripts Disponibles

```bash
npm start          # Iniciar servidor de desarrollo
npm run build      # Compilar para producción
npm test           # Ejecutar tests
npm run lint       # Verificar código
```

## 🐛 Solución de Problemas

### Error de CORS
Si encuentras errores de CORS, asegúrate de que tu API Laravel tenga configurado correctamente:
- `php artisan config:clear`
- Verificar `config/cors.php`

### Token no válido
Si recibes errores de autenticación:
- Verifica que el token se esté enviando correctamente
- Revisa la configuración del interceptor
- Asegúrate de que el token no haya expirado

### Rutas no funcionan
- Verifica que hayas importado correctamente los componentes
- Asegúrate de que los guards estén configurados
- Revisa la consola del navegador para errores

## 📝 Notas de Desarrollo

- La aplicación usa **Signals** para gestión de estado reactivo
- Todos los componentes son **Standalone** (sin módulos)
- Los estilos están encapsulados por componente
- Se usa **TypeScript strict mode** para mayor seguridad de tipos

## 🤝 Contribución

1. Haz fork del proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto es de código abierto y está disponible bajo la licencia MIT.

## 👥 Autores

Sistema desarrollado como parte de un proyecto de control escolar.

## 🔗 Enlaces Útiles

- [Documentación de Angular](https://angular.dev)
- [Documentación de TypeScript](https://www.typescriptlang.org)
- [Guía de Signals en Angular](https://angular.dev/guide/signals)

---

¿Necesitas ayuda? Abre un issue en el repositorio.
