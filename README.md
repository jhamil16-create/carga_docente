# Sistema de Gestión Académica

Sistema web administrativo completo para la gestión de instituciones educativas, desarrollado con Next.js 16 y React 19. Incluye módulos para gestión de usuarios, docentes, materias, grupos, aulas y periodos académicos.

## 📋 Tabla de Contenidos

- [Características](#características)
- [Tecnologías Utilizadas](#tecnologías-utilizadas)
- [Requisitos](#requisitos)
- [Instalación](#instalación)
- [Configuración](#configuración)
- [Ejecución en Local](#ejecución-en-local)
- [Despliegue en Producción](#despliegue-en-producción)
- [Estructura del Proyecto](#estructura-del-proyecto)
- [Módulos Funcionales](#módulos-funcionales)
- [API Routes](#api-routes)
- [Credenciales de Prueba](#credenciales-de-prueba)
- [Integración con Base de Datos](#integración-con-base-de-datos)

## ✨ Características

### Módulos Implementados

- ✅ **Sistema de Autenticación**
  - Login con validación
  - Gestión de sesiones con tokens JWT
  - Middleware para protección de rutas
  - Context API para estado global de autenticación

- ✅ **Gestión de Usuarios**
  - CRUD completo de usuarios
  - Asignación de roles (Administrador, Director)
  - Estados de usuario (Activo, Inactivo)
  - Búsqueda y filtrado

- ✅ **Gestión de Docentes**
  - Registro completo de información docente
  - Control de carga horaria
  - Especialidades y títulos académicos
  - Estados de disponibilidad

- ✅ **Gestión de Materias**
  - Catálogo de materias/asignaturas
  - Códigos y créditos académicos
  - Niveles y semestres
  - Estados activo/inactivo

- ✅ **Gestión de Grupos**
  - Asignación de docentes a materias
  - Horarios y capacidad de estudiantes
  - Vinculación con aulas
  - Gestión de turnos

- ✅ **Gestión de Aulas**
  - Inventario de espacios físicos
  - Capacidad y tipo de aula
  - Ubicación (edificio, piso)
  - Estados y disponibilidad

- ✅ **Gestión de Periodos Académicos**
  - Configuración de gestiones académicas
  - Fechas de inicio y fin
  - Semestres y años académicos
  - Validación de periodos

## 🛠 Tecnologías Utilizadas

### Frontend Framework
- **Next.js 16** - Framework React con App Router
- **React 19.2** - Biblioteca de UI con características canary
- **TypeScript** - Tipado estático

### Styling
- **Tailwind CSS v4** - Framework de CSS utility-first
- **shadcn/ui** - Componentes de UI reutilizables
- **Radix UI** - Primitivos de UI accesibles
- **Lucide React** - Iconos modernos

### Estado y Datos
- **React Context API** - Gestión de estado global
- **SWR** - Fetching y caché de datos (preparado para implementar)

### Formularios y Validación
- **React Hook Form** - Gestión de formularios
- **Zod** - Validación de esquemas

### UI Components
- **date-fns** - Manipulación de fechas
- **Sonner** - Notificaciones toast
- **Recharts** - Gráficos (disponible para reportes)

## 📦 Requisitos

### Software Necesario
- **Node.js** >= 18.17.0
- **npm** >= 9.0.0 o **pnpm** >= 8.0.0 (recomendado)
- **Git** (para clonar el repositorio)

### Navegadores Soportados
- Chrome/Edge >= 90
- Firefox >= 88
- Safari >= 14

## 🚀 Instalación

### 1. Clonar el Repositorio

\`\`\`bash
git clone <url-del-repositorio>
cd sistema-gestion-academica
\`\`\`

### 2. Instalar Dependencias

Con npm:
\`\`\`bash
npm install
\`\`\`

Con pnpm (recomendado):
\`\`\`bash
pnpm install
\`\`\`

### 3. Configurar Variables de Entorno

Crea un archivo `.env.local` en la raíz del proyecto:

\`\`\`env
# Configuración de la aplicación
NEXT_PUBLIC_APP_URL=http://localhost:3000

# JWT Secret (cambiar en producción)
JWT_SECRET=tu-secreto-super-seguro-cambiar-en-produccion

# Base de datos (cuando esté lista)
# DATABASE_URL=postgresql://usuario:password@localhost:5432/gestion_academica
\`\`\`

## 💻 Ejecución en Local

### Modo Desarrollo

\`\`\`bash
npm run dev
# o
pnpm dev
\`\`\`

La aplicación estará disponible en: `http://localhost:3000`

### Build de Producción Local

\`\`\`bash
npm run build
npm run start
# o
pnpm build
pnpm start
\`\`\`

### Linting

\`\`\`bash
npm run lint
# o
pnpm lint
\`\`\`

## 🌐 Despliegue en Producción

### Opción 1: Vercel (Recomendado)

1. **Conectar con GitHub**
   - Push tu código a GitHub
   - Importa el proyecto en [Vercel](https://vercel.com)

2. **Configurar Variables de Entorno**
   \`\`\`
   JWT_SECRET=<generar-secreto-seguro>
   DATABASE_URL=<url-de-tu-base-de-datos>
   \`\`\`

3. **Deploy Automático**
   - Vercel detectará Next.js automáticamente
   - Cada push a main desplegará automáticamente

### Opción 2: Docker

\`\`\`dockerfile
# Dockerfile (crear en la raíz)
FROM node:18-alpine AS base

FROM base AS deps
WORKDIR /app
COPY package.json pnpm-lock.yaml ./
RUN npm install -g pnpm && pnpm install --frozen-lockfile

FROM base AS builder
WORKDIR /app
COPY --from=deps /app/node_modules ./node_modules
COPY . .
RUN npm run build

FROM base AS runner
WORKDIR /app
ENV NODE_ENV production
COPY --from=builder /app/public ./public
COPY --from=builder /app/.next/standalone ./
COPY --from=builder /app/.next/static ./.next/static

EXPOSE 3000
CMD ["node", "server.js"]
\`\`\`

\`\`\`bash
docker build -t gestion-academica .
docker run -p 3000:3000 gestion-academica
\`\`\`

### Opción 3: Servidor VPS

\`\`\`bash
# En el servidor
git clone <url-del-repositorio>
cd sistema-gestion-academica
npm install
npm run build
npm install -g pm2
pm2 start npm --name "gestion-academica" -- start
pm2 save
pm2 startup
\`\`\`

## 📁 Estructura del Proyecto

\`\`\`
sistema-gestion-academica/
├── app/                          # App Router de Next.js
│   ├── api/                      # API Routes
│   │   ├── auth/
│   │   │   └── login/           # Endpoint de autenticación
│   │   ├── users/               # CRUD de usuarios
│   │   ├── docentes/            # CRUD de docentes
│   │   ├── materias/            # CRUD de materias
│   │   ├── grupos/              # CRUD de grupos
│   │   ├── aulas/               # CRUD de aulas
│   │   └── periodos/            # CRUD de periodos académicos
│   ├── dashboard/               # Páginas del dashboard
│   │   ├── users/               # Gestión de usuarios
│   │   ├── docentes/            # Gestión de docentes
│   │   ├── materias/            # Gestión de materias
│   │   ├── grupos/              # Gestión de grupos
│   │   ├── aulas/               # Gestión de aulas
│   │   ├── periodos/            # Gestión de periodos
│   │   ├── layout.tsx           # Layout del dashboard
│   │   └── page.tsx             # Dashboard principal
│   ├── login/                   # Página de login
│   ├── layout.tsx               # Layout raíz
│   ├── page.tsx                 # Página de inicio
│   └── globals.css              # Estilos globales
├── components/                   # Componentes React
│   ├── auth/                    # Componentes de autenticación
│   ├── layout/                  # Componentes de layout
│   ├── users/                   # Componentes de usuarios
│   ├── docentes/                # Componentes de docentes
│   ├── materias/                # Componentes de materias
│   ├── grupos/                  # Componentes de grupos
│   ├── aulas/                   # Componentes de aulas
│   ├── periodos/                # Componentes de periodos
│   └── ui/                      # Componentes UI de shadcn
├── lib/                         # Utilidades y configuración
│   ├── auth-context.tsx         # Context de autenticación
│   ├── types.ts                 # Tipos TypeScript
│   └── utils.ts                 # Funciones utilitarias
├── hooks/                       # Custom hooks
│   ├── use-mobile.ts            # Hook para detección mobile
│   └── use-toast.ts             # Hook para notificaciones
├── public/                      # Archivos estáticos
├── middleware.ts                # Middleware de Next.js
├── next.config.mjs              # Configuración de Next.js
├── tailwind.config.ts           # Configuración de Tailwind
├── tsconfig.json                # Configuración de TypeScript
└── package.json                 # Dependencias del proyecto
\`\`\`

## 🎯 Módulos Funcionales

### 1. Autenticación y Seguridad
- **Archivos principales:**
  - `app/login/page.tsx` - Página de login
  - `components/auth/login-form.tsx` - Formulario de login
  - `lib/auth-context.tsx` - Context de autenticación
  - `middleware.ts` - Protección de rutas
  - `app/api/auth/login/route.ts` - API de login

- **Funcionalidad:**
  - Login con email y contraseña
  - Generación de tokens JWT
  - Almacenamiento seguro en cookies
  - Redirección automática según autenticación
  - Logout con limpieza de sesión

### 2. Gestión de Usuarios
- **Archivos principales:**
  - `app/dashboard/users/page.tsx`
  - `components/users/users-table.tsx`
  - `components/users/user-dialog.tsx`
  - `app/api/users/route.ts`

- **Funcionalidad:**
  - Crear, editar, eliminar usuarios
  - Asignación de roles
  - Búsqueda por nombre o email
  - Estados activo/inactivo

### 3. Gestión de Docentes
- **Archivos principales:**
  - `app/dashboard/docentes/page.tsx`
  - `components/docentes/docentes-table.tsx`
  - `components/docentes/docente-dialog.tsx`
  - `app/api/docentes/route.ts`

- **Funcionalidad:**
  - Registro completo de docentes
  - Control de carga horaria
  - Especialidades y títulos
  - Estados de disponibilidad

### 4. Gestión de Materias
- **Archivos principales:**
  - `app/dashboard/materias/page.tsx`
  - `components/materias/materias-table.tsx`
  - `components/materias/materia-dialog.tsx`
  - `app/api/materias/route.ts`

- **Funcionalidad:**
  - Catálogo de materias
  - Códigos y créditos
  - Niveles y semestres
  - Estados activo/inactivo

### 5. Gestión de Grupos
- **Archivos principales:**
  - `app/dashboard/grupos/page.tsx`
  - `components/grupos/grupos-table.tsx`
  - `components/grupos/grupo-dialog.tsx`
  - `app/api/grupos/route.ts`

- **Funcionalidad:**
  - Asignación docente-materia
  - Horarios y capacidad
  - Vinculación con aulas
  - Gestión de turnos

### 6. Gestión de Aulas
- **Archivos principales:**
  - `app/dashboard/aulas/page.tsx`
  - `components/aulas/aulas-table.tsx`
  - `components/aulas/aula-dialog.tsx`
  - `app/api/aulas/route.ts`

- **Funcionalidad:**
  - Inventario de aulas
  - Capacidad y tipo
  - Ubicación física
  - Estados y disponibilidad

### 7. Gestión de Periodos Académicos
- **Archivos principales:**
  - `app/dashboard/periodos/page.tsx`
  - `components/periodos/periodos-table.tsx`
  - `components/periodos/periodo-dialog.tsx`
  - `app/api/periodos/route.ts`

- **Funcionalidad:**
  - Configuración de periodos
  - Fechas de inicio y fin
  - Semestres y años
  - Validación de solapamiento

## 🔌 API Routes

Todas las API routes siguen el patrón RESTful:

### Autenticación
\`\`\`
POST   /api/auth/login          # Iniciar sesión
\`\`\`

### Usuarios
\`\`\`
GET    /api/users               # Listar usuarios
POST   /api/users               # Crear usuario
GET    /api/users/[id]          # Obtener usuario
PUT    /api/users/[id]          # Actualizar usuario
DELETE /api/users/[id]          # Eliminar usuario
\`\`\`

### Docentes
\`\`\`
GET    /api/docentes            # Listar docentes
POST   /api/docentes            # Crear docente
GET    /api/docentes/[id]       # Obtener docente
PUT    /api/docentes/[id]       # Actualizar docente
DELETE /api/docentes/[id]       # Eliminar docente
\`\`\`

### Materias
\`\`\`
GET    /api/materias            # Listar materias
POST   /api/materias            # Crear materia
GET    /api/materias/[id]       # Obtener materia
PUT    /api/materias/[id]       # Actualizar materia
DELETE /api/materias/[id]       # Eliminar materia
\`\`\`

### Grupos
\`\`\`
GET    /api/grupos              # Listar grupos
POST   /api/grupos              # Crear grupo
GET    /api/grupos/[id]         # Obtener grupo
PUT    /api/grupos/[id]         # Actualizar grupo
DELETE /api/grupos/[id]         # Eliminar grupo
\`\`\`

### Aulas
\`\`\`
GET    /api/aulas               # Listar aulas
POST   /api/aulas               # Crear aula
GET    /api/aulas/[id]          # Obtener aula
PUT    /api/aulas/[id]          # Actualizar aula
DELETE /api/aulas/[id]          # Eliminar aula
\`\`\`

### Periodos Académicos
\`\`\`
GET    /api/periodos            # Listar periodos
POST   /api/periodos            # Crear periodo
GET    /api/periodos/[id]       # Obtener periodo
PUT    /api/periodos/[id]       # Actualizar periodo
DELETE /api/periodos/[id]       # Eliminar periodo
\`\`\`

## 🔑 Credenciales de Prueba

Para probar el sistema, usa estas credenciales:

**Administrador:**
- Email: `admin@universidad.edu`
- Contraseña: `admin123`

**Director:**
- Email: `director@universidad.edu`
- Contraseña: `director123`

## 🗄️ Integración con Base de Datos

### Estado Actual

El proyecto está **100% preparado** para conectarse a una base de datos real. Actualmente usa datos mock en memoria para demostración.

### Pasos para Integrar Base de Datos

#### 1. Instalar ORM (Recomendado: Prisma)

\`\`\`bash
npm install prisma @prisma/client
npx prisma init
\`\`\`

#### 2. Definir Schema

Crear `prisma/schema.prisma`:

\`\`\`prisma
datasource db {
  provider = "postgresql"
  url      = env("DATABASE_URL")
}

generator client {
  provider = "prisma-client-js"
}

model User {
  id        String   @id @default(cuid())
  email     String   @unique
  password  String
  name      String
  role      String
  status    String
  createdAt DateTime @default(now())
  updatedAt DateTime @updatedAt
}

model Docente {
  id              String   @id @default(cuid())
  nombre          String
  apellido        String
  email           String   @unique
  telefono        String?
  especialidad    String
  titulo          String
  cargaHoraria    Int
  estado          String
  createdAt       DateTime @default(now())
  updatedAt       DateTime @updatedAt
  grupos          Grupo[]
}

model Materia {
  id          String   @id @default(cuid())
  codigo      String   @unique
  nombre      String
  creditos    Int
  nivel       String
  semestre    Int
  descripcion String?
  estado      String
  createdAt   DateTime @default(now())
  updatedAt   DateTime @updatedAt
  grupos      Grupo[]
}

model Grupo {
  id              String   @id @default(cuid())
  codigo          String   @unique
  materiaId       String
  docenteId       String
  aulaId          String?
  horario         String
  turno           String
  capacidad       Int
  inscritos       Int      @default(0)
  estado          String
  createdAt       DateTime @default(now())
  updatedAt       DateTime @updatedAt
  materia         Materia  @relation(fields: [materiaId], references: [id])
  docente         Docente  @relation(fields: [docenteId], references: [id])
  aula            Aula?    @relation(fields: [aulaId], references: [id])
}

model Aula {
  id          String   @id @default(cuid())
  codigo      String   @unique
  nombre      String
  capacidad   Int
  tipo        String
  edificio    String
  piso        Int
  estado      String
  createdAt   DateTime @default(now())
  updatedAt   DateTime @updatedAt
  grupos      Grupo[]
}

model PeriodoAcademico {
  id          String   @id @default(cuid())
  nombre      String
  anio        Int
  semestre    Int
  fechaInicio DateTime
  fechaFin    DateTime
  estado      String
  createdAt   DateTime @default(now())
  updatedAt   DateTime @updatedAt
}
\`\`\`

#### 3. Migrar Base de Datos

\`\`\`bash
npx prisma migrate dev --name init
npx prisma generate
\`\`\`

#### 4. Actualizar API Routes

Reemplazar los datos mock con llamadas a Prisma:

\`\`\`typescript
// Ejemplo: app/api/users/route.ts
import { PrismaClient } from '@prisma/client'

const prisma = new PrismaClient()

export async function GET() {
  const users = await prisma.user.findMany()
  return Response.json(users)
}

export async function POST(request: Request) {
  const body = await request.json()
  const user = await prisma.user.create({
    data: body
  })
  return Response.json(user)
}
\`\`\`

#### 5. Configurar Variables de Entorno

\`\`\`env
DATABASE_URL="postgresql://usuario:password@localhost:5432/gestion_academica"
\`\`\`

### Bases de Datos Soportadas

- PostgreSQL (Recomendado)
- MySQL
- SQLite (desarrollo)
- MongoDB
- SQL Server

## 📊 Próximas Funcionalidades

### Pendientes de Implementar

- [ ] **Carga Masiva de Usuarios** (CU5)
  - Upload de archivos CSV/Excel
  - Validación de datos
  - Generación automática de cuentas

- [ ] **Gestión de Roles y Permisos** (CU4)
  - Definición de permisos granulares
  - Asignación de permisos a roles
  - Control de acceso por módulo

- [ ] **Reportes y Estadísticas**
  - Dashboard con métricas
  - Exportación a PDF/Excel
  - Gráficos de asistencia

- [ ] **Control de Asistencia**
  - Registro de asistencia docente
  - Reportes de asistencia
  - Alertas de inasistencias

## 🤝 Contribución

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📝 Licencia

Este proyecto es privado y confidencial.

## 👥 Soporte

Para soporte técnico, contactar a: soporte@universidad.edu

---

**Desarrollado con ❤️ usando Next.js 16 y React 19**
