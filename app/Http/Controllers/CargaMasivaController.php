<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Rol;
use App\Models\CargaMasiva;
use App\Models\Docente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CargaMasivaController extends Controller
{
    public function index()
    {
        $cargas = CargaMasiva::with('usuario') // 👈 AGREGAR eager loading
            ->orderBy('fecha_carga', 'desc')
            ->limit(10)
            ->get();

        return view('carga-masiva.index', compact('cargas'));
    }

    public function create()
    {
        return view('carga-masiva.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'archivo' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240',
            'tipo' => 'required|in:estudiantes,docentes,materias,aulas'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ($request->tipo !== 'docentes') {
            return back()->with('warning', 'Por ahora solo está habilitada la carga de docentes/usuarios.');
        }

        $file = $request->file('archivo');
        $usuarios_creados = [];
        $errores = [];

        DB::beginTransaction();

        try {
            $contenido = file_get_contents($file->getRealPath());
            $lineas = explode("\n", $contenido);
            
            $encabezados = str_getcsv(array_shift($lineas));
            
            $encabezados = array_map(function($header) {
                return trim(strtolower(str_replace("\xEF\xBB\xBF", '', $header)));
            }, $encabezados);

            foreach ($lineas as $index => $linea) {
                $linea = trim($linea);
                if (empty($linea)) continue;

                $datos = str_getcsv($linea);
                
                $fila = [];
                foreach ($encabezados as $key => $header) {
                    $fila[$header] = isset($datos[$key]) ? trim($datos[$key]) : '';
                }

                if (empty($fila['nombre']) || empty($fila['apellido']) || empty($fila['email_institucional'])) {
                    $errores[] = "Línea " . ($index + 2) . ": Faltan datos obligatorios (nombre, apellido o email)";
                    continue;
                }

                $nombre = $fila['nombre'];
                $apellido = $fila['apellido'];
                $email = $fila['email_institucional'];
                $tipo_usuario = isset($fila['tipo_usuario']) ? strtolower($fila['tipo_usuario']) : 'docente';

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errores[] = "Línea " . ($index + 2) . ": Email inválido - $email";
                    continue;
                }

                if (Usuario::where('email_institucional', $email)->exists()) {
                    if ($request->has('actualizar_existentes')) {
                        $usuario = Usuario::where('email_institucional', $email)->first();
                        $usuario->nombre = $nombre;
                        $usuario->apellido = $apellido;
                        $usuario->save();
                        
                        $usuarios_creados[] = [
                            'nombre' => $nombre . ' ' . $apellido,
                            'email' => $email,
                            'codigo' => $usuario->codigo_usuario,
                            'contraseña' => 'Sin cambios',
                            'rol' => $this->obtenerNombreRol($usuario->rol_id),
                            'accion' => 'Actualizado'
                        ];
                    } else {
                        $errores[] = "Línea " . ($index + 2) . ": El email $email ya está registrado";
                    }
                    continue;
                }

                $rol_id = $this->determinarRol($tipo_usuario);
                $codigo_usuario = $this->generarCodigoUsuario($tipo_usuario);
                $contraseña_temporal = $this->generarContraseñaTemporal();

                // Crear usuario
                $usuario = new Usuario();
                $usuario->rol_id = $rol_id;
                $usuario->codigo_usuario = $codigo_usuario;
                $usuario->nombre = $nombre;
                $usuario->apellido = $apellido;
                $usuario->email_institucional = $email;
                $usuario->contraseña_hash = Hash::make($contraseña_temporal);
                $usuario->activo = true;
                $usuario->save(); 

                // Si es docente (rol_id = 2), crear registro en la tabla Docente
                if ($rol_id == 2) {
                    Docente::create([
                    'usuario_id' => $usuario->usuario_id,
                    'especialidad' => 'Por definir',
                    'telefono' => null,
                    'fecha_registro' => now()
                    ]);
                }

                $usuarios_creados[] = [
                    'nombre' => $nombre . ' ' . $apellido,
                    'email' => $email,
                    'codigo' => $codigo_usuario,
                    'contraseña' => $contraseña_temporal,
                    'rol' => $this->obtenerNombreRol($rol_id),
                    'accion' => 'Creado'
                ];
            }

            $carga = CargaMasiva::create([
                'archivo_nombre' => $file->getClientOriginalName(),
                'registros_exitosos' => count($usuarios_creados),
                'registros_fallidos' => count($errores),
                'fecha_carga' => now(),
                'usuario_admin_id' => auth()->id()
            ]);

            $carga_id = $carga->carga_id;

            if (count($errores) > 0) {
                foreach ($errores as $index => $error) {
                    preg_match('/Línea (\d+):/', $error, $matches);
                    $numero_fila = isset($matches[1]) ? (int)$matches[1] : ($index + 1);
                    
                    DB::table('errorescarga')->insert([
                        'carga_id' => $carga_id,
                        'numero_fila' => $numero_fila,
                        'mensaje_error' => $error,
                        'fecha_error' => now()
                    ]);
                }
            }

            DB::commit();

            return view('carga-masiva.show', [
                'usuarios_creados' => $usuarios_creados,
                'errores' => $errores,
                'total_exitosos' => count($usuarios_creados),
                'total_errores' => count($errores),
                'carga_id' => $carga_id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al procesar el archivo: ' . $e->getMessage()]);
        }
    }

    private function determinarRol($tipo_usuario)
    {
        $roles = [
            'administrador' => 1,
            'admin' => 1,
            'coordinador' => 1,
            'docente' => 2,
            'profesor' => 2,
            'teacher' => 2
        ];

        return $roles[$tipo_usuario] ?? 2;
    }

    private function generarCodigoUsuario($tipo_usuario)
    {
        $prefijos = [
            'administrador' => 'ADM',
            'admin' => 'ADM',
            'coordinador' => 'CRD',
            'docente' => 'DOC',
            'profesor' => 'DOC',
            'teacher' => 'DOC'
        ];

        $prefijo = $prefijos[$tipo_usuario] ?? 'DOC';

        $ultimo_codigo = Usuario::where('codigo_usuario', 'LIKE', $prefijo . '%')
            ->orderBy('codigo_usuario', 'desc')
            ->value('codigo_usuario');

        if ($ultimo_codigo) {
            $numero = intval(substr($ultimo_codigo, -3)) + 1;
        } else {
            $numero = 1;
        }

        return $prefijo . str_pad($numero, 3, '0', STR_PAD_LEFT);
    }

    private function generarContraseñaTemporal()
    {
        $caracteres = 'abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $contraseña = '';
        
        for ($i = 0; $i < 8; $i++) {
            $contraseña .= $caracteres[random_int(0, strlen($caracteres) - 1)];
        }
        
        return $contraseña;
    }

    private function obtenerNombreRol($rol_id)
    {
        $rol = Rol::find($rol_id);
        return $rol ? $rol->nombre_rol : 'Desconocido';
    }

    public function descargarPlantilla($tipo)
    {
        $plantillas = [
            'docentes' => "nombre,apellido,email_institucional,tipo_usuario\nJuan,Pérez,juan.perez@ficct.uagrm.edu.bo,docente\nMaría,González,maria.gonzalez@ficct.uagrm.edu.bo,docente\nCarlos,Admin,carlos.admin@ficct.uagrm.edu.bo,administrador\n",
            'estudiantes' => "nombre,apellido,email\nPedro,Rodríguez,pedro@estudiante.edu.bo\n",
            'materias' => "nombre_materia,codigo_materia,creditos\nProgramación I,PRG101,4\n",
            'aulas' => "nombre_aula,capacidad,ubicacion\nLAB-101,40,Módulo A\n"
        ];

        $contenido = $plantillas[$tipo] ?? $plantillas['docentes'];

        return response($contenido)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', "attachment; filename=plantilla_$tipo.csv")
            ->header('Content-Transfer-Encoding', 'binary');
    }
}