<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MateriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $materias = Materia::with('grupos')->paginate(10);
        return view('materias.index', compact('materias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('materias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre_materia' => 'required|string|max:100',
            'codigo_materia' => 'required|string|max:20|unique:materias,codigo_materia',
            'creditos' => 'required|integer|min:1|max:10'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Materia::create($request->all());

        return redirect()->route('materias.index')
            ->with('success', 'Materia creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Materia $materia)
    {
        $materia->load('grupos.asignaciones.docente.usuario');
        return view('materias.show', compact('materia'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Materia $materia)
    {
        return view('materias.edit', compact('materia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Materia $materia)
    {
        $validator = Validator::make($request->all(), [
            'nombre_materia' => 'required|string|max:100',
            'codigo_materia' => 'required|string|max:20|unique:materias,codigo_materia,' . $materia->materia_id . ',materia_id',
            'creditos' => 'required|integer|min:1|max:10'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $materia->update($request->all());

        return redirect()->route('materias.index')
            ->with('success', 'Materia actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Materia $materia)
    {
        // Verificar si la materia tiene grupos asociados
        if ($materia->grupos()->count() > 0) {
            return redirect()->route('materias.index')
                ->with('error', 'No se puede eliminar la materia porque tiene grupos asociados.');
        }

        $materia->delete();

        return redirect()->route('materias.index')
            ->with('success', 'Materia eliminada exitosamente.');
    }

    /**
     * API endpoint para obtener materias
     */
    public function api()
    {
        $materias = Materia::all();
        return response()->json($materias);
    }
}
