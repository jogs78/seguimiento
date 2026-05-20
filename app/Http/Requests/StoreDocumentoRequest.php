<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            // Tipo documento
            'tipo_documento_id' => [
                'required',
                'exists:tipo_documentos,id'
            ],

            // Archivo opcional
            'archivo' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx',
                'max:10240', // 10MB en KB
            ],

            // URL opcional
            'url_documento' => [
                'nullable',
                'url'
            ],

        ];
    }

    /**
     * Validación extra
    */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $archivo = $this->file('archivo');
            $url = $this->url_documento;

            // Debe enviar al menos uno
            if (!$archivo && !$url) {

                $validator->errors()->add(
                    'archivo',
                    'Debe subir un archivo o proporcionar una URL.'
                );
            }

        });
    }

    /**
     * Mensajes personalizados
     */
    public function messages(): array
    {
        return [

            'tipo_documento_id.required' =>
                'El tipo de documento es obligatorio.',

            'tipo_documento_id.exists' =>
                'El tipo de documento no existe.',

            'archivo.file' =>
                'El archivo enviado no es válido.',

            'archivo.mimes' =>
                'Solo se permiten archivos PDF, DOC y DOCX.',

            'archivo.max' =>
                'El archivo supera el tamaño máximo permitido.',

            'url_documento.url' =>
                'La URL proporcionada no es válida.',
        ];
    }
}
