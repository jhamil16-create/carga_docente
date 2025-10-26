import { LoginForm } from "@/components/auth/login-form"


export default function LoginPage() {
  return (
   <div
      className="min-h-screen flex items-center justify-center bg-cover bg-center p-4"
      style={{ backgroundImage: "url('/backgroundHomePage.jpg')" }}
    >
        <LoginForm />
    </div>
  )
}
