<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function index()
    {
        $usuarios = Usuario::with('rol')->get(); // Cargar usuarios con sus roles
        
        return view('usuarios.index', compact('usuarios'));
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_institucional' => 'required|email',
            'contraseña' => 'required|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $usuario = Usuario::where('email_institucional', $request->email_institucional)
            ->where('activo', true)
            ->first();

        if (!$usuario || !Hash::check($request->contraseña, $usuario->contraseña_hash)) {
            return back()->withErrors([
                'email_institucional' => 'Credenciales incorrectas.'
            ]);
        }

        Auth::login($usuario);

        return redirect()->intended('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
