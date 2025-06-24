<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ItemsProcured;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ItemsProcuredExport;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log; // Added for logging

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
    public $notificationType = 'success';
    public $no_changes = null; // *** Added for "no changes" notification ***

    public $sortField = 'supplier';
    public $sortDirection = 'asc';

    // *** Added to store original data for comparison ***
    public $originalItemData = [];

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
            'unit_cost' => 'required|string|max:255',
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
        // Only validate if a validation rule exists for the property
        if (array_key_exists($propertyName, $this->rules())) {
            $this->validateOnly($propertyName);
        }

        if ($propertyName === 'search') {
            $this->resetPage();
        }
    }

    protected $messages = [
        'supplier.required' => 'Supplier is required.',
        'item_project.required' => 'Item/Project is required.',
        'unit_cost.required' => 'Unit cost is required.',
        'year.required' => 'Year is required.',
        'month.required' => 'Month is required.',
    ];

    public function closeModal()
    {
        $this->isAddModalOpen = false;
        $this->isEditModalOpen = false;
        $this->isDeleteModalOpen = false;
        $this->reset([
            'supplier',
            'item_project',
            'unit_cost',
            'year',
            'month',
            'editItemId',
            'deletingItemId',
            'originalItemData', // *** Reset original data here ***
        ]);
        $this->resetValidation();
        $this->no_changes = null; // *** Reset no_changes here ***
        $this->showNotification = false;
        $this->notificationMessage = '';
        $this->notificationType = 'success';
    }

    public function saveItem()
    {
        if ($this->editItemId) {
            $this->updateItem();
        } else {
            $this->addItem();
        }
    }

    public function addItem()
    {
        Log::info('Attempting to add item...');
        try {
            $this->validate();
            Log::info('Validation successful.');

            $newItem = ItemsProcured::create([
                'supplier' => $this->supplier,
                'item_project' => $this->item_project,
                'unit_cost' => $this->unit_cost,
                'year' => $this->year,
                'month' => $this->month,
            ]);

            Log::info('Item created successfully. ID: ' . $newItem->id);

            $this->closeModal();
            $this->notificationType = 'success';
            $this->notificationMessage = 'Item added successfully!';
            $this->showNotification = true;

            $this->dispatch('itemAdded');
        } catch (ValidationException $e) {
            $this->notificationType = 'error';
            $this->notificationMessage = 'Validation failed. Please check the form for errors.';
            $this->showNotification = true;
            Log::error('Validation error adding item: ' . json_encode($e->errors()));
            throw $e;
        } catch (\Exception $e) {
            $this->notificationType = 'error';
            $this->notificationMessage = 'An unexpected error occurred while adding item: ' . $e->getMessage();
            $this->showNotification = true;
            Log::error('An unexpected error occurred while adding item: ' . $e->getMessage());
        }
    }

    public function dismissNotification()
    {
        $this->showNotification = false;
        $this->notificationMessage = '';
        $this->notificationType = 'success';
    }

    // *** This method is now redundant and commented out ***
    // private function resetInputFields()
    // {
    //     $this->supplier = '';
    //     $this->item_project = '';
    //     $this->unit_cost = '';
    //     $this->year = '';
    //     $this->month = '';
    // }

    public function openAddModal()
    {
        // *** Replaced resetInputFields() with direct reset() call ***
        $this->reset([
            'supplier',
            'item_project',
            'unit_cost',
            'year',
            'month',
            'editItemId', // Ensure edit ID is reset
            'deletingItemId', // Ensure delete ID is reset
            'originalItemData', // Ensure original data is reset
        ]);
        $this->resetValidation();
        $this->no_changes = null; // *** Reset no_changes here ***
        $this->showNotification = false;
        $this->notificationMessage = '';
        $this->notificationType = 'success';
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

            // *** Store original data for comparison ***
            $this->originalItemData = [
                'supplier' => (string) $item->supplier,
                'item_project' => (string) $item->item_project,
                'unit_cost' => (string) $item->unit_cost, // Cast to string for consistent comparison
                'year' => (string) $item->year,
                'month' => (string) $item->month,
            ];

            Log::debug('Original Item Data stored: ', $this->originalItemData);

            $this->isEditModalOpen = true;
            $this->resetValidation();
            $this->no_changes = null; // *** Reset no_changes here ***
            $this->showNotification = false;
            $this->notificationMessage = '';
            $this->notificationType = 'success';
        } else {
            $this->notificationType = 'error';
            $this->notificationMessage = 'Item not found.';
            $this->showNotification = true;
        }
    }

    public function updateItem()
    {
        try {
            $item = ItemsProcured::find($this->editItemId);
            if (!$item) {
                $this->notificationType = 'error';
                $this->notificationMessage = 'Item not found.';
                $this->showNotification = true;
                $this->dispatch('itemUpdateFailed');
                return;
            }

            // Prepare current data for comparison
            $currentData = [
                'supplier' => (string) $this->supplier,
                'item_project' => (string) $this->item_project,
                'unit_cost' => (string) $this->unit_cost, // Cast to string for consistent comparison
                'year' => (string) $this->year,
                'month' => (string) $this->month,
            ];

            Log::debug('Current Item Data for comparison: ', $currentData);
            Log::debug('Original Item Data for comparison: ', $this->originalItemData);

            $changesMade = false;
            foreach ($currentData as $key => $currentValue) {
                // Ensure original value is also cast to string and defaults to empty string if missing
                $originalValue = (string) ($this->originalItemData[$key] ?? '');
                $currentValue = (string) ($currentValue ?? '');

                Log::debug("Comparing {$key}: Original='{$originalValue}' | Current='{$currentValue}'");

                if ($originalValue !== $currentValue) {
                    Log::debug("Difference detected for {$key}: Original='{$originalValue}' vs Current='{$currentValue}'");
                    $changesMade = true;
                    break;
                }
            }

            if (!$changesMade) {
                $this->no_changes = 'No changes were made to the item record.';
                $this->notificationType = 'info';
                $this->notificationMessage = 'No changes were made to the item record.';
                $this->showNotification = true;
                // *** REMOVED: $this->closeModal(); and return; ***
                // The modal will now stay open, and the notification will be visible.
                // The user can then manually close the modal.
                return;
            }

            $validatedData = $this->validate(); // Validate only if changes are made

            $item->update($validatedData);
            // *** Removed resetInputFields() here, closeModal() handles it ***
            $this->closeModal(); // Close modal only if update actually happens
            $this->notificationType = 'success';
            $this->notificationMessage = 'Item updated successfully!';
            $this->showNotification = true;
            $this->dispatch('itemUpdated');
        } catch (ValidationException $e) {
            $this->notificationType = 'error';
            $this->notificationMessage = 'Validation failed. Please check the form for errors.';
            $this->showNotification = true;
            Log::error('Validation error updating item: ' . json_encode($e->errors()));
            throw $e;
        } catch (\Exception $e) {
            $this->notificationType = 'error';
            $this->notificationMessage = 'Error updating Item: ' . $e->getMessage();
            $this->showNotification = true;
            Log::error('Error updating Item: ' . $e->getMessage());
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
                $this->notificationType = 'success';
                $this->notificationMessage = 'Item deleted successfully!';
                $this->showNotification = true;
                $this->dispatch('itemDeleted');
            } else {
                $this->notificationType = 'error';
                $this->notificationMessage = 'Item not found.';
                $this->showNotification = true;
                $this->dispatch('itemDeleteFailed');
            }
        } catch (\Exception $e) {
            $this->notificationType = 'error';
            $this->notificationMessage = 'Error deleting item: ' . $e->getMessage();
            $this->showNotification = true;
            Log::error('Error deleting item: ' . $e->getMessage());
            $this->dispatch('itemDeleteFailed');
        }
    }

    public function exportExcel()
    {
        $filename = 'Item Procurement.xlsx';

        try {
            return Excel::download(new ItemsProcuredExport($this->filterYear, $this->filterMonth, $this->search), $filename);
        } catch (\Exception $e) {
            Log::error('Error exporting items to Excel: ' . $e->getMessage());
            $this->notificationType = 'error';
            $this->notificationMessage = 'Failed to export to Excel: ' . $e->getMessage();
            $this->showNotification = true;
        }
    }

    public function exportPdf()
    {
        $filename = 'Item Procurement.pdf';

        try {
            return Excel::download(new ItemsProcuredExport($this->filterYear, $this->filterMonth, $this->search), $filename, \Maatwebsite\Excel\Excel::DOMPDF);
        } catch (\Exception $e) {
            Log::error('Error exporting items to PDF: ' . $e->getMessage());
            $this->notificationType = 'error';
            $this->notificationMessage = 'Failed to export to PDF: ' . $e->getMessage();
            $this->showNotification = true;
        }
    }

    public function render()
    {
        $query = ItemsProcured::query();

        Log::info("Filter Year: {$this->filterYear}");
        Log::info("Filter Month: {$this->filterMonth}");

        if ($this->filterYear) {
            $query->where('year', $this->filterYear);
            Log::info("Applying Year Filter: {$this->filterYear}");
        }

        if ($this->filterMonth) {
            $query->whereRaw('LOWER(TRIM(month)) = ?', [strtolower(trim($this->filterMonth))]);
            Log::info("Applying Month Filter: {$this->filterMonth}");
        }

        if ($this->search) {
            $searchItem = strtolower($this->search); // Convert search term to lowercase once
            $query->where(function ($query) use ($searchItem) {
                $query->whereRaw('LOWER(supplier) LIKE ?', ["%{$searchItem}%"])
                    ->orWhereRaw('LOWER(item_project) LIKE ?', ["%{$searchItem}%"])
                    ->orWhereRaw('LOWER(unit_cost) LIKE ?', ["%{$searchItem}%"]) // Unit cost might be numeric, but stored as string. Lowercasing may impact. Careful here.
                    ->orWhereRaw('LOWER(year) LIKE ?', ["%{$searchItem}%"])
                    ->orWhereRaw('LOWER(month) LIKE ?', ['%' . $searchItem . '%']); // Already lowercased searchItem
            });
            Log::info("Applying Search: {$this->search}");
            Log::info('Applying Search: ' . $this->search);
        }

        if ($this->sortField && in_array($this->sortField, ['supplier', 'item_project', 'unit_cost', 'year', 'month'])) {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        // Only log SQL query if debugging is active, it can be resource intensive
        // Log::info('SQL Query: ' . $query->toSql());
        // Log::info('SQL Bindings: ' . json_encode($query->getBindings()));

        $items = $query->paginate($this->perPage);

        return view('livewire.items-procured-index', [
            'items' => $items,
        ])->layout('layouts.app', ['title' => 'Items Procured']);
    }
}