import { LoginForm } from "@/components/auth/login-form"
import { Clock, FileBarChart, Smartphone, Shield, Calendar } from "lucide-react"
import Image from "next/image"

export default function LoginPage() {
  return (
    <div className="min-h-screen flex">
      {/* Left Side - Campus Background with Information Panel */}
      <div className="hidden lg:flex lg:w-1/2 text-white p-12 flex-col items-center justify-center relative overflow-hidden">
        <Image src="/backgroundHomePage.jpg" alt="Campus FICCT" fill className="object-cover" priority />
        {/* Dark overlay for text readability */}
        <div className="absolute inset-0 bg-gradient-to-br from-primary/50 via-primary/90 to-primary/85" />
        {/* </CHANGE> */}

        {/* Decorative circles */}
        <div className="absolute top-20 right-20 w-64 h-64 bg-white/5 rounded-full blur-3xl" />
        <div className="absolute bottom-20 left-20 w-96 h-96 bg-white/5 rounded-full blur-3xl" />

        <div className="relative z-10">
          <div className="flex items-center gap-3 mb-12">
            <div className="w-14 h-14 bg-white/10 rounded-xl flex items-center justify-center backdrop-blur-sm p-2">
              <Image src="/logoFicct.png" alt="Logo FICCT" width={48} height={48} className="object-contain" />
            </div>
            <div>
              <h2 className="text-xl font-bold">Sistema FICCT</h2>
              <p className="text-sm text-white/80">Gestión Académica Integral</p>
            </div>
          </div>
          {/* </CHANGE> */}

          {/* Main Heading */}
          <div className="mb-8">
            <h1 className="text-4xl font-bold mb-4 leading-tight">
              Sistema de Gestión
              <br />
              Académica
            </h1>
            <p className="text-lg text-white/80 leading-relaxed">
              Plataforma integral para la asignación de horarios,
              <br />
              aulas, materias, grupos y control de asistencia
              <br />
              docente.
            </p>
          </div>
        </div>
              </div>

      {/* Right Side - Login Form */}
      <div className="w-full lg:w-1/2 flex items-center justify-center p-8 bg-background">
        <LoginForm />
      </div>
    </div>
  )
}
