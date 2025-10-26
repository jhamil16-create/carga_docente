"use client"

import { useState, useEffect } from "react"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table"
import { Badge } from "@/components/ui/badge"
import { Plus, Search, Pencil, Trash2 } from "lucide-react"
import { GrupoDialog } from "./grupo-dialog"
import { DeleteGrupoDialog } from "./delete-grupo-dialog"
import type { Grupo } from "@/lib/types"

interface GrupoWithDetails extends Grupo {
  materiaNombre?: string
  docenteNombre?: string
}

export function GruposTable() {
  const [grupos, setGrupos] = useState<GrupoWithDetails[]>([])
  const [loading, setLoading] = useState(true)
  const [searchTerm, setSearchTerm] = useState("")
  const [dialogOpen, setDialogOpen] = useState(false)
  const [deleteDialogOpen, setDeleteDialogOpen] = useState(false)
  const [selectedGrupo, setSelectedGrupo] = useState<Grupo | null>(null)

  useEffect(() => {
    fetchGrupos()
  }, [])

  const fetchGrupos = async () => {
    try {
      const response = await fetch("/api/grupos")
      const data = await response.json()
      setGrupos(data.grupos)
    } catch (error) {
      console.error("Error fetching grupos:", error)
    } finally {
      setLoading(false)
    }
  }

  const handleEdit = (grupo: Grupo) => {
    setSelectedGrupo(grupo)
    setDialogOpen(true)
  }

  const handleDelete = (grupo: Grupo) => {
    setSelectedGrupo(grupo)
    setDeleteDialogOpen(true)
  }

  const handleDialogClose = (refresh?: boolean) => {
    setDialogOpen(false)
    setSelectedGrupo(null)
    if (refresh) {
      fetchGrupos()
    }
  }

  const handleDeleteDialogClose = (refresh?: boolean) => {
    setDeleteDialogOpen(false)
    setSelectedGrupo(null)
    if (refresh) {
      fetchGrupos()
    }
  }

  const filteredGrupos = grupos.filter(
    (grupo) =>
      grupo.codigo.toLowerCase().includes(searchTerm.toLowerCase()) ||
      grupo.materiaNombre?.toLowerCase().includes(searchTerm.toLowerCase()) ||
      grupo.docenteNombre?.toLowerCase().includes(searchTerm.toLowerCase()) ||
      grupo.semestre.toLowerCase().includes(searchTerm.toLowerCase()),
  )

  if (loading) {
    return <div className="text-center py-8 text-muted-foreground">Cargando grupos...</div>
  }

  return (
    <div className="space-y-4">
      <div className="flex items-center justify-between gap-4">
        <div className="relative flex-1 max-w-sm">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
          <Input
            placeholder="Buscar grupos..."
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            className="pl-9"
          />
        </div>
        <Button onClick={() => setDialogOpen(true)}>
          <Plus className="mr-2 h-4 w-4" />
          Nuevo Grupo
        </Button>
      </div>

      <div className="border rounded-lg">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>Código</TableHead>
              <TableHead>Materia</TableHead>
              <TableHead>Docente</TableHead>
              <TableHead>Capacidad</TableHead>
              <TableHead>Horario</TableHead>
              <TableHead>Semestre</TableHead>
              <TableHead className="text-right">Acciones</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            {filteredGrupos.length === 0 ? (
              <TableRow>
                <TableCell colSpan={7} className="text-center py-8 text-muted-foreground">
                  No se encontraron grupos
                </TableCell>
              </TableRow>
            ) : (
              filteredGrupos.map((grupo) => (
                <TableRow key={grupo.id}>
                  <TableCell className="font-medium">{grupo.codigo}</TableCell>
                  <TableCell>{grupo.materiaNombre || "N/A"}</TableCell>
                  <TableCell>{grupo.docenteNombre || "N/A"}</TableCell>
                  <TableCell>{grupo.capacidad}</TableCell>
                  <TableCell>{grupo.horario}</TableCell>
                  <TableCell>
                    <Badge>{grupo.semestre}</Badge>
                  </TableCell>
                  <TableCell className="text-right">
                    <div className="flex justify-end gap-2">
                      <Button variant="ghost" size="icon" onClick={() => handleEdit(grupo)}>
                        <Pencil className="h-4 w-4" />
                      </Button>
                      <Button variant="ghost" size="icon" onClick={() => handleDelete(grupo)}>
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

      <GrupoDialog open={dialogOpen} onClose={handleDialogClose} grupo={selectedGrupo} />

      <DeleteGrupoDialog open={deleteDialogOpen} onClose={handleDeleteDialogClose} grupo={selectedGrupo} />
    </div>
  )
}
