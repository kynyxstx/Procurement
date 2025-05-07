<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ProcurementOutgoing;
use Livewire\WithPagination;

class ProcurementOutgoingIndex extends Component
{
    use WithPagination;

    public $received_date = '';
    public $end_user = '';
    public $pr_no = '';
    public $particulars = '';
    public $amount = '';
    public $creditor = '';
    public $remarks = '';
    public $responsibility = '';
    public $received_by = '';

    public $search = '';
    public $filterCreditor = ''; // Changed from filterSupplier to be more relevant
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
            'received_date' => 'nullable|date',
            'end_user' => 'nullable|string|max:255',
            'pr_no' => 'required|string|max:255',
            'particulars' => 'nullable|string|max:500',
            'amount' => 'nullable|numeric',
            'creditor' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:500',
            'responsibility' => 'nullable|string|max:255',
            'received_by' => 'nullable|string|max:255',
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
    ];

    // Close modals
    public function closeModal()
    {
        $this->isAddModalOpen = false;
        $this->isEditModalOpen = false;
        $this->isDeleteModalOpen = false;
        $this->reset([
            'received_date',
            'end_user',
            'pr_no',
            'particulars',
            'amount',
            'creditor',
            'remarks',
            'responsibility',
            'received_by',
            'editItemId',
            'isEditModalOpen',
            'isDeleteModalOpen',
        ]);
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

            ProcurementOutgoing::create([
                'received_date' => $this->received_date,
                'end_user' => $this->end_user,
                'pr_no' => $this->pr_no,
                'particulars' => $this->particulars,
                'amount' => $this->amount,
                'creditor' => $this->creditor,
                'remarks' => $this->remarks,
                'responsibility' => $this->responsibility,
                'received_by' => $this->received_by,
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
        $item = ProcurementOutgoing::find($itemId);

        if ($item) {
            $this->editItemId = $itemId;
            $this->received_date = $item->received_date ? $item->received_date->format('Y-m-d') : null;
            $this->end_user = $item->end_user;
            $this->pr_no = $item->pr_no;
            $this->particulars = $item->particulars;
            $this->amount = $item->amount;
            $this->creditor = $item->creditor;
            $this->remarks = $item->remarks;
            $this->responsibility = $item->responsibility;
            $this->received_by = $item->received_by;

            $this->isEditModalOpen = true;
        } else {
            session()->flash('error', 'Procurement record not found.');
        }
    }

    public function updateItem()
    {
        try {
            $validatedData = $this->validate();

            $item = ProcurementOutgoing::find($this->editItemId);
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
            $item = ProcurementOutgoing::find($this->deletingItemId);

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
        $query = ProcurementOutgoing::query()
            ->when($this->search, function ($query) {
                $query->where('received_date', 'like', '%' . $this->search . '%')
                    ->orWhere('end_user', 'like', '%' . $this->search . '%')
                    ->orWhere('pr_no', 'like', '%' . $this->search . '%')
                    ->orWhere('particulars', 'like', '%' . $this->search . '%')
                    ->orWhere('amount', 'like', '%' . $this->search . '%')
                    ->orWhere('creditor', 'like', '%' . $this->search . '%')
                    ->orWhere('remarks', 'like', '%' . $this->search . '%')
                    ->orWhere('responsibility', 'like', '%' . $this->search . '%')
                    ->orWhere('received_by', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterCreditor, function ($query) {
                $creditorNames = explode(';', $this->filterCreditor);
                $query->where(function ($query) use ($creditorNames) {
                    foreach ($creditorNames as $creditorName) {
                        $query->orWhere('creditor', 'like', '%' . trim($creditorName) . '%');
                    }
                });
            });

        $outgoings = $query->paginate(100);

        return view('livewire.procurement-outgoing-index', [
            'outgoings' => $outgoings,
        ])->layout('layouts.app');
    }

    public function performSearch()
    {
        $this->resetPage(); // Reset pagination when searching
    }

    private function resetFields()
    {
        $this->received_date = '';
        $this->end_user = '';
        $this->pr_no = '';
        $this->particulars = '';
        $this->amount = '';
        $this->creditor = '';
        $this->remarks = '';
        $this->responsibility = '';
        $this->received_by = '';
    }
}