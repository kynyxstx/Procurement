<?php

namespace App\Exports;

use App\Models\SupplierDirectory;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SuppliersExport implements FromQuery, WithHeadings, ShouldAutoSize
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
            'Supplier Name',
            'Address',
            'Items',
            'Contact Person',
            'Position',
            'Mobile No.',
            'Telephone No.',
            'Email Address',
            'Created At',
            'Updated At',
        ];
    }
}