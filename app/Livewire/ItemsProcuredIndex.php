<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ItemsProcured;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ItemsProcuredExport;

class ItemsProcuredIndex extends Component
{
    use WithPagination;

    public $supplier = '';
    public $item_project = '';
    public $unit_cost = '';
    public $year = '';
    public $month = '';

    // These are already declared, and are the correct ones to use for filters
    public $filterYear = '';
    public $filterMonth = '';
    public $search = '';

    public $isEditModalOpen = false;
    public $editItemId;
    public $isDeleteModalOpen = false;
    public $deletingItemId;
    public $isAddModalOpen = false;

    public $showNotification = false;
    public $notificationMessage = '';

    protected $paginationTheme = 'tailwind';
    protected $perPage = 50;

    /**
     * The properties that should be included in the query string.
     *
     * @var array
     */
    protected $queryString = [
        'search',
        'filterYear',
        'filterMonth',
    ];

    public function rules()
    {
        return [
            'supplier' => 'required|string|max:255',
            'item_project' => 'required|string|max:500',
            'unit_cost' => 'nullable|string|max:255',
            'year' => 'required|string|max:4',
            'month' => 'required|string|max:10',
        ];
    }

    protected $listeners = ['refreshItemsProcured' => '$refresh'];

    public function mount(): void
    {
        $this->resetPage();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    protected $messages = [
        'supplier.required' => 'Supplier is required.',
        'item_project.required' => 'Item/Project is required.',
        'unit_cost.required' => 'Unit cost is required.',
    ];

    public function closeModal()
    {
        $this->isAddModalOpen = false;
        $this->isEditModalOpen = false;
        $this->isDeleteModalOpen = false;
        $this->reset(['supplier', 'item_project', 'unit_cost', 'editItemId', 'isEditModalOpen', 'isDeleteModalOpen', 'year', 'month']); // Added year and month to reset
    }

    public function saveItem()
    {
        \Log::info('Attempting to save item...');
        try {
            $this->validate();
            \Log::info('Validation successful.');

            $newItem = ItemsProcured::create([
                'supplier' => $this->supplier,
                'item_project' => $this->item_project,
                'unit_cost' => $this->unit_cost,
                'year' => $this->year,
                'month' => $this->month,
            ]);

            \Log::info('Item created successfully. ID: ' . $newItem->id);

            $this->closeModal();
            $this->notificationMessage = 'Item added successfully!';
            $this->showNotification = true;
            $this->resetInputFields();
            // Pagination handled in render
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('Database error adding item: ' . $e->getMessage());
            session()->flash('error', 'Error adding item to the database: ' . $e->getMessage());
        } catch (\Exception $e) {
            \Log::error('An unexpected error occurred while adding item: ' . $e->getMessage());
            session()->flash('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    public function dismissNotification()
    {
        $this->showNotification = false;
        $this->notificationMessage = '';
    }

    private function resetInputFields()
    {
        $this->supplier = '';
        $this->item_project = '';
        $this->unit_cost = '';
        $this->year = '';
        $this->month = '';
    }

    public function openAddModal()
    {
        $this->resetInputFields(); // Ensure fields are clear when opening add modal
        $this->isAddModalOpen = true;
    }

    public function openEditModal($itemId)
    {
        $item = ItemsProcured::find($itemId);

        if ($item) {
            $this->editItemId = $itemId;
            $this->supplier = $item->supplier;
            $this->item_project = $item->item_project;
            $this->unit_cost = $item->unit_cost;
            $this->year = $item->year;
            $this->month = $item->month;

            $this->isEditModalOpen = true;
        } else {
            session()->flash('error', 'Item not found.');
        }
    }

    public function updateItem()
    {
        try {
            $validatedData = $this->validate();

            $item = ItemsProcured::find($this->editItemId);
            if ($item) {
                $item->update($validatedData);
                $this->resetInputFields();
                $this->closeModal();
                $this->notificationMessage = 'Item updated successfully!';
                $this->showNotification = true;
                $this->dispatch('itemUpdated');
            } else {
                session()->flash('error', 'Item not found.');
                $this->dispatch('itemUpdateFailed');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error updating Item.');
            \Log::error('Error updating Item: ' . $e->getMessage());
            $this->dispatch('itemUpdateFailed');
        }
    }

    public function openDeleteModal($itemId)
    {
        $this->deletingItemId = $itemId;
        $this->isDeleteModalOpen = true;
    }

    public function deleteItem()
    {
        try {
            $item = ItemsProcured::find($this->deletingItemId);

            if ($item) {
                $item->delete();
                $this->closeModal();
                $this->notificationMessage = 'Item deleted successfully!';
                $this->showNotification = true;
                $this->dispatch('itemDeleted');
            } else {
                session()->flash('error', 'Item not found.');
                $this->dispatch('itemDeleteFailed');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting item.');
            \Log::error('Error deleting item: ' . $e->getMessage());
            $this->dispatch('itemDeleteFailed');
        }
    }

    // Corrected export method name as per previous error
    public function exportExcel() // Renamed from exportToExcel to match common usage, can be exportToExcel if you prefer
    {
        // Use $this->filterYear and $this->filterMonth instead of undeclared $this->selectedYear/$selectedMonth
        $filename = 'Item Procurement.xlsx'; // Shortened filename, < 31 chars

        try {
            return Excel::download(new ItemsProcuredExport($this->filterYear, $this->filterMonth, $this->search), $filename);
        } catch (\Exception $e) {
            \Log::error('Error exporting items to Excel: ' . $e->getMessage());
            session()->flash('error', 'Failed to export to Excel: ' . $e->getMessage());
            return back();
        }
    }

    // New exportPdf method, as per previous error
    public function exportPdf()
    {
        // Use $this->filterYear and $this->filterMonth instead of undeclared $this->selectedYear/$selectedMonth
        $filename = 'Item Procurement.pdf'; // Shortened filename, < 31 chars

        try {
            return Excel::download(new ItemsProcuredExport($this->filterYear, $this->filterMonth, $this->search), $filename, \Maatwebsite\Excel\Excel::DOMPDF);
        } catch (\Exception $e) {
            \Log::error('Error exporting items to PDF: ' . $e->getMessage());
            session()->flash('error', 'Failed to export to PDF: ' . $e->getMessage());
            return back();
        }
    }

    public function render()
    {
        $query = ItemsProcured::query();

        \Log::info('Filter Year: ' . $this->filterYear);
        \Log::info('Filter Month: ' . $this->filterMonth);

        if ($this->filterYear) {
            $query->where('year', $this->filterYear);
            \Log::info('Applying Year Filter: ' . $this->filterYear);
        }

        if ($this->filterMonth) {
            $query->whereRaw('LOWER(month) = ?', [strtolower($this->filterMonth)]);
            \Log::info('Applying Month Filter: ' . $this->filterMonth);
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
            \Log::info('Applying Search: ' . $this->search);
        }

        \Log::info('SQL Query: ' . $query->toSql());
        \Log::info('SQL Bindings: ' . json_encode($query->getBindings()));

        $items = $query->paginate($this->perPage);

        return view('livewire.items-procured-index', [
            'items' => $items,
        ])->layout('layouts.app', ['title' => 'Items Procured']);
    }
}