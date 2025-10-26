// Tipos para el sistema de gestión académica

export interface User {
  id: string
  email: string
  nombre: string
  apellido: string
  rol: "admin" | "director"
  estado: "activo" | "inactivo"
  fechaCreacion: string
}

export interface Role {
  id: string
  nombre: string
  descripcion: string
  permisos: string[]
}

export interface Docente {
  id: string
  nombre: string
  apellido: string
  email: string
  telefono: string
  especialidad: string
  cargaHoraria: number
  estado: "activo" | "inactivo"
}

export interface Materia {
  id: string
  codigo: string
  nombre: string
  creditos: number
  horasSemanales: number
  departamento: string
  estado: "activa" | "inactiva"
}

export interface Grupo {
  id: string
  codigo: string
  materiaId: string
  docenteId: string
  capacidad: number
  horario: string
  semestre: string
}

export interface Aula {
  id: string
  codigo: string
  nombre: string
  capacidad: number
  tipo: "teorica" | "laboratorio" | "auditorio"
  edificio: string
  piso: number
  estado: "disponible" | "ocupada" | "mantenimiento"
}

export interface GestionAcademica {
  id: string
  nombre: string
  fechaInicio: string
  fechaFin: string
  estado: "activa" | "finalizada" | "planificada"
  semestre: "1" | "2"
  año: number
}
