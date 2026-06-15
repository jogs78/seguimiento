<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoDocumento;

class TipoDocumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeder para tipos de documentos
        $tipos = [
            [
                'nombre' => 'Solicitud de residencia profesional',
                'obligatorio' => true,
            ],
            [
                'nombre' => 'Anteproyecto',
                'obligatorio' => true,
            ],
            [
                'nombre' => 'Solicitud de cancelación de proyecto',
                'obligatorio' => false,
            ],
            [
                'nombre' => 'KARDEX (SII)',
                'obligatorio' => true,
            ],
            [
                'nombre' => 'Afiliación del seguro social',
                'obligatorio' => true,
            ],
            [
                'nombre' => 'Constancia de servicio social',
                'obligatorio' => true,
            ],
        ];

        foreach ($tipos as $tipo) {
            \App\Models\TipoDocumento::create([
                'nombre' => $tipo['nombre'],
                'descripcion' => 'Descripción para ' . $tipo['nombre'],
                'extensiones_permitidas' => json_encode(['pdf', 'docx']),
                'tamano_maximo_mb' => 10,
                'obligatorio' => $tipo['obligatorio'],
                'permite_url' => true,
                'activo' => true,
            ]);
        }
    }
}
