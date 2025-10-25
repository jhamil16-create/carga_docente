<?php

namespace App\Http\Controllers;

use App\Models\Reporte;
use App\Models\Asistencia;
use App\Models\Asignacion;
use App\Models\Docente;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ReporteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reportes = Reporte::with('usuario')
            ->orderBy('fecha_generacion', 'desc')
            ->paginate(10);
        return view('reportes.index', compact('reportes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $docentes = Docente::with('usuario')->get();
        $grupos = Grupo::with('materia')->get();
        
        return view('reportes.create', compact('docentes', 'grupos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tipo_reporte' => 'required|string|in:asistencia_docente,asistencia_grupo,asistencia_general,resumen_mensual',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'docente_id' => 'nullable|exists:docentes,docente_id',
            'grupo_id' => 'nullable|exists:grupos,grupo_id',
            'formato' => 'required|in:pdf,excel'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Crear el registro del reporte
        $reporte = Reporte::create([
            'usuario_id' => Auth::user()->usuario_id,
            'tipo_reporte' => $request->tipo_reporte,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'fecha_generacion' => now(),
        ]);

        // Generar el reporte según el tipo
        $rutaArchivo = $this->generarReporte($reporte, $request);
        
        // Actualizar la ruta del archivo
        $reporte->update(['ruta_archivo' => $rutaArchivo]);

        return redirect()->route('reportes.index')
            ->with('success', 'Reporte generado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reporte $reporte)
    {
        return view('reportes.show', compact('reporte'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reporte $reporte)
    {
        // Los reportes generalmente no se editan, pero se puede implementar si es necesario
        return redirect()->route('reportes.index')
            ->with('info', 'Los reportes no pueden ser editados. Genere un nuevo reporte si es necesario.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reporte $reporte)
    {
        // Los reportes generalmente no se actualizan
        return redirect()->route('reportes.index')
            ->with('info', 'Los reportes no pueden ser actualizados.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reporte $reporte)
    {
        // Eliminar el archivo físico si existe
        if ($reporte->ruta_archivo && Storage::exists($reporte->ruta_archivo)) {
            Storage::delete($reporte->ruta_archivo);
        }

        $reporte->delete();

        return redirect()->route('reportes.index')
            ->with('success', 'Reporte eliminado exitosamente.');
    }

    /**
     * Descargar reporte
     */
    public function descargar(Reporte $reporte)
    {
        if (!$reporte->ruta_archivo || !Storage::exists($reporte->ruta_archivo)) {
            return redirect()->back()
                ->with('error', 'El archivo del reporte no existe.');
        }

        return Storage::download($reporte->ruta_archivo);
    }

    /**
     * Generar reporte según el tipo
     */
    private function generarReporte(Reporte $reporte, Request $request)
    {
        $datos = $this->obtenerDatosReporte($reporte, $request);
        
        $nombreArchivo = 'reporte_' . $reporte->tipo_reporte . '_' . 
                        $reporte->fecha_inicio . '_' . $reporte->fecha_fin . '_' . 
                        time() . '.' . $request->formato;

        if ($request->formato === 'pdf') {
            return $this->generarPDF($datos, $nombreArchivo, $reporte->tipo_reporte);
        } else {
            return $this->generarExcel($datos, $nombreArchivo, $reporte->tipo_reporte);
        }
    }

    /**
     * Obtener datos para el reporte
     */
    private function obtenerDatosReporte(Reporte $reporte, Request $request)
    {
        $fechaInicio = Carbon::parse($reporte->fecha_inicio);
        $fechaFin = Carbon::parse($reporte->fecha_fin);

        switch ($reporte->tipo_reporte) {
            case 'asistencia_docente':
                return $this->obtenerAsistenciaDocente($request->docente_id, $fechaInicio, $fechaFin);
            
            case 'asistencia_grupo':
                return $this->obtenerAsistenciaGrupo($request->grupo_id, $fechaInicio, $fechaFin);
            
            case 'asistencia_general':
                return $this->obtenerAsistenciaGeneral($fechaInicio, $fechaFin);
            
            case 'resumen_mensual':
                return $this->obtenerResumenMensual($fechaInicio, $fechaFin);
            
            default:
                return [];
        }
    }

    /**
     * Obtener datos de asistencia por docente
     */
    private function obtenerAsistenciaDocente($docente_id, $fechaInicio, $fechaFin)
    {
        return Asistencia::with('asignacion.docente.usuario', 'asignacion.grupo.materia', 'asignacion.aula')
            ->whereHas('asignacion', function ($query) use ($docente_id) {
                $query->where('docente_id', $docente_id);
            })
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->orderBy('fecha', 'desc')
            ->get();
    }

    /**
     * Obtener datos de asistencia por grupo
     */
    private function obtenerAsistenciaGrupo($grupo_id, $fechaInicio, $fechaFin)
    {
        return Asistencia::with('asignacion.docente.usuario', 'asignacion.grupo.materia', 'asignacion.aula')
            ->whereHas('asignacion', function ($query) use ($grupo_id) {
                $query->where('grupo_id', $grupo_id);
            })
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->orderBy('fecha', 'desc')
            ->get();
    }

    /**
     * Obtener datos de asistencia general
     */
    private function obtenerAsistenciaGeneral($fechaInicio, $fechaFin)
    {
        return Asistencia::with('asignacion.docente.usuario', 'asignacion.grupo.materia', 'asignacion.aula')
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->orderBy('fecha', 'desc')
            ->get();
    }

    /**
     * Obtener resumen mensual
     */
    private function obtenerResumenMensual($fechaInicio, $fechaFin)
    {
        return [
            'total_asistencias' => Asistencia::whereBetween('fecha', [$fechaInicio, $fechaFin])->count(),
            'presentes' => Asistencia::where('estado', 'presente')->whereBetween('fecha', [$fechaInicio, $fechaFin])->count(),
            'ausentes' => Asistencia::where('estado', 'ausente')->whereBetween('fecha', [$fechaInicio, $fechaFin])->count(),
            'tardanzas' => Asistencia::where('estado', 'tardanza')->whereBetween('fecha', [$fechaInicio, $fechaFin])->count(),
            'por_docente' => Asistencia::with('asignacion.docente.usuario')
                ->whereBetween('fecha', [$fechaInicio, $fechaFin])
                ->get()
                ->groupBy('asignacion.docente.usuario.nombre')
                ->map(function ($asistencias) {
                    return [
                        'total' => $asistencias->count(),
                        'presente' => $asistencias->where('estado', 'presente')->count(),
                        'ausente' => $asistencias->where('estado', 'ausente')->count(),
                        'tardanza' => $asistencias->where('estado', 'tardanza')->count(),
                    ];
                })
        ];
    }

    /**
     * Generar PDF (placeholder - requiere implementación con DomPDF o similar)
     */
    private function generarPDF($datos, $nombreArchivo, $tipoReporte)
    {
        // Aquí se implementaría la generación de PDF
        // Por ahora retornamos un placeholder
        $rutaArchivo = 'reportes/' . $nombreArchivo;
        Storage::put($rutaArchivo, 'Contenido del reporte PDF - ' . $tipoReporte);
        return $rutaArchivo;
    }

    /**
     * Generar Excel (placeholder - requiere implementación con PhpSpreadsheet o similar)
     */
    private function generarExcel($datos, $nombreArchivo, $tipoReporte)
    {
        // Aquí se implementaría la generación de Excel
        // Por ahora retornamos un placeholder
        $rutaArchivo = 'reportes/' . $nombreArchivo;
        Storage::put($rutaArchivo, 'Contenido del reporte Excel - ' . $tipoReporte);
        return $rutaArchivo;
    }
}
