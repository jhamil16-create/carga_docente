import { NextResponse } from "next/server"
import type { NextRequest } from "next/server"
import type { Docente } from "@/lib/types"

// Mock database
const MOCK_DOCENTES: Docente[] = [
  {
    id: "1",
    nombre: "Juan",
    apellido: "Pérez",
    email: "juan.perez@universidad.edu",
    telefono: "+1234567890",
    especialidad: "Matemáticas",
    cargaHoraria: 20,
    estado: "activo",
  },
  {
    id: "2",
    nombre: "María",
    apellido: "González",
    email: "maria.gonzalez@universidad.edu",
    telefono: "+1234567891",
    especialidad: "Física",
    cargaHoraria: 18,
    estado: "activo",
  },
  {
    id: "3",
    nombre: "Carlos",
    apellido: "Rodríguez",
    email: "carlos.rodriguez@universidad.edu",
    telefono: "+1234567892",
    especialidad: "Programación",
    cargaHoraria: 22,
    estado: "activo",
  },
]

// GET - List all docentes
export async function GET(request: NextRequest) {
  try {
    await new Promise((resolve) => setTimeout(resolve, 300))

    return NextResponse.json({
      docentes: MOCK_DOCENTES,
      total: MOCK_DOCENTES.length,
    })
  } catch (error) {
    console.error("Error fetching docentes:", error)
    return NextResponse.json({ message: "Error al obtener docentes" }, { status: 500 })
  }
}

// POST - Create new docente
export async function POST(request: NextRequest) {
  try {
    const body = await request.json()
    const { nombre, apellido, email, telefono, especialidad, cargaHoraria, estado } = body

    if (!nombre || !apellido || !email || !telefono || !especialidad || cargaHoraria === undefined) {
      return NextResponse.json({ message: "Todos los campos son requeridos" }, { status: 400 })
    }

    if (MOCK_DOCENTES.some((d) => d.email === email)) {
      return NextResponse.json({ message: "El email ya está registrado" }, { status: 400 })
    }

    const newDocente: Docente = {
      id: String(Date.now()),
      nombre,
      apellido,
      email,
      telefono,
      especialidad,
      cargaHoraria,
      estado: estado || "activo",
    }

    MOCK_DOCENTES.push(newDocente)

    return NextResponse.json({
      message: "Docente creado exitosamente",
      docente: newDocente,
    })
  } catch (error) {
    console.error("Error creating docente:", error)
    return NextResponse.json({ message: "Error al crear docente" }, { status: 500 })
  }
}
