import { NextResponse } from "next/server"
import type { NextRequest } from "next/server"
import type { GestionAcademica } from "@/lib/types"

const MOCK_PERIODOS: GestionAcademica[] = [
  {
    id: "1",
    nombre: "Periodo Académico 2024-1",
    fechaInicio: "2024-01-15T00:00:00.000Z",
    fechaFin: "2024-06-30T00:00:00.000Z",
    estado: "activa",
    semestre: "1",
    año: 2024,
  },
  {
    id: "2",
    nombre: "Periodo Académico 2024-2",
    fechaInicio: "2024-07-15T00:00:00.000Z",
    fechaFin: "2024-12-20T00:00:00.000Z",
    estado: "planificada",
    semestre: "2",
    año: 2024,
  },
  {
    id: "3",
    nombre: "Periodo Académico 2023-2",
    fechaInicio: "2023-07-15T00:00:00.000Z",
    fechaFin: "2023-12-20T00:00:00.000Z",
    estado: "finalizada",
    semestre: "2",
    año: 2023,
  },
]

// PUT - Update periodo
export async function PUT(request: NextRequest, { params }: { params: Promise<{ id: string }> }) {
  try {
    const { id } = await params
    const body = await request.json()

    const periodoIndex = MOCK_PERIODOS.findIndex((p) => p.id === id)
    if (periodoIndex === -1) {
      return NextResponse.json({ message: "Periodo no encontrado" }, { status: 404 })
    }

    // Validate dates if they're being updated
    if (body.fechaInicio && body.fechaFin) {
      const inicio = new Date(body.fechaInicio)
      const fin = new Date(body.fechaFin)

      if (fin <= inicio) {
        return NextResponse.json(
          { message: "La fecha de fin debe ser posterior a la fecha de inicio" },
          { status: 400 },
        )
      }
    }

    MOCK_PERIODOS[periodoIndex] = {
      ...MOCK_PERIODOS[periodoIndex],
      ...body,
    }

    return NextResponse.json({
      message: "Periodo actualizado exitosamente",
      periodo: MOCK_PERIODOS[periodoIndex],
    })
  } catch (error) {
    console.error("Error updating periodo:", error)
    return NextResponse.json({ message: "Error al actualizar periodo" }, { status: 500 })
  }
}

// DELETE - Delete periodo
export async function DELETE(request: NextRequest, { params }: { params: Promise<{ id: string }> }) {
  try {
    const { id } = await params

    const periodoIndex = MOCK_PERIODOS.findIndex((p) => p.id === id)
    if (periodoIndex === -1) {
      return NextResponse.json({ message: "Periodo no encontrado" }, { status: 404 })
    }

    MOCK_PERIODOS.splice(periodoIndex, 1)

    return NextResponse.json({
      message: "Periodo eliminado exitosamente",
    })
  } catch (error) {
    console.error("Error deleting periodo:", error)
    return NextResponse.json({ message: "Error al eliminar periodo" }, { status: 500 })
  }
}
