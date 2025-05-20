<?php

namespace App\Exports;

use App\Models\ProcurementOutgoing;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping; // Potentially useful for custom data formatting

class OutgoingExport implements FromQuery, WithHeadings, ShouldAutoSize // Consider WithMapping
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return $this->query;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Received Date', // Better for Excel
            'End User',
            'PR No',
            'Particulars',
            'Amount',
            'Creditor',
            'Remarks',
            'Responsibility',
            'Received By',
            'Created At',
            'Updated At',
        ];
    }

    /*
    // Optional: If you need to format data for Excel (e.g., dates, amounts)
    public function map($outgoing): array
    {
        return [
            $outgoing->id,
            $outgoing->received_date ? $outgoing->received_date->format('Y-m-d') : '',
            $outgoing->end_user,
            $outgoing->pr_no,
            $outgoing->particulars,
            // Format amount as a number for Excel, not a string with commas
            (float)str_replace(',', '', $outgoing->amount),
            $outgoing->creditor,
            $outgoing->remarks,
            $outgoing->responsibility,
            $outgoing->received_by,
            $outgoing->created_at->format('Y-m-d H:i:s'),
            $outgoing->updated_at->format('Y-m-d H:i:s'),
        ];
    }
    */
}