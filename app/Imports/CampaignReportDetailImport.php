<?php

namespace App\Imports;

use App\Models\CampaignReportDetail;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;

class CampaignReportDetailImport implements ToModel, WithHeadingRow
{
    // ToModel, WithHeadingRow, WithColumnFormatting, WithMapping
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new CampaignReportDetail([
            // 'date' =>$row['date'],
            // 'amount' => $row['amount'],
            // 'description' => $row['description'],
            // 'evidence' => $row['evidence'],
            // 'type' => $row['type'],
        ]);
    }


    // public function headingRow(): int
    // {
    //     return 0;
    // }
}
