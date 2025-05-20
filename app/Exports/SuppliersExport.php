<?php

namespace App\Exports;

use App\Models\SupplierDirectory; // Make sure this is correct based on your model's namespace
use Maatwebsite\Excel\Concerns\FromQuery; // IMPORTANT: Use FromQuery
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Optional but good for formatting

class SuppliersExport implements FromQuery, WithHeadings, ShouldAutoSize
{
    protected $query; // This will hold your Eloquent query builder

    public function __construct($query)
    {
        $this->query = $query;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query() // Method name is query() when using FromQuery
    {
        // Return the query builder instance that was passed in the constructor
        return $this->query;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Define your column headers here
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