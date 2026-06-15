<?php

namespace App\Helpers;

use App\Models\Proyecto;
use App\Models\DocumentoEstudiante;
use App\Models\Estudiante;

class DocumentosHelper
{
    /**
     * Mapeo de nombres de tipos de documentos a claves simplificadas
     */
    protected static $mapeoDocumentos = [
        'Solicitud de residencia profesional' => 'solicitud',
        'Anteproyecto' => 'anteproyecto',
        'Solicitud de cancelación de proyecto' => 'solicitud_cancelacion',
        'KARDEX (SII)' => 'kardex',
        'Afiliación del seguro social' => 'seguro',
        'Constancia de servicio social' => 'servicio',
    ];

    /**
     * Procesar documentos de una colección de proyectos
     */
    public static function procesarDocumentosDeProyectos($proyectos)
    {
        $documentosProcesados = [];

        foreach ($proyectos as $proyecto) {
            foreach ($proyecto->estudiantes as $estudiante) {
                $documentosProcesados[$estudiante->id] = self::inicializarEstructuraDocumentos();
                
                foreach ($estudiante->documentos as $documento) {
                    $nombreTipo = $documento->tipoDocumento->nombre;
                    
                    if (isset(self::$mapeoDocumentos[$nombreTipo])) {
                        $clave = self::$mapeoDocumentos[$nombreTipo];
                        $documentosProcesados[$estudiante->id][$clave] = self::formatearDocumento($documento, $nombreTipo);
                    }
                }
            }
        }

        return $documentosProcesados;
    }

    /**
     * Inicializar estructura de documentos
     */
    protected static function inicializarEstructuraDocumentos()
    {
        return [
            'solicitud' => null,
            'anteproyecto' => null,
            'solicitud_cancelacion' => null,
            'kardex' => null,
            'seguro' => null,
            'servicio' => null,
        ];
    }

    /**
     * Formatear un documento para la vista
     */
    protected static function formatearDocumento($documento, $nombreTipo)
    {
        return [
            'id' => $documento->id,
            'subido' => !is_null($documento->ruta_archivo) || !is_null($documento->url_documento),
            'nombre' => $documento->nombre_original ?? $nombreTipo,
            'ruta_archivo' => $documento->ruta_archivo,
            'url_documento' => $documento->url_documento,
        ];
    }

    /**
     * Cargar relaciones de documentos para proyectos
     */
    public static function cargarRelacionesDocumentos($query)
    {
        return $query->with(['estudiantes' => function($q) {
            $q->with([
                'primer',
                'segundo',
                'ultimo',
                'documentos.tipoDocumento'
            ]);
        }]);
    }
}