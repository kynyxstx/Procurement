<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ProcurementMonitoring;
use Livewire\WithPagination;

class ProcurementMonitoringIndex extends Component
{
    use WithPagination;

    public $pr_no = '';
    public $title = '';
    public $processor = '';
    public $supplier = '';
    public $end_user = '';
    public $status = '';
    public $date_endorsement = '';

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
            'pr_no' => 'required|string|max:255',
            'title' => 'required|string|max:500',
            'processor' => 'nullable|string|max:255',
            'supplier' => 'nullable|string|max:255',
            'end_user' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'date_endorsement' => 'nullable|date',
        ];
    }

    protected $listeners = ['refreshProcurementMonitoring' => '$refresh'];

    public function mount(): void
    {
        $this->resetPage();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    protected $messages = [
        'pr_no.required' => 'PR No is required.',
        'title.required' => 'Title is required.',
    ];

    // Close modals
    public function closeModal()
    {
        $this->isAddModalOpen = false;
        $this->isEditModalOpen = false;
        $this->isDeleteModalOpen = false;
        $this->reset(['pr_no', 'title', 'processor', 'supplier', 'end_user', 'status', 'date_endorsement', 'editItemId', 'isEditModalOpen', 'isDeleteModalOpen']);
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

            ProcurementMonitoring::create([
                'pr_no' => $this->pr_no,
                'title' => $this->title,
                'processor' => $this->processor,
                'supplier' => $this->supplier,
                'end_user' => $this->end_user,
                'status' => $this->status,
                'date_endorsement' => $this->date_endorsement,
            ]);

            $this->closeModal();
            session()->flash('message', 'Procurement record added successfully!');
            $this->resetFields();
            $this->dispatch('itemAdded');
        } catch (\Exception $e) {
            session()->flash('error', 'Error adding procurement record.');
            \Log::error('Error adding procurement record: ' . $e->getMessage());
            $this->dispatch('itemAddFailed');
        }
    }

    public function openAddModal()
    {
        $this->isAddModalOpen = true;
    }

    public function openEditModal($itemId)
    {
        $item = ProcurementMonitoring::find($itemId);

        if ($item) {
            $this->editItemId = $itemId;
            $this->pr_no = $item->pr_no;
            $this->title = $item->title;
            $this->processor = $item->processor;
            $this->supplier = $item->supplier;
            $this->end_user = $item->end_user;
            $this->status = $item->status;
            $this->date_endorsement = $item->date_endorsement ? $item->date_endorsement->format('Y-m-d') : null;

            $this->isEditModalOpen = true;
        } else {
            session()->flash('error', 'Procurement record not found.');
        }
    }

    public function updateItem()
    {
        try {
            $validatedData = $this->validate();

            $item = ProcurementMonitoring::find($this->editItemId);
            if ($item) {
                $item->update($validatedData);
                $this->resetFields();
                $this->closeModal();
                session()->flash('message', 'Procurement record updated successfully!');
                $this->dispatch('itemUpdated');
            } else {
                session()->flash('error', 'Procurement record not found.');
                $this->dispatch('itemUpdateFailed');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error updating procurement record.');
            \Log::error('Error updating procurement record: ' . $e->getMessage());
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
            $item = ProcurementMonitoring::find($this->deletingItemId);

            if ($item) {
                $item->delete();
                $this->closeModal();
                session()->flash('message', 'Procurement record deleted successfully!');
                $this->dispatch('itemDeleted');
            } else {
                session()->flash('error', 'Procurement record not found.');
                $this->dispatch('itemDeleteFailed');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting procurement record.');
            \Log::error('Error deleting procurement record: ' . $e->getMessage());
            $this->dispatch('itemDeleteFailed');
        }
    }

    public function render()
    {
        $query = ProcurementMonitoring::query()
            ->when($this->search, function ($query) {
                $query->where('pr_no', 'like', '%' . $this->search . '%')
                    ->orWhere('title', 'like', '%' . $this->search . '%')
                    ->orWhere('processor', 'like', '%' . $this->search . '%')
                    ->orWhere('supplier', 'like', '%' . $this->search . '%')
                    ->orWhere('end_user', 'like', '%' . $this->search . '%')
                    ->orWhere('status', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterSupplier, function ($query) {
                $supplierNames = explode(';', $this->filterSupplier);
                $query->where(function ($query) use ($supplierNames) {
                    foreach ($supplierNames as $supplierName) {
                        $query->orWhere('supplier', 'like', '%' . trim($supplierName) . '%');
                    }
                });
            });

        $monitorings = $query->paginate(10);

        return view('livewire.procurement-monitoring-index', [
            'monitorings' => $monitorings,
        ])->layout('layouts.app');
    }


    public function performSearch()
    {
        $this->resetPage(); // Reset pagination when searching
    }

    private function resetFields()
    {
        $this->pr_no = '';
        $this->title = '';
        $this->processor = '';
        $this->supplier = '';
        $this->end_user = '';
        $this->status = '';
        $this->date_endorsement = '';
    }
}