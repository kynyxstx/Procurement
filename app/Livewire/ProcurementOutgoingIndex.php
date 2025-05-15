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
    public $filterCreditor = '';
    public $isEditModalOpen = false;
    public $editOutgoingId;
    public $isDeleteModalOpen = false;
    public $deletingOutgoingId;
    public $isAddModalOpen = false;

    public $showNotification = false;
    public $notificationMessage = '';

    protected $paginationTheme = 'tailwind';
    protected $perPage = 5;

    public function rules()
    {
        return [
            'received_date' => 'nullable|date',
            'end_user' => 'required|string|max:255',
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
        'end_user.required' => 'End User is required.',
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
            'editOutgoingId',
            'isEditModalOpen',
            'isDeleteModalOpen',
        ]);
    }

    // Save Outgoing (Create or Update)
    public function saveOutgoing()
    {
        if ($this->editOutgoingId) {
            $this->updateOutgoing();
        } else {
            $this->addOutgoing();
        }
    }

    public function addOutgoing()
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
            $this->dispatch('outgoingAdded');
        } catch (\Exception $e) {
            session()->flash('error', 'Error adding procurement record.');
            \Log::error('Error adding procurement record: ' . $e->getMessage());
            $this->dispatch('outgoingAddFailed');
        }
    }

    public function openAddModal()
    {
        $this->isAddModalOpen = true;
    }

    public function openEditModal($outgoingId)
    {
        $outgoing = ProcurementOutgoing::find($outgoingId);

        if ($outgoing) {
            $this->editOutgoingId = $outgoingId;
            $this->received_date = $outgoing->received_date ? $outgoing->received_date->format('Y-m-d') : null;
            $this->end_user = $outgoing->end_user;
            $this->pr_no = $outgoing->pr_no;
            $this->particulars = $outgoing->particulars;
            $this->amount = $outgoing->amount;
            $this->creditor = $outgoing->creditor;
            $this->remarks = $outgoing->remarks;
            $this->responsibility = $outgoing->responsibility;
            $this->received_by = $outgoing->received_by;

            $this->isEditModalOpen = true;
        } else {
            session()->flash('error', 'Procurement record not found.');
        }
    }

    public function updateOutgoing()
    {
        try {
            $validatedData = $this->validate();

            $outgoing = ProcurementOutgoing::find($this->editOutgoingId);
            if ($outgoing) {
                $outgoing->update($validatedData);
                $this->resetFields();
                $this->closeModal();
                session()->flash('message', 'Procurement record updated successfully!');
                $this->dispatch('outgoingUpdated');
            } else {
                session()->flash('error', 'Procurement record not found.');
                $this->dispatch('outgoingUpdateFailed');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error updating procurement record.');
            \Log::error('Error updating procurement record: ' . $e->getMessage());
            $this->dispatch('outgoingUpdateFailed');
        }
    }

    public function openDeleteModal($outgoingId)
    {
        $this->deletingOutgoingId = $outgoingId;
        $this->isDeleteModalOpen = true;
    }

    public function deleteOutgoing()
    {
        try {
            $outgoing = ProcurementOutgoing::find($this->deletingOutgoingId);

            if ($outgoing) {
                $outgoing->delete();
                $this->closeModal();
                session()->flash('message', 'Procurement record deleted successfully!');
                $this->dispatch('outgoingDeleted');
            } else {
                session()->flash('error', 'Procurement record not found.');
                $this->dispatch('outgoingDeleteFailed');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting procurement record.');
            \Log::error('Error deleting procurement record: ' . $e->getMessage());
            $this->dispatch('outgoingDeleteFailed');
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

        $outgoings = $query->paginate(5);

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