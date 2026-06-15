<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
class StoreProyectoRequest extends FormRequest
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
            "num_registro" => "nullable|unique:proyectos,num_registro",
            "nombre"=>"required",
            "objetivo_general"=>"required",
            "lugar"=>"required",
            "informacion"=>"required",
            "justificacion"=>"required",
            "origen"=>"required|in:Banco de Proyectos,Propuesta propia,Trabajador",
            "asesor_id"=>"required",
            "empresa_id" => [
                'required',
                function ($attribute, $value, $fail) {
                    if ($value !== '-1' && !\DB::table('empresas')->where('id', $value)->exists()) {
                        $fail('El campo empresa_id debe ser un ID válido o -1.');
                    }
                },
            ],
            "rfc"=>"nullable|max:15|min:12",
            "periodo_id"=>"required",
        ];
    }

    public function messages(): array
    {
        return [
            "nombre.required"=>"El nombre del proyecto es requerido",
            "objetivo_general.required"=>"Por favor ingrese un objetivo general",
            "lugar.required"=>"Por favor ingrese un lugar",
            "informacion.required"=>"Por favor llene el campo de informacion",
            "justificacion.required"=>"Por favor llene el campo justificacion",
            "num_registro.unique"=>"El número de registro ya está en uso",
            "origen.in" => "El origen seleccionado no es válido",
            "asesor_id.required"=>"asesor_id Es necesario llenar este campo",
            "empresa_id.required"=>"empresa_id Es necesario llenar este campo",
            "periodo_id.required"=>"periodo_id Es necesario llenar este campo",
            "rfc.max" => "El RFC no puede tener más de 13 caracteres.",
            "rfc.min" => "El RFC no puede tener menos de 12 caracteres.",
        ];
    }
}
