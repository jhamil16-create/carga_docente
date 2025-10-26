"use client"

import { useState } from "react"
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table"
import { Button } from "@/components/ui/button"
import { Badge } from "@/components/ui/badge"
import { Edit, Trash2 } from "lucide-react"
import { DeleteRoleDialog } from "./delete-role-dialog"
import { useToast } from "@/hooks/use-toast"

interface Role {
  id: string
  nombre: string
  descripcion: string
  permisos: string[]
  activo: boolean
}

const mockRoles: Role[] = [
  {
    id: "1",
    nombre: "Administrador",
    descripcion: "Acceso completo al sistema",
    permisos: [
      "usuarios.crear",
      "usuarios.editar",
      "usuarios.eliminar",
      "docentes.gestionar",
      "materias.gestionar",
      "grupos.gestionar",
      "aulas.gestionar",
      "periodos.gestionar",
      "reportes.generar",
    ],
    activo: true,
  },
  {
    id: "2",
    nombre: "Director",
    descripcion: "Gestión académica y reportes",
    permisos: [
      "docentes.ver",
      "materias.gestionar",
      "grupos.gestionar",
      "aulas.gestionar",
      "periodos.gestionar",
      "reportes.generar",
    ],
    activo: true,
  },
  {
    id: "3",
    nombre: "Docente",
    descripcion: "Acceso limitado a información personal",
    permisos: ["horario.ver", "asistencia.registrar", "materias.ver"],
    activo: true,
  },
]

interface RolesTableProps {
  onEdit: (role: Role) => void
}

export function RolesTable({ onEdit }: RolesTableProps) {
  const [roles, setRoles] = useState<Role[]>(mockRoles)
  const [deleteDialogOpen, setDeleteDialogOpen] = useState(false)
  const [roleToDelete, setRoleToDelete] = useState<Role | null>(null)
  const { toast } = useToast()

  const handleDelete = (role: Role) => {
    setRoleToDelete(role)
    setDeleteDialogOpen(true)
  }

  const confirmDelete = () => {
    if (roleToDelete) {
      setRoles(roles.filter((r) => r.id !== roleToDelete.id))
      toast({
        title: "Rol eliminado",
        description: `El rol "${roleToDelete.nombre}" ha sido eliminado correctamente.`,
      })
      setDeleteDialogOpen(false)
      setRoleToDelete(null)
    }
  }

  return (
    <>
      <Table>
        <TableHeader>
          <TableRow>
            <TableHead>Nombre</TableHead>
            <TableHead>Descripción</TableHead>
            <TableHead>Permisos</TableHead>
            <TableHead>Estado</TableHead>
            <TableHead className="text-right">Acciones</TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          {roles.map((role) => (
            <TableRow key={role.id}>
              <TableCell className="font-medium">{role.nombre}</TableCell>
              <TableCell>{role.descripcion}</TableCell>
              <TableCell>
                <div className="flex flex-wrap gap-1">
                  {role.permisos.slice(0, 3).map((permiso) => (
                    <Badge key={permiso} variant="secondary" className="text-xs">
                      {permiso}
                    </Badge>
                  ))}
                  {role.permisos.length > 3 && (
                    <Badge variant="outline" className="text-xs">
                      +{role.permisos.length - 3} más
                    </Badge>
                  )}
                </div>
              </TableCell>
              <TableCell>
                <Badge variant={role.activo ? "default" : "secondary"}>{role.activo ? "Activo" : "Inactivo"}</Badge>
              </TableCell>
              <TableCell className="text-right">
                <div className="flex justify-end gap-2">
                  <Button variant="ghost" size="icon" onClick={() => onEdit(role)}>
                    <Edit className="h-4 w-4" />
                  </Button>
                  <Button variant="ghost" size="icon" onClick={() => handleDelete(role)}>
                    <Trash2 className="h-4 w-4 text-destructive" />
                  </Button>
                </div>
              </TableCell>
            </TableRow>
          ))}
        </TableBody>
      </Table>

      <DeleteRoleDialog
        open={deleteDialogOpen}
        onOpenChange={setDeleteDialogOpen}
        onConfirm={confirmDelete}
        roleName={roleToDelete?.nombre || ""}
      />
    </>
  )
}
