"use client"

import { useState, useEffect } from "react"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table"
import { Badge } from "@/components/ui/badge"
import { Plus, Search, Pencil, Trash2 } from "lucide-react"
import { DocenteDialog } from "./docente-dialog"
import { DeleteDocenteDialog } from "./delete-docente-dialog"
import type { Docente } from "@/lib/types"

export function DocentesTable() {
  const [docentes, setDocentes] = useState<Docente[]>([])
  const [loading, setLoading] = useState(true)
  const [searchTerm, setSearchTerm] = useState("")
  const [dialogOpen, setDialogOpen] = useState(false)
  const [deleteDialogOpen, setDeleteDialogOpen] = useState(false)
  const [selectedDocente, setSelectedDocente] = useState<Docente | null>(null)

  useEffect(() => {
    fetchDocentes()
  }, [])

  const fetchDocentes = async () => {
    try {
      const response = await fetch("/api/docentes")
      const data = await response.json()
      setDocentes(data.docentes)
    } catch (error) {
      console.error("Error fetching docentes:", error)
    } finally {
      setLoading(false)
    }
  }

  const handleEdit = (docente: Docente) => {
    setSelectedDocente(docente)
    setDialogOpen(true)
  }

  const handleDelete = (docente: Docente) => {
    setSelectedDocente(docente)
    setDeleteDialogOpen(true)
  }

  const handleDialogClose = (refresh?: boolean) => {
    setDialogOpen(false)
    setSelectedDocente(null)
    if (refresh) {
      fetchDocentes()
    }
  }

  const handleDeleteDialogClose = (refresh?: boolean) => {
    setDeleteDialogOpen(false)
    setSelectedDocente(null)
    if (refresh) {
      fetchDocentes()
    }
  }

  const filteredDocentes = docentes.filter(
    (docente) =>
      docente.nombre.toLowerCase().includes(searchTerm.toLowerCase()) ||
      docente.apellido.toLowerCase().includes(searchTerm.toLowerCase()) ||
      docente.email.toLowerCase().includes(searchTerm.toLowerCase()) ||
      docente.especialidad.toLowerCase().includes(searchTerm.toLowerCase()),
  )

  if (loading) {
    return <div className="text-center py-8 text-muted-foreground">Cargando docentes...</div>
  }

  return (
    <div className="space-y-4">
      <div className="flex items-center justify-between gap-4">
        <div className="relative flex-1 max-w-sm">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
          <Input
            placeholder="Buscar docentes..."
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            className="pl-9"
          />
        </div>
        <Button onClick={() => setDialogOpen(true)}>
          <Plus className="mr-2 h-4 w-4" />
          Nuevo Docente
        </Button>
      </div>

      <div className="border rounded-lg">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>Nombre</TableHead>
              <TableHead>Email</TableHead>
              <TableHead>Teléfono</TableHead>
              <TableHead>Especialidad</TableHead>
              <TableHead>Carga Horaria</TableHead>
              <TableHead>Estado</TableHead>
              <TableHead className="text-right">Acciones</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            {filteredDocentes.length === 0 ? (
              <TableRow>
                <TableCell colSpan={7} className="text-center py-8 text-muted-foreground">
                  No se encontraron docentes
                </TableCell>
              </TableRow>
            ) : (
              filteredDocentes.map((docente) => (
                <TableRow key={docente.id}>
                  <TableCell className="font-medium">
                    {docente.nombre} {docente.apellido}
                  </TableCell>
                  <TableCell>{docente.email}</TableCell>
                  <TableCell>{docente.telefono}</TableCell>
                  <TableCell>{docente.especialidad}</TableCell>
                  <TableCell>{docente.cargaHoraria}h</TableCell>
                  <TableCell>
                    <Badge variant={docente.estado === "activo" ? "default" : "secondary"}>
                      {docente.estado === "activo" ? "Activo" : "Inactivo"}
                    </Badge>
                  </TableCell>
                  <TableCell className="text-right">
                    <div className="flex justify-end gap-2">
                      <Button variant="ghost" size="icon" onClick={() => handleEdit(docente)}>
                        <Pencil className="h-4 w-4" />
                      </Button>
                      <Button variant="ghost" size="icon" onClick={() => handleDelete(docente)}>
                        <Trash2 className="h-4 w-4 text-destructive" />
                      </Button>
                    </div>
                  </TableCell>
                </TableRow>
              ))
            )}
          </TableBody>
        </Table>
      </div>

      <DocenteDialog open={dialogOpen} onClose={handleDialogClose} docente={selectedDocente} />

      <DeleteDocenteDialog open={deleteDialogOpen} onClose={handleDeleteDialogClose} docente={selectedDocente} />
    </div>
  )
}
