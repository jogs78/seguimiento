<?php

namespace App\Exports;

use App\Models\Externo;
use App\Models\Proyecto;
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

    protected $periodo_id;
    protected $carrera_id;

    public function __construct($periodo_id = null, $carrera_id = null)
    {
        $this->periodo_id = $periodo_id ?: ConfiguracionServiceProvider::get('periodo_id');
        $this->carrera_id = $carrera_id ?: session('carrera_id');
    }

    public function collection()
    {
        // Obtener IDs de externos que tienen proyectos en el período actual
        $externosIds = Proyecto::where('periodo_id', $this->periodo_id)
            ->when($this->carrera_id, function($q) {
                $q->whereHas('estudiantes', function($sq) {
                    $sq->where('carrera_id', $this->carrera_id);
                });
            })
            ->whereNotNull('externo_id')
            ->pluck('externo_id')
            ->unique();

        return Externo::whereIn('id', $externosIds)
            ->select('titulo', 'nombre', 'apellido_paterno', 'apellido_materno', 'correo_electronico', 'puesto')
            ->get();
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
