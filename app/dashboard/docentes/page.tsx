import { DocentesTable } from "@/components/docentes/docentes-table"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"

export default function DocentesPage() {
  return (
    <div className="space-y-6">
      <div>
        <h1 className="text-3xl font-bold text-foreground">Gestión de Docentes</h1>
        <p className="text-muted-foreground mt-2">Administre el personal docente de la institución</p>
      </div>

      <Card>
        <CardHeader>
          <CardTitle>Docentes Registrados</CardTitle>
          <CardDescription>Lista completa de docentes activos e inactivos</CardDescription>
        </CardHeader>
        <CardContent>
          <DocentesTable />
        </CardContent>
      </Card>
    </div>
  )
}
