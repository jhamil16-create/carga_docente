import { NextResponse } from "next/server"
import type { NextRequest } from "next/server"
import type { GestionAcademica } from "@/lib/types"

// Mock database
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

// GET - List all periodos
export async function GET(request: NextRequest) {
  try {
    await new Promise((resolve) => setTimeout(resolve, 300))

    // Sort by year and semester descending
    const sortedPeriodos = [...MOCK_PERIODOS].sort((a, b) => {
      if (a.año !== b.año) return b.año - a.año
      return Number(b.semestre) - Number(a.semestre)
    })

    return NextResponse.json({
      periodos: sortedPeriodos,
      total: sortedPeriodos.length,
    })
  } catch (error) {
    console.error("Error fetching periodos:", error)
    return NextResponse.json({ message: "Error al obtener periodos" }, { status: 500 })
  }
}

// POST - Create new periodo
export async function POST(request: NextRequest) {
  try {
    const body = await request.json()
    const { nombre, fechaInicio, fechaFin, estado, semestre, año } = body

    if (!nombre || !fechaInicio || !fechaFin || !estado || !semestre || !año) {
      return NextResponse.json({ message: "Todos los campos son requeridos" }, { status: 400 })
    }

    // Validate dates
    const inicio = new Date(fechaInicio)
    const fin = new Date(fechaFin)

    if (fin <= inicio) {
      return NextResponse.json({ message: "La fecha de fin debe ser posterior a la fecha de inicio" }, { status: 400 })
    }

    // Check for overlapping periods
    const hasOverlap = MOCK_PERIODOS.some((p) => {
      const pInicio = new Date(p.fechaInicio)
      const pFin = new Date(p.fechaFin)
      return (inicio >= pInicio && inicio <= pFin) || (fin >= pInicio && fin <= pFin)
    })

    if (hasOverlap) {
      return NextResponse.json({ message: "Las fechas se superponen con otro periodo existente" }, { status: 400 })
    }

    const newPeriodo: GestionAcademica = {
      id: String(Date.now()),
      nombre,
      fechaInicio,
      fechaFin,
      estado,
      semestre,
      año,
    }

    MOCK_PERIODOS.push(newPeriodo)

    return NextResponse.json({
      message: "Periodo creado exitosamente",
      periodo: newPeriodo,
    })
  } catch (error) {
    console.error("Error creating periodo:", error)
    return NextResponse.json({ message: "Error al crear periodo" }, { status: 500 })
  }
}
