"use client"

import { useState, useEffect } from "react"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table"
import { Badge } from "@/components/ui/badge"
import { Plus, Search, Pencil, Trash2 } from "lucide-react"
import { PeriodoDialog } from "./periodo-dialog"
import { DeletePeriodoDialog } from "./delete-periodo-dialog"
import type { GestionAcademica } from "@/lib/types"

export function PeriodosTable() {
  const [periodos, setPeriodos] = useState<GestionAcademica[]>([])
  const [loading, setLoading] = useState(true)
  const [searchTerm, setSearchTerm] = useState("")
  const [dialogOpen, setDialogOpen] = useState(false)
  const [deleteDialogOpen, setDeleteDialogOpen] = useState(false)
  const [selectedPeriodo, setSelectedPeriodo] = useState<GestionAcademica | null>(null)

  useEffect(() => {
    fetchPeriodos()
  }, [])

  const fetchPeriodos = async () => {
    try {
      const response = await fetch("/api/periodos")
      const data = await response.json()
      setPeriodos(data.periodos)
    } catch (error) {
      console.error("Error fetching periodos:", error)
    } finally {
      setLoading(false)
    }
  }

  const handleEdit = (periodo: GestionAcademica) => {
    setSelectedPeriodo(periodo)
    setDialogOpen(true)
  }

  const handleDelete = (periodo: GestionAcademica) => {
    setSelectedPeriodo(periodo)
    setDeleteDialogOpen(true)
  }

  const handleDialogClose = (refresh?: boolean) => {
    setDialogOpen(false)
    setSelectedPeriodo(null)
    if (refresh) {
      fetchPeriodos()
    }
  }

  const handleDeleteDialogClose = (refresh?: boolean) => {
    setDeleteDialogOpen(false)
    setSelectedPeriodo(null)
    if (refresh) {
      fetchPeriodos()
    }
  }

  const filteredPeriodos = periodos.filter(
    (periodo) =>
      periodo.nombre.toLowerCase().includes(searchTerm.toLowerCase()) ||
      periodo.año.toString().includes(searchTerm) ||
      periodo.semestre.includes(searchTerm),
  )

  const getEstadoBadgeVariant = (estado: string) => {
    switch (estado) {
      case "activa":
        return "default"
      case "planificada":
        return "secondary"
      case "finalizada":
        return "outline"
      default:
        return "secondary"
    }
  }

  if (loading) {
    return <div className="text-center py-8 text-muted-foreground">Cargando periodos...</div>
  }

  return (
    <div className="space-y-4">
      <div className="flex items-center justify-between gap-4">
        <div className="relative flex-1 max-w-sm">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
          <Input
            placeholder="Buscar periodos..."
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            className="pl-9"
          />
        </div>
        <Button onClick={() => setDialogOpen(true)}>
          <Plus className="mr-2 h-4 w-4" />
          Nuevo Periodo
        </Button>
      </div>

      <div className="border rounded-lg">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>Nombre</TableHead>
              <TableHead>Año</TableHead>
              <TableHead>Semestre</TableHead>
              <TableHead>Fecha Inicio</TableHead>
              <TableHead>Fecha Fin</TableHead>
              <TableHead>Estado</TableHead>
              <TableHead className="text-right">Acciones</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            {filteredPeriodos.length === 0 ? (
              <TableRow>
                <TableCell colSpan={7} className="text-center py-8 text-muted-foreground">
                  No se encontraron periodos académicos
                </TableCell>
              </TableRow>
            ) : (
              filteredPeriodos.map((periodo) => (
                <TableRow key={periodo.id}>
                  <TableCell className="font-medium">{periodo.nombre}</TableCell>
                  <TableCell>{periodo.año}</TableCell>
                  <TableCell>
                    <Badge variant="outline">Semestre {periodo.semestre}</Badge>
                  </TableCell>
                  <TableCell>{new Date(periodo.fechaInicio).toLocaleDateString()}</TableCell>
                  <TableCell>{new Date(periodo.fechaFin).toLocaleDateString()}</TableCell>
                  <TableCell>
                    <Badge variant={getEstadoBadgeVariant(periodo.estado)}>
                      {periodo.estado === "activa"
                        ? "Activa"
                        : periodo.estado === "planificada"
                          ? "Planificada"
                          : "Finalizada"}
                    </Badge>
                  </TableCell>
                  <TableCell className="text-right">
                    <div className="flex justify-end gap-2">
                      <Button variant="ghost" size="icon" onClick={() => handleEdit(periodo)}>
                        <Pencil className="h-4 w-4" />
                      </Button>
                      <Button variant="ghost" size="icon" onClick={() => handleDelete(periodo)}>
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

      <PeriodoDialog open={dialogOpen} onClose={handleDialogClose} periodo={selectedPeriodo} />

      <DeletePeriodoDialog open={deleteDialogOpen} onClose={handleDeleteDialogClose} periodo={selectedPeriodo} />
    </div>
  )
}
