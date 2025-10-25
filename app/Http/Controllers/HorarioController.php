<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HorarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $horarios = Horario::orderBy('dia_semana')
            ->orderBy('hora_inicio')
            ->paginate(15);

        return view('horarios.index', compact('horarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('horarios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dia_semana' => 'required|in:lunes,martes,miercoles,jueves,viernes,sabado,domingo',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verificar que no haya conflictos de horario
        $conflicto = Horario::where('dia_semana', $request->dia_semana)
            ->where(function ($query) use ($request) {
                $query->whereBetween('hora_inicio', [$request->hora_inicio, $request->hora_fin])
                    ->orWhereBetween('hora_fin', [$request->hora_inicio, $request->hora_fin])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('hora_inicio', '<=', $request->hora_inicio)
                          ->where('hora_fin', '>=', $request->hora_fin);
                    });
            })
            ->exists();

        if ($conflicto) {
            return redirect()->back()
                ->withErrors(['hora_inicio' => 'Ya existe un horario que se superpone con el horario especificado.'])
                ->withInput();
        }

        Horario::create($request->all());

        return redirect()->route('horarios.index')
            ->with('success', 'Horario creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Horario $horario)
    {
        $horario->load('asignaciones.docente.usuario', 'asignaciones.grupo.materia', 'asignaciones.aula');
        return view('horarios.show', compact('horario'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Horario $horario)
    {
        return view('horarios.edit', compact('horario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Horario $horario)
    {
        $validator = Validator::make($request->all(), [
            'dia_semana' => 'required|in:lunes,martes,miercoles,jueves,viernes,sabado,domingo',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verificar que no haya conflictos de horario (excluyendo el horario actual)
        $conflicto = Horario::where('dia_semana', $request->dia_semana)
            ->where('horario_id', '!=', $horario->horario_id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('hora_inicio', [$request->hora_inicio, $request->hora_fin])
                    ->orWhereBetween('hora_fin', [$request->hora_inicio, $request->hora_fin])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('hora_inicio', '<=', $request->hora_inicio)
                          ->where('hora_fin', '>=', $request->hora_fin);
                    });
            })
            ->exists();

        if ($conflicto) {
            return redirect()->back()
                ->withErrors(['hora_inicio' => 'Ya existe un horario que se superpone con el horario especificado.'])
                ->withInput();
        }

        $horario->update($request->all());

        return redirect()->route('horarios.index')
            ->with('success', 'Horario actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Horario $horario)
    {
        // Verificar si el horario tiene asignaciones
        if ($horario->asignaciones()->count() > 0) {
            return redirect()->route('horarios.index')
                ->with('error', 'No se puede eliminar el horario porque tiene asignaciones asociadas.');
        }

        $horario->delete();

        return redirect()->route('horarios.index')
            ->with('success', 'Horario eliminado exitosamente.');
    }

    /**
     * Obtener horarios disponibles para un día específico
     */
    public function disponibles(Request $request)
    {
        $dia = $request->get('dia_semana');
        
        if (!$dia) {
            return response()->json(['error' => 'Día de la semana requerido'], 400);
        }

        $horarios = Horario::where('dia_semana', $dia)
            ->orderBy('hora_inicio')
            ->get();

        return response()->json($horarios);
    }

    /**
     * API para obtener todos los horarios
     */
    public function api()
    {
        $horarios = Horario::orderBy('dia_semana')
            ->orderBy('hora_inicio')
            ->get();

        return response()->json($horarios);
    }
}
