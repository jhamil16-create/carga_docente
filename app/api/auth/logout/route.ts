import { NextResponse } from "next/server"

export async function POST() {
  try {
    // Create response
    const response = NextResponse.json({
      message: "Logout exitoso",
    })

    // Clear the auth cookie
    response.cookies.set("auth_token", "", {
      httpOnly: true,
      secure: process.env.NODE_ENV === "production",
      sameSite: "lax",
      maxAge: 0, // Expire immediately
      path: "/",
    })

    return response
  } catch (error) {
    console.error("Logout error:", error)
    return NextResponse.json({ message: "Error interno del servidor" }, { status: 500 })
  }
}
