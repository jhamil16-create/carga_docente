import { AulasTable } from "@/components/aulas/aulas-table"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"

export default function AulasPage() {
  return (
    <div className="space-y-6">
      <div>
        <h1 className="text-3xl font-bold text-foreground">Gestión de Aulas</h1>
        <p className="text-muted-foreground mt-2">Administre las aulas y espacios físicos de la institución</p>
      </div>

      <Card>
        <CardHeader>
          <CardTitle>Aulas Registradas</CardTitle>
          <CardDescription>Lista completa de aulas disponibles y su estado actual</CardDescription>
        </CardHeader>
        <CardContent>
          <AulasTable />
        </CardContent>
      </Card>
    </div>
  )
}
