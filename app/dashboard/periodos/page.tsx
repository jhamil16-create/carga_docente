import { PeriodosTable } from "@/components/periodos/periodos-table"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"

export default function PeriodosPage() {
  return (
    <div className="space-y-6">
      <div>
        <h1 className="text-3xl font-bold text-foreground">Gestión de Periodos Académicos</h1>
        <p className="text-muted-foreground mt-2">Administre los periodos académicos y su configuración</p>
      </div>

      <Card>
        <CardHeader>
          <CardTitle>Periodos Académicos</CardTitle>
          <CardDescription>Lista completa de periodos académicos registrados en el sistema</CardDescription>
        </CardHeader>
        <CardContent>
          <PeriodosTable />
        </CardContent>
      </Card>
    </div>
  )
}
