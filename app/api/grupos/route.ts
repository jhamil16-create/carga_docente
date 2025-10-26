import { NextResponse } from "next/server"
import type { NextRequest } from "next/server"
import type { Grupo } from "@/lib/types"

// Mock database
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

// GET - List all grupos with details
export async function GET(request: NextRequest) {
  try {
    await new Promise((resolve) => setTimeout(resolve, 300))

    // Fetch materias and docentes to enrich the response
    const materiasResponse = await fetch(`${request.nextUrl.origin}/api/materias`)
    const docentesResponse = await fetch(`${request.nextUrl.origin}/api/docentes`)

    const { materias } = await materiasResponse.json()
    const { docentes } = await docentesResponse.json()

    // Enrich grupos with materia and docente names
    const gruposWithDetails = MOCK_GRUPOS.map((grupo) => {
      const materia = materias.find((m: any) => m.id === grupo.materiaId)
      const docente = docentes.find((d: any) => d.id === grupo.docenteId)

      return {
        ...grupo,
        materiaNombre: materia ? `${materia.codigo} - ${materia.nombre}` : "N/A",
        docenteNombre: docente ? `${docente.nombre} ${docente.apellido}` : "N/A",
      }
    })

    return NextResponse.json({
      grupos: gruposWithDetails,
      total: gruposWithDetails.length,
    })
  } catch (error) {
    console.error("Error fetching grupos:", error)
    return NextResponse.json({ message: "Error al obtener grupos" }, { status: 500 })
  }
}

// POST - Create new grupo
export async function POST(request: NextRequest) {
  try {
    const body = await request.json()
    const { codigo, materiaId, docenteId, capacidad, horario, semestre } = body

    if (!codigo || !materiaId || !docenteId || !capacidad || !horario || !semestre) {
      return NextResponse.json({ message: "Todos los campos son requeridos" }, { status: 400 })
    }

    if (MOCK_GRUPOS.some((g) => g.codigo === codigo && g.semestre === semestre)) {
      return NextResponse.json({ message: "El código de grupo ya existe para este semestre" }, { status: 400 })
    }

    const newGrupo: Grupo = {
      id: String(Date.now()),
      codigo,
      materiaId,
      docenteId,
      capacidad,
      horario,
      semestre,
    }

    MOCK_GRUPOS.push(newGrupo)

    return NextResponse.json({
      message: "Grupo creado exitosamente",
      grupo: newGrupo,
    })
  } catch (error) {
    console.error("Error creating grupo:", error)
    return NextResponse.json({ message: "Error al crear grupo" }, { status: 500 })
  }
}
