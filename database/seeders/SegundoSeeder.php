<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SegundoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $segundo = new Segundo();
        $segundo->puntualidad = 8;
        $segundo->conocimiento = 19;
        $segundo->equipo = 14;
        $segundo->dedicado = 17;
        $segundo->orden = 18;
        $segundo->mejoras = 13;
        $segundo->ruta = "C:\\Users\\omner\\Desktop";
        $segundo->save(); 

        $segundo = new Segundo();
        $segundo->puntualidad = 10;
        $segundo->conocimiento = 20;
        $segundo->equipo = 15;
        $segundo->dedicado = 20;
        $segundo->orden = 20;
        $segundo->mejoras = 15;
        $segundo->ruta = "C:\\Users\\jorge\\Pictures";
        $segundo->save(); 
    }
}
