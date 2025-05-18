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
    public $filterMonth = '';
    public $sortBy = 'received_date';
    public $sortDirection = 'desc';

    public $isEditModalOpen = false;
    public $editOutgoingId;
    public $isDeleteModalOpen = false;
    public $deletingOutgoingId;
    public $isAddModalOpen = false;

    public $showNotification = false;
    public $notificationMessage = '';
    public string $notificationType = 'success';

    protected $paginationTheme = 'tailwind';
    protected $perPage = 10;

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
        if (in_array($propertyName, ['search', 'filterMonth'])) {
            $this->performSearch();
        }
    }

    protected $messages = [
        'pr_no.required' => 'PR No is required.',
        'end_user.required' => 'End User is required.',
    ];

    public function dismissNotification()
    {
        $this->showNotification = false;
        $this->notificationMessage = '';
        $this->notificationType = 'success';
    }


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
            $amount = str_replace(',', '.', $this->amount);
            $this->amount = $amount;
            $validatedData = $this->validate();

            ProcurementOutgoing::create($validatedData);

            $this->closeModal();
            $this->resetFields();
            $this->dispatch('notify', message: 'Procurement record added successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('notify', message: 'Validation error occurred.', type: 'error');
        } catch (\Exception $e) {
            \Log::error('Error adding procurement record: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Error adding procurement record.', type: 'error');
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
            $this->amount = str_replace(',', '.', $this->amount);
            $validatedData = $this->validate();
            $outgoing = ProcurementOutgoing::find($this->editOutgoingId);

            if ($outgoing) {
                $outgoing->update($validatedData);
                $this->resetFields();
                $this->closeModal();
                $this->dispatch('notify', message: 'Procurement record updated successfully!');
            } else {
                $this->dispatch('notify', message: 'Procurement record not found.', type: 'error');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('notify', message: 'Validation error occurred.', type: 'error');
        } catch (\Exception $e) {
            \Log::error('Error updating procurement record: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Error updating procurement record.', type: 'error');
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
                $this->dispatch('notify', message: 'Procurement record deleted successfully!');
            } else {
                $this->dispatch('notify', message: 'Procurement record not found.', type: 'error');
            }
        } catch (\Exception $e) {
            \Log::error('Error deleting procurement record: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Error deleting procurement record.', type: 'error');
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
            ->when($this->filterMonth, function ($query) {
                $query->whereMonth('received_date', date('m', strtotime($this->filterMonth)));
            })
            ->orderBy($this->sortBy, $this->sortDirection);

        $outgoings = $query->paginate($this->perPage);

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