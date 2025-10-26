"use client"

import { useState, useEffect } from "react"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table"
import { Badge } from "@/components/ui/badge"
import { Plus, Search, Pencil, Trash2 } from "lucide-react"
import { MateriaDialog } from "./materia-dialog"
import { DeleteMateriaDialog } from "./delete-materia-dialog"
import type { Materia } from "@/lib/types"

export function MateriasTable() {
  const [materias, setMaterias] = useState<Materia[]>([])
  const [loading, setLoading] = useState(true)
  const [searchTerm, setSearchTerm] = useState("")
  const [dialogOpen, setDialogOpen] = useState(false)
  const [deleteDialogOpen, setDeleteDialogOpen] = useState(false)
  const [selectedMateria, setSelectedMateria] = useState<Materia | null>(null)

  useEffect(() => {
    fetchMaterias()
  }, [])

  const fetchMaterias = async () => {
    try {
      const response = await fetch("/api/materias")
      const data = await response.json()
      setMaterias(data.materias)
    } catch (error) {
      console.error("Error fetching materias:", error)
    } finally {
      setLoading(false)
    }
  }

  const handleEdit = (materia: Materia) => {
    setSelectedMateria(materia)
    setDialogOpen(true)
  }

  const handleDelete = (materia: Materia) => {
    setSelectedMateria(materia)
    setDeleteDialogOpen(true)
  }

  const handleDialogClose = (refresh?: boolean) => {
    setDialogOpen(false)
    setSelectedMateria(null)
    if (refresh) {
      fetchMaterias()
    }
  }

  const handleDeleteDialogClose = (refresh?: boolean) => {
    setDeleteDialogOpen(false)
    setSelectedMateria(null)
    if (refresh) {
      fetchMaterias()
    }
  }

  const filteredMaterias = materias.filter(
    (materia) =>
      materia.nombre.toLowerCase().includes(searchTerm.toLowerCase()) ||
      materia.codigo.toLowerCase().includes(searchTerm.toLowerCase()) ||
      materia.departamento.toLowerCase().includes(searchTerm.toLowerCase()),
  )

  if (loading) {
    return <div className="text-center py-8 text-muted-foreground">Cargando materias...</div>
  }

  return (
    <div className="space-y-4">
      <div className="flex items-center justify-between gap-4">
        <div className="relative flex-1 max-w-sm">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
          <Input
            placeholder="Buscar materias..."
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            className="pl-9"
          />
        </div>
        <Button onClick={() => setDialogOpen(true)}>
          <Plus className="mr-2 h-4 w-4" />
          Nueva Materia
        </Button>
      </div>

      <div className="border rounded-lg">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>Código</TableHead>
              <TableHead>Nombre</TableHead>
              <TableHead>Departamento</TableHead>
              <TableHead>Créditos</TableHead>
              <TableHead>Horas/Semana</TableHead>
              <TableHead>Estado</TableHead>
              <TableHead className="text-right">Acciones</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            {filteredMaterias.length === 0 ? (
              <TableRow>
                <TableCell colSpan={7} className="text-center py-8 text-muted-foreground">
                  No se encontraron materias
                </TableCell>
              </TableRow>
            ) : (
              filteredMaterias.map((materia) => (
                <TableRow key={materia.id}>
                  <TableCell className="font-medium">{materia.codigo}</TableCell>
                  <TableCell>{materia.nombre}</TableCell>
                  <TableCell>{materia.departamento}</TableCell>
                  <TableCell>{materia.creditos}</TableCell>
                  <TableCell>{materia.horasSemanales}h</TableCell>
                  <TableCell>
                    <Badge variant={materia.estado === "activa" ? "default" : "secondary"}>
                      {materia.estado === "activa" ? "Activa" : "Inactiva"}
                    </Badge>
                  </TableCell>
                  <TableCell className="text-right">
                    <div className="flex justify-end gap-2">
                      <Button variant="ghost" size="icon" onClick={() => handleEdit(materia)}>
                        <Pencil className="h-4 w-4" />
                      </Button>
                      <Button variant="ghost" size="icon" onClick={() => handleDelete(materia)}>
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

      <MateriaDialog open={dialogOpen} onClose={handleDialogClose} materia={selectedMateria} />

      <DeleteMateriaDialog open={deleteDialogOpen} onClose={handleDeleteDialogClose} materia={selectedMateria} />
    </div>
  )
}
