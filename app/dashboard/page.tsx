import { cn } from "@/lib/utils"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Users, BookOpen, Building2, Calendar } from "lucide-react"

export default function DashboardPage() {
  const stats = [
    {
      title: "Total Usuarios",
      value: "1,234",
      description: "+12% desde el último mes",
      icon: Users,
      color: "text-blue-600",
      bgColor: "bg-blue-100",
    },
    {
      title: "Docentes Activos",
      value: "156",
      description: "89% con carga completa",
      icon: Users,
      color: "text-green-600",
      bgColor: "bg-green-100",
    },
    {
      title: "Materias",
      value: "89",
      description: "12 nuevas este semestre",
      icon: BookOpen,
      color: "text-purple-600",
      bgColor: "bg-purple-100",
    },
    {
      title: "Aulas Disponibles",
      value: "45",
      description: "95% de ocupación",
      icon: Building2,
      color: "text-orange-600",
      bgColor: "bg-orange-100",
    },
  ]

  return (
    <div className="space-y-6">
      <div>
        <h2 className="text-3xl font-bold tracking-tight text-foreground">Panel Principal</h2>
        <p className="text-muted-foreground">Resumen general del sistema de gestión académica</p>
      </div>

      <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        {stats.map((stat) => {
          const Icon = stat.icon
          return (
            <Card key={stat.title}>
              <CardHeader className="flex flex-row items-center justify-between pb-2">
                <CardTitle className="text-sm font-medium text-muted-foreground">{stat.title}</CardTitle>
                <div className={cn("p-2 rounded-lg", stat.bgColor)}>
                  <Icon className={cn("h-4 w-4", stat.color)} />
                </div>
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold text-foreground">{stat.value}</div>
                <p className="text-xs text-muted-foreground mt-1">{stat.description}</p>
              </CardContent>
            </Card>
          )
        })}
      </div>

      <div className="grid gap-4 md:grid-cols-2">
        <Card>
          <CardHeader>
            <CardTitle>Actividad Reciente</CardTitle>
            <CardDescription>Últimas acciones en el sistema</CardDescription>
          </CardHeader>
          <CardContent>
            <div className="space-y-4">
              {[
                { action: "Nuevo docente registrado", time: "Hace 5 minutos" },
                { action: "Materia actualizada", time: "Hace 1 hora" },
                { action: "Grupo asignado a aula", time: "Hace 2 horas" },
                { action: "Usuario creado", time: "Hace 3 horas" },
              ].map((item, i) => (
                <div key={i} className="flex items-center justify-between">
                  <p className="text-sm text-foreground">{item.action}</p>
                  <p className="text-xs text-muted-foreground">{item.time}</p>
                </div>
              ))}
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <CardTitle>Próximos Eventos</CardTitle>
            <CardDescription>Calendario académico</CardDescription>
          </CardHeader>
          <CardContent>
            <div className="space-y-4">
              {[
                { event: "Inicio de clases", date: "15 Marzo 2025" },
                { event: "Exámenes parciales", date: "20 Abril 2025" },
                { event: "Fin de semestre", date: "30 Junio 2025" },
                { event: "Inscripciones", date: "1 Julio 2025" },
              ].map((item, i) => (
                <div key={i} className="flex items-center gap-3">
                  <Calendar className="h-4 w-4 text-primary" />
                  <div className="flex-1">
                    <p className="text-sm font-medium text-foreground">{item.event}</p>
                    <p className="text-xs text-muted-foreground">{item.date}</p>
                  </div>
                </div>
              ))}
            </div>
          </CardContent>
        </Card>
      </div>
    </div>
  )
}
