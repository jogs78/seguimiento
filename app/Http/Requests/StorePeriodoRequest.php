<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePeriodoRequest extends FormRequest
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

            "fecha_inicio"=>"required",
            "fecha_final"=>"required|after:fecha_inicio|after_or_equal:fecha_final_reporte_final",

            "fecha_inicio_1er_reporte"=>"required",
            "fecha_final_1er_reporte"=>"required|after:fecha_inicio_1er_reporte",

            "fecha_inicio_2do_reporte"=>"required",
            "fecha_final_2do_reporte"=>"required|after:fecha_inicio_2do_reporte",

            "fecha_inicio_reporte_final"=>"required",
            "fecha_final_reporte_final"=>"required|after:fecha_inicio_reporte_final",
        ];
    }

    public function messages(): array
    {
        return [
            "nombre.required"=>"Es necesario llenar este campo",

            "fecha_inicio.required"=>"Es necesario llenar este campo",
            "fecha_final.required"=>"Es necesario llenar este campo",
            "fecha_final.after"=>"no puedes poner una fecha de inicio posterior a la final",
            "fecha_final.after_or_equal"=>"la fecha de conclusion tiene que ser posterior o igual al fechas final del ultimo reporte",

            "fecha_inicio_1er_reporte.required"=>"Es necesario llenar este campo",
            "fecha_final_1er_reporte.required"=>"Es necesario llenar este campo",
            "fecha_final_1er_reporte.after"=>"no puedes poner una fecha de inicio posterior a la final",

            "fecha_inicio_2do_reporte.required"=>"Es necesario llenar este campo",
            "fecha_final_2do_reporte.required"=>"Es necesario llenar este campo",
            "fecha_final_2do_reporte.after"=>"no puedes poner una fecha de inicio posterior a la final",


            "fecha_inicio_reporte_final.required"=>"Es necesario llenar este campo",
            "fecha_final_reporte_final.required"=>"Es necesario llenar este campo",
            "fecha_final_reporte_final.after"=>"no puedes poner una fecha de inicio posterior a la final",
            

        ];
    }
}
