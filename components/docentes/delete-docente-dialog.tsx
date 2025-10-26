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
import type { Docente } from "@/lib/types"

interface DeleteDocenteDialogProps {
  open: boolean
  onClose: (refresh?: boolean) => void
  docente: Docente | null
}

export function DeleteDocenteDialog({ open, onClose, docente }: DeleteDocenteDialogProps) {
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState("")

  const handleDelete = async () => {
    if (!docente) return

    setLoading(true)
    setError("")

    try {
      const response = await fetch(`/api/docentes/${docente.id}`, {
        method: "DELETE",
      })

      if (!response.ok) {
        const data = await response.json()
        throw new Error(data.message || "Error al eliminar docente")
      }

      onClose(true)
    } catch (err) {
      setError(err instanceof Error ? err.message : "Error al eliminar docente")
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
            Eliminar Docente
          </DialogTitle>
          <DialogDescription>
            Esta acción no se puede deshacer. El docente será eliminado permanentemente del sistema.
          </DialogDescription>
        </DialogHeader>

        {error && (
          <Alert variant="destructive">
            <AlertDescription>{error}</AlertDescription>
          </Alert>
        )}

        {docente && (
          <div className="bg-muted p-4 rounded-lg">
            <p className="text-sm font-medium">
              {docente.nombre} {docente.apellido}
            </p>
            <p className="text-sm text-muted-foreground">{docente.email}</p>
            <p className="text-sm text-muted-foreground">{docente.especialidad}</p>
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
