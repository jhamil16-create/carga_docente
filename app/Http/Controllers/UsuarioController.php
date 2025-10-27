<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rol_id' => 'nullable|exists:rol,rol_id',
            'activo' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'mensaje' => 'Parámetros de filtro inválidos',
                'errores' => $validator->errors()
            ], 422);
        }

        $query = Usuario::with(['rol', 'docente']);

        if ($request->has('rol_id')) {
            $query->where('rol_id', $request->rol_id);
        }

        if ($request->has('activo')) {
            $query->where('activo', $request->activo);
        }

        $usuarios = $query->orderBy('nombre')->get();

        return view('usuarios.index', compact('usuarios'));
    }

    public function obtenerUsuario($usuario_id)
    {
        $usuario = Usuario::with(['rol', 'docente'])->findOrFail($usuario_id);

        return response()->json([
            'usuario' => $usuario
        ]);
    }

    public function crearUsuario(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'email_institucional' => 'required|email|unique:usuario,email_institucional',
            'contraseña' => 'required|string|min:8',
            'rol_id' => 'required|exists:rol,rol_id',
            'activo' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'mensaje' => 'Datos de usuario inválidos',
                'errores' => $validator->errors()
            ], 422);
        }

        $usuario = new Usuario($request->all());
        $usuario->contraseña_hash = Hash::make($request->contraseña);
        $usuario->activo = $request->has('activo') ? $request->activo : true;
        $usuario->save();

        return response()->json([
            'mensaje' => 'Usuario creado correctamente',
            'usuario' => $usuario
        ], 201);
    }

    public function actualizarUsuario(Request $request, $usuario_id)
    {
        $usuario = Usuario::findOrFail($usuario_id);

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'email_institucional' => 'required|email|unique:usuario,email_institucional,' . $usuario_id . ',usuario_id',
            'contraseña' => 'nullable|string|min:8',
            'rol_id' => 'required|exists:rol,rol_id',
            'activo' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'mensaje' => 'Datos de actualización inválidos',
                'errores' => $validator->errors()
            ], 422);
        }

        $data = $request->except('contraseña');
        if ($request->has('contraseña')) {
            $data['contraseña_hash'] = Hash::make($request->contraseña);
        }

        $usuario->update($data);

        return response()->json([
            'mensaje' => 'Usuario actualizado correctamente',
            'usuario' => $usuario
        ]);
    }

    public function eliminarUsuario($usuario_id)
    {
        $usuario = Usuario::findOrFail($usuario_id);

        if ($usuario->docente()->exists()) {
            return response()->json([
                'mensaje' => 'No se puede eliminar el usuario porque tiene un docente asociado'
            ], 422);
        }

        $usuario->delete();

        return response()->json([
            'mensaje' => 'Usuario eliminado correctamente'
        ]);
    }

    public function cambiarContraseña(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contraseña_actual' => 'required|string',
            'nueva_contraseña' => 'required|string|min:8|different:contraseña_actual',
            'confirmar_contraseña' => 'required|string|same:nueva_contraseña'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'mensaje' => 'Datos inválidos',
                'errores' => $validator->errors()
            ], 422);
        }

        $usuario = Auth::user();

        if (!Hash::check($request->contraseña_actual, $usuario->contraseña_hash)) {
            return response()->json([
                'mensaje' => 'La contraseña actual es incorrecta'
            ], 422);
        }

        $usuario->contraseña_hash = Hash::make($request->nueva_contraseña);
        $usuario->save();

        return response()->json([
            'mensaje' => 'Contraseña actualizada correctamente'
        ]);
    }

    public function miPerfil()
    {
        $usuario = Auth::user()->load(['rol', 'docente']);

        return response()->json([
            'usuario' => $usuario
        ]);
    }

    public function actualizarPerfil(Request $request)
    {
        $usuario = Auth::user();

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'email_institucional' => 'required|email|unique:usuario,email_institucional,' . $usuario->usuario_id . ',usuario_id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'mensaje' => 'Datos de actualización inválidos',
                'errores' => $validator->errors()
            ], 422);
        }

        $usuario->update($request->only(['nombre', 'email_institucional']));

        return response()->json([
            'mensaje' => 'Perfil actualizado correctamente',
            'usuario' => $usuario
        ]);
    }
}