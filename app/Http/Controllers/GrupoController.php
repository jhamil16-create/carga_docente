<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Materia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GrupoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grupos = Grupo::with('materia', 'asignaciones.docente.usuario')->paginate(10);
        return view('grupos.index', compact('grupos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $materias = Materia::all();
        return view('grupos.create', compact('materias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'materia_id' => 'required|exists:materias,materia_id',
            'nombre_grupo' => 'required|string|max:50',
            'capacidad_maxima' => 'required|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verificar que no exista un grupo con el mismo nombre para la misma materia
        $existeGrupo = Grupo::where('materia_id', $request->materia_id)
            ->where('nombre_grupo', $request->nombre_grupo)
            ->exists();

        if ($existeGrupo) {
            return redirect()->back()
                ->withErrors(['nombre_grupo' => 'Ya existe un grupo con este nombre para la materia seleccionada.'])
                ->withInput();
        }

        Grupo::create($request->all());

        return redirect()->route('grupos.index')
            ->with('success', 'Grupo creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Grupo $grupo)
    {
        $grupo->load('materia', 'asignaciones.docente.usuario', 'asignaciones.aula', 'asignaciones.horario');
        return view('grupos.show', compact('grupo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grupo $grupo)
    {
        $materias = Materia::all();
        return view('grupos.edit', compact('grupo', 'materias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grupo $grupo)
    {
        $validator = Validator::make($request->all(), [
            'materia_id' => 'required|exists:materias,materia_id',
            'nombre_grupo' => 'required|string|max:50',
            'capacidad_maxima' => 'required|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verificar que no exista otro grupo con el mismo nombre para la misma materia
        $existeGrupo = Grupo::where('materia_id', $request->materia_id)
            ->where('nombre_grupo', $request->nombre_grupo)
            ->where('grupo_id', '!=', $grupo->grupo_id)
            ->exists();

        if ($existeGrupo) {
            return redirect()->back()
                ->withErrors(['nombre_grupo' => 'Ya existe un grupo con este nombre para la materia seleccionada.'])
                ->withInput();
        }

        $grupo->update($request->all());

        return redirect()->route('grupos.index')
            ->with('success', 'Grupo actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grupo $grupo)
    {
        // Verificar si el grupo tiene asignaciones
        if ($grupo->asignaciones()->count() > 0) {
            return redirect()->route('grupos.index')
                ->with('error', 'No se puede eliminar el grupo porque tiene asignaciones activas.');
        }

        $grupo->delete();

        return redirect()->route('grupos.index')
            ->with('success', 'Grupo eliminado exitosamente.');
    }

    /**
     * API endpoint para obtener grupos por materia
     */
    public function porMateria($materia_id)
    {
        $grupos = Grupo::where('materia_id', $materia_id)->get();
        return response()->json($grupos);
    }
}
