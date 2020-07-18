<?php

namespace App\Exports;

use App\Envelope;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class EnvelopeExport implements FromCollection,WithStrictNullComparison,WithHeadings,WithTitle ,WithColumnFormatting,ShouldAutoSize
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
            'E' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'F' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'G' =>NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'H' =>NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'I' =>NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'J' =>NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,



        ];
    }
    public function headings(): array
    {
        return [
            'ENVELOPEID',
            'OPERATORID',
            'DRIVERID',
            'BATCHID',
            'MDT_CLAIMED',
            'TAXISAVERS_CLAIMED',
            'POS_CLAIMED',
            'GC_CLAIMED',
            'ORIGINAL_TOTAL_CLAIMED',
            'UNVERIFIED_ENVLEOPE_TOTAL',
        ];
    }
    public function collection()
    {
        $rows = Envelope::
        select('envelope_id as ENVELOPEID',
         'operator_id as OPERATORID',
         'driver_id as DRIVERID',
         'batch_id as BATCHID',
         'mdt_claimed as MDT_CLAIMED',
         'taxi_savers_claimed as TAXISAVERS_CLAIMED',
         'pos_claimed as POS_CLAIMED',
         'gift_certificates_claimed as GC_CLAIMED',
         'original_total_claimed as ORIGINAL_TOTAL_CLAIMED',
         'unverified_envelope_total as UNVERIFIED_ENVLEOPE_TOTAL'
         )
        ->where('batch_id','LIKE',$this->batchId)
        ->get();
        foreach ($rows as $row) {
            $row->ENVELOPEID = $this->batchId.'-'.$row->ENVELOPEID;
            $row->MDT_CLAIMED =  number_format((float)$row->MDT_CLAIMED, 2, '.', '');
            $row->TAXISAVERS_CLAIMED =  number_format((float)$row->TAXISAVERS_CLAIMED, 2, '.', '');
            $row->POS_CLAIMED =  number_format((float)$row->POS_CLAIMED, 2, '.', '');
            $row->GC_CLAIMED =  number_format((float)$row->GC_CLAIMED, 2, '.', '');
            $row->ORIGINAL_TOTAL_CLAIMED =  number_format((float)$row->ORIGINAL_TOTAL_CLAIMED, 2, '.', '');
            $row->UNVERIFIED_ENVLEOPE_TOTAL =  number_format((float)$row->UNVERIFIED_ENVLEOPE_TOTAL, 2, '.', '');
        }
        return $rows;
    }
}
