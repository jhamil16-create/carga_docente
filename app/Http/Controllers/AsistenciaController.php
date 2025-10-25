<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Asignacion;
use App\Models\Docente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AsistenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Asistencia::with('asignacion.docente.usuario', 'asignacion.grupo.materia', 'asignacion.aula');

        // Filtros
        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->fecha);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('docente_id')) {
            $query->whereHas('asignacion', function ($q) use ($request) {
                $q->where('docente_id', $request->docente_id);
            });
        }

        $asistencias = $query->orderBy('fecha', 'desc')
            ->orderBy('hora_registro', 'desc')
            ->paginate(15);

        $docentes = Docente::with('usuario')->get();

        return view('asistencias.index', compact('asistencias', 'docentes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $asignaciones = Asignacion::with('docente.usuario', 'grupo.materia', 'aula', 'horario')->get();
        return view('asistencias.create', compact('asignaciones'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'asignacion_id' => 'required|exists:asignaciones,asignacion_id',
            'fecha' => 'required|date',
            'hora_registro' => 'required|date_format:H:i',
            'estado' => 'required|in:presente,ausente,tardanza',
            'observaciones' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verificar si ya existe un registro de asistencia para esta asignación y fecha
        $existeAsistencia = Asistencia::where('asignacion_id', $request->asignacion_id)
            ->whereDate('fecha', $request->fecha)
            ->exists();

        if ($existeAsistencia) {
            return redirect()->back()
                ->withErrors(['fecha' => 'Ya existe un registro de asistencia para esta asignación en la fecha seleccionada.'])
                ->withInput();
        }

        Asistencia::create($request->all());

        return redirect()->route('asistencias.index')
            ->with('success', 'Registro de asistencia creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Asistencia $asistencia)
    {
        $asistencia->load('asignacion.docente.usuario', 'asignacion.grupo.materia', 'asignacion.aula', 'asignacion.horario');
        return view('asistencias.show', compact('asistencia'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asistencia $asistencia)
    {
        $asignaciones = Asignacion::with('docente.usuario', 'grupo.materia', 'aula', 'horario')->get();
        return view('asistencias.edit', compact('asistencia', 'asignaciones'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asistencia $asistencia)
    {
        $validator = Validator::make($request->all(), [
            'asignacion_id' => 'required|exists:asignaciones,asignacion_id',
            'fecha' => 'required|date',
            'hora_registro' => 'required|date_format:H:i',
            'estado' => 'required|in:presente,ausente,tardanza',
            'observaciones' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verificar si ya existe otro registro de asistencia para esta asignación y fecha
        $existeAsistencia = Asistencia::where('asignacion_id', $request->asignacion_id)
            ->whereDate('fecha', $request->fecha)
            ->where('asistencia_id', '!=', $asistencia->asistencia_id)
            ->exists();

        if ($existeAsistencia) {
            return redirect()->back()
                ->withErrors(['fecha' => 'Ya existe otro registro de asistencia para esta asignación en la fecha seleccionada.'])
                ->withInput();
        }

        $asistencia->update($request->all());

        return redirect()->route('asistencias.index')
            ->with('success', 'Registro de asistencia actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asistencia $asistencia)
    {
        $asistencia->delete();

        return redirect()->route('asistencias.index')
            ->with('success', 'Registro de asistencia eliminado exitosamente.');
    }

    /**
     * Registro rápido de asistencia para el día actual
     */
    public function registroRapido()
    {
        $fechaHoy = Carbon::today();
        
        // Obtener asignaciones del día actual (esto podría mejorarse con lógica de horarios)
        $asignaciones = Asignacion::with('docente.usuario', 'grupo.materia', 'aula', 'horario')
            ->whereDoesntHave('asistencias', function ($query) use ($fechaHoy) {
                $query->whereDate('fecha', $fechaHoy);
            })
            ->get();

        return view('asistencias.registro-rapido', compact('asignaciones', 'fechaHoy'));
    }

    /**
     * Procesar registro rápido múltiple
     */
    public function procesarRegistroRapido(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fecha' => 'required|date',
            'asistencias' => 'required|array',
            'asistencias.*.asignacion_id' => 'required|exists:asignaciones,asignacion_id',
            'asistencias.*.estado' => 'required|in:presente,ausente,tardanza',
            'asistencias.*.observaciones' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $registrosCreados = 0;
        $errores = [];

        foreach ($request->asistencias as $index => $asistenciaData) {
            // Verificar si ya existe un registro para esta asignación y fecha
            $existeAsistencia = Asistencia::where('asignacion_id', $asistenciaData['asignacion_id'])
                ->whereDate('fecha', $request->fecha)
                ->exists();

            if (!$existeAsistencia) {
                Asistencia::create([
                    'asignacion_id' => $asistenciaData['asignacion_id'],
                    'fecha' => $request->fecha,
                    'hora_registro' => now()->format('H:i:s'),
                    'estado' => $asistenciaData['estado'],
                    'observaciones' => $asistenciaData['observaciones'] ?? null
                ]);
                $registrosCreados++;
            } else {
                $errores[] = "Ya existe registro para la asignación " . ($index + 1);
            }
        }

        $mensaje = "Se crearon {$registrosCreados} registros de asistencia.";
        if (!empty($errores)) {
            $mensaje .= " Errores: " . implode(', ', $errores);
        }

        return redirect()->route('asistencias.index')
            ->with('success', $mensaje);
    }

    /**
     * Dashboard de asistencia con estadísticas
     */
    public function dashboard()
    {
        $fechaHoy = Carbon::today();
        $fechaInicio = Carbon::today()->startOfMonth();
        $fechaFin = Carbon::today()->endOfMonth();

        $estadisticas = [
            'hoy' => [
                'total' => Asistencia::whereDate('fecha', $fechaHoy)->count(),
                'presente' => Asistencia::whereDate('fecha', $fechaHoy)->where('estado', 'presente')->count(),
                'ausente' => Asistencia::whereDate('fecha', $fechaHoy)->where('estado', 'ausente')->count(),
                'tardanza' => Asistencia::whereDate('fecha', $fechaHoy)->where('estado', 'tardanza')->count(),
            ],
            'mes_actual' => [
                'total' => Asistencia::whereBetween('fecha', [$fechaInicio, $fechaFin])->count(),
                'presente' => Asistencia::whereBetween('fecha', [$fechaInicio, $fechaFin])->where('estado', 'presente')->count(),
                'ausente' => Asistencia::whereBetween('fecha', [$fechaInicio, $fechaFin])->where('estado', 'ausente')->count(),
                'tardanza' => Asistencia::whereBetween('fecha', [$fechaInicio, $fechaFin])->where('estado', 'tardanza')->count(),
            ]
        ];

        // Asistencias recientes
        $asistenciasRecientes = Asistencia::with('asignacion.docente.usuario', 'asignacion.grupo.materia')
            ->orderBy('fecha', 'desc')
            ->orderBy('hora_registro', 'desc')
            ->limit(10)
            ->get();

        return view('asistencias.dashboard', compact('estadisticas', 'asistenciasRecientes'));
    }
}
