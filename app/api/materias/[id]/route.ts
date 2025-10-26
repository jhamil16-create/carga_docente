import { NextResponse } from "next/server"
import type { NextRequest } from "next/server"
import type { Materia } from "@/lib/types"

const MOCK_MATERIAS: Materia[] = [
  {
    id: "1",
    codigo: "MAT101",
    nombre: "Cálculo Diferencial",
    creditos: 4,
    horasSemanales: 6,
    departamento: "Ciencias Exactas",
    estado: "activa",
  },
  {
    id: "2",
    codigo: "FIS201",
    nombre: "Física General",
    creditos: 3,
    horasSemanales: 5,
    departamento: "Ciencias Exactas",
    estado: "activa",
  },
  {
    id: "3",
    codigo: "PRG301",
    nombre: "Programación Avanzada",
    creditos: 4,
    horasSemanales: 6,
    departamento: "Ingeniería",
    estado: "activa",
  },
]

// PUT - Update materia
export async function PUT(request: NextRequest, { params }: { params: Promise<{ id: string }> }) {
  try {
    const { id } = await params
    const body = await request.json()

    const materiaIndex = MOCK_MATERIAS.findIndex((m) => m.id === id)
    if (materiaIndex === -1) {
      return NextResponse.json({ message: "Materia no encontrada" }, { status: 404 })
    }

    MOCK_MATERIAS[materiaIndex] = {
      ...MOCK_MATERIAS[materiaIndex],
      ...body,
    }

    return NextResponse.json({
      message: "Materia actualizada exitosamente",
      materia: MOCK_MATERIAS[materiaIndex],
    })
  } catch (error) {
    console.error("Error updating materia:", error)
    return NextResponse.json({ message: "Error al actualizar materia" }, { status: 500 })
  }
}

// DELETE - Delete materia
export async function DELETE(request: NextRequest, { params }: { params: Promise<{ id: string }> }) {
  try {
    const { id } = await params

    const materiaIndex = MOCK_MATERIAS.findIndex((m) => m.id === id)
    if (materiaIndex === -1) {
      return NextResponse.json({ message: "Materia no encontrada" }, { status: 404 })
    }

    MOCK_MATERIAS.splice(materiaIndex, 1)

    return NextResponse.json({
      message: "Materia eliminada exitosamente",
    })
  } catch (error) {
    console.error("Error deleting materia:", error)
    return NextResponse.json({ message: "Error al eliminar materia" }, { status: 500 })
  }
}
