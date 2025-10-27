<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asistencia;
use App\Models\Docente;
use App\Models\Asignacion;

class AsistenciaController extends Controller
{
    public function index()
    {
        // Cargar asistencias con relaciones
        $asistencias = Asistencia::with([
            'docente.usuario', 
            'asignacion.grupo.materia', 
            'asignacion.aula'
            ])
            ->orderBy('fecha', 'desc')
            ->orderBy('hora_entrada', 'desc')
            ->paginate(10);

        return view('asistencias.index', compact('asistencias'));
    }

    public function create()
    {
        $docentes = Docente::with('usuario')->get();
        $asignaciones = Asignacion::with([
            'docente.usuario', 
            'grupo.materia', 
            'aula'])->get();
        return view('asistencias.create', compact('docentes', 'asignaciones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'docente_id' => 'required|exists:docente,docente_id',
            'asignacion_id' => 'required|exists:asignacion,asignacion_id',
            'fecha' => 'required|date',
            'estado' => 'required|in:Presente,Ausente,Tardanza',
        ]);

        Asistencia::create([
            'docente_id' => $request->docente_id,
            'asignacion_id' => $request->asignacion_id,
            'fecha' => $request->fecha,
            'hora_entrada' => now()->format('H:i:s'), // o desde el request
            'estado' => $request->estado,
            'metodo_registro' => 'Manual',
        ]);

        return redirect()->route('asistencias.index')->with('success', 'Asistencia registrada correctamente.');
    }
}