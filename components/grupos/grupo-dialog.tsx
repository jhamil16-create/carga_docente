"use client"

import type React from "react"

import { useState, useEffect } from "react"
import { Button } from "@/components/ui/button"
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select"
import { Alert, AlertDescription } from "@/components/ui/alert"
import { Loader2 } from "lucide-react"
import type { Grupo, Materia, Docente } from "@/lib/types"

interface GrupoDialogProps {
  open: boolean
  onClose: (refresh?: boolean) => void
  grupo: Grupo | null
}

export function GrupoDialog({ open, onClose, grupo }: GrupoDialogProps) {
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState("")
  const [materias, setMaterias] = useState<Materia[]>([])
  const [docentes, setDocentes] = useState<Docente[]>([])
  const [formData, setFormData] = useState({
    codigo: "",
    materiaId: "",
    docenteId: "",
    capacidad: 0,
    horario: "",
    semestre: "",
  })

  useEffect(() => {
    if (open) {
      fetchMaterias()
      fetchDocentes()
    }
  }, [open])

  useEffect(() => {
    if (grupo) {
      setFormData({
        codigo: grupo.codigo,
        materiaId: grupo.materiaId,
        docenteId: grupo.docenteId,
        capacidad: grupo.capacidad,
        horario: grupo.horario,
        semestre: grupo.semestre,
      })
    } else {
      setFormData({
        codigo: "",
        materiaId: "",
        docenteId: "",
        capacidad: 0,
        horario: "",
        semestre: "",
      })
    }
    setError("")
  }, [grupo, open])

  const fetchMaterias = async () => {
    try {
      const response = await fetch("/api/materias")
      const data = await response.json()
      setMaterias(data.materias.filter((m: Materia) => m.estado === "activa"))
    } catch (error) {
      console.error("Error fetching materias:", error)
    }
  }

  const fetchDocentes = async () => {
    try {
      const response = await fetch("/api/docentes")
      const data = await response.json()
      setDocentes(data.docentes.filter((d: Docente) => d.estado === "activo"))
    } catch (error) {
      console.error("Error fetching docentes:", error)
    }
  }

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setLoading(true)
    setError("")

    try {
      const url = grupo ? `/api/grupos/${grupo.id}` : "/api/grupos"
      const method = grupo ? "PUT" : "POST"

      const response = await fetch(url, {
        method,
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(formData),
      })

      if (!response.ok) {
        const data = await response.json()
        throw new Error(data.message || "Error al guardar grupo")
      }

      onClose(true)
    } catch (err) {
      setError(err instanceof Error ? err.message : "Error al guardar grupo")
    } finally {
      setLoading(false)
    }
  }

  return (
    <Dialog open={open} onOpenChange={() => onClose()}>
      <DialogContent className="sm:max-w-[600px]">
        <DialogHeader>
          <DialogTitle>{grupo ? "Editar Grupo" : "Nuevo Grupo"}</DialogTitle>
          <DialogDescription>
            {grupo ? "Modifique los datos del grupo" : "Complete los datos para crear un nuevo grupo"}
          </DialogDescription>
        </DialogHeader>

        <form onSubmit={handleSubmit} className="space-y-4">
          {error && (
            <Alert variant="destructive">
              <AlertDescription>{error}</AlertDescription>
            </Alert>
          )}

          <div className="grid grid-cols-2 gap-4">
            <div className="space-y-2">
              <Label htmlFor="codigo">Código del Grupo</Label>
              <Input
                id="codigo"
                value={formData.codigo}
                onChange={(e) => setFormData({ ...formData, codigo: e.target.value })}
                placeholder="Ej: G01"
                required
              />
            </div>

            <div className="space-y-2">
              <Label htmlFor="semestre">Semestre</Label>
              <Input
                id="semestre"
                value={formData.semestre}
                onChange={(e) => setFormData({ ...formData, semestre: e.target.value })}
                placeholder="Ej: 2024-1"
                required
              />
            </div>
          </div>

          <div className="space-y-2">
            <Label htmlFor="materiaId">Materia</Label>
            <Select
              value={formData.materiaId}
              onValueChange={(value) => setFormData({ ...formData, materiaId: value })}
            >
              <SelectTrigger>
                <SelectValue placeholder="Seleccione una materia" />
              </SelectTrigger>
              <SelectContent>
                {materias.map((materia) => (
                  <SelectItem key={materia.id} value={materia.id}>
                    {materia.codigo} - {materia.nombre}
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>
          </div>

          <div className="space-y-2">
            <Label htmlFor="docenteId">Docente</Label>
            <Select
              value={formData.docenteId}
              onValueChange={(value) => setFormData({ ...formData, docenteId: value })}
            >
              <SelectTrigger>
                <SelectValue placeholder="Seleccione un docente" />
              </SelectTrigger>
              <SelectContent>
                {docentes.map((docente) => (
                  <SelectItem key={docente.id} value={docente.id}>
                    {docente.nombre} {docente.apellido} - {docente.especialidad}
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>
          </div>

          <div className="grid grid-cols-2 gap-4">
            <div className="space-y-2">
              <Label htmlFor="capacidad">Capacidad</Label>
              <Input
                id="capacidad"
                type="number"
                min="1"
                max="100"
                value={formData.capacidad}
                onChange={(e) => setFormData({ ...formData, capacidad: Number(e.target.value) })}
                required
              />
            </div>

            <div className="space-y-2">
              <Label htmlFor="horario">Horario</Label>
              <Input
                id="horario"
                value={formData.horario}
                onChange={(e) => setFormData({ ...formData, horario: e.target.value })}
                placeholder="Ej: Lun-Mie 8:00-10:00"
                required
              />
            </div>
          </div>

          <DialogFooter>
            <Button type="button" variant="outline" onClick={() => onClose()} disabled={loading}>
              Cancelar
            </Button>
            <Button type="submit" disabled={loading}>
              {loading ? (
                <>
                  <Loader2 className="mr-2 h-4 w-4 animate-spin" />
                  Guardando...
                </>
              ) : (
                "Guardar"
              )}
            </Button>
          </DialogFooter>
        </form>
      </DialogContent>
    </Dialog>
  )
}
