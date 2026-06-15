<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentoEstudiante;
use App\Models\TipoDocumento;
use App\Http\Requests\StoreDocumentoRequest;
use App\Providers\ConfiguracionServiceProvider;
use App\Models\Estudiante;
use App\Models\Periodo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
class DocumentoEstudianteController extends Controller
{


public function index()
{
    $estudiante = auth()->user()->usa;

    if (!$estudiante) {
        return redirect()
            ->route('home')
            ->with(
                'error',
                'No se encontró el estudiante autenticado.'
            );
    }

    $tiposDocumento = TipoDocumento::query();
   //si el estudiante no tiene proyecto registrado o no tiene num_registro, ocultar el documento de cancelación
    if ( !$estudiante->proyecto || !$estudiante->proyecto->num_registro) 
    {
        $tiposDocumento->where(
            'nombre',
            '!=',
            'Solicitud de cancelación de proyecto'
        );
    }

    $tiposDocumento = $tiposDocumento
        ->orderBy('id')
        ->get();

    $documentos = DocumentoEstudiante::with('tipoDocumento')
        ->where('estudiante_id', $estudiante->id)
        ->get();

    return Inertia::render('estudiante/evidencias', [
        'tiposDocumento' => $tiposDocumento,
        'documentos' => $documentos,
        'proyecto' => $estudiante->proyecto
    ]);
}

    public function store(StoreDocumentoRequest $request)
    {
        $estudiante = auth()->user()->usa;

        $tipoDocumento = TipoDocumento::findOrFail(
            $request->tipo_documento_id
        );

        $rutaArchivo = null;
        $nombreOriginal = null;
        $mimeType = null;
        $tamano = null;
      
        // Subida de archivo
        if ($request->hasFile('archivo')) {

            $archivo = $request->file('archivo');

            $nombreArchivo = Str::slug($tipoDocumento->nombre) . '.' .
                $archivo->getClientOriginalExtension();

            $periodoActual = Periodo::find(ConfiguracionServiceProvider::get('periodo_id'));

            $carpeta = 'estudiantes_' . $periodoActual->nombre . '/expedientes/' .
                    Str::slug($estudiante->nombre . ' ' . $estudiante->apellido_paterno . ' ' . $estudiante->apellido_materno) . '_' .
                    $estudiante->numero_de_control;
            $rutaArchivo = Storage::disk('documentos')
                ->putFileAs(
                    $carpeta,
                    $archivo,
                    $nombreArchivo
                );

            $nombreOriginal =
                $archivo->getClientOriginalName();

            $mimeType =
                $archivo->getMimeType();

            $tamano =
                $archivo->getSize();
        }

       
        //Guardar registro BD

        DocumentoEstudiante::updateOrCreate(

            [
                'estudiante_id' => $estudiante->id,
                'tipo_documento_id' =>
                    $tipoDocumento->id,
            ],

            [
                'ruta_archivo' => $rutaArchivo,
                'nombre_original' => $nombreOriginal,
                'mime_type' => $mimeType,
                'peso_bytes' => $tamano,

                'url_documento' =>
                    $request->url_documento,

                'subido_en' => now(),
            ]
        );

        return back()->with(
            'success',
            'Documento guardado correctamente.'
        );
    }

    public function download($id)
    {
        $documento = DocumentoEstudiante::findOrFail($id);

        if (!$documento->ruta_archivo) {
            abort(404);
        }

        return Storage::disk('documentos')
            ->download(
                $documento->ruta_archivo,
                $documento->nombre_original
            );
    }

    public function ver($id)
    {
        $documento = DocumentoEstudiante::findOrFail($id);

        return Storage::disk('documentos')
            ->response($documento->ruta_archivo);
    }
    public function destroy($id)
    {
        $documento = DocumentoEstudiante::findOrFail($id);

        // borrar archivo físico
        if (
            $documento->ruta_archivo &&
            Storage::disk('documentos')
                ->exists($documento->ruta_archivo)
        ) {

            Storage::disk('documentos')
                ->delete($documento->ruta_archivo);
        }

        // borrar registro
        $documento->delete();

        return back()->with(
            'success',
            'Documento eliminado.'
        );
    }
}
