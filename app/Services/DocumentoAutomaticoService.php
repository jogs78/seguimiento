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
    public static function guardarAnteproyecto(Estudiante $estudiante)
    {
        /*
        |--------------------------------------------------------------------------
        | Obtener tipo documento
        |--------------------------------------------------------------------------
        */
        $tipoDocumento = TipoDocumento::where('nombre', 'Anteproyecto')->first();

        if (!$tipoDocumento) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Cargar relaciones necesarias
        |--------------------------------------------------------------------------
        */
        $estudiante->load([
            'proyecto.actividades.cronogramas',
            'proyecto.asesor'
        ]);

        /*
        |--------------------------------------------------------------------------
        | Generar PDF
        |--------------------------------------------------------------------------
        */
        $pdf = Pdf::loadView('estudiante.impresiones.anteproyecto', compact('estudiante'));
        
        // Configurar PDF
        $pdf->setPaper('letter', 'portrait');
        $pdf->setOptions([
            'defaultFont' => 'sans-serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true
        ]);

        /*
        |--------------------------------------------------------------------------
        | Nombre archivo
        |--------------------------------------------------------------------------
        */
        $nombreArchivo = 'anteproyecto_' . $estudiante->numero_de_control . '.pdf';

        /*
        |--------------------------------------------------------------------------
        | Carpeta estudiante
        |--------------------------------------------------------------------------
        */
        $carpeta = 'estudiantes/' . $estudiante->numero_de_control;

        /*
        |--------------------------------------------------------------------------
        | Ruta final
        |--------------------------------------------------------------------------
        */
        $rutaCompleta = $carpeta . '/' . $nombreArchivo;

        /*
        |--------------------------------------------------------------------------
        | Guardar PDF físicamente
        |--------------------------------------------------------------------------
        */
        Storage::disk('documentos')->put($rutaCompleta, $pdf->output());

        /*
        |--------------------------------------------------------------------------
        | Guardar o actualizar BD
        |--------------------------------------------------------------------------
        */
        DocumentoEstudiante::updateOrCreate(
            [
                'estudiante_id' => $estudiante->id,
                'tipo_documento_id' => $tipoDocumento->id,
            ],
            [
                'ruta_archivo' => $rutaCompleta,
                'nombre_original' => $nombreArchivo,
                'mime_type' => 'application/pdf',
                'peso_bytes' => Storage::disk('documentos')->size($rutaCompleta),
                'subido_en' => now(),
            ]
        );
    }

    public static function guardarSolicitud($estudiante)
    {
        $tipoDocumento = TipoDocumento::where(
            'nombre',
            'Solicitud de residencia profesional'
        )->first();

        $jefe = ConfiguracionServiceProvider::get('jefe_division');

        $cantidadEstudiantes =
            $estudiante->proyecto
                ->estudiantes()
                ->count();

        $pdf = Pdf::loadView(
            'estudiante.impresiones.solicitud',
            compact(
                'jefe',
                'estudiante',
                'cantidadEstudiantes'
            )
        );

        $nombreArchivo =
            'solicitud_' .
            $estudiante->numero_de_control .
            '.pdf';

        $carpeta =
            'estudiantes/' .
            $estudiante->numero_de_control;

        $ruta =
            $carpeta . '/' . $nombreArchivo;

        Storage::disk('documentos')->put(
            $ruta,
            $pdf->output()
        );

        DocumentoEstudiante::updateOrCreate(

            [
                'estudiante_id' => $estudiante->id,
                'tipo_documento_id' => $tipoDocumento->id
            ],

            [
                'ruta_archivo' => $ruta,
                'nombre_original' => $nombreArchivo,
                'mime_type' => 'application/pdf',
                'peso_bytes' => Storage::disk('documentos')->size($ruta),
                'subido_en' => now(),
            ]);
    }
}