import { NextResponse } from "next/server"
import type { NextRequest } from "next/server"
import type { Aula } from "@/lib/types"

// Mock database
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

// GET - List all aulas
export async function GET(request: NextRequest) {
  try {
    await new Promise((resolve) => setTimeout(resolve, 300))

    return NextResponse.json({
      aulas: MOCK_AULAS,
      total: MOCK_AULAS.length,
    })
  } catch (error) {
    console.error("Error fetching aulas:", error)
    return NextResponse.json({ message: "Error al obtener aulas" }, { status: 500 })
  }
}

// POST - Create new aula
export async function POST(request: NextRequest) {
  try {
    const body = await request.json()
    const { codigo, nombre, capacidad, tipo, edificio, piso, estado } = body

    if (!codigo || !nombre || capacidad === undefined || !tipo || !edificio || piso === undefined) {
      return NextResponse.json({ message: "Todos los campos son requeridos" }, { status: 400 })
    }

    if (MOCK_AULAS.some((a) => a.codigo === codigo)) {
      return NextResponse.json({ message: "El código de aula ya está registrado" }, { status: 400 })
    }

    const newAula: Aula = {
      id: String(Date.now()),
      codigo,
      nombre,
      capacidad,
      tipo,
      edificio,
      piso,
      estado: estado || "disponible",
    }

    MOCK_AULAS.push(newAula)

    return NextResponse.json({
      message: "Aula creada exitosamente",
      aula: newAula,
    })
  } catch (error) {
    console.error("Error creating aula:", error)
    return NextResponse.json({ message: "Error al crear aula" }, { status: 500 })
  }
}
