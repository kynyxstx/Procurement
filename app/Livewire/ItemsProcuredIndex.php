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
            session()->flash('message', 'Item added successfully!');
            $this->resetInputFields();
        } catch (\Exception $e) {
            session()->flash('error', 'Error adding Item.');
            \Log::error('Error adding Item: ' . $e->getMessage());
        }
    }

    public function openAddModal()
    {
        $this->resetInputFields();
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
                $item->update([
                    'supplier' => $validatedData['supplier'],
                    'item_project' => $validatedData['item_project'],
                    'unit_cost' => $validatedData['unit_cost'],
                    'year' => $validatedData['year'],
                    'month' => $validatedData['month'],
                ]);
                $this->resetInputFields();
                $this->closeModal();
                session()->flash('message', 'Item updated successfully!');
                $this->dispatch('itemUpdated');
                $this->loadItems();
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
                session()->flash('message', 'Item deleted successfully!');
                $this->dispatch('itemDeleted');
                $this->loadItems();
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

    public function render()
    {
        $items = ItemsProcured::query()
            ->when($this->search, function ($query) {
                $query->where('supplier', 'like', '%' . $this->search . '%')
                    ->orWhere('item_project', 'like', '%' . $this->search . '%')
                    ->orWhere('unit_cost', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterSupplier, function ($query) {
                $supplierNames = explode(';', $this->filterSupplier);
                $query->where(function ($query) use ($supplierNames) {
                    foreach ($supplierNames as $supplierName) {
                        $query->orWhere('supplier', 'like', '%' . trim($supplierName) . '%');
                    }
                });
            })
            ->paginate(perPage: 100);

        return view('livewire.items-procured-index', [
            'items' => $items,
        ])->layout('layouts.app');
    }

    public function performSearch()
    {
        $this->resetPage();
    }
}