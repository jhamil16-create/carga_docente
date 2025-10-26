"use client"

import { GraduationCap, Bell, Settings, LogOut } from "lucide-react"
import { Button } from "@/components/ui/button"
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu"
import { Avatar, AvatarFallback } from "@/components/ui/avatar"
import { useAuth } from "@/lib/auth-context"
import { useRouter } from "next/navigation"

export function DashboardHeader() {
  const { user, logout } = useAuth()
  const router = useRouter()

  const initials = user ? `${user.nombre.charAt(0)}${user.apellido.charAt(0)}`.toUpperCase() : "AD"

  const handleLogout = () => {
    logout()
    window.location.href = "/login"
  }

  const handleSettings = () => {
    router.push("/dashboard/configuracion")
  }

  return (
    <header className="sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur supports-backdrop-filter:bg-background/60">
      <div className="flex h-16 items-center px-6">
        <div className="flex items-center gap-3">
          <div className="flex items-center justify-center w-10 h-10">
             <img
              src="/logoFicct.png"
              alt="Graduation Icon"
              className="w-10 h-12"
            />
          </div>
          <div>
            <h1 className="text-lg font-semibold text-foreground">Sistema de Gestión Académica</h1>
            <p className="text-xs text-muted-foreground">Universidad Nacional</p>
          </div>
        </div>

        <div className="ml-auto flex items-center gap-4">
          <Button variant="ghost" size="icon">
            <Bell className="h-5 w-5" />
          </Button>

          <DropdownMenu>
            <DropdownMenuTrigger asChild>
              <Button variant="ghost" className="relative h-10 w-10 rounded-full">
                <Avatar>
                  <AvatarFallback className="bg-primary text-primary-foreground">{initials}</AvatarFallback>
                </Avatar>
              </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end" className="w-56">
              <DropdownMenuLabel>
                <div className="flex flex-col space-y-1">
                  <p className="text-sm font-medium">{user ? `${user.nombre} ${user.apellido}` : "Usuario"}</p>
                  <p className="text-xs text-muted-foreground">{user?.email || "usuario@universidad.edu"}</p>
                  <p className="text-xs text-muted-foreground capitalize">Rol: {user?.rol || "N/A"}</p>
                </div>
              </DropdownMenuLabel>
              <DropdownMenuSeparator />
              <DropdownMenuItem onClick={handleSettings}>
                <Settings className="mr-2 h-4 w-4" />
                Configuración
              </DropdownMenuItem>
              <DropdownMenuSeparator />
              <DropdownMenuItem onClick={handleLogout} className="text-destructive">
                <LogOut className="mr-2 h-4 w-4" />
                Cerrar Sesión
              </DropdownMenuItem>
            </DropdownMenuContent>
          </DropdownMenu>
        </div>
      </div>
    </header>
  )
}
