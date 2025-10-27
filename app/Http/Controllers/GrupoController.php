<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Materia;
use App\Models\Docente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GrupoController extends Controller
{
    /**
     * Mostrar listado de grupos
     */
    public function index(Request $request)
    {
        $query = Grupo::with(['materia', 'asignaciones.docente.usuario']);

        // Filtros
        if ($request->has('materia_id') && $request->materia_id) {
            $query->where('materia_id', $request->materia_id);
        }

        $grupos = $query->orderBy('materia_id')
            ->orderBy('nombre_grupo')
            ->paginate(15);

        $materias = Materia::orderBy('nombre_materia')->get();

        return view('grupos.index', compact('grupos', 'materias'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $materias = Materia::orderBy('nombre_materia')->get();
        
        return view('grupos.create', compact('materias'));
    }

    /**
     * Guardar nuevo grupo
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'materia_id' => 'required|exists:materia,materia_id',
            'nombre_grupo' => 'required|string|max:50',
            'capacidad_maxima' => 'required|integer|min:1|max:200'
        ], [
            'materia_id.required' => 'Debe seleccionar una materia',
            'materia_id.exists' => 'La materia seleccionada no existe',
            'nombre_grupo.required' => 'El nombre del grupo es obligatorio',
            'nombre_grupo.max' => 'El nombre del grupo no puede exceder 50 caracteres',
            'capacidad_maxima.required' => 'La capacidad máxima es obligatoria',
            'capacidad_maxima.integer' => 'La capacidad debe ser un número entero',
            'capacidad_maxima.min' => 'La capacidad mínima es 1',
            'capacidad_maxima.max' => 'La capacidad máxima es 200'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verificar si ya existe un grupo con el mismo nombre para la materia
        $existeGrupo = Grupo::where('materia_id', $request->materia_id)
            ->where('nombre_grupo', $request->nombre_grupo)
            ->exists();

        if ($existeGrupo) {
            return redirect()->back()
                ->withErrors(['nombre_grupo' => 'Ya existe un grupo con este nombre para esta materia'])
                ->withInput();
        }

        Grupo::create($request->only(['materia_id', 'nombre_grupo', 'capacidad_maxima']));

        return redirect()->route('grupos.index')
            ->with('success', 'Grupo creado exitosamente');
    }

    /**
     * Mostrar detalle del grupo
     */
    public function show($grupo_id)
    {
        $grupo = Grupo::with(['materia', 'asignaciones.docente.usuario', 'asignaciones.aula', 'asignaciones.horario'])
            ->findOrFail($grupo_id);

        return view('grupos.show', compact('grupo'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($grupo_id)
    {
        $grupo = Grupo::findOrFail($grupo_id);
        $materias = Materia::orderBy('nombre_materia')->get();

        return view('grupos.edit', compact('grupo', 'materias'));
    }

    /**
     * Actualizar grupo
     */
    public function update(Request $request, $grupo_id)
    {
        $grupo = Grupo::findOrFail($grupo_id);

        $validator = Validator::make($request->all(), [
            'materia_id' => 'required|exists:materia,materia_id',
            'nombre_grupo' => 'required|string|max:50',
            'capacidad_maxima' => 'required|integer|min:1|max:200'
        ], [
            'materia_id.required' => 'Debe seleccionar una materia',
            'materia_id.exists' => 'La materia seleccionada no existe',
            'nombre_grupo.required' => 'El nombre del grupo es obligatorio',
            'nombre_grupo.max' => 'El nombre del grupo no puede exceder 50 caracteres',
            'capacidad_maxima.required' => 'La capacidad máxima es obligatoria',
            'capacidad_maxima.integer' => 'La capacidad debe ser un número entero',
            'capacidad_maxima.min' => 'La capacidad mínima es 1',
            'capacidad_maxima.max' => 'La capacidad máxima es 200'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verificar si ya existe otro grupo con el mismo nombre para la materia
        $existeGrupo = Grupo::where('materia_id', $request->materia_id)
            ->where('nombre_grupo', $request->nombre_grupo)
            ->where('grupo_id', '!=', $grupo_id)
            ->exists();

        if ($existeGrupo) {
            return redirect()->back()
                ->withErrors(['nombre_grupo' => 'Ya existe otro grupo con este nombre para esta materia'])
                ->withInput();
        }

        $grupo->update($request->only(['materia_id', 'nombre_grupo', 'capacidad_maxima']));

        return redirect()->route('grupos.show', $grupo_id)
            ->with('success', 'Grupo actualizado exitosamente');
    }

    /**
     * Eliminar grupo
     */
    public function destroy($grupo_id)
    {
        $grupo = Grupo::findOrFail($grupo_id);

        // Verificar si tiene asignaciones
        if ($grupo->asignaciones()->exists()) {
            return redirect()->route('grupos.index')
                ->with('error', 'No se puede eliminar el grupo porque tiene asignaciones activas');
        }

        $grupo->delete();

        return redirect()->route('grupos.index')
            ->with('success', 'Grupo eliminado exitosamente');
    }
}