<?php

namespace App\Livewire;

use Illuminate\Notifications\Notification;
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
    public $editMonitoringId;
    public $isDeleteModalOpen = false;
    public $deletingmonitoringId;
    public $isAddModalOpen = false;
    public $showNotification = false;
    public $notificationMessage = '';

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
        $this->reset(['pr_no', 'title', 'processor', 'supplier', 'end_user', 'status', 'date_endorsement', 'editMonitoringId', 'isEditModalOpen', 'isDeleteModalOpen']);
    }

    public function saveMonitoring()
    {
        if ($this->editMonitoringId) {
            $this->updateMonitoring();
        } else {
            $this->addMonitoring();
        }
    }

    public function loadMonitoring()
    {
        $this->reset(['pr_no', 'title', 'processor', 'supplier', 'end_user', 'status', 'date_endorsement']);
    }

    public function addMonitoring()
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
            $this->showNotification = 'Procurement Monitoring Added Successfully';
            $this->notificationMessage = true;
            $this->resetFields();
            $this->dispatch('monitoringAdded');
        } catch (\Exception $e) {
            session()->flash('error', 'Error adding procurement record.');
            \Log::error('Error adding procurement record: ' . $e->getMessage());
            $this->dispatch('monitoringAddFailed');
        }
    }

    public function openAddModal()
    {
        $this->isAddModalOpen = true;
    }

    public function openEditModal($imonitoringId)
    {
        $monitoring = ProcurementMonitoring::find($imonitoringId);

        if ($monitoring) {
            $this->editMonitoringId = $monitoring->id;
            $this->pr_no = $monitoring->pr_no;
            $this->title = $monitoring->title;
            $this->processor = $monitoring->processor;
            $this->supplier = $monitoring->supplier;
            $this->end_user = $monitoring->end_user;
            $this->status = $monitoring->status;
            $this->date_endorsement = $monitoring->date_endorsement;

            $this->isEditModalOpen = true;
        } else {
            session()->flash('error', 'Procurement record not found.');
        }
    }

    public function updateMonitoring()
    {
        try {
            $validatedData = $this->validate();

            $monitoring = ProcurementMonitoring::find($this->editMonitoringId);
            if ($monitoring) {
                $monitoring->update($validatedData);
                $this->resetFields();
                $this->closeModal();
                $this->notificationMessage = 'Procurement Monitoring Updated Successfully';
                $this->showNotification = true;
                $this->dispatch('monitoringUpdated');
            } else {
                session()->flash('error', 'Procurement record not found.');
                $this->dispatch('monitoringUpdateFailed');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error updating procurement record.');
            \Log::error('Error updating procurement record: ' . $e->getMessage());
            $this->dispatch('monitoringUpdateFailed');
        }
    }

    public function openDeleteModal($monitoringId)
    {
        $this->deletingMonitoringId = $monitoringId;
        $this->isDeleteModalOpen = true;
    }

    public function deleteMonitoring()
    {
        try {
            $monitoring = ProcurementMonitoring::find($this->deletingMonitoringId);

            if ($monitoring) {
                $monitoring->delete();
                $this->closeModal();
                $this->notificationMessage = 'Procurement Monitoring Deleted Successfully';
                $this->showNotification = true;
                $this->dispatch('monitoringDeleted');
            } else {
                session()->flash('error', 'Procurement record not found.');
                $this->dispatch('monitoringDeleteFailed');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting procurement record.');
            \Log::error('Error deleting procurement record: ' . $e->getMessage());
            $this->dispatch('monitoringDeleteFailed');
        }
    }

    public function dismissNotification()
    {
        $this->showNotification = false;
        $this->notificationMessage = '';
    }

    public function render()
    {
        $query = ProcurementMonitoring::query();

        if ($this->search) {
            $searchMonitoring = $this->search;
            $query->where(function ($query) use ($searchMonitoring) {
                $query->where('pr_no', 'like', "%{$searchMonitoring}%")
                    ->orWhere('title', 'like', "%{$searchMonitoring}%")
                    ->orWhere('processor', 'like', "%{$searchMonitoring}%")
                    ->orWhere('supplier', 'like', "%{$searchMonitoring}%")
                    ->orWhere('end_user', 'like', "%{$searchMonitoring}%")
                    ->orWhere('status', 'like', "%{$searchMonitoring}%");
            });
        }

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