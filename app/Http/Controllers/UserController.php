<?php

namespace App\Http\Controllers;

use App\Models\FacultyMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function importForm()
    {
        return view('users.import');
    }

    public function importFromCSV(Request $request)
    {
        $request->validate([
            'csv' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        if (!Schema::hasTable('faculty_members')) {
            return back()->withErrors(['import' => 'Faculty members table not found. Please run migrations.']);
        }

        $file = $request->file('csv');
        $path = $file->store('imports');
        $fullPath = Storage::path($path);

        $created = 0;
        $skipped = 0;
        $errors = 0;

        if (($handle = fopen($fullPath, 'r')) === false) {
            return back()->withErrors(['import' => 'Unable to read the uploaded CSV file.']);
        }

        // Leer y limpiar encabezado
        $header = fgetcsv($handle);
        if ($header === false) {
            fclose($handle);
            return back()->withErrors(['import' => 'CSV file is empty or invalid (no header row).']);
        }

        // Eliminar BOM del primer campo si existe (UTF-8 BOM: EF BB BF)
        $header[0] = ltrim($header[0], "\xEF\xBB\xBF");
        $header = array_map('trim', $header);

        // Verificar que las columnas requeridas existan
        if (!in_array('name', $header) || !in_array('email', $header)) {
            fclose($handle);
            return back()->withErrors([
                'import' => 'CSV must contain "name" and "email" columns. Found: ' . implode(', ', $header)
            ]);
        }

        while (($row = fgetcsv($handle)) !== false) {
            // Saltar filas vacías o incompletas
            if (count($row) < count($header)) {
                $skipped++;
                continue;
            }

            $data = array_combine($header, $row);

            // Validar campos esenciales
            if (!isset($data['email']) || empty(trim($data['email']))) {
                $skipped++;
                \Log::warning('Skipped row: missing email', $data);
                continue;
            }

            $email = trim($data['email']);
            $name = isset($data['name']) ? trim($data['name']) : '';

            // Validación básica de email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors++;
                \Log::error("Invalid email in row: {$email}", $data);
                continue;
            }

            try {
                // Usamos firstOrCreate para evitar duplicados por email
                $faculty = FacultyMember::firstOrCreate(
                    ['email' => $email],
                    ['name' => $name]
                );

                if ($faculty->wasRecentlyCreated) {
                    $created++;
                } else {
                    // Ya existía → podrías contar como "skipped" si lo deseas
                    $skipped++;
                }
            } catch (\Throwable $e) {
                $errors++;
                \Log::error("Import error for email: {$email}", [
                    'data' => $data,
                    'exception' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]);
            }
        }

        fclose($handle);
        Storage::delete($path); // Opcional: borrar archivo temporal

        $message = "Import finished. Created: $created, Skipped: $skipped, Errors: $errors";
        return back()->with('status', $message);
    }
}