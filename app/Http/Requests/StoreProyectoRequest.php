<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            "nombre"=>"required",
            "objetivo_general"=>"required",
            "lugar"=>"required",
            "informacion"=>"required",
            "justificacion"=>"required",
            "asesor_id"=>"required",
            "empresa_id"=>"required",
            "periodo_id"=>"required",
        ];
    }

    public function messages(): array
    {
        return [
            "nombre.required"=>"Es necesario llenar este campo",
            "objetivo_general.required"=>"Es necesario llenar este campo",
            "lugar.required"=>"Es necesario llenar este campo",
            "informacion.required"=>"Es necesario llenar este campo",
            "justificacion.required"=>"Es necesario llenar este campo",
            "asesor_id.required"=>"Es necesario llenar este campo",
            "empresa_id.required"=>"Es necesario llenar este campo",
            "periodo_id.required"=>"Es necesario llenar este campo",
        ];
    }
}
