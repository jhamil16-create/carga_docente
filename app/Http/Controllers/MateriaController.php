<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MateriaController extends Controller
{
    public function listarMaterias()
    {
        $materias = Materia::with('grupos')
            ->orderBy('nombre_materia')
            ->get();

        return response()->json([
            'materias' => $materias
        ]);
    }

    public function obtenerMateria($materia_id)
    {
        $materia = Materia::with('grupos')
            ->findOrFail($materia_id);

        return response()->json([
            'materia' => $materia
        ]);
    }

    public function crearMateria(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre_materia' => 'required|string|max:100|unique:materia,nombre_materia',
            'codigo_materia' => 'required|string|max:20|unique:materia,codigo_materia',
            'creditos' => 'required|integer|min:1|max:10'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'mensaje' => 'Datos de materia inválidos',
                'errores' => $validator->errors()
            ], 422);
        }

        $materia = Materia::create($request->all());

        return response()->json([
            'mensaje' => 'Materia creada correctamente',
            'materia' => $materia
        ], 201);
    }

    public function actualizarMateria(Request $request, $materia_id)
    {
        $materia = Materia::findOrFail($materia_id);

        $validator = Validator::make($request->all(), [
            'nombre_materia' => 'required|string|max:100|unique:materia,nombre_materia,' . $materia_id . ',materia_id',
            'codigo_materia' => 'required|string|max:20|unique:materia,codigo_materia,' . $materia_id . ',materia_id',
            'creditos' => 'required|integer|min:1|max:10'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'mensaje' => 'Datos de actualización inválidos',
                'errores' => $validator->errors()
            ], 422);
        }

        $materia->update($request->all());

        return response()->json([
            'mensaje' => 'Materia actualizada correctamente',
            'materia' => $materia
        ]);
    }

    public function eliminarMateria($materia_id)
    {
        $materia = Materia::findOrFail($materia_id);

        if ($materia->grupos()->exists()) {
            return response()->json([
                'mensaje' => 'No se puede eliminar la materia porque tiene grupos asociados'
            ], 422);
        }

        $materia->delete();

        return response()->json([
            'mensaje' => 'Materia eliminada correctamente'
        ]);
    }
}
