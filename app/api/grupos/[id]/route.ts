import { NextResponse } from "next/server"
import type { NextRequest } from "next/server"
import type { Grupo } from "@/lib/types"

const MOCK_GRUPOS: Grupo[] = [
  {
    id: "1",
    codigo: "G01",
    materiaId: "1",
    docenteId: "1",
    capacidad: 30,
    horario: "Lun-Mie 8:00-10:00",
    semestre: "2024-1",
  },
  {
    id: "2",
    codigo: "G02",
    materiaId: "2",
    docenteId: "2",
    capacidad: 25,
    horario: "Mar-Jue 10:00-12:00",
    semestre: "2024-1",
  },
]

// PUT - Update grupo
export async function PUT(request: NextRequest, { params }: { params: Promise<{ id: string }> }) {
  try {
    const { id } = await params
    const body = await request.json()

    const grupoIndex = MOCK_GRUPOS.findIndex((g) => g.id === id)
    if (grupoIndex === -1) {
      return NextResponse.json({ message: "Grupo no encontrado" }, { status: 404 })
    }

    MOCK_GRUPOS[grupoIndex] = {
      ...MOCK_GRUPOS[grupoIndex],
      ...body,
    }

    return NextResponse.json({
      message: "Grupo actualizado exitosamente",
      grupo: MOCK_GRUPOS[grupoIndex],
    })
  } catch (error) {
    console.error("Error updating grupo:", error)
    return NextResponse.json({ message: "Error al actualizar grupo" }, { status: 500 })
  }
}

// DELETE - Delete grupo
export async function DELETE(request: NextRequest, { params }: { params: Promise<{ id: string }> }) {
  try {
    const { id } = await params

    const grupoIndex = MOCK_GRUPOS.findIndex((g) => g.id === id)
    if (grupoIndex === -1) {
      return NextResponse.json({ message: "Grupo no encontrado" }, { status: 404 })
    }

    MOCK_GRUPOS.splice(grupoIndex, 1)

    return NextResponse.json({
      message: "Grupo eliminado exitosamente",
    })
  } catch (error) {
    console.error("Error deleting grupo:", error)
    return NextResponse.json({ message: "Error al eliminar grupo" }, { status: 500 })
  }
}
