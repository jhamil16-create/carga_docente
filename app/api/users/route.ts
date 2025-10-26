import { NextResponse } from "next/server"
import type { NextRequest } from "next/server"
import type { User } from "@/lib/types"

// Mock database (in production, this would be a real database)
const MOCK_USERS: User[] = [
  {
    id: "1",
    email: "admin@universidad.edu",
    nombre: "Administrador",
    apellido: "Sistema",
    rol: "admin",
    estado: "activo",
    fechaCreacion: "2024-01-15T10:00:00.000Z",
  },
  {
    id: "2",
    email: "director@universidad.edu",
    nombre: "Director",
    apellido: "Académico",
    rol: "director",
    estado: "activo",
    fechaCreacion: "2024-01-20T14:30:00.000Z",
  },
  {
    id: "3",
    email: "maria.garcia@universidad.edu",
    nombre: "María",
    apellido: "García",
    rol: "director",
    estado: "activo",
    fechaCreacion: "2024-02-01T09:15:00.000Z",
  },
]

// GET - List all users
export async function GET(request: NextRequest) {
  try {
    // Simulate database delay
    await new Promise((resolve) => setTimeout(resolve, 300))

    return NextResponse.json({
      users: MOCK_USERS,
      total: MOCK_USERS.length,
    })
  } catch (error) {
    console.error("Error fetching users:", error)
    return NextResponse.json({ message: "Error al obtener usuarios" }, { status: 500 })
  }
}

// POST - Create new user
export async function POST(request: NextRequest) {
  try {
    const body = await request.json()
    const { nombre, apellido, email, rol, estado, password } = body

    // Validate required fields
    if (!nombre || !apellido || !email || !rol || !password) {
      return NextResponse.json({ message: "Todos los campos son requeridos" }, { status: 400 })
    }

    // Check if email already exists
    if (MOCK_USERS.some((u) => u.email === email)) {
      return NextResponse.json({ message: "El email ya está registrado" }, { status: 400 })
    }

    // Validate password length
    if (password.length < 6) {
      return NextResponse.json({ message: "La contraseña debe tener al menos 6 caracteres" }, { status: 400 })
    }

    // Create new user
    const newUser: User = {
      id: String(Date.now()),
      nombre,
      apellido,
      email,
      rol,
      estado: estado || "activo",
      fechaCreacion: new Date().toISOString(),
    }

    MOCK_USERS.push(newUser)

    return NextResponse.json({
      message: "Usuario creado exitosamente",
      user: newUser,
    })
  } catch (error) {
    console.error("Error creating user:", error)
    return NextResponse.json({ message: "Error al crear usuario" }, { status: 500 })
  }
}
