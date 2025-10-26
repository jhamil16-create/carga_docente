"use client"

import type React from "react"
import { createContext, useContext, useState, useEffect } from "react"
import { useRouter } from "next/navigation"
import type { User } from "./types"

interface AuthContextType {
  user: User | null
  loading: boolean
  login: (email: string, password: string) => Promise<void>
  logout: () => void
  isAuthenticated: boolean
}

const AuthContext = createContext<AuthContextType | undefined>(undefined)

export function AuthProvider({ children }: { children: React.ReactNode }) {
  const [user, setUser] = useState<User | null>(null)
  const [loading, setLoading] = useState(true)
  const router = useRouter()

  // Check for existing session on mount
  useEffect(() => {
    const checkAuth = () => {
      console.log("[v0] AuthContext - Checking authentication")
      const authToken = localStorage.getItem("auth_token")
      const userEmail = localStorage.getItem("user_email")
      const userRole = localStorage.getItem("user_role")

      console.log("[v0] AuthContext - Token exists:", !!authToken)
      console.log("[v0] AuthContext - User email:", userEmail)

      if (authToken && userEmail && userRole) {
        // Reconstruct user object from localStorage
        setUser({
          id: "1",
          email: userEmail,
          nombre: "Usuario",
          apellido: "Demo",
          rol: userRole as "admin" | "director",
          estado: "activo",
          fechaCreacion: new Date().toISOString(),
        })
        console.log("[v0] AuthContext - User authenticated from localStorage")
      }

      setLoading(false)
    }

    checkAuth()
  }, [])

  const login = async (email: string, password: string) => {
    try {
      console.log("[v0] AuthContext - Attempting login for:", email)

      // Call the login API
      const response = await fetch("/api/auth/login", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ email, password }),
      })

      console.log("[v0] AuthContext - Login response status:", response.status)

      if (!response.ok) {
        const error = await response.json()
        throw new Error(error.message || "Error al iniciar sesión")
      }

      const data = await response.json()
      console.log("[v0] AuthContext - Login successful, user:", data.user.email)

      // Store auth data
      localStorage.setItem("auth_token", data.token)
      localStorage.setItem("user_email", data.user.email)
      localStorage.setItem("user_role", data.user.rol)

      // Set user state
      setUser(data.user)

      console.log("[v0] AuthContext - Redirecting to dashboard")

      window.location.href = "/dashboard"
    } catch (error) {
      console.error("[v0] AuthContext - Login error:", error)
      throw error
    }
  }

  const logout = async () => {
    console.log("[v0] AuthContext - Logging out")

    try {
      // Call logout API to clear cookies
      await fetch("/api/auth/logout", {
        method: "POST",
      })
    } catch (error) {
      console.error("[v0] AuthContext - Logout API error:", error)
    }

    // Clear auth data from localStorage
    localStorage.removeItem("auth_token")
    localStorage.removeItem("user_email")
    localStorage.removeItem("user_role")

    // Clear user state
    setUser(null)

    // Redirect to login
    window.location.href = "/login"
  }

  const value = {
    user,
    loading,
    login,
    logout,
    isAuthenticated: !!user,
  }

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>
}

export function useAuth() {
  const context = useContext(AuthContext)
  if (context === undefined) {
    throw new Error("useAuth must be used within an AuthProvider")
  }
  return context
}
