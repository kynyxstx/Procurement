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
        if ($this->editItemId) {
            $this->updateItem();
        } else {
            $this->addItem();
        }
    }

    public function addItem()
    {
        try {
            $this->validate();

            ItemsProcured::create([
                'supplier' => $this->supplier,
                'item_project' => $this->item_project,
                'unit_cost' => $this->unit_cost,
            ]);

            $this->closeModal();
            session()->flash('message', 'Item added successfully!');
            $this->resetFields();
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

    public function openEditModal($itemId)
    {
        $item = ItemsProcured::find($itemId);

        if ($item) {
            $this->editItemId = $itemId;
            $this->supplier = $item->supplier;
            $this->item_project = $item->item_project;
            $this->unit_cost = $item->unit_cost;

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
                $this->resetFields();
                $this->closeModal();
                session()->flash('message', 'Item updated successfully!');
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
                session()->flash('message', 'Item deleted successfully!');
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

    private function resetFields()
    {
        $this->supplier = '';
        $this->item_project = '';
        $this->unit_cost = '';
    }
}