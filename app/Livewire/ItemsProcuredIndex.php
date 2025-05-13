<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ItemsProcured;
use Livewire\WithPagination;


class ItemsProcuredIndex extends Component
{
    use WithPagination;

    public $supplier = '';
    public $item_project = '';
    public $unit_cost = '';
    public $year = '';
    public $month = '';

    public $search = '';
    public $filterSupplier = '';
    public $isEditModalOpen = false;
    public $editItemId;
    public $isDeleteModalOpen = false;
    public $deletingItemId;
    public $isAddModalOpen = false;

    public $showNotification = false;
    public $notificationMessage = '';

    protected $paginationTheme = 'tailwind';
    protected $perPage = 5;

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

    public function loadData()
    {
        $this->resetPage(); // Reset pagination if needed
    }
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

    // Close modals
    public function closeModal()
    {
        $this->isAddModalOpen = false;
        $this->isEditModalOpen = false;
        $this->isDeleteModalOpen = false;
        $this->reset(['supplier', 'item_project', 'unit_cost', 'editItemId', 'isEditModalOpen', 'isDeleteModalOpen']);
    }

    // Save item (Create or Update)
    public function saveItem()
    {
        $this->validate();

        ItemsProcured::create([
            'supplier' => $this->supplier,
            'item_project' => $this->item_project,
            'unit_cost' => $this->unit_cost,
            'year' => $this->year,
            'month' => $this->month,
        ]);

        $this->closeModal();
        session()->flash('message', 'Item added successfully!');
        $this->resetInputFields();
        $this->loadItems();
    }

    protected function loadItems()
    {
        $this->items = ItemsProcured::latest()->paginate(100);
    }

    public function addItem()
    {
        try {
            $this->validate();

            ItemsProcured::create([
                'supplier' => $this->supplier,
                'item_project' => $this->item_project,
                'unit_cost' => $this->unit_cost,
                'year' => $this->year,
                'month' => $this->month,
            ]);

            $this->closeModal();
            $this->notificationMessage = 'Item added successfully!';
            $this->showNotification = true;
            $this->resetInputFields();
            $this->dispatch('itemAdded');
        } catch (\Exception $e) {
            session()->flash('error', 'Error adding Item.');
            \Log::error('Error adding Item: ' . $e->getMessage());
            $this->dispatch('itemAddFailed');
        }
    }

    public function openAddModal()
    {
        $this->isAddModalOpen = true;
    }

    private function resetInputFields()
    {
        $this->supplier = '';
        $this->item_project = '';
        $this->unit_cost = '';
        $this->year = '';
        $this->month = '';
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

    public function dismissNotification()
    {
        $this->showNotification = false;
        $this->notificationMessage = '';
    }

    public function render()
    {
        $query = ItemsProcured::query();

        if (property_exists($this, 'filterMonth') && $this->filterMonth) {
            $query->whereRaw('LOWER(month) = ?', [strtolower($this->filterMonth)]);
        }

        if (property_exists($this, 'filterYear') && $this->filterYear) {
            $query->where('year', $this->filterYear);
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

        $items = $query->paginate(100);

        return view('livewire.items-procured-index', [
            'items' => $items,
        ])->layout('layouts.app', ['title' => 'Items Procured']);
    }

    public function performSearch()
    {
        $this->resetPage();
    }
}