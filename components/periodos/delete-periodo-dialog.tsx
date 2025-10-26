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
import type { GestionAcademica } from "@/lib/types"

interface DeletePeriodoDialogProps {
  open: boolean
  onClose: (refresh?: boolean) => void
  periodo: GestionAcademica | null
}

export function DeletePeriodoDialog({ open, onClose, periodo }: DeletePeriodoDialogProps) {
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState("")

  const handleDelete = async () => {
    if (!periodo) return

    setLoading(true)
    setError("")

    try {
      const response = await fetch(`/api/periodos/${periodo.id}`, {
        method: "DELETE",
      })

      if (!response.ok) {
        const data = await response.json()
        throw new Error(data.message || "Error al eliminar periodo")
      }

      onClose(true)
    } catch (err) {
      setError(err instanceof Error ? err.message : "Error al eliminar periodo")
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
            Eliminar Periodo Académico
          </DialogTitle>
          <DialogDescription>
            Esta acción no se puede deshacer. El periodo académico será eliminado permanentemente del sistema.
          </DialogDescription>
        </DialogHeader>

        {error && (
          <Alert variant="destructive">
            <AlertDescription>{error}</AlertDescription>
          </Alert>
        )}

        {periodo && (
          <div className="bg-muted p-4 rounded-lg">
            <p className="text-sm font-medium">{periodo.nombre}</p>
            <p className="text-sm text-muted-foreground">
              {periodo.año} - Semestre {periodo.semestre}
            </p>
            <p className="text-sm text-muted-foreground">
              {new Date(periodo.fechaInicio).toLocaleDateString()} - {new Date(periodo.fechaFin).toLocaleDateString()}
            </p>
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
