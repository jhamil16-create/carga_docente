<?php

namespace App\Http\Controllers;

use App\Models\Aula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AulaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $aulas = Aula::with('asignaciones')->paginate(10);
        return view('aulas.index', compact('aulas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('aulas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre_aula' => 'required|string|max:50|unique:aulas,nombre_aula',
            'capacidad' => 'required|integer|min:1|max:200',
            'ubicacion' => 'nullable|string|max:100'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Aula::create($request->all());

        return redirect()->route('aulas.index')
            ->with('success', 'Aula creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Aula $aula)
    {
        $aula->load('asignaciones.grupo.materia', 'asignaciones.docente.usuario', 'asignaciones.horario');
        return view('aulas.show', compact('aula'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Aula $aula)
    {
        return view('aulas.edit', compact('aula'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Aula $aula)
    {
        $validator = Validator::make($request->all(), [
            'nombre_aula' => 'required|string|max:50|unique:aulas,nombre_aula,' . $aula->aula_id . ',aula_id',
            'capacidad' => 'required|integer|min:1|max:200',
            'ubicacion' => 'nullable|string|max:100'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $aula->update($request->all());

        return redirect()->route('aulas.index')
            ->with('success', 'Aula actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Aula $aula)
    {
        // Verificar si el aula tiene asignaciones
        if ($aula->asignaciones()->count() > 0) {
            return redirect()->route('aulas.index')
                ->with('error', 'No se puede eliminar el aula porque tiene asignaciones activas.');
        }

        $aula->delete();

        return redirect()->route('aulas.index')
            ->with('success', 'Aula eliminada exitosamente.');
    }

    /**
     * API endpoint para obtener aulas disponibles
     */
    public function disponibles(Request $request)
    {
        $horario_id = $request->get('horario_id');
        
        if ($horario_id) {
            // Obtener aulas que no están ocupadas en ese horario
            $aulas = Aula::whereDoesntHave('asignaciones', function ($query) use ($horario_id) {
                $query->where('horario_id', $horario_id);
            })->get();
        } else {
            $aulas = Aula::all();
        }

        return response()->json($aulas);
    }
}
