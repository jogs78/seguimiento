<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrimeroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $primero = new Primero();
        $primero->puntualidad = 10;
        $primero->conocimiento = 20;
        $primero->equipo = 15;
        $primero->dedicado = 20;
        $primero->orden = 20;
        $primero->mejoras = 15;
        $primero->ruta = "C:\\Users\\omner\\Desktop";
        $primero->save(); 

        $primero = new Primero();
        $primero->puntualidad = 9;
        $primero->conocimiento = 15;
        $primero->equipo = 10;
        $primero->dedicado = 18;
        $primero->orden = 17;
        $primero->mejoras = 15;
        $primero->ruta = "C:\\Users\\jorge\\Pictures";
        $primero->save(); 
    }
}
