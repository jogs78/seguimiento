<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmpresaRequest extends FormRequest
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
            "giro"=>"required",
            "rfc"=>"required|max:13|min:12",
            "direccion"=>"required",
            "telefono"=>"required|numeric|max:10|min:10",
            "correo"=>"required|email",
            "titular"=>"required",
            "puesto_titular"=>"required",
            "asesor_externo"=>"required",
            "puesto_asesor"=>"required",
            "informacion"=>"required",
        ];
    }

    public function messages(): array
    {
        return [
            "nombre.required"=>"Es necesario llenar este campo",
            "giro.required"=>"Es necesario llenar este campo",

            "rfc.required"=>"Es necesario llenar este campo",
            "rfc.max" => "El RFC no puede tener más de 13 caracteres.",
            "rfc.min" => "El RFC no puede tener menos de 12 caracteres.",

            "direccion.required"=>"Es necesario llenar este campo",

            "telefono.required"=>"Es necesario llenar este campo",
            "telefono.numeric" => "El campo teléfono solo puede contener números",
            "telefono.max" => "El Telefono no puede tener más de 10 caracteres.",
            "telefono.min" => "El Telefono no puede tener menos de 10 caracteres.",

            "correo.required"=>"Es necesario llenar este campo",
            "correo.email" => "El correo debe ser una dirección de correo válida.",


            "titular.required"=>"Es necesario llenar este campo",
            "puesto_titular.required"=>"Es necesario llenar este campo",
            "asesor_externo.required"=>"Es necesario llenar este campo",
            "puesto_asesor.required"=>"Es necesario llenar este campo",
            "informacion.required"=>"Es necesario llenar este campo",
            
        ];
    }
}
