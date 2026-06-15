<?php

namespace App\Services;

use App\Models\DocumentoEstudiante;
use App\Models\Estudiante;
use App\Models\TipoDocumento;
use App\Providers\ConfiguracionServiceProvider;


use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentoAutomaticoService
{
    /**
 * Guardar o actualizar un documento PDF
 */
    private static function guardarOActualizarDocumento(
        Estudiante $estudiante,
        TipoDocumento $tipoDocumento,
        $pdfContent,
        $nombreArchivo
    ) {
        $carpeta = 'estudiantes/' . $estudiante->numero_de_control;
        $rutaCompleta = $carpeta . '/' . $nombreArchivo;
        
        // Guardar PDF
        Storage::disk('documentos')->put($rutaCompleta, $pdfContent);
        
        $documentoExistente = DocumentoEstudiante::where('estudiante_id', $estudiante->id)
            ->where('tipo_documento_id', $tipoDocumento->id)
            ->first();
        
        $data = [
            'ruta_archivo' => $rutaCompleta,
            'nombre_original' => $nombreArchivo,
            'mime_type' => 'application/pdf',
            'peso_bytes' => Storage::disk('documentos')->size($rutaCompleta),
        ];
        
        if ($documentoExistente) {
            // Actualizar
            $data['actualizado_en'] = now();
            $documentoExistente->update($data);
        } else {
            // Crear
            $data['subido_en'] = now();
            $data['actualizado_en'] = null;
            DocumentoEstudiante::create(array_merge([
                'estudiante_id' => $estudiante->id,
                'tipo_documento_id' => $tipoDocumento->id,
            ], $data));
        }
    }

    public static function guardarAnteproyecto(Estudiante $estudiante)
    {
        $tipoDocumento = TipoDocumento::where('nombre', 'Anteproyecto')->first();
        if (!$tipoDocumento) return;
        
        $estudiante->load(['proyecto.actividades.cronogramas', 'proyecto.asesor']);
        
        $pdf = Pdf::loadView('estudiante.impresiones.anteproyecto', compact('estudiante'));
        $pdf->setPaper('letter', 'portrait');
        
        $nombreArchivo = 'anteproyecto_' . $estudiante->numero_de_control . '.pdf';
        
        self::guardarOActualizarDocumento($estudiante, $tipoDocumento, $pdf->output(), $nombreArchivo);
    }

    public static function guardarSolicitud($estudiante)
    {
        $tipoDocumento = TipoDocumento::where('nombre', 'Solicitud de residencia profesional')->first();
        if (!$tipoDocumento) return;
        
        $jefe = ConfiguracionServiceProvider::get('jefe_division');
        $cantidadEstudiantes = $estudiante->proyecto->estudiantes()->count();
        
        $pdf = Pdf::loadView('estudiante.impresiones.solicitud', compact('jefe', 'estudiante', 'cantidadEstudiantes'));
        
        $nombreArchivo = 'solicitud_' . $estudiante->numero_de_control . '.pdf';
        
        self::guardarOActualizarDocumento($estudiante, $tipoDocumento, $pdf->output(), $nombreArchivo);
    }
}