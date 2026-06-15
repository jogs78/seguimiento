<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\DocumentoEstudiante;
use App\Providers\ConfiguracionServiceProvider;
use App\Models\TipoDocumento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreDocumentoRequest;
use Carbon\Carbon;
use Inertia\Inertia;

class DocumentoCoordinadorController extends Controller
{

    public function index()
{
    $carrera_id = session('carrera_id');

    // Obtener período actual
    $periodo_id = ConfiguracionServiceProvider::get('periodo_id');


    // Obtener estudiantes filtrados por carrera y período actual
    $estudiantes = Estudiante::with(['carrera'])
        ->join('proyectos', 'estudiantes.proyecto_id', '=', 'proyectos.id')
        ->where('proyectos.periodo_id', $periodo_id)
        ->where('estudiantes.carrera_id', $carrera_id)
        ->select('estudiantes.*') // IMPORTANTE para evitar conflictos
        ->get();

    // Obtener tipos de documento
    $tiposDocumento = TipoDocumento::orderBy('id')->get();

    // Obtener documentos agrupados por estudiante
    $documentos = DocumentoEstudiante::whereIn(
            'estudiante_id',
            $estudiantes->pluck('id')
        )
        ->get()
        ->groupBy('estudiante_id');

    $estudiantesData = [];

    // Total de documentos obligatorios
    $totalRequeridos = $tiposDocumento
        ->where('obligatorio', true)
        ->count();

    foreach ($estudiantes as $estudiante) {

        $documentosEstudiante = $documentos->get(
            $estudiante->id,
            collect()
        );

        $documentosData = [];
        $completados = 0;

        foreach ($tiposDocumento as $tipo) {

            $doc = $documentosEstudiante
                ->firstWhere('tipo_documento_id', $tipo->id);

            $documentosData[$this->getTipoKey($tipo->id)] = [
                'subido' => !is_null($doc),
                'nombre' => $doc?->nombre_original,
                'tamaño' => $doc
                    ? round(($doc->peso_bytes ?? 0) / 1024, 2)
                    : null,
                'fecha' => $doc
                    ? Carbon::parse($doc->subido_en)->format('d/m/Y')
                    : null,
                'id' => $doc?->id
            ];

            if ($tipo->obligatorio && $doc) {
                $completados++;
            }
        }

        $progreso = $totalRequeridos > 0
            ? round(($completados / $totalRequeridos) * 100)
            : 0;

        $estudiantesData[] = [
            'id' => $estudiante->id,
            'numero_de_control' => $estudiante->numero_de_control,
            'nombre' => $estudiante->nombre,
            'apellido_paterno' => $estudiante->apellido_paterno,
            'apellido_materno' => $estudiante->apellido_materno,
            'carrera' => $estudiante->carrera?->nombre,
            'proyecto' => $estudiante->proyecto?->nombre,
            'progreso' => $progreso,
            'documentos' => $documentosData
        ];
    }

    return Inertia::render('estudiante/listar-evidencias', [
        'estudiantes' => $estudiantesData,
        'tiposDocumento' => $tiposDocumento
    ]);
}
    
    private function getTipoKey($id)
    {
        $keys = [
            1 => 'solicitud',
            2 => 'anteproyecto',
            3 => 'cancelacion',
            4 => 'kardex',
            5 => 'seguro',
            6 => 'servicio'
        ];
        return $keys[$id] ?? 'desconocido';
    }
}