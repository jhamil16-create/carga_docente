# FICCT Faculty Scheduling (Laravel 11)

A web system to manage schedules, classrooms, subjects, groups, and teacher attendance per department. Ready for PostgreSQL and cloud deployment.

## Modules & Features
- Faculty management: registration, editing, and class load (scaffolded via models/migrations).
- Subjects, groups, classrooms, and schedules management.
- Automatic/manual schedule assignment with conflict validation service.
- Attendance control with QR code endpoint.
- Admin dashboard with exports (PDF and Excel).
- PWA basics: manifest + service worker.
- RBAC: roles and permissions via Spatie.

## Getting Started
1. Install dependencies:
   - `composer install`
   - `npm install`
2. Configure environment:
   - Copy `.env.example` to `.env`
   - Set `APP_KEY` (run `php artisan key:generate`)
   - Configure `DB_CONNECTION=pgsql` and your Postgres credentials.
3. Migrate & seed:
   - `php artisan migrate`
   - `php artisan db:seed` (creates roles and an `admin@example.com` user)
4. Run dev server:
   - `php artisan serve`
   - `npm run dev` (for Vite assets)

Note: Routes and controllers guard for missing tables, so pages won’t crash if DB isn’t ready.

## Key Routes
- `GET /` Dashboard
- `GET /schedules` List schedules
- `GET /schedules/create` Create schedule
- `POST /schedules` Store schedule (validates conflicts)
- `GET /exports/schedules/pdf` PDF export
- `GET /exports/schedules/excel` Excel export
- `GET /qr/schedule?schedule_id=1&date=2025-01-01` QR for attendance

## RBAC
- Roles: `admin`, `coordinator`, `instructor`
- Permissions mapped in `RoleSeeder`.
- Middleware aliases registered in `bootstrap/app.php` (`role`, `permission`, `role_or_permission`).

## Deployment (AWS preferred)
### Option A: ECS Fargate (Docker)
- Build Docker image using `Dockerfile`.
- Use `docker-compose.yml` to define services. Convert to ECS task definitions via AWS Copilot or Compose on ECS.
- Provision:
  - Postgres (Amazon RDS for PostgreSQL)
  - S3 bucket for file storage if needed
  - Secrets in AWS Secrets Manager for DB credentials
- Environment variables: configure in ECS task or via SSM Parameter Store.
- Set `APP_ENV=production`, `APP_DEBUG=false`.

### Option B: Elastic Beanstalk (Docker single container)
- Use the provided `Dockerfile` and create an EB application with a single Docker platform.
- Attach an RDS PostgreSQL instance.

### Postgres Schema
- All migrations under `database/migrations` are ready and align with departments, subjects, groups, classrooms, schedules, and attendance.

## Notes
- Authentication UI (login/register) isn’t scaffolded yet; recommend `laravel/breeze` for auth flows.
- QR scanning integration for attendance can be expanded with a mobile page to parse JSON payloads and post attendance.
- Exports are basic and ready to be customized per report requirements.
