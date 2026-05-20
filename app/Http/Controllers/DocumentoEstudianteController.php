<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentoEstudiante;
use App\Models\TipoDocumento;
use App\Http\Requests\StoreDocumentoRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
class DocumentoEstudianteController extends Controller
{

public function index2()
{
    $tiposDocumento = [
        ['id' => 1, 'nombre' => 'Prueba 1'],
        ['id' => 2, 'nombre' => 'Prueba 2'],
    ];
    $documentos = []; // Vacío para pruebas
    return Inertia::render('estudiante/prueba', [
        'tiposDocumento' => $tiposDocumento,
        'documentos' => $documentos
    ]);
}

   public function index()
{
    $estudiante = auth()->user()->usa;

    if (!$estudiante) {
        return redirect()->route('home')->with('error', 'No se encontró el estudiante autenticado.');
    }
    
    // ✅ DATOS DE PRUEBA (MOCK) - Comenta esto cuando ya funcione
      // Obtener TODOS los tipos de documento
    $tiposDocumento = TipoDocumento::orderBy('id')->get();

    
    $documentos = DocumentoEstudiante::with('tipoDocumento')
        ->where('estudiante_id', $estudiante->id)
        ->get();
    
    // ✅ Descomenta esto cuando quieras usar la BD
    // $documentos = DocumentoEstudiante::with('tipoDocumento')
    //     ->where('estudiante_id', $estudiante->id)
    //     ->get();
    // 
    // $tiposDocumento = TipoDocumento::orderBy('id')->get();

     return Inertia::render('estudiante/evidencias', [
        'tiposDocumento' => $tiposDocumento,
        'documentos' => $documentos
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

            $nombreArchivo = time() . '_' .
                Str::slug($tipoDocumento->nombre) . '.' .
                $archivo->getClientOriginalExtension();

            $carpeta = 'estudiantes/' .
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
                'tamano_bytes' => $tamano,

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
