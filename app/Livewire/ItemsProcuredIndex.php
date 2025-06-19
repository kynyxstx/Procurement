<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ItemsProcured;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ItemsProcuredExport;
use Illuminate\Validation\ValidationException; // IMPORTANT: Add this import

class ItemsProcuredIndex extends Component
{
    use WithPagination;

    public $supplier = '';
    public $item_project = '';
    public $unit_cost = '';
    public $year = '';
    public $month = '';

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
    public $notificationType = 'success'; // Add this property for message box styling

    public $sortField = 'supplier';
    public $sortDirection = 'asc';

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }
    protected $paginationTheme = 'tailwind';
    protected $perPage = 100;

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
            // Corrected: unit_cost is not required in your messages, but it is here.
            // If it can be empty, make it nullable.
            // Based on your message for unit_cost, it seems you intend for it to be required.
            'unit_cost' => 'required|string|max:255', // Assuming it can be non-numeric (e.g. "N/A", "TBD")
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

        if ($propertyName === 'search') {
            $this->resetPage();
        }
    }

    protected $messages = [
        'supplier.required' => 'Supplier is required.',
        'item_project.required' => 'Item/Project is required.',
        'unit_cost.required' => 'Unit cost is required.',
        'year.required' => 'Year is required.', // Added for completeness based on rules
        'month.required' => 'Month is required.', // Added for completeness based on rules
    ];

    public function closeModal()
    {
        $this->isAddModalOpen = false;
        $this->isEditModalOpen = false;
        $this->isDeleteModalOpen = false;
        $this->reset(['supplier', 'item_project', 'unit_cost', 'year', 'month']); // Reset all form-related properties
        $this->resetValidation(); // Clear validation errors
        $this->showNotification = false; // Hide notification
        $this->notificationMessage = ''; // Clear notification message
        $this->notificationType = 'success'; // Reset notification type
    }

    public function saveItem()
    {
        // This method combines add/update. We need to distinguish it for specific logic.
        if ($this->editItemId) {
            $this->updateItem();
        } else {
            $this->addItem(); // Changed to addItem for consistency
        }
    }

    public function addItem() // Renamed from saveItem to addItem for clarity
    {
        \Log::info('Attempting to add item...');
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
            $this->notificationType = 'success'; // Set type for success
            $this->notificationMessage = 'Item added successfully!';
            $this->showNotification = true;
            // No need for resetInputFields() here, closeModal() already calls reset on properties
            $this->dispatch('itemAdded'); // Dispatch event for other components if needed
        } catch (ValidationException $e) { // Catch validation exceptions specifically
            $this->notificationType = 'error'; // Set type for error
            $this->notificationMessage = 'Validation failed. Please check the form for errors.';
            $this->showNotification = true;
            \Log::error('Validation error adding item: ' . json_encode($e->errors()));
            throw $e; // Re-throw to make Livewire display errors next to fields
        } catch (\Exception $e) { // Catch other general exceptions
            $this->notificationType = 'error'; // Set type for error
            $this->notificationMessage = 'An unexpected error occurred while adding item: ' . $e->getMessage();
            $this->showNotification = true;
            \Log::error('An unexpected error occurred while adding item: ' . $e->getMessage());
            // No need for session()->flash here if using custom notification
        }
    }

    public function dismissNotification()
    {
        $this->showNotification = false;
        $this->notificationMessage = '';
        $this->notificationType = 'success'; // Reset to default type
    }

    private function resetInputFields() // This method is now effectively replaced by closeModal's reset()
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
        $this->resetValidation(); // Clear any previous validation errors
        $this->showNotification = false; // Hide notification
        $this->notificationMessage = ''; // Clear notification message
        $this->notificationType = 'success'; // Reset notification type
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
            $this->resetValidation(); // Clear any previous validation errors
            $this->showNotification = false; // Hide notification
            $this->notificationMessage = ''; // Clear notification message
            $this->notificationType = 'success'; // Reset notification type
        } else {
            $this->notificationType = 'error'; // Set type for error
            $this->notificationMessage = 'Item not found.';
            $this->showNotification = true;
            // No need for session()->flash here
        }
    }

    public function updateItem()
    {
        try {
            $validatedData = $this->validate();

            $item = ItemsProcured::find($this->editItemId);
            if ($item) {
                $item->update($validatedData);
                $this->resetInputFields(); // Use this if you want to clear fields after edit, or remove if close modal clears
                $this->closeModal();
                $this->notificationType = 'success'; // Set type for success
                $this->notificationMessage = 'Item updated successfully!';
                $this->showNotification = true;
                $this->dispatch('itemUpdated');
            } else {
                $this->notificationType = 'error'; // Set type for error
                $this->notificationMessage = 'Item not found.';
                $this->showNotification = true;
                $this->dispatch('itemUpdateFailed');
            }
        } catch (ValidationException $e) { // Catch validation exceptions specifically
            $this->notificationType = 'error'; // Set type for error
            $this->notificationMessage = 'Validation failed. Please check the form for errors.';
            $this->showNotification = true;
            \Log::error('Validation error updating item: ' . json_encode($e->errors()));
            throw $e; // Re-throw to make Livewire display errors next to fields
        } catch (\Exception $e) {
            $this->notificationType = 'error'; // Set type for error
            $this->notificationMessage = 'Error updating Item: ' . $e->getMessage();
            $this->showNotification = true;
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
                $this->notificationType = 'success'; // Set type for success
                $this->notificationMessage = 'Item deleted successfully!';
                $this->showNotification = true;
                $this->dispatch('itemDeleted');
            } else {
                $this->notificationType = 'error'; // Set type for error
                $this->notificationMessage = 'Item not found.';
                $this->showNotification = true;
                $this->dispatch('itemDeleteFailed');
            }
        } catch (\Exception $e) {
            $this->notificationType = 'error'; // Set type for error
            $this->notificationMessage = 'Error deleting item: ' . $e->getMessage();
            $this->showNotification = true;
            \Log::error('Error deleting item: ' . $e->getMessage());
            $this->dispatch('itemDeleteFailed');
        }
    }

    public function exportExcel()
    {
        $filename = 'Item Procurement.xlsx';

        try {
            // Ensure your ItemsProcuredExport constructor accepts the parameters correctly
            return Excel::download(new ItemsProcuredExport($this->filterYear, $this->filterMonth, $this->search), $filename);
        } catch (\Exception $e) {
            \Log::error('Error exporting items to Excel: ' . $e->getMessage());
            $this->notificationType = 'error'; // Set type for error
            $this->notificationMessage = 'Failed to export to Excel: ' . $e->getMessage();
            $this->showNotification = true;
            // No need for session()->flash here
            // return back(); // This is for redirects, not ideal in Livewire without a full page refresh
        }
    }

    public function exportPdf()
    {
        $filename = 'Item Procurement.pdf';

        try {
            // Note: Maatwebsite\Excel::DOMPDF requires the PDF writer to be configured
            // If you're using Barryvdh\DomPDF, you might need a different approach here.
            // If this is throwing errors, you might want to switch to Barryvdh\DomPDF directly here.
            return Excel::download(new ItemsProcuredExport($this->filterYear, $this->filterMonth, $this->search), $filename, \Maatwebsite\Excel\Excel::DOMPDF);
        } catch (\Exception $e) {
            \Log::error('Error exporting items to PDF: ' . $e->getMessage());
            $this->notificationType = 'error'; // Set type for error
            $this->notificationMessage = 'Failed to export to PDF: ' . $e->getMessage();
            $this->showNotification = true;
            // No need for session()->flash here
            // return back(); // This is for redirects, not ideal in Livewire without a full page refresh
        }
    }

    public function render()
    {
        $query = ItemsProcured::query();

        \Log::info("Filter Year: {$this->filterYear}");
        \Log::info("Filter Month: {$this->filterMonth}");

        if ($this->filterYear) {
            $query->where('year', $this->filterYear);
            \Log::info("Applying Year Filter: {$this->filterYear}");
        }

        if ($this->filterMonth) {
            $query->whereRaw('LOWER(TRIM(month)) = ?', [strtolower(trim($this->filterMonth))]);
            \Log::info("Applying Month Filter: {$this->filterMonth}");
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
            \Log::info("Applying Search: {$this->search}");
            \Log::info('Applying Search: ' . $this->search);
        }

        if ($this->sortField && in_array($this->sortField, ['supplier', 'item_project', 'unit_cost', 'year', 'month'])) {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        \Log::info('SQL Query: ' . $query->toSql());
        \Log::info('SQL Bindings: ' . json_encode($query->getBindings()));

        $items = $query->paginate($this->perPage);

        return view('livewire.items-procured-index', [
            'items' => $items,
        ])->layout('layouts.app', ['title' => 'Items Procured']);
    }
}