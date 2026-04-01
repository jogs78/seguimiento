<?php

namespace App\Exports;

use App\Models\Asesor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class AsesoresExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $carrera_id;

    public function __construct($carrera_id = null)
    {
        $this->carrera_id = $carrera_id;
    }

    public function collection()
    {
        $query = Asesor::select(
            'nombre', 
            'apellido_paterno', 
            'apellido_materno', 
            'correo_electronico', 
            'profesion', 
            'carrera', 
            'numero_cedula'
        );

        // Aplicar filtro por carrera si existe
        if ($this->carrera_id) {
            $query->whereHas('carreras', function($q) {
                $q->where('carrera_id', $this->carrera_id);
            });
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Nombre Completo',
            'Correo Electrónico',
            'Profesion',
            'Carrera',
            'Numero Cedula',
        ];
    }

    public function map($asesor): array
    {
        return [
            $asesor->nombre . ' ' . $asesor->apellido_paterno . ' ' . $asesor->apellido_materno,
            $asesor->correo_electronico,
            $asesor->profesion,
            $asesor->carrera,
            $asesor->numero_cedula,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Aplica estilo a la primera fila (encabezados)
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '000000'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '6a6ae0'
                ]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true, // permite que el texto se ajuste
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Aplica bordes a todo el contenido (incluyendo encabezados)
        $lastRow = Asesor::count() + 1; // +1 por la fila de encabezado
        $sheet->getStyle("A1:E$lastRow")->applyFromArray([ 
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);
    }
}
