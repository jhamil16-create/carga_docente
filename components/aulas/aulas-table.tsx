"use client"

import { useState, useEffect } from "react"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table"
import { Badge } from "@/components/ui/badge"
import { Plus, Search, Pencil, Trash2 } from "lucide-react"
import { AulaDialog } from "./aula-dialog"
import { DeleteAulaDialog } from "./delete-aula-dialog"
import type { Aula } from "@/lib/types"

export function AulasTable() {
  const [aulas, setAulas] = useState<Aula[]>([])
  const [loading, setLoading] = useState(true)
  const [searchTerm, setSearchTerm] = useState("")
  const [dialogOpen, setDialogOpen] = useState(false)
  const [deleteDialogOpen, setDeleteDialogOpen] = useState(false)
  const [selectedAula, setSelectedAula] = useState<Aula | null>(null)

  useEffect(() => {
    fetchAulas()
  }, [])

  const fetchAulas = async () => {
    try {
      const response = await fetch("/api/aulas")
      const data = await response.json()
      setAulas(data.aulas)
    } catch (error) {
      console.error("Error fetching aulas:", error)
    } finally {
      setLoading(false)
    }
  }

  const handleEdit = (aula: Aula) => {
    setSelectedAula(aula)
    setDialogOpen(true)
  }

  const handleDelete = (aula: Aula) => {
    setSelectedAula(aula)
    setDeleteDialogOpen(true)
  }

  const handleDialogClose = (refresh?: boolean) => {
    setDialogOpen(false)
    setSelectedAula(null)
    if (refresh) {
      fetchAulas()
    }
  }

  const handleDeleteDialogClose = (refresh?: boolean) => {
    setDeleteDialogOpen(false)
    setSelectedAula(null)
    if (refresh) {
      fetchAulas()
    }
  }

  const filteredAulas = aulas.filter(
    (aula) =>
      aula.codigo.toLowerCase().includes(searchTerm.toLowerCase()) ||
      aula.nombre.toLowerCase().includes(searchTerm.toLowerCase()) ||
      aula.edificio.toLowerCase().includes(searchTerm.toLowerCase()) ||
      aula.tipo.toLowerCase().includes(searchTerm.toLowerCase()),
  )

  const getEstadoBadgeVariant = (estado: string) => {
    switch (estado) {
      case "disponible":
        return "default"
      case "ocupada":
        return "secondary"
      case "mantenimiento":
        return "destructive"
      default:
        return "secondary"
    }
  }

  const getTipoBadgeVariant = (tipo: string) => {
    switch (tipo) {
      case "teorica":
        return "default"
      case "laboratorio":
        return "secondary"
      case "auditorio":
        return "outline"
      default:
        return "secondary"
    }
  }

  if (loading) {
    return <div className="text-center py-8 text-muted-foreground">Cargando aulas...</div>
  }

  return (
    <div className="space-y-4">
      <div className="flex items-center justify-between gap-4">
        <div className="relative flex-1 max-w-sm">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
          <Input
            placeholder="Buscar aulas..."
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            className="pl-9"
          />
        </div>
        <Button onClick={() => setDialogOpen(true)}>
          <Plus className="mr-2 h-4 w-4" />
          Nueva Aula
        </Button>
      </div>

      <div className="border rounded-lg">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>Código</TableHead>
              <TableHead>Nombre</TableHead>
              <TableHead>Tipo</TableHead>
              <TableHead>Capacidad</TableHead>
              <TableHead>Edificio</TableHead>
              <TableHead>Piso</TableHead>
              <TableHead>Estado</TableHead>
              <TableHead className="text-right">Acciones</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            {filteredAulas.length === 0 ? (
              <TableRow>
                <TableCell colSpan={8} className="text-center py-8 text-muted-foreground">
                  No se encontraron aulas
                </TableCell>
              </TableRow>
            ) : (
              filteredAulas.map((aula) => (
                <TableRow key={aula.id}>
                  <TableCell className="font-medium">{aula.codigo}</TableCell>
                  <TableCell>{aula.nombre}</TableCell>
                  <TableCell>
                    <Badge variant={getTipoBadgeVariant(aula.tipo)}>
                      {aula.tipo === "teorica" ? "Teórica" : aula.tipo === "laboratorio" ? "Laboratorio" : "Auditorio"}
                    </Badge>
                  </TableCell>
                  <TableCell>{aula.capacidad}</TableCell>
                  <TableCell>{aula.edificio}</TableCell>
                  <TableCell>{aula.piso}</TableCell>
                  <TableCell>
                    <Badge variant={getEstadoBadgeVariant(aula.estado)}>
                      {aula.estado === "disponible"
                        ? "Disponible"
                        : aula.estado === "ocupada"
                          ? "Ocupada"
                          : "Mantenimiento"}
                    </Badge>
                  </TableCell>
                  <TableCell className="text-right">
                    <div className="flex justify-end gap-2">
                      <Button variant="ghost" size="icon" onClick={() => handleEdit(aula)}>
                        <Pencil className="h-4 w-4" />
                      </Button>
                      <Button variant="ghost" size="icon" onClick={() => handleDelete(aula)}>
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

      <AulaDialog open={dialogOpen} onClose={handleDialogClose} aula={selectedAula} />

      <DeleteAulaDialog open={deleteDialogOpen} onClose={handleDeleteDialogClose} aula={selectedAula} />
    </div>
  )
}
