import { NextResponse } from "next/server"
import type { NextRequest } from "next/server"

const publicRoutes = ["/login"]

export function middleware(request: NextRequest) {
  const { pathname } = request.nextUrl

  console.log("[v0] Middleware - pathname:", pathname)

  const isPublicRoute = publicRoutes.includes(pathname) || pathname.startsWith("/api/auth/login")

  // Get auth token from cookies
  const authToken = request.cookies.get("auth_token")?.value

  console.log("[v0] Middleware - isPublicRoute:", isPublicRoute)
  console.log("[v0] Middleware - authToken exists:", !!authToken)

  // Root path handling
  if (pathname === "/") {
    if (authToken) {
      console.log("[v0] Middleware - Redirecting root to dashboard")
      return NextResponse.redirect(new URL("/dashboard", request.url))
    } else {
      console.log("[v0] Middleware - Redirecting root to login")
      return NextResponse.redirect(new URL("/login", request.url))
    }
  }

  // If trying to access protected route without auth token, redirect to login
  if (!isPublicRoute && !authToken) {
    console.log("[v0] Middleware - Redirecting to login (no auth)")
    return NextResponse.redirect(new URL("/login", request.url))
  }

  // If trying to access login page with valid auth token, redirect to dashboard
  if (pathname === "/login" && authToken) {
    console.log("[v0] Middleware - Redirecting to dashboard (already authenticated)")
    return NextResponse.redirect(new URL("/dashboard", request.url))
  }

  console.log("[v0] Middleware - Allowing request to proceed")
  return NextResponse.next()
}

// Configure which routes use this middleware
export const config = {
  matcher: [
    /*
     * Match all request paths except:
     * - api routes (except auth)
     * - _next/static (static files)
     * - _next/image (image optimization files)
     * - favicon.ico (favicon file)
     * - public files (public folder)
     */
    "/((?!api/(?!auth)|_next/static|_next/image|favicon.ico|.*\\..*|public).*)",
  ],
}
