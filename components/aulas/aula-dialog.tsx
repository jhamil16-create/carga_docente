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
import type { Aula } from "@/lib/types"

interface AulaDialogProps {
  open: boolean
  onClose: (refresh?: boolean) => void
  aula: Aula | null
}

export function AulaDialog({ open, onClose, aula }: AulaDialogProps) {
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState("")
  const [formData, setFormData] = useState({
    codigo: "",
    nombre: "",
    capacidad: 0,
    tipo: "teorica" as "teorica" | "laboratorio" | "auditorio",
    edificio: "",
    piso: 1,
    estado: "disponible" as "disponible" | "ocupada" | "mantenimiento",
  })

  useEffect(() => {
    if (aula) {
      setFormData({
        codigo: aula.codigo,
        nombre: aula.nombre,
        capacidad: aula.capacidad,
        tipo: aula.tipo,
        edificio: aula.edificio,
        piso: aula.piso,
        estado: aula.estado,
      })
    } else {
      setFormData({
        codigo: "",
        nombre: "",
        capacidad: 0,
        tipo: "teorica",
        edificio: "",
        piso: 1,
        estado: "disponible",
      })
    }
    setError("")
  }, [aula, open])

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setLoading(true)
    setError("")

    try {
      const url = aula ? `/api/aulas/${aula.id}` : "/api/aulas"
      const method = aula ? "PUT" : "POST"

      const response = await fetch(url, {
        method,
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(formData),
      })

      if (!response.ok) {
        const data = await response.json()
        throw new Error(data.message || "Error al guardar aula")
      }

      onClose(true)
    } catch (err) {
      setError(err instanceof Error ? err.message : "Error al guardar aula")
    } finally {
      setLoading(false)
    }
  }

  return (
    <Dialog open={open} onOpenChange={() => onClose()}>
      <DialogContent className="sm:max-w-[600px]">
        <DialogHeader>
          <DialogTitle>{aula ? "Editar Aula" : "Nueva Aula"}</DialogTitle>
          <DialogDescription>
            {aula ? "Modifique los datos del aula" : "Complete los datos para registrar una nueva aula"}
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
                placeholder="Ej: A101"
                required
              />
            </div>

            <div className="space-y-2">
              <Label htmlFor="tipo">Tipo de Aula</Label>
              <Select
                value={formData.tipo}
                onValueChange={(value: "teorica" | "laboratorio" | "auditorio") =>
                  setFormData({ ...formData, tipo: value })
                }
              >
                <SelectTrigger>
                  <SelectValue />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="teorica">Teórica</SelectItem>
                  <SelectItem value="laboratorio">Laboratorio</SelectItem>
                  <SelectItem value="auditorio">Auditorio</SelectItem>
                </SelectContent>
              </Select>
            </div>
          </div>

          <div className="space-y-2">
            <Label htmlFor="nombre">Nombre del Aula</Label>
            <Input
              id="nombre"
              value={formData.nombre}
              onChange={(e) => setFormData({ ...formData, nombre: e.target.value })}
              placeholder="Ej: Aula de Matemáticas"
              required
            />
          </div>

          <div className="grid grid-cols-3 gap-4">
            <div className="space-y-2">
              <Label htmlFor="capacidad">Capacidad</Label>
              <Input
                id="capacidad"
                type="number"
                min="1"
                max="500"
                value={formData.capacidad}
                onChange={(e) => setFormData({ ...formData, capacidad: Number(e.target.value) })}
                required
              />
            </div>

            <div className="space-y-2">
              <Label htmlFor="edificio">Edificio</Label>
              <Input
                id="edificio"
                value={formData.edificio}
                onChange={(e) => setFormData({ ...formData, edificio: e.target.value })}
                placeholder="Ej: A, B, C"
                required
              />
            </div>

            <div className="space-y-2">
              <Label htmlFor="piso">Piso</Label>
              <Input
                id="piso"
                type="number"
                min="0"
                max="20"
                value={formData.piso}
                onChange={(e) => setFormData({ ...formData, piso: Number(e.target.value) })}
                required
              />
            </div>
          </div>

          <div className="space-y-2">
            <Label htmlFor="estado">Estado</Label>
            <Select
              value={formData.estado}
              onValueChange={(value: "disponible" | "ocupada" | "mantenimiento") =>
                setFormData({ ...formData, estado: value })
              }
            >
              <SelectTrigger>
                <SelectValue />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="disponible">Disponible</SelectItem>
                <SelectItem value="ocupada">Ocupada</SelectItem>
                <SelectItem value="mantenimiento">Mantenimiento</SelectItem>
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
