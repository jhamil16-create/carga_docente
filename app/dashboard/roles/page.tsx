"use client"

import { useState } from "react"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Plus } from "lucide-react"
import { RolesTable } from "@/components/roles/roles-table"
import { RoleDialog } from "@/components/roles/role-dialog"

export default function RolesPage() {
  const [isDialogOpen, setIsDialogOpen] = useState(false)
  const [selectedRole, setSelectedRole] = useState<any>(null)

  const handleEdit = (role: any) => {
    setSelectedRole(role)
    setIsDialogOpen(true)
  }

  const handleCloseDialog = () => {
    setIsDialogOpen(false)
    setSelectedRole(null)
  }

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-bold text-foreground">Roles y Permisos</h1>
          <p className="text-muted-foreground">Gestiona los roles y permisos del sistema</p>
        </div>
        <Button onClick={() => setIsDialogOpen(true)}>
          <Plus className="mr-2 h-4 w-4" />
          Nuevo Rol
        </Button>
      </div>

      <Card>
        <CardHeader>
          <CardTitle>Roles del Sistema</CardTitle>
          <CardDescription>Lista de roles y sus permisos asociados</CardDescription>
        </CardHeader>
        <CardContent>
          <RolesTable onEdit={handleEdit} />
        </CardContent>
      </Card>

      <RoleDialog open={isDialogOpen} onOpenChange={handleCloseDialog} role={selectedRole} />
    </div>
  )
}
