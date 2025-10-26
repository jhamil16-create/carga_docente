import { NextResponse } from "next/server"
import type { NextRequest } from "next/server"
import type { User } from "@/lib/types"

// Mock database (shared with route.ts)
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

// PUT - Update user
export async function PUT(request: NextRequest, { params }: { params: Promise<{ id: string }> }) {
  try {
    const { id } = await params
    const body = await request.json()
    const { nombre, apellido, email, rol, estado } = body

    // Find user
    const userIndex = MOCK_USERS.findIndex((u) => u.id === id)
    if (userIndex === -1) {
      return NextResponse.json({ message: "Usuario no encontrado" }, { status: 404 })
    }

    // Check if email is being changed and already exists
    if (email !== MOCK_USERS[userIndex].email && MOCK_USERS.some((u) => u.email === email)) {
      return NextResponse.json({ message: "El email ya está registrado" }, { status: 400 })
    }

    // Update user
    MOCK_USERS[userIndex] = {
      ...MOCK_USERS[userIndex],
      nombre,
      apellido,
      email,
      rol,
      estado,
    }

    return NextResponse.json({
      message: "Usuario actualizado exitosamente",
      user: MOCK_USERS[userIndex],
    })
  } catch (error) {
    console.error("Error updating user:", error)
    return NextResponse.json({ message: "Error al actualizar usuario" }, { status: 500 })
  }
}

// DELETE - Delete user
export async function DELETE(request: NextRequest, { params }: { params: Promise<{ id: string }> }) {
  try {
    const { id } = await params

    // Find user
    const userIndex = MOCK_USERS.findIndex((u) => u.id === id)
    if (userIndex === -1) {
      return NextResponse.json({ message: "Usuario no encontrado" }, { status: 404 })
    }

    // Remove user
    MOCK_USERS.splice(userIndex, 1)

    return NextResponse.json({
      message: "Usuario eliminado exitosamente",
    })
  } catch (error) {
    console.error("Error deleting user:", error)
    return NextResponse.json({ message: "Error al eliminar usuario" }, { status: 500 })
  }
}
