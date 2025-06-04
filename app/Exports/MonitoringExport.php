<?php

namespace App\Exports;

use App\Models\ProcurementMonitoring;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MonitoringExport implements FromQuery, WithHeadings, ShouldAutoSize
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
        return $this->query; // This will now receive the filtered query from Livewire
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'PR No',
            'Title',
            'Processor',
            'Supplier',
            'End User',
            'Status',
            'Date Endorsement',
            'Created At',
            'Updated At',
        ];
    }
}