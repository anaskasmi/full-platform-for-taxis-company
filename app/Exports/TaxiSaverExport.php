<?php

namespace App\Exports;
use App\Batch;
use App\TaxiSaver;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TaxiSaverExport implements FromCollection,WithStrictNullComparison,WithHeadings,WithTitle ,WithColumnFormatting,ShouldAutoSize
{
    protected $batchId;

    public function __construct($batchId)
    {
        $this->batchId = $batchId;
    }
    public function title(): string
    {
        return 'Sheet1';
    }
    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,

            

        ];
    }
    public function headings(): array
    {
        return [
            'DATE',
            'ENVELOPE',
            'Slip',
            'Amount',
        ];
    }
    public function collection()
    {
        $rows = TaxiSaver::
        select('date as DATE',
        'envelope_id as ENVELOPE',
        'slip_id as Slip',
        'amount as Amount'
        ) 
        ->where('batch_id','LIKE',$this->batchId)
        ->get();
        $batch = Batch::where('batch_id','=',$this->batchId)->first();
        foreach ($rows as $row) {
            $row->DATE = date("d/m/Y", strtotime($row->DATE)); ;
            $row->Slip = $batch->number . $batch->month . $batch->year .$row->ENVELOPE. $row->Slip;
            $row->ENVELOPE = $this->batchId.'-'.$row->ENVELOPE;
            $row->Amount =  number_format((float)$row->Amount, 2, '.', '');
        }
        return $rows;
    }
}
