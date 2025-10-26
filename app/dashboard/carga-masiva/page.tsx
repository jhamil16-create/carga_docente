"use client"

import type React from "react"

import { useState } from "react"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Upload, Download, FileSpreadsheet, AlertCircle, CheckCircle2 } from "lucide-react"
import { useToast } from "@/hooks/use-toast"
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert"
import { Progress } from "@/components/ui/progress"
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table"
import { Badge } from "@/components/ui/badge"

export default function CargaMasivaPage() {
  const { toast } = useToast()
  const [file, setFile] = useState<File | null>(null)
  const [uploading, setUploading] = useState(false)
  const [progress, setProgress] = useState(0)
  const [results, setResults] = useState<any>(null)

  const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    if (e.target.files && e.target.files[0]) {
      const selectedFile = e.target.files[0]
      const fileExtension = selectedFile.name.split(".").pop()?.toLowerCase()

      if (fileExtension === "csv" || fileExtension === "xlsx" || fileExtension === "xls") {
        setFile(selectedFile)
        setResults(null)
      } else {
        toast({
          title: "Archivo no válido",
          description: "Por favor selecciona un archivo CSV o Excel (.xlsx, .xls)",
          variant: "destructive",
        })
      }
    }
  }

  const handleUpload = async () => {
    if (!file) return

    setUploading(true)
    setProgress(0)

    // Simulate file upload and processing
    const interval = setInterval(() => {
      setProgress((prev) => {
        if (prev >= 100) {
          clearInterval(interval)
          return 100
        }
        return prev + 10
      })
    }, 200)

    setTimeout(() => {
      setUploading(false)
      setResults({
        total: 150,
        exitosos: 145,
        errores: 5,
        detalles: [
          { fila: 23, error: "Email duplicado: juan.perez@universidad.edu" },
          { fila: 45, error: "Formato de CI inválido" },
          { fila: 67, error: "Rol no existe: Coordinador" },
          { fila: 89, error: "Email duplicado: maria.lopez@universidad.edu" },
          { fila: 112, error: "Campo requerido faltante: apellido" },
        ],
      })
      toast({
        title: "Carga completada",
        description: "145 usuarios creados exitosamente, 5 errores encontrados.",
      })
    }, 2500)
  }

  const downloadTemplate = () => {
    toast({
      title: "Descargando plantilla",
      description: "La plantilla CSV se descargará en breve.",
    })
    // In a real app, this would trigger a file download
  }

  return (
    <div className="space-y-6">
      <div>
        <h1 className="text-3xl font-bold text-foreground">Carga Masiva de Usuarios</h1>
        <p className="text-muted-foreground">Importa múltiples usuarios desde archivos CSV o Excel</p>
      </div>

      <Alert>
        <AlertCircle className="h-4 w-4" />
        <AlertTitle>Instrucciones</AlertTitle>
        <AlertDescription>
          Descarga la plantilla, completa los datos de los usuarios y sube el archivo. El sistema validará y creará las
          cuentas automáticamente.
        </AlertDescription>
      </Alert>

      <div className="grid gap-6 md:grid-cols-2">
        <Card>
          <CardHeader>
            <CardTitle>Paso 1: Descargar Plantilla</CardTitle>
            <CardDescription>Descarga la plantilla con el formato correcto</CardDescription>
          </CardHeader>
          <CardContent className="space-y-4">
            <div className="flex items-center justify-center p-8 border-2 border-dashed rounded-lg">
              <div className="text-center space-y-2">
                <FileSpreadsheet className="h-12 w-12 mx-auto text-muted-foreground" />
                <p className="text-sm text-muted-foreground">Plantilla con campos requeridos</p>
              </div>
            </div>
            <Button onClick={downloadTemplate} className="w-full bg-transparent" variant="outline">
              <Download className="mr-2 h-4 w-4" />
              Descargar Plantilla CSV
            </Button>
            <div className="text-xs text-muted-foreground space-y-1">
              <p>
                <strong>Campos requeridos:</strong>
              </p>
              <p>nombre, apellido, email, ci, rol, telefono</p>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <CardTitle>Paso 2: Subir Archivo</CardTitle>
            <CardDescription>Sube el archivo completado con los datos</CardDescription>
          </CardHeader>
          <CardContent className="space-y-4">
            <div className="flex items-center justify-center p-8 border-2 border-dashed rounded-lg">
              <div className="text-center space-y-2">
                <Upload className="h-12 w-12 mx-auto text-muted-foreground" />
                <p className="text-sm text-muted-foreground">
                  {file ? file.name : "Selecciona un archivo CSV o Excel"}
                </p>
              </div>
            </div>
            <input
              type="file"
              accept=".csv,.xlsx,.xls"
              onChange={handleFileChange}
              className="hidden"
              id="file-upload"
            />
            <label htmlFor="file-upload">
              <Button variant="outline" className="w-full bg-transparent" asChild>
                <span>
                  <FileSpreadsheet className="mr-2 h-4 w-4" />
                  Seleccionar Archivo
                </span>
              </Button>
            </label>
            <Button onClick={handleUpload} disabled={!file || uploading} className="w-full">
              {uploading ? "Procesando..." : "Cargar Usuarios"}
            </Button>
            {uploading && (
              <div className="space-y-2">
                <Progress value={progress} />
                <p className="text-xs text-center text-muted-foreground">{progress}% completado</p>
              </div>
            )}
          </CardContent>
        </Card>
      </div>

      {results && (
        <Card>
          <CardHeader>
            <CardTitle>Resultados de la Carga</CardTitle>
            <CardDescription>Resumen del proceso de importación</CardDescription>
          </CardHeader>
          <CardContent className="space-y-4">
            <div className="grid gap-4 md:grid-cols-3">
              <div className="flex items-center gap-3 p-4 border rounded-lg">
                <FileSpreadsheet className="h-8 w-8 text-muted-foreground" />
                <div>
                  <p className="text-2xl font-bold">{results.total}</p>
                  <p className="text-sm text-muted-foreground">Total registros</p>
                </div>
              </div>
              <div className="flex items-center gap-3 p-4 border rounded-lg bg-green-50 dark:bg-green-950">
                <CheckCircle2 className="h-8 w-8 text-green-600" />
                <div>
                  <p className="text-2xl font-bold text-green-600">{results.exitosos}</p>
                  <p className="text-sm text-muted-foreground">Exitosos</p>
                </div>
              </div>
              <div className="flex items-center gap-3 p-4 border rounded-lg bg-red-50 dark:bg-red-950">
                <AlertCircle className="h-8 w-8 text-red-600" />
                <div>
                  <p className="text-2xl font-bold text-red-600">{results.errores}</p>
                  <p className="text-sm text-muted-foreground">Errores</p>
                </div>
              </div>
            </div>

            {results.errores > 0 && (
              <div className="space-y-2">
                <h3 className="font-semibold">Errores Encontrados</h3>
                <Table>
                  <TableHeader>
                    <TableRow>
                      <TableHead>Fila</TableHead>
                      <TableHead>Error</TableHead>
                    </TableRow>
                  </TableHeader>
                  <TableBody>
                    {results.detalles.map((detalle: any, index: number) => (
                      <TableRow key={index}>
                        <TableCell>
                          <Badge variant="destructive">Fila {detalle.fila}</Badge>
                        </TableCell>
                        <TableCell className="text-sm">{detalle.error}</TableCell>
                      </TableRow>
                    ))}
                  </TableBody>
                </Table>
              </div>
            )}
          </CardContent>
        </Card>
      )}
    </div>
  )
}
