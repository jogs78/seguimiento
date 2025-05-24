<?php

namespace App\Exports;

use App\Models\Externo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ExternosExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Externo::select('titulo','nombre','apellido_paterno','apellido_materno','correo_electronico','puesto')->get();
    }
    public function headings(): array
    {
        return [
            //'ID',
            'Nombre Completo',
            'Correo Electrónico',
            'Puesto',
        ];
    }
    public function map($externo): array
    {
        return [
            //$externo->id,
            $externo->titulo . ' ' . $externo->nombre . ' ' . $externo->apellido_paterno . ' ' . $externo->apellido_materno,
            $externo->correo_electronico,
            $externo->puesto,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Aplica estilo a la primera fila (encabezados)
        $sheet->getStyle('A1:C1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '000000'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '5eebeb'
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
        $lastRow = Externo::count() + 1; // +1 por la fila de encabezado
        $sheet->getStyle("A1:C$lastRow")->applyFromArray([ 
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);
    }
}
