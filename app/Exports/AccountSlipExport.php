<?php

namespace App\Exports;
use App\Batch;
use App\AccountSlip;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AccountSlipExport implements FromCollection,WithStrictNullComparison,WithHeadings,WithTitle,WithColumnFormatting,ShouldAutoSize
{
    protected $batchId;

    public function __construct($batchId)
    {
        $this->batchId = $batchId;

    }
    public function columnFormats(): array
    {
        return [

            'L' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'M' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'N' =>NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'O' =>NumberFormat::FORMAT_TEXT,

            

        ];
    }
    public function title(): string
    {
        return 'Sheet1';
    }
    public function headings(): array
    {
        return [
            'JOBID',
            'STATUS',
            'EnvelopeIDS',
            'IMAGEID',
            'DATE',
            'TIME',
            'VEHICLE',
            'BADGEID',
            'CUSTOMERNAME',
            'PICKUP',
            'DROPOFF',
            'FARE',
            'TIP',
            'TOTAL',
            'ACCOUNT',
            'PO',
        ];
    }
    public function collection()
    {
        $rows = AccountSlip::
        select(
        'job_id as JOBID',
        'status as STATUS',
        'envelope_id as EnvelopeIDS',
        'image_id as IMAGEID',
        'date as DATE',
        'date as TIME',
        'vehicle as VEHICLE',
        'badge_id as BADGEID',
        'customer_name as CUSTOMERNAME',
        'pickup as PICKUP',
        'dropoff as DROPOFF',
        'fare as FARE',
        'tip as TIP',
        'total as TOTAL',
        'account as ACCOUNT',
        'PO as PO',
        'slip_id'
        ) 
        ->where('batch_id','LIKE',$this->batchId)
        ->where('status','=','verified')
        ->get();

        foreach ($rows as $row) {
            $row->EnvelopeIDS = $this->batchId.'-'.$row->EnvelopeIDS;
            $row->STATUS = strtoupper($row->STATUS);
            $row->IMAGEID = $row->IMAGEID.'.pdf';
            $row->TIME = date("H:i", strtotime($row->DATE)); ;
            $row->DATE = date("d-m-Y", strtotime($row->DATE)); ;
            $row->FARE =   number_format(((float)$row->FARE /100), 2, '.', ' ');
            $row->TIP =   number_format(((float)$row->TIP /100), 2, '.', ' ');
            $row->TOTAL =   number_format(((float)$row->TOTAL /100), 2, '.', ' ');
            $row->slip_id =  null;
        }
        return $rows;
    }
}
