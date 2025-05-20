<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SuppliersExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $suppliers;

    public function __construct(Collection $suppliers)
    {
        $this->suppliers = $suppliers;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->suppliers;
    }

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