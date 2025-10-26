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
import type { Materia } from "@/lib/types"

interface MateriaDialogProps {
  open: boolean
  onClose: (refresh?: boolean) => void
  materia: Materia | null
}

export function MateriaDialog({ open, onClose, materia }: MateriaDialogProps) {
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState("")
  const [formData, setFormData] = useState({
    codigo: "",
    nombre: "",
    creditos: 0,
    horasSemanales: 0,
    departamento: "",
    estado: "activa" as "activa" | "inactiva",
  })

  useEffect(() => {
    if (materia) {
      setFormData({
        codigo: materia.codigo,
        nombre: materia.nombre,
        creditos: materia.creditos,
        horasSemanales: materia.horasSemanales,
        departamento: materia.departamento,
        estado: materia.estado,
      })
    } else {
      setFormData({
        codigo: "",
        nombre: "",
        creditos: 0,
        horasSemanales: 0,
        departamento: "",
        estado: "activa",
      })
    }
    setError("")
  }, [materia, open])

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setLoading(true)
    setError("")

    try {
      const url = materia ? `/api/materias/${materia.id}` : "/api/materias"
      const method = materia ? "PUT" : "POST"

      const response = await fetch(url, {
        method,
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(formData),
      })

      if (!response.ok) {
        const data = await response.json()
        throw new Error(data.message || "Error al guardar materia")
      }

      onClose(true)
    } catch (err) {
      setError(err instanceof Error ? err.message : "Error al guardar materia")
    } finally {
      setLoading(false)
    }
  }

  return (
    <Dialog open={open} onOpenChange={() => onClose()}>
      <DialogContent className="sm:max-w-[600px]">
        <DialogHeader>
          <DialogTitle>{materia ? "Editar Materia" : "Nueva Materia"}</DialogTitle>
          <DialogDescription>
            {materia ? "Modifique los datos de la materia" : "Complete los datos para registrar una nueva materia"}
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
              <Label htmlFor="codigo">Código</Label>
              <Input
                id="codigo"
                value={formData.codigo}
                onChange={(e) => setFormData({ ...formData, codigo: e.target.value })}
                placeholder="Ej: MAT101"
                required
              />
            </div>

            <div className="space-y-2">
              <Label htmlFor="departamento">Departamento</Label>
              <Input
                id="departamento"
                value={formData.departamento}
                onChange={(e) => setFormData({ ...formData, departamento: e.target.value })}
                placeholder="Ej: Ciencias Exactas"
                required
              />
            </div>
          </div>

          <div className="space-y-2">
            <Label htmlFor="nombre">Nombre de la Materia</Label>
            <Input
              id="nombre"
              value={formData.nombre}
              onChange={(e) => setFormData({ ...formData, nombre: e.target.value })}
              placeholder="Ej: Cálculo Diferencial"
              required
            />
          </div>

          <div className="grid grid-cols-3 gap-4">
            <div className="space-y-2">
              <Label htmlFor="creditos">Créditos</Label>
              <Input
                id="creditos"
                type="number"
                min="0"
                max="10"
                value={formData.creditos}
                onChange={(e) => setFormData({ ...formData, creditos: Number(e.target.value) })}
                required
              />
            </div>

            <div className="space-y-2">
              <Label htmlFor="horasSemanales">Horas/Semana</Label>
              <Input
                id="horasSemanales"
                type="number"
                min="0"
                max="20"
                value={formData.horasSemanales}
                onChange={(e) => setFormData({ ...formData, horasSemanales: Number(e.target.value) })}
                required
              />
            </div>

            <div className="space-y-2">
              <Label htmlFor="estado">Estado</Label>
              <Select
                value={formData.estado}
                onValueChange={(value: "activa" | "inactiva") => setFormData({ ...formData, estado: value })}
              >
                <SelectTrigger>
                  <SelectValue />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="activa">Activa</SelectItem>
                  <SelectItem value="inactiva">Inactiva</SelectItem>
                </SelectContent>
              </Select>
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
