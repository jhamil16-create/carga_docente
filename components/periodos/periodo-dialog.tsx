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
import type { GestionAcademica } from "@/lib/types"

interface PeriodoDialogProps {
  open: boolean
  onClose: (refresh?: boolean) => void
  periodo: GestionAcademica | null
}

export function PeriodoDialog({ open, onClose, periodo }: PeriodoDialogProps) {
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState("")
  const [formData, setFormData] = useState({
    nombre: "",
    fechaInicio: "",
    fechaFin: "",
    estado: "planificada" as "activa" | "finalizada" | "planificada",
    semestre: "1" as "1" | "2",
    año: new Date().getFullYear(),
  })

  useEffect(() => {
    if (periodo) {
      setFormData({
        nombre: periodo.nombre,
        fechaInicio: periodo.fechaInicio.split("T")[0],
        fechaFin: periodo.fechaFin.split("T")[0],
        estado: periodo.estado,
        semestre: periodo.semestre,
        año: periodo.año,
      })
    } else {
      setFormData({
        nombre: "",
        fechaInicio: "",
        fechaFin: "",
        estado: "planificada",
        semestre: "1",
        año: new Date().getFullYear(),
      })
    }
    setError("")
  }, [periodo, open])

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setLoading(true)
    setError("")

    try {
      // Validate dates
      const inicio = new Date(formData.fechaInicio)
      const fin = new Date(formData.fechaFin)

      if (fin <= inicio) {
        throw new Error("La fecha de fin debe ser posterior a la fecha de inicio")
      }

      const url = periodo ? `/api/periodos/${periodo.id}` : "/api/periodos"
      const method = periodo ? "PUT" : "POST"

      const response = await fetch(url, {
        method,
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(formData),
      })

      if (!response.ok) {
        const data = await response.json()
        throw new Error(data.message || "Error al guardar periodo")
      }

      onClose(true)
    } catch (err) {
      setError(err instanceof Error ? err.message : "Error al guardar periodo")
    } finally {
      setLoading(false)
    }
  }

  return (
    <Dialog open={open} onOpenChange={() => onClose()}>
      <DialogContent className="sm:max-w-[600px]">
        <DialogHeader>
          <DialogTitle>{periodo ? "Editar Periodo Académico" : "Nuevo Periodo Académico"}</DialogTitle>
          <DialogDescription>
            {periodo
              ? "Modifique los datos del periodo académico"
              : "Complete los datos para crear un nuevo periodo académico"}
          </DialogDescription>
        </DialogHeader>

        <form onSubmit={handleSubmit} className="space-y-4">
          {error && (
            <Alert variant="destructive">
              <AlertDescription>{error}</AlertDescription>
            </Alert>
          )}

          <div className="space-y-2">
            <Label htmlFor="nombre">Nombre del Periodo</Label>
            <Input
              id="nombre"
              value={formData.nombre}
              onChange={(e) => setFormData({ ...formData, nombre: e.target.value })}
              placeholder="Ej: Periodo Académico 2024-1"
              required
            />
          </div>

          <div className="grid grid-cols-2 gap-4">
            <div className="space-y-2">
              <Label htmlFor="año">Año</Label>
              <Input
                id="año"
                type="number"
                min="2020"
                max="2050"
                value={formData.año}
                onChange={(e) => setFormData({ ...formData, año: Number(e.target.value) })}
                required
              />
            </div>

            <div className="space-y-2">
              <Label htmlFor="semestre">Semestre</Label>
              <Select
                value={formData.semestre}
                onValueChange={(value: "1" | "2") => setFormData({ ...formData, semestre: value })}
              >
                <SelectTrigger>
                  <SelectValue />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="1">Semestre 1</SelectItem>
                  <SelectItem value="2">Semestre 2</SelectItem>
                </SelectContent>
              </Select>
            </div>
          </div>

          <div className="grid grid-cols-2 gap-4">
            <div className="space-y-2">
              <Label htmlFor="fechaInicio">Fecha de Inicio</Label>
              <Input
                id="fechaInicio"
                type="date"
                value={formData.fechaInicio}
                onChange={(e) => setFormData({ ...formData, fechaInicio: e.target.value })}
                required
              />
            </div>

            <div className="space-y-2">
              <Label htmlFor="fechaFin">Fecha de Fin</Label>
              <Input
                id="fechaFin"
                type="date"
                value={formData.fechaFin}
                onChange={(e) => setFormData({ ...formData, fechaFin: e.target.value })}
                required
              />
            </div>
          </div>

          <div className="space-y-2">
            <Label htmlFor="estado">Estado</Label>
            <Select
              value={formData.estado}
              onValueChange={(value: "activa" | "finalizada" | "planificada") =>
                setFormData({ ...formData, estado: value })
              }
            >
              <SelectTrigger>
                <SelectValue />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="planificada">Planificada</SelectItem>
                <SelectItem value="activa">Activa</SelectItem>
                <SelectItem value="finalizada">Finalizada</SelectItem>
              </SelectContent>
            </Select>
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
