import { NextResponse } from "next/server"
import type { NextRequest } from "next/server"

// Mock user database (in production, this would be a real database)
const MOCK_USERS = [
  {
    id: "1",
    email: "admin@universidad.edu",
    password: "admin123", // In production, this would be hashed
    nombre: "Administrador",
    apellido: "Sistema",
    rol: "admin" as const,
    estado: "activo" as const,
    fechaCreacion: new Date().toISOString(),
  },
  {
    id: "2",
    email: "director@universidad.edu",
    password: "director123",
    nombre: "Director",
    apellido: "Académico",
    rol: "director" as const,
    estado: "activo" as const,
    fechaCreacion: new Date().toISOString(),
  },
]

export async function POST(request: NextRequest) {
  try {
    const body = await request.json()
    const { email, password } = body

    // Validate input
    if (!email || !password) {
      return NextResponse.json({ message: "Email y contraseña son requeridos" }, { status: 400 })
    }

    // Simulate database lookup
    await new Promise((resolve) => setTimeout(resolve, 500))

    // Find user
    const user = MOCK_USERS.find((u) => u.email === email && u.password === password)

    if (!user) {
      return NextResponse.json({ message: "Credenciales inválidas" }, { status: 401 })
    }

    // Generate mock token (in production, use JWT)
    const token = `token_${user.id}_${Date.now()}`

    // Remove password from response
    const { password: _, ...userWithoutPassword } = user

    // Create response with auth cookie
    const response = NextResponse.json({
      message: "Login exitoso",
      token,
      user: userWithoutPassword,
    })

    // Set HTTP-only cookie for security
    response.cookies.set("auth_token", token, {
      httpOnly: true,
      secure: process.env.NODE_ENV === "production",
      sameSite: "lax",
      maxAge: 60 * 60 * 24 * 7, // 7 days
      path: "/",
    })

    return response
  } catch (error) {
    console.error("Login error:", error)
    return NextResponse.json({ message: "Error interno del servidor" }, { status: 500 })
  }
}
