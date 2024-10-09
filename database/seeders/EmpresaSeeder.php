<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Empresa;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void 
    {
        $empresa = new Empresa();
        $empresa->nombre = "Almacenes y graneros Torre de roca S.A";
        $empresa->giro = "Servicios";
        $empresa->rfc = "HNG184936OUY";
        $empresa->direccion = "5ta norte y 6ta poniente 120 - C";
        $empresa->telefono = "9614829432";
        $empresa->correo = "Torre_Roca_SA@gmail.com";
        $empresa->titular = "Marcos Alcantara Solis";
        $empresa->puesto_titular = "Representante legal de la Sociedad";
        $empresa->asesor_externo = "Daniela Robles Cruz";
        $empresa->puesto_asesor = "Asesora financiera";
        $empresa->informacion = "Somos una empresa encargada de almecenar productos alimienticios";
        $empresa->save(); 

        $empresa = new Empresa();
        $empresa->nombre = "Frituras y Botanas El rancherito";
        $empresa->giro = "industrial";
        $empresa->rfc = "IYO969745PMU";
        $empresa->direccion = "9na sur y 3ra oriente 76 - A";
        $empresa->telefono = "9679865312";
        $empresa->correo = "ElRancherito@gmail.com";
        $empresa->titular = "Fernando Vazques Sol";
        $empresa->puesto_titular = "Dueño de la marca";
        $empresa->asesor_externo = "Alfonso Mena Becerra";
        $empresa->puesto_asesor = "Asesor de Ventas e imagen";
        $empresa->informacion = "Somos una empresa productora y distribuidora de productos fritos";
        $empresa->save(); 
    }

}
