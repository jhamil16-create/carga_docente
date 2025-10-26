import { GruposTable } from "@/components/grupos/grupos-table"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"

export default function GruposPage() {
  return (
    <div className="space-y-6">
      <div>
        <h1 className="text-3xl font-bold text-foreground">Gestión de Grupos</h1>
        <p className="text-muted-foreground mt-2">Administre los grupos académicos y sus asignaciones</p>
      </div>

      <Card>
        <CardHeader>
          <CardTitle>Grupos Registrados</CardTitle>
          <CardDescription>Lista completa de grupos con sus docentes y materias asignadas</CardDescription>
        </CardHeader>
        <CardContent>
          <GruposTable />
        </CardContent>
      </Card>
    </div>
  )
}
