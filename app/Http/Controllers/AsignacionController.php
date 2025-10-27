<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Grupo;
use App\Models\Docente;
use App\Models\Aula;
use App\Models\Horario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AsignacionController extends Controller
{
    public function index()
    {
        $asignaciones = Asignacion::with(['grupo.materia', 'docente.usuario', 'aula', 'horario', 'estadisticas'])
            ->latest()
            ->paginate(15);
            
        return view('asignaciones.index', compact('asignaciones'));
    }

    public function create(Request $request)
    {
        $grupo = null;
        if ($request->has('grupo_id')) {
            $grupo = Grupo::findOrFail($request->grupo_id);
        }

        $grupos = Grupo::with('materia')->orderBy('nombre_grupo')->get();
        $docentes = Docente::join('usuario', 'docente.usuario_id', '=', 'usuario.usuario_id')
        ->select('docente.*')
        ->orderBy('usuario.nombre')
        ->orderBy('usuario.apellido')
        ->with('usuario')
        ->get();
        $aulas = Aula::orderBy('nombre_aula')->get();
        $horarios = Horario::orderBy('dia_semana')->orderBy('hora_inicio')->get();

        return view('asignaciones.create', compact('grupo', 'grupos', 'docentes', 'aulas', 'horarios'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'grupo_id' => 'required|exists:grupo,grupo_id',
            'docente_id' => 'required|exists:docente,docente_id',
            'aula_id' => 'required|exists:aula,aula_id',
            'horario_id' => 'required|exists:horario,horario_id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Verificar conflictos (opcional, pero recomendado)
        $conflicto = Asignacion::where(function($query) use ($request) {
            $query->where('docente_id', $request->docente_id)
                  ->orWhere('aula_id', $request->aula_id);
        })->where('horario_id', $request->horario_id)->exists();

        if ($conflicto) {
            return back()->withErrors([
                'horario_id' => 'Conflicto detectado: el docente o el aula ya están asignados en este horario.'
            ])->withInput();
        }

        Asignacion::create([
            'grupo_id' => $request->grupo_id,
            'docente_id' => $request->docente_id,
            'aula_id' => $request->aula_id,
            'horario_id' => $request->horario_id,
            'fecha_asignacion' => now(),
        ]);

        return redirect()->route('grupos.show', $request->grupo_id)
            ->with('success', 'Asignación creada exitosamente');
    }

    public function show($asignacion_id)
    {
        $asignacion = Asignacion::with(['grupo.materia', 'docente.usuario', 'aula', 'horario'])
            ->findOrFail($asignacion_id);
            
        return view('asignaciones.show', compact('asignacion'));
    }

    public function edit($asignacion_id)
    {
        $asignacion = Asignacion::with(['grupo.materia', 'docente.usuario', 'aula', 'horario'])
            ->findOrFail($asignacion_id);
            
        $grupos = Grupo::with('materia')->orderBy('nombre_grupo')->get();
        $docentes = Docente::join('usuario', 'docente.usuario_id', '=', 'usuario.usuario_id')
        ->select('docente.*')
        ->orderBy('usuario.nombre')
        ->orderBy('usuario.apellido')
        ->with('usuario')
        ->get();
        $aulas = Aula::orderBy('nombre_aula')->get();
        $horarios = Horario::orderBy('dia_semana')->orderBy('hora_inicio')->get();

        return view('asignaciones.edit', compact('asignacion', 'grupos', 'docentes', 'aulas', 'horarios'));
    }

    public function update(Request $request, $asignacion_id)
    {
        $asignacion = Asignacion::findOrFail($asignacion_id);

        $validator = Validator::make($request->all(), [
            'grupo_id' => 'required|exists:grupo,grupo_id',
            'docente_id' => 'required|exists:docente,docente_id',
            'aula_id' => 'required|exists:aula,aula_id',
            'horario_id' => 'required|exists:horario,horario_id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Verificar conflictos (excluyendo la asignación actual)
        $conflicto = Asignacion::where('asignacion_id', '!=', $asignacion_id)
            ->where(function($query) use ($request) {
                $query->where('docente_id', $request->docente_id)
                      ->orWhere('aula_id', $request->aula_id);
            })->where('horario_id', $request->horario_id)->exists();

        if ($conflicto) {
            return back()->withErrors([
                'horario_id' => 'Conflicto detectado: el docente o el aula ya están asignados en este horario.'
            ])->withInput();
        }

        $asignacion->update([
            'grupo_id' => $request->grupo_id,
            'docente_id' => $request->docente_id,
            'aula_id' => $request->aula_id,
            'horario_id' => $request->horario_id,
        ]);

        return redirect()->route('asignaciones.show', $asignacion_id)
            ->with('success', 'Asignación actualizada exitosamente');
    }

    public function destroy($asignacion_id)
    {
        $asignacion = Asignacion::findOrFail($asignacion_id);
        $grupo_id = $asignacion->grupo_id;
        
        $asignacion->delete();

        return redirect()->route('grupos.show', $grupo_id)
            ->with('success', 'Asignación eliminada exitosamente');
    }
}
