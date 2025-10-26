"use client"

import type React from "react"
import { useState } from "react"
import { useAuth } from "@/lib/auth-context"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Alert, AlertDescription } from "@/components/ui/alert"
import { Checkbox } from "@/components/ui/checkbox"
import { Eye, EyeOff, Loader2, LogIn } from "lucide-react"
import Image from "next/image"
import { User, Lock } from "lucide-react"


export function LoginForm() {
  const { login } = useAuth()
  const [showPassword, setShowPassword] = useState(false)
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState("")
  const [rememberMe, setRememberMe] = useState(false)
  const [formData, setFormData] = useState({
    email: "",
    password: "",
  })

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setLoading(true)
    setError("")

    try {
      await login(formData.email, formData.password)
    } catch (err) {
      console.error("[v0] LoginForm - Login failed:", err)
      setError(err instanceof Error ? err.message : "Error al iniciar sesión")
      setLoading(false)
    }
  }

  return (
    <div className="w-full max-w-md">
      <div className="text-center mb-8">
        <div className="inline-flex items-center justify-center w-24 h-24 bg-primary/5 rounded-full mb-4 p-3">
          <Image src="/logoFicct.png" alt="Logo FICCT" width={80} height={80} className="object-contain" />
        </div>
        <h1 className="text-3xl font-bold text-foreground mb-2">FICCT</h1>
        <p className="text-sm text-muted-foreground leading-relaxed">
          Facultad de Ingeniería en Ciencias de la
          <br />
          Computación y Telecomunicaciones
        </p>
      </div>

      <form onSubmit={handleSubmit} className="space-y-5">
        {error && (
          <Alert variant="destructive">
            <AlertDescription>{error}</AlertDescription>
          </Alert>
        )}

        <div className="space-y-2">
          <Label htmlFor="email" className="flex items-center gap-2 text-sm font-medium">
            <div className="w-5 h-5 bg-primary/10 rounded flex items-center justify-center">
              <User/>
            </div>
            Registro
          </Label>
          <Input
            id="email"
            type="text"
            placeholder="218100001"
            value={formData.email}
            onChange={(e) => setFormData({ ...formData, email: e.target.value })}
            className="h-12"
            required
          />
        </div>

        <div className="space-y-2">
          <Label htmlFor="password" className="flex items-center gap-2 text-sm font-medium">
            <div className="w-5 h-5 bg-primary/10 rounded flex items-center justify-center">
              <Lock/>
            </div>
            Contraseña
          </Label>
          <div className="relative">
            <Input
              id="password"
              type={showPassword ? "text" : "password"}
              placeholder="••••••••"
              value={formData.password}
              onChange={(e) => setFormData({ ...formData, password: e.target.value })}
              className="h-12 pr-10"
              required
            />
            <button
              type="button"
              onClick={() => setShowPassword(!showPassword)}
              className="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground transition-colors"
            >
              {showPassword ? <EyeOff className="w-5 h-5" /> : <Eye className="w-5 h-5" />}
            </button>
          </div>
        </div>

        <div className="flex items-center justify-between">
          <div className="flex items-center gap-2">
            <Checkbox
              id="remember"
              checked={rememberMe}
              onCheckedChange={(checked) => setRememberMe(checked as boolean)}
            />
            <Label htmlFor="remember" className="text-sm font-normal cursor-pointer">
              Recordar sesión
            </Label>
          </div>
          <a href="#" className="text-sm text-primary hover:underline">
            ¿Olvidaste tu contraseña?
          </a>
        </div>

        <Button type="submit" className="w-full h-12 text-base" disabled={loading}>
          {loading ? (
            <>
              <Loader2 className="mr-2 h-5 w-5 animate-spin" />
              Iniciando sesión...
            </>
          ) : (
            <>
              <LogIn className="mr-2 h-5 w-5" />
              Iniciar Sesión
            </>
          )}
        </Button>

        <div className="text-xs text-muted-foreground text-center space-y-1 pt-2 border-t">
          <p className="font-medium">Credenciales de prueba:</p>
          <p>Admin: admin@universidad.edu / admin123</p>
          <p>Director: director@universidad.edu / director123</p>
        </div>
      </form>
    </div>
  )
}