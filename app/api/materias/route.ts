import { NextResponse } from "next/server"
import type { NextRequest } from "next/server"
import type { Materia } from "@/lib/types"

// Mock database
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

// GET - List all materias
export async function GET(request: NextRequest) {
  try {
    await new Promise((resolve) => setTimeout(resolve, 300))

    return NextResponse.json({
      materias: MOCK_MATERIAS,
      total: MOCK_MATERIAS.length,
    })
  } catch (error) {
    console.error("Error fetching materias:", error)
    return NextResponse.json({ message: "Error al obtener materias" }, { status: 500 })
  }
}

// POST - Create new materia
export async function POST(request: NextRequest) {
  try {
    const body = await request.json()
    const { codigo, nombre, creditos, horasSemanales, departamento, estado } = body

    if (!codigo || !nombre || creditos === undefined || horasSemanales === undefined || !departamento) {
      return NextResponse.json({ message: "Todos los campos son requeridos" }, { status: 400 })
    }

    if (MOCK_MATERIAS.some((m) => m.codigo === codigo)) {
      return NextResponse.json({ message: "El código ya está registrado" }, { status: 400 })
    }

    const newMateria: Materia = {
      id: String(Date.now()),
      codigo,
      nombre,
      creditos,
      horasSemanales,
      departamento,
      estado: estado || "activa",
    }

    MOCK_MATERIAS.push(newMateria)

    return NextResponse.json({
      message: "Materia creada exitosamente",
      materia: newMateria,
    })
  } catch (error) {
    console.error("Error creating materia:", error)
    return NextResponse.json({ message: "Error al crear materia" }, { status: 500 })
  }
}
