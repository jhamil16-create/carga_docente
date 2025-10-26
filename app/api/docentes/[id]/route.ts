import { NextResponse } from "next/server"
import type { NextRequest } from "next/server"
import type { Docente } from "@/lib/types"

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

// PUT - Update docente
export async function PUT(request: NextRequest, { params }: { params: Promise<{ id: string }> }) {
  try {
    const { id } = await params
    const body = await request.json()

    const docenteIndex = MOCK_DOCENTES.findIndex((d) => d.id === id)
    if (docenteIndex === -1) {
      return NextResponse.json({ message: "Docente no encontrado" }, { status: 404 })
    }

    MOCK_DOCENTES[docenteIndex] = {
      ...MOCK_DOCENTES[docenteIndex],
      ...body,
    }

    return NextResponse.json({
      message: "Docente actualizado exitosamente",
      docente: MOCK_DOCENTES[docenteIndex],
    })
  } catch (error) {
    console.error("Error updating docente:", error)
    return NextResponse.json({ message: "Error al actualizar docente" }, { status: 500 })
  }
}

// DELETE - Delete docente
export async function DELETE(request: NextRequest, { params }: { params: Promise<{ id: string }> }) {
  try {
    const { id } = await params

    const docenteIndex = MOCK_DOCENTES.findIndex((d) => d.id === id)
    if (docenteIndex === -1) {
      return NextResponse.json({ message: "Docente no encontrado" }, { status: 404 })
    }

    MOCK_DOCENTES.splice(docenteIndex, 1)

    return NextResponse.json({
      message: "Docente eliminado exitosamente",
    })
  } catch (error) {
    console.error("Error deleting docente:", error)
    return NextResponse.json({ message: "Error al eliminar docente" }, { status: 500 })
  }
}
