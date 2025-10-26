"use client"

import { useState } from "react"
import { Button } from "@/components/ui/button"
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog"
import { Alert, AlertDescription } from "@/components/ui/alert"
import { Loader2, AlertTriangle } from "lucide-react"
import type { Materia } from "@/lib/types"

interface DeleteMateriaDialogProps {
  open: boolean
  onClose: (refresh?: boolean) => void
  materia: Materia | null
}

export function DeleteMateriaDialog({ open, onClose, materia }: DeleteMateriaDialogProps) {
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState("")

  const handleDelete = async () => {
    if (!materia) return

    setLoading(true)
    setError("")

    try {
      const response = await fetch(`/api/materias/${materia.id}`, {
        method: "DELETE",
      })

      if (!response.ok) {
        const data = await response.json()
        throw new Error(data.message || "Error al eliminar materia")
      }

      onClose(true)
    } catch (err) {
      setError(err instanceof Error ? err.message : "Error al eliminar materia")
    } finally {
      setLoading(false)
    }
  }

  return (
    <Dialog open={open} onOpenChange={() => onClose()}>
      <DialogContent className="sm:max-w-[425px]">
        <DialogHeader>
          <DialogTitle className="flex items-center gap-2 text-destructive">
            <AlertTriangle className="h-5 w-5" />
            Eliminar Materia
          </DialogTitle>
          <DialogDescription>
            Esta acción no se puede deshacer. La materia será eliminada permanentemente del sistema.
          </DialogDescription>
        </DialogHeader>

        {error && (
          <Alert variant="destructive">
            <AlertDescription>{error}</AlertDescription>
          </Alert>
        )}

        {materia && (
          <div className="bg-muted p-4 rounded-lg">
            <p className="text-sm font-medium">
              {materia.codigo} - {materia.nombre}
            </p>
            <p className="text-sm text-muted-foreground">{materia.departamento}</p>
          </div>
        )}

        <DialogFooter>
          <Button type="button" variant="outline" onClick={() => onClose()} disabled={loading}>
            Cancelar
          </Button>
          <Button type="button" variant="destructive" onClick={handleDelete} disabled={loading}>
            {loading ? (
              <>
                <Loader2 className="mr-2 h-4 w-4 animate-spin" />
                Eliminando...
              </>
            ) : (
              "Eliminar"
            )}
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  )
}
