<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Docente;
use App\Models\Grupo;
use App\Models\Aula;
use App\Models\Horario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AsignacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $asignaciones = Asignacion::with('docente.usuario', 'grupo.materia', 'aula', 'horario')
            ->paginate(10);
        return view('asignaciones.index', compact('asignaciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $docentes = Docente::with('usuario')->get();
        $grupos = Grupo::with('materia')->get();
        $aulas = Aula::all();
        $horarios = Horario::all();
        
        return view('asignaciones.create', compact('docentes', 'grupos', 'aulas', 'horarios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'docente_id' => 'required|exists:docentes,docente_id',
            'grupo_id' => 'required|exists:grupos,grupo_id',
            'aula_id' => 'required|exists:aulas,aula_id',
            'horario_id' => 'required|exists:horarios,horario_id',
            'fecha_asignacion' => 'nullable|date'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verificar conflictos de horario para el docente
        $conflictoDocente = Asignacion::where('docente_id', $request->docente_id)
            ->where('horario_id', $request->horario_id)
            ->exists();

        if ($conflictoDocente) {
            return redirect()->back()
                ->withErrors(['horario_id' => 'El docente ya tiene una asignación en este horario.'])
                ->withInput();
        }

        // Verificar conflictos de horario para el aula
        $conflictoAula = Asignacion::where('aula_id', $request->aula_id)
            ->where('horario_id', $request->horario_id)
            ->exists();

        if ($conflictoAula) {
            return redirect()->back()
                ->withErrors(['aula_id' => 'El aula ya está ocupada en este horario.'])
                ->withInput();
        }

        $data = $request->all();
        if (!$data['fecha_asignacion']) {
            $data['fecha_asignacion'] = now()->toDateString();
        }

        Asignacion::create($data);

        return redirect()->route('asignaciones.index')
            ->with('success', 'Asignación creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Asignacion $asignacion)
    {
        $asignacion->load('docente.usuario', 'grupo.materia', 'aula', 'horario', 'asistencias');
        return view('asignaciones.show', compact('asignacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asignacion $asignacion)
    {
        $docentes = Docente::with('usuario')->get();
        $grupos = Grupo::with('materia')->get();
        $aulas = Aula::all();
        $horarios = Horario::all();
        
        return view('asignaciones.edit', compact('asignacion', 'docentes', 'grupos', 'aulas', 'horarios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asignacion $asignacion)
    {
        $validator = Validator::make($request->all(), [
            'docente_id' => 'required|exists:docentes,docente_id',
            'grupo_id' => 'required|exists:grupos,grupo_id',
            'aula_id' => 'required|exists:aulas,aula_id',
            'horario_id' => 'required|exists:horarios,horario_id',
            'fecha_asignacion' => 'nullable|date'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verificar conflictos de horario para el docente (excluyendo la asignación actual)
        $conflictoDocente = Asignacion::where('docente_id', $request->docente_id)
            ->where('horario_id', $request->horario_id)
            ->where('asignacion_id', '!=', $asignacion->asignacion_id)
            ->exists();

        if ($conflictoDocente) {
            return redirect()->back()
                ->withErrors(['horario_id' => 'El docente ya tiene una asignación en este horario.'])
                ->withInput();
        }

        // Verificar conflictos de horario para el aula (excluyendo la asignación actual)
        $conflictoAula = Asignacion::where('aula_id', $request->aula_id)
            ->where('horario_id', $request->horario_id)
            ->where('asignacion_id', '!=', $asignacion->asignacion_id)
            ->exists();

        if ($conflictoAula) {
            return redirect()->back()
                ->withErrors(['aula_id' => 'El aula ya está ocupada en este horario.'])
                ->withInput();
        }

        $asignacion->update($request->all());

        return redirect()->route('asignaciones.index')
            ->with('success', 'Asignación actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asignacion $asignacion)
    {
        // Verificar si la asignación tiene registros de asistencia
        if ($asignacion->asistencias()->count() > 0) {
            return redirect()->route('asignaciones.index')
                ->with('error', 'No se puede eliminar la asignación porque tiene registros de asistencia.');
        }

        $asignacion->delete();

        return redirect()->route('asignaciones.index')
            ->with('success', 'Asignación eliminada exitosamente.');
    }

    /**
     * Obtener horarios de un docente
     */
    public function horariosDocente($docente_id)
    {
        $asignaciones = Asignacion::with('horario', 'grupo.materia', 'aula')
            ->where('docente_id', $docente_id)
            ->get();

        return response()->json($asignaciones);
    }
}
