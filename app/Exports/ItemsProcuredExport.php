<?php

namespace App\Exports;

use App\Models\ItemsProcured;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;

class ItemsProcuredExport implements FromView, ShouldAutoSize, WithTitle // Ensure WithTitle is here
{
    protected $filterYear;
    protected $filterMonth;
    protected $search;

    public function __construct($filterYear, $filterMonth, $search)
    {
        $this->filterYear = $filterYear;
        $this->filterMonth = $filterMonth;
        $this->search = $search;
    }

    /**
     * Return the title of the sheet.
     * This title will be used for both Excel sheet and as an internal reference for PDF.
     * Max 31 characters.
     *
     * @return string
     */
    public function title(): string
    {
        \Log::info('ItemsProcuredExport title() called: Short'); // Add this log
        return 'Short'; // Change this to a very short, distinct title
    }
    /**
     * @return View
     */
    public function view(): View
    {
        $query = ItemsProcured::query();

        if ($this->filterYear) {
            $query->where('year', $this->filterYear);
        }

        if ($this->filterMonth) {
            $query->whereRaw('LOWER(month) = ?', [strtolower($this->filterMonth)]);
        }

        if ($this->search) {
            $searchItem = $this->search;
            $query->where(function ($query) use ($searchItem) {
                $query->where('supplier', 'like', "%{$searchItem}%")
                    ->orWhere('item_project', 'like', "%{$searchItem}%")
                    ->orWhere('unit_cost', 'like', "%{$searchItem}%")
                    ->orWhere('year', 'like', "%{$searchItem}%")
                    ->orWhereRaw('LOWER(month) LIKE ?', ['%' . strtolower($searchItem) . '%']);
            });
        }

        $items = $query->get();

        return view('exports.item_pdf', [
            'items' => $items
        ]);
    }

    // You can keep ShouldAutoSize or WithColumnWidths if you also intend to use this export class for Excel.
    // For PDF generation via FromView, these might not directly apply to the PDF layout itself.
    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 30,
            'C' => 50,
            'D' => 15,
            'E' => 10,
            'F' => 12,
            'G' => 20,
            'H' => 20,
        ];
    }
}