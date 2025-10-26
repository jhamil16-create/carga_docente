import { NextResponse } from "next/server"
import type { NextRequest } from "next/server"
import type { Aula } from "@/lib/types"

const MOCK_AULAS: Aula[] = [
  {
    id: "1",
    codigo: "A101",
    nombre: "Aula de Matemáticas",
    capacidad: 40,
    tipo: "teorica",
    edificio: "A",
    piso: 1,
    estado: "disponible",
  },
  {
    id: "2",
    codigo: "B201",
    nombre: "Laboratorio de Física",
    capacidad: 30,
    tipo: "laboratorio",
    edificio: "B",
    piso: 2,
    estado: "disponible",
  },
  {
    id: "3",
    codigo: "C301",
    nombre: "Auditorio Principal",
    capacidad: 200,
    tipo: "auditorio",
    edificio: "C",
    piso: 3,
    estado: "disponible",
  },
  {
    id: "4",
    codigo: "A102",
    nombre: "Aula de Programación",
    capacidad: 35,
    tipo: "teorica",
    edificio: "A",
    piso: 1,
    estado: "ocupada",
  },
  {
    id: "5",
    codigo: "B101",
    nombre: "Laboratorio de Química",
    capacidad: 25,
    tipo: "laboratorio",
    edificio: "B",
    piso: 1,
    estado: "mantenimiento",
  },
]

// PUT - Update aula
export async function PUT(request: NextRequest, { params }: { params: Promise<{ id: string }> }) {
  try {
    const { id } = await params
    const body = await request.json()

    const aulaIndex = MOCK_AULAS.findIndex((a) => a.id === id)
    if (aulaIndex === -1) {
      return NextResponse.json({ message: "Aula no encontrada" }, { status: 404 })
    }

    MOCK_AULAS[aulaIndex] = {
      ...MOCK_AULAS[aulaIndex],
      ...body,
    }

    return NextResponse.json({
      message: "Aula actualizada exitosamente",
      aula: MOCK_AULAS[aulaIndex],
    })
  } catch (error) {
    console.error("Error updating aula:", error)
    return NextResponse.json({ message: "Error al actualizar aula" }, { status: 500 })
  }
}

// DELETE - Delete aula
export async function DELETE(request: NextRequest, { params }: { params: Promise<{ id: string }> }) {
  try {
    const { id } = await params

    const aulaIndex = MOCK_AULAS.findIndex((a) => a.id === id)
    if (aulaIndex === -1) {
      return NextResponse.json({ message: "Aula no encontrada" }, { status: 404 })
    }

    MOCK_AULAS.splice(aulaIndex, 1)

    return NextResponse.json({
      message: "Aula eliminada exitosamente",
    })
  } catch (error) {
    console.error("Error deleting aula:", error)
    return NextResponse.json({ message: "Error al eliminar aula" }, { status: 500 })
  }
}
