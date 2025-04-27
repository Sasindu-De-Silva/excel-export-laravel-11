<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ItemExport implements FromView, WithColumnWidths, WithStyles, WithColumnFormatting
{
    protected $data;

    public function view(): View
    {
        return view('export.items', ['data' => $this->data]);
    }

    public function export($data)
    {
        $this->data = $data['items'];
        return $this;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 20,
            'C' => 30,
            'D' => 20,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_NUMBER_00,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:D1');

        return [
            // 1st row
            1    => [
                'font' => ['bold' => true, 'size' => 15, 'color' => array('rgb' => 'FFFFFF')],
                'fill' => [
                    'fillType'   => Fill::FILL_GRADIENT_LINEAR,
                    'startColor' => ['rgb' => '47C435'],
                    'endColor' => ['rgb' => '74D666']
                ]
            ],
        ];
    }
}
