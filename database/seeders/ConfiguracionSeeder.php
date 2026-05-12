<?php

namespace Database\Seeders;

use App\Models\Configuracion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConfiguracionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $nueva = new Configuracion();
        $nueva->variable = "jefe_division";
        $nueva->valor = "DR. FCO.ALEXANDER RINCON MOLINA";
        $nueva->save();

        $nueva = new Configuracion();
        $nueva->variable = "tecnologico";
        $nueva->valor = "Tecnológico de Tuxtla Gutiérrez";
        $nueva->save();

        $nueva = new Configuracion();
        $nueva->variable = "año";
        $nueva->valor = "2026";
        $nueva->save();

        $nueva = new Configuracion();
        $nueva->variable = "periodo_id";
        $nueva->valor = "11";
        $nueva->save();

        $nueva = new Configuracion();
        $nueva->variable = "delegado";
        $nueva->valor = "si";
        $nueva->save();

    }
}
