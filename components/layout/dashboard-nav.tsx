"use client"

import Link from "next/link"
import { usePathname } from "next/navigation"
import { cn } from "@/lib/utils"
import {
  LayoutDashboard,
  Users,
  GraduationCap,
  BookOpen,
  UsersRound,
  DoorOpen,
  Calendar,
  Shield,
  Upload,
} from "lucide-react"

const navItems = [
  {
    title: "Dashboard",
    href: "/dashboard",
    icon: LayoutDashboard,
  },
  {
    title: "Usuarios",
    href: "/dashboard/users",
    icon: Users,
  },
  {
    title: "Roles y Permisos",
    href: "/dashboard/roles",
    icon: Shield,
  },
  {
    title: "Carga Masiva",
    href: "/dashboard/carga-masiva",
    icon: Upload,
  },
  {
    title: "Docentes",
    href: "/dashboard/docentes",
    icon: GraduationCap,
  },
  {
    title: "Materias",
    href: "/dashboard/materias",
    icon: BookOpen,
  },
  {
    title: "Grupos",
    href: "/dashboard/grupos",
    icon: UsersRound,
  },
  {
    title: "Aulas",
    href: "/dashboard/aulas",
    icon: DoorOpen,
  },
  {
    title: "Periodos",
    href: "/dashboard/periodos",
    icon: Calendar,
  },
]

export function DashboardNav() {
  const pathname = usePathname()

  return (
    <aside className="w-64 border-r bg-background min-h-[calc(100vh-4rem)] p-4">
      <nav className="space-y-1">
        {navItems.map((item) => {
          const Icon = item.icon
          const isActive = pathname === item.href

          return (
            <Link
              key={item.href}
              href={item.href}
              className={cn(
                "flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors",
                isActive
                  ? "bg-primary text-primary-foreground"
                  : "text-muted-foreground hover:bg-muted hover:text-foreground",
              )}
            >
              <Icon className="h-5 w-5" />
              {item.title}
            </Link>
          )
        })}
      </nav>
    </aside>
  )
}
