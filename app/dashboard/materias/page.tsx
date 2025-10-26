import { MateriasTable } from "@/components/materias/materias-table"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"

export default function MateriasPage() {
  return (
    <div className="space-y-6">
      <div>
        <h1 className="text-3xl font-bold text-foreground">Gestión de Materias</h1>
        <p className="text-muted-foreground mt-2">Administre las materias del plan de estudios</p>
      </div>

      <Card>
        <CardHeader>
          <CardTitle>Materias Registradas</CardTitle>
          <CardDescription>Lista completa de materias activas e inactivas</CardDescription>
        </CardHeader>
        <CardContent>
          <MateriasTable />
        </CardContent>
      </Card>
    </div>
  )
}
