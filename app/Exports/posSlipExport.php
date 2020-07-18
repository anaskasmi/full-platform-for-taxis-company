<?php

namespace App\Exports;

use App\posSlip;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class posSlipExport implements FromCollection,WithStrictNullComparison,WithHeadings,WithTitle,WithColumnFormatting,ShouldAutoSize
{
    protected $batchId;

    public function __construct($batchId)
    {
        $this->batchId = $batchId;
    }
    
    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
           
            

        ];
    }
    public function title(): string
    {
        return 'Sheet1';
    }
    public function headings(): array
    {
        return [
            'Envelope',
            'Amount',
        ];
    }
    public function collection()
    {
        $rows = posSlip::
        select('envelope_id as Envelope', 'amount as Amount')
        ->where('batch_id','LIKE',$this->batchId)
        ->get();
        foreach ($rows as $row) {
            $row->Envelope = $this->batchId.'-'.$row->Envelope;
            $row->Amount =  number_format((float)$row->Amount, 2, '.', '');
            
        }
        return $rows;
    }
}
