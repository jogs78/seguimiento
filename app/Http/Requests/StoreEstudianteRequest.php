<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEstudianteRequest extends FormRequest
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
            "apellido_paterno"=>"required",
            "apellido_materno"=>"required",
            "correo_electronico"=>"required|email",
            "numero_de_control"=>"required|max_digits:8|min_digits:3|gt:555",
            "nombre_del_proyecto"=>"required",
            "telefono"=>"required|numeric|max_digits:10|min_digits:10",
            "carrera"=>"required",
            "direccion"=>"required",
            "numero_de_seguridad_social"=>"required",

           
        ];
    }

    public function messages(): array
    {
        return [
            "nombre.required"=>"Es necesario llenar este campo",
            "apellido_paterno.required"=>"Es necesario llenar este campo",
            "apellido_materno.required"=>"Es necesario llenar este campo",
            "correo_electronico.required"=>"Es necesario llenar este campo",
            'correo_electronico.email' => 'Por favor, introduce un correo electrónico válido.',
            "numero_de_control.required"=>"Es necesario llenar este campo",
            "numero_de_control.numeric"=>"Este Campo debe ser un Numero",
            "numero_de_control.max_digits"=>"Este Campo admite maximo 8 digitos",
            "numero_de_control.min_digits"=>"Este Campo admite minimo 3 digitos",
            "numero_de_control.gt"=>"Este Campo debe ser mayor que 555",
            "nombre_del_proyecto.required"=>"Es necesario llenar este campo",
            "telefono.required"=>"Es necesario Poner el Numero de Telefono",
            "telefono.numeric"=>"Este Campo debe ser un Numero",
            "telefono.max_digits"=>"Este Campo admite maximo 10 digitos",
            "telefono.min_digits"=>"Este Campo admite minimo 10 digitos",
            "carrera.required"=>"Es necesario llenar este campo",
            "direccion.required"=>"Es necesario llenar este campo",
            "numero_de_seguridad_social.required"=>"Es necesario llenar este campo",
        ];
    }
}
