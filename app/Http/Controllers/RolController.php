<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RolController extends Controller
{
    public function listarRoles()
    {
        $roles = Rol::withCount('usuarios')->get();

        return response()->json([
            'roles' => $roles
        ]);
    }

    public function obtenerRol($rol_id)
    {
        $rol = Rol::with('usuarios')->findOrFail($rol_id);

        return response()->json([
            'rol' => $rol
        ]);
    }

    public function crearRol(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre_rol' => 'required|string|max:50|unique:rol,nombre_rol'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'mensaje' => 'Datos de rol inválidos',
                'errores' => $validator->errors()
            ], 422);
        }

        $rol = Rol::create($request->all());

        return response()->json([
            'mensaje' => 'Rol creado correctamente',
            'rol' => $rol
        ], 201);
    }

    public function actualizarRol(Request $request, $rol_id)
    {
        $rol = Rol::findOrFail($rol_id);

        $validator = Validator::make($request->all(), [
            'nombre_rol' => 'required|string|max:50|unique:rol,nombre_rol,' . $rol_id . ',rol_id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'mensaje' => 'Datos de actualización inválidos',
                'errores' => $validator->errors()
            ], 422);
        }

        $rol->update($request->all());

        return response()->json([
            'mensaje' => 'Rol actualizado correctamente',
            'rol' => $rol
        ]);
    }

    public function eliminarRol($rol_id)
    {
        $rol = Rol::findOrFail($rol_id);

        if ($rol->usuarios()->exists()) {
            return response()->json([
                'mensaje' => 'No se puede eliminar el rol porque tiene usuarios asociados'
            ], 422);
        }

        $rol->delete();

        return response()->json([
            'mensaje' => 'Rol eliminado correctamente'
        ]);
    }
}