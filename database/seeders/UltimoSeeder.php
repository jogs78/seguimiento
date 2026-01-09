<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ultimo;

class UltimoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ultimo = new Ultimo();
        $ultimo->estudiante_id = 1;
        $ultimo->portada_externo = 2;
        $ultimo->agradecimientos_externo = 2;
        $ultimo->resumen_externo = 2;
        $ultimo->indice_externo = 2;
        $ultimo->introduccion_externo = 2;
        $ultimo->problemas_externo = 5;
        $ultimo->objetivos_externo = 2;
        $ultimo->justificacion_externo = 3;
        $ultimo->marco_teorico_externo = 10;
        $ultimo->procedimiento_externo = 5;
        $ultimo->resultados_externo = 45;
        $ultimo->conclusiones_externo = 15;
        $ultimo->competencias_externo = 3;
        $ultimo->fuentes_externo = 2;
        $ultimo->promedio_externo = 100;
        $ultimo->comentarios_externo = "ultimo comentario externo";
        $ultimo->portada_interno = 2;
        $ultimo->agradecimientos_interno = 2;
        $ultimo->resumen_interno = 2;
        $ultimo->indice_interno = 2;
        $ultimo->introduccion_interno = 2;
        $ultimo->problemas_interno = 5;
        $ultimo->objetivos_interno = 2;
        $ultimo->justificacion_interno = 3;
        $ultimo->marco_teorico_interno = 10;
        $ultimo->procedimiento_interno = 5;
        $ultimo->resultados_interno = 45;
        $ultimo->conclusiones_interno = 15;
        $ultimo->competencias_interno = 3;
        $ultimo->fuentes_interno = 2;
        $ultimo->promedio_interno = 100;
        $ultimo->comentarios_interno = "ultimo comentario externo";
        $ultimo->save(); 

        $ultimo = new Ultimo();
        $ultimo->estudiante_id = 4;
        $ultimo->portada_externo = 2;
        $ultimo->agradecimientos_externo = 2;
        $ultimo->resumen_externo = 2;
        $ultimo->indice_externo = 2;
        $ultimo->introduccion_externo = 2;
        $ultimo->problemas_externo = 5;
        $ultimo->objetivos_externo = 2;
        $ultimo->justificacion_externo = 3;
        $ultimo->marco_teorico_externo = 10;
        $ultimo->procedimiento_externo = 5;
        $ultimo->resultados_externo = 45;
        $ultimo->conclusiones_externo = 15;
        $ultimo->competencias_externo = 3;
        $ultimo->fuentes_externo = 2;
        $ultimo->promedio_externo = 100;
        $ultimo->comentarios_externo = "ultimo comentario externo";
        $ultimo->portada_interno = 2;
        $ultimo->agradecimientos_interno = 2;
        $ultimo->resumen_interno = 2;
        $ultimo->indice_interno = 2;
        $ultimo->introduccion_interno = 2;
        $ultimo->problemas_interno = 5;
        $ultimo->objetivos_interno = 2;
        $ultimo->justificacion_interno = 3;
        $ultimo->marco_teorico_interno = 10;
        $ultimo->procedimiento_interno = 5;
        $ultimo->resultados_interno = 45;
        $ultimo->conclusiones_interno = 15;
        $ultimo->competencias_interno = 3;
        $ultimo->fuentes_interno = 2;
        $ultimo->promedio_interno = 100;
        $ultimo->comentarios_interno = "ultimo comentario externo";
        $ultimo->save(); 

    }

}
