"use client"

import type React from "react"

import { useState, useEffect } from "react"
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Textarea } from "@/components/ui/textarea"
import { Checkbox } from "@/components/ui/checkbox"
import { useToast } from "@/hooks/use-toast"

const availablePermissions = [
  { id: "usuarios.crear", label: "Crear usuarios", category: "Usuarios" },
  { id: "usuarios.editar", label: "Editar usuarios", category: "Usuarios" },
  { id: "usuarios.eliminar", label: "Eliminar usuarios", category: "Usuarios" },
  { id: "usuarios.ver", label: "Ver usuarios", category: "Usuarios" },
  { id: "docentes.gestionar", label: "Gestionar docentes", category: "Docentes" },
  { id: "docentes.ver", label: "Ver docentes", category: "Docentes" },
  { id: "materias.gestionar", label: "Gestionar materias", category: "Materias" },
  { id: "materias.ver", label: "Ver materias", category: "Materias" },
  { id: "grupos.gestionar", label: "Gestionar grupos", category: "Grupos" },
  { id: "grupos.ver", label: "Ver grupos", category: "Grupos" },
  { id: "aulas.gestionar", label: "Gestionar aulas", category: "Aulas" },
  { id: "aulas.ver", label: "Ver aulas", category: "Aulas" },
  { id: "periodos.gestionar", label: "Gestionar periodos", category: "Periodos" },
  { id: "periodos.ver", label: "Ver periodos", category: "Periodos" },
  { id: "horario.ver", label: "Ver horarios", category: "Horarios" },
  { id: "asistencia.registrar", label: "Registrar asistencia", category: "Asistencia" },
  { id: "reportes.generar", label: "Generar reportes", category: "Reportes" },
]

interface RoleDialogProps {
  open: boolean
  onOpenChange: (open: boolean) => void
  role?: any
}

export function RoleDialog({ open, onOpenChange, role }: RoleDialogProps) {
  const { toast } = useToast()
  const [loading, setLoading] = useState(false)
  const [formData, setFormData] = useState({
    nombre: "",
    descripcion: "",
    permisos: [] as string[],
  })

  useEffect(() => {
    if (role) {
      setFormData({
        nombre: role.nombre,
        descripcion: role.descripcion,
        permisos: role.permisos,
      })
    } else {
      setFormData({
        nombre: "",
        descripcion: "",
        permisos: [],
      })
    }
  }, [role, open])

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setLoading(true)

    // Simulate API call
    setTimeout(() => {
      toast({
        title: role ? "Rol actualizado" : "Rol creado",
        description: `El rol "${formData.nombre}" ha sido ${role ? "actualizado" : "creado"} correctamente.`,
      })
      setLoading(false)
      onOpenChange(false)
    }, 1000)
  }

  const togglePermission = (permissionId: string) => {
    setFormData((prev) => ({
      ...prev,
      permisos: prev.permisos.includes(permissionId)
        ? prev.permisos.filter((p) => p !== permissionId)
        : [...prev.permisos, permissionId],
    }))
  }

  const groupedPermissions = availablePermissions.reduce(
    (acc, permission) => {
      if (!acc[permission.category]) {
        acc[permission.category] = []
      }
      acc[permission.category].push(permission)
      return acc
    },
    {} as Record<string, typeof availablePermissions>,
  )

  return (
    <Dialog open={open} onOpenChange={onOpenChange}>
      <DialogContent className="max-w-2xl max-h-[90vh] overflow-y-auto">
        <DialogHeader>
          <DialogTitle>{role ? "Editar Rol" : "Nuevo Rol"}</DialogTitle>
          <DialogDescription>
            {role ? "Modifica los datos del rol" : "Completa los datos para crear un nuevo rol"}
          </DialogDescription>
        </DialogHeader>
        <form onSubmit={handleSubmit}>
          <div className="space-y-4 py-4">
            <div className="space-y-2">
              <Label htmlFor="nombre">Nombre del Rol</Label>
              <Input
                id="nombre"
                value={formData.nombre}
                onChange={(e) => setFormData({ ...formData, nombre: e.target.value })}
                placeholder="Ej: Coordinador"
                required
              />
            </div>
            <div className="space-y-2">
              <Label htmlFor="descripcion">Descripción</Label>
              <Textarea
                id="descripcion"
                value={formData.descripcion}
                onChange={(e) => setFormData({ ...formData, descripcion: e.target.value })}
                placeholder="Describe las responsabilidades de este rol"
                rows={3}
              />
            </div>
            <div className="space-y-3">
              <Label>Permisos</Label>
              <div className="border rounded-lg p-4 space-y-4">
                {Object.entries(groupedPermissions).map(([category, permissions]) => (
                  <div key={category} className="space-y-2">
                    <h4 className="font-medium text-sm text-muted-foreground">{category}</h4>
                    <div className="grid grid-cols-2 gap-3">
                      {permissions.map((permission) => (
                        <div key={permission.id} className="flex items-center space-x-2">
                          <Checkbox
                            id={permission.id}
                            checked={formData.permisos.includes(permission.id)}
                            onCheckedChange={() => togglePermission(permission.id)}
                          />
                          <label
                            htmlFor={permission.id}
                            className="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 cursor-pointer"
                          >
                            {permission.label}
                          </label>
                        </div>
                      ))}
                    </div>
                  </div>
                ))}
              </div>
            </div>
          </div>
          <DialogFooter>
            <Button type="button" variant="outline" onClick={() => onOpenChange(false)}>
              Cancelar
            </Button>
            <Button type="submit" disabled={loading}>
              {loading ? "Guardando..." : role ? "Actualizar" : "Crear"}
            </Button>
          </DialogFooter>
        </form>
      </DialogContent>
    </Dialog>
  )
}
