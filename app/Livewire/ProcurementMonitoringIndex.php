<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\ProcurementMonitoring;
use Livewire\WithPagination;
use App\Exports\MonitoringExport;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException; // Import ValidationException

class ProcurementMonitoringIndex extends Component
{
    use WithPagination;

    public $pr_no = '';
    public $title = '';
    public $processor = '';
    public $supplier = '';
    public $end_user = '';
    public $status = '';
    public $date_endorsement = ''; // Keep as empty string for HTML input type="date"
    public $specific_notes = '';

    public $monitoringId;
    public $search = '';
    public $filterDays = '';
    public $filterProcessor = '';

    public $isEditModalOpen = false;
    public $editMonitoringId;
    public $isDeleteModalOpen = false;
    public $deletingMonitoringId;
    public $isAddModalOpen = false;
    public $showNotification = false;
    public $notificationMessage = '';
    public $notificationType = 'success';

    public $selectedYear;
    public $selectedMonth;

    public $sortField = 'pr_no';
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
    protected $perPage = 50;

    protected $listeners = ['refreshProcurementMonitoring' => '$refresh'];

    public function mount(): void
    {
        $this->resetPage();
    }

    public function rules()
    {
        return [
            'pr_no' => 'required|string|max:255',
            'title' => 'required|string|max:500',
            'processor' => 'required|string|max:255',
            'supplier' => 'nullable|string|max:255',
            'end_user' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'date_endorsement' => 'nullable|date', // This rule is correct and sufficient with the mutator
            'specific_notes' => 'nullable|string|max:1000',
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    protected $messages = [
        'pr_no.required' => 'PR No is required.',
        'title.required' => 'Title is required.',
        'processor.required' => 'Processor is required.',
    ];

    // Close modals and reset notification state
    public function closeModal()
    {
        $this->isAddModalOpen = false;
        $this->isEditModalOpen = false;
        $this->isDeleteModalOpen = false;
        $this->reset([
            'pr_no',
            'title',
            'processor',
            'supplier',
            'end_user',
            'status',
            'date_endorsement',
            'specific_notes',
            'editMonitoringId',
            'deletingMonitoringId'
        ]);
        $this->resetValidation(); // Clear validation errors
        $this->dismissNotification(); // Clear and hide notification
    }

    public function saveMonitoring()
    {
        if ($this->editMonitoringId) {
            $this->updateMonitoring();
        } else {
            $this->addMonitoring();
        }
    }

    // loadMonitoring method seems redundant if closeModal resets fields.
    // Keeping it here for now but consider removing if not used elsewhere.
    public function loadMonitoring()
    {
        $this->reset(['pr_no', 'title', 'processor', 'supplier', 'end_user', 'status', 'date_endorsement', 'specific_notes']);
    }

    public function addMonitoring()
    {
        Log::info('Attempting to add monitoring: ' . json_encode($this->only(['pr_no', 'title', 'processor', 'supplier', 'end_user', 'status', 'date_endorsement', 'specific_notes'])));
        try {
            $validatedData = $this->validate();

            // The model mutator will handle the empty string to null conversion for date_endorsement
            ProcurementMonitoring::create($validatedData);

            $this->closeModal();
            $this->notificationType = 'success'; // Set type for success
            $this->notificationMessage = 'Procurement Monitoring Added Successfully!';
            $this->showNotification = true;
            // No need for resetFields() here, closeModal() already calls reset on properties
            $this->dispatch('refreshProcurementMonitoring');
            $this->dispatch('monitoringAdded');
        } catch (ValidationException $e) {
            Log::error('Validation error adding procurement record: ' . json_encode($e->errors()));
            $this->notificationType = 'error'; // Set type for error
            $this->notificationMessage = 'Validation failed: Please check the form for errors.';
            $this->showNotification = true;
            // Do not dispatch 'monitoringAddFailed' if it's a validation error, Livewire handles validation message display.
            throw $e; // Re-throw to make Livewire display errors next to fields
        } catch (\Exception $e) {
            Log::error('Error adding procurement record: ' . $e->getMessage());
            $this->notificationType = 'error'; // Set type for error
            $this->notificationMessage = 'Error adding procurement record: ' . $e->getMessage();
            $this->showNotification = true;
            $this->dispatch('monitoringAddFailed');
        }
    }

    public function updateMonitoring()
    {
        Log::info('Attempting to update monitoring ID ' . $this->editMonitoringId . ' with data: ' . json_encode($this->only(['pr_no', 'title', 'processor', 'supplier', 'end_user', 'status', 'date_endorsement', 'specific_notes'])));
        try {
            $validatedData = $this->validate();
            Log::info('Validated data for update: ' . json_encode($validatedData));

            $monitoring = ProcurementMonitoring::findOrFail($this->editMonitoringId);
            // The model mutator will handle the empty string to null conversion for date_endorsement
            $monitoring->update($validatedData);

            $this->resetFields(); // This clears fields, ensure it's desired after update and before closing modal
            $this->closeModal();
            $this->notificationType = 'success'; // Set type for success
            $this->notificationMessage = 'Procurement Monitoring Updated Successfully!';
            $this->showNotification = true;
            $this->dispatch('refreshProcurementMonitoring');
            $this->dispatch('monitoringUpdated');
        } catch (ValidationException $e) {
            Log::error('Validation error during update: ' . json_encode($e->errors()));
            $this->notificationType = 'error'; // Set type for error
            $this->notificationMessage = 'Validation failed: Please check the form for errors.';
            $this->showNotification = true;
            // Do not dispatch 'monitoringUpdateFailed' if it's a validation error, Livewire handles validation message display.
            throw $e; // Re-throw to make Livewire display errors next to fields
        } catch (\Exception $e) {
            Log::error('Error updating procurement record: ' . $e->getMessage());
            $this->notificationType = 'error'; // Set type for error
            $this->notificationMessage = 'Error updating procurement record: ' . $e->getMessage();
            $this->showNotification = true;
            $this->dispatch('monitoringUpdateFailed');
        }
    }

    public function deleteMonitoring()
    {
        Log::info('Attempting to delete monitoring ID: ' . $this->deletingMonitoringId);
        try {
            $monitoring = ProcurementMonitoring::findOrFail($this->deletingMonitoringId);
            $monitoring->delete();

            $this->closeModal();
            $this->notificationType = 'success'; // Set type for success
            $this->notificationMessage = 'Procurement Monitoring Deleted Successfully!';
            $this->showNotification = true;
            $this->dispatch('refreshProcurementMonitoring');
            $this->dispatch('monitoringDeleted');
        } catch (\Exception $e) {
            Log::error('Error deleting procurement record: ' . $e->getMessage());
            $this->notificationType = 'error'; // Set type for error
            $this->notificationMessage = 'Error deleting procurement record: ' . $e->getMessage();
            $this->showNotification = true;
            $this->dispatch('monitoringDeleteFailed');
        }
    }

    public function openAddModal()
    {
        $this->resetFields();
        $this->resetValidation();
        $this->dismissNotification(); // Clear and hide notification
        $this->isAddModalOpen = true;
    }

    // This method seems redundant if openEditModal is the one being used for opening the modal.
    // It's effectively duplicated by openEditModal. Consider removing this if not called elsewhere.
    public function editMonitoring($id)
    {
        $monitoring = ProcurementMonitoring::findOrFail($id);
        $this->monitoringId = $monitoring->id; // This property is not used for edit, editMonitoringId is.
        $this->pr_no = $monitoring->pr_no;
        $this->title = $monitoring->title;
        $this->processor = $monitoring->processor;
        $this->supplier = $monitoring->supplier;
        $this->end_user = $monitoring->end_user;
        $this->status = $monitoring->status;
        // Format date for display: empty string if null, formatted date string otherwise
        $this->date_endorsement = $monitoring->date_endorsement ? Carbon::parse($monitoring->date_endorsement)->format('Y-m-d') : '';
        $this->specific_notes = $monitoring->specific_notes;
        $this->resetValidation();
        $this->dismissNotification(); // Clear and hide notification
        $this->isEditModalOpen = true;
    }

    public function openEditModal($monitoringId)
    {
        Log::info('Opening edit modal for ID: ' . $monitoringId);
        $monitoring = ProcurementMonitoring::findOrFail($monitoringId);
        $this->editMonitoringId = $monitoring->id;
        $this->pr_no = $monitoring->pr_no;
        $this->title = $monitoring->title;
        $this->processor = $monitoring->processor;
        $this->supplier = $monitoring->supplier;
        $this->end_user = $monitoring->end_user;
        $this->status = $monitoring->status;
        // Format date for display: empty string if null, formatted date string otherwise
        $this->date_endorsement = $monitoring->date_endorsement ? Carbon::parse($monitoring->date_endorsement)->format('Y-m-d') : '';
        $this->specific_notes = $monitoring->specific_notes;
        $this->resetValidation();
        $this->dismissNotification(); // Clear and hide notification
        $this->isEditModalOpen = true;
    }

    public function openDeleteModal($monitoringId)
    {
        Log::info('Opening delete modal for ID: ' . $monitoringId);
        $this->deletingMonitoringId = $monitoringId;
        $this->isDeleteModalOpen = true;
        $this->dismissNotification(); // Clear and hide notification
    }

    public function dismissNotification()
    {
        $this->showNotification = false;
        $this->notificationMessage = '';
        $this->notificationType = 'success'; // Reset to default success type
    }

    public function performSearch()
    {
        $this->resetPage();
    }

    private function resetFields()
    {
        $this->pr_no = '';
        $this->title = '';
        $this->processor = '';
        $this->supplier = '';
        $this->end_user = '';
        $this->status = '';
        $this->date_endorsement = ''; // Always reset to empty string for HTML input type="date"
        $this->specific_notes = '';
    }

    private function buildMonitoringQuery()
    {
        $today = Carbon::now()->startOfDay();
        $query = ProcurementMonitoring::query();

        if ($this->search) {
            $searchMonitoring = $this->search;
            $query->where(function ($query) use ($searchMonitoring) {
                $query->where('pr_no', 'like', "%{$searchMonitoring}%")
                    ->orWhere('title', 'like', "%{$searchMonitoring}%")
                    ->orWhere('processor', 'like', "%{$searchMonitoring}%")
                    ->orWhere('supplier', 'like', "%{$searchMonitoring}%")
                    ->orWhere('end_user', 'like', "%{$searchMonitoring}%")
                    ->orWhere('status', 'like', "%{$searchMonitoring}%")
                    ->orWhere('specific_notes', 'like', "%{$searchMonitoring}%");


                if (strtotime($searchMonitoring) !== false) {
                    $query->orWhereDate('date_endorsement', 'like', "%{$searchMonitoring}%");
                }
            });
        }

        if ($this->filterProcessor) {
            $filterProcessors = explode(';', $this->filterProcessor);
            $query->where(function ($query) use ($filterProcessors) {
                foreach ($filterProcessors as $name) {
                    $query->orWhere('processor', 'like', '%' . trim($name) . '%');
                }
            });
        }

        if ($this->filterDays === 'within_3_days') {
            $query->where('date_endorsement', '>=', $today->copy()->subDays(2)->toDateString())
                ->where('date_endorsement', '<=', Carbon::now()->toDateString());
        } elseif ($this->filterDays === '3_to_8_days') {
            $query->where('date_endorsement', '<', $today->copy()->subDays(2)->toDateString())
                ->where('date_endorsement', '>=', $today->copy()->subDays(7)->toDateString());
        } elseif ($this->filterDays === 'more_than_8_days') {
            $query->where('date_endorsement', '<', $today->copy()->subDays(7)->toDateString());
        }

        if ($this->selectedYear && $this->selectedYear !== 'all') {
            $query->whereYear('date_endorsement', $this->selectedYear);
        }
        if ($this->selectedMonth && $this->selectedMonth !== 'all') {
            $query->whereMonth('date_endorsement', $this->selectedMonth);
        }

        if ($this->sortField && $this->sortDirection) {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        return $query;
    }

    public function render()
    {
        $monitorings = $this->buildMonitoringQuery()->paginate($this->perPage);

        return view('livewire.procurement-monitoring-index', [
            'monitorings' => $monitorings,
        ])->layout('layouts.app');
    }

    public function exportToExcel()
    {
        try {
            $exportQuery = $this->buildMonitoringQuery();
            Log::info('Exporting ' . $exportQuery->count() . ' ProcurementMonitoring items to Excel.');
            return Excel::download(new MonitoringExport($exportQuery), 'procurement_monitoring.xlsx');
        } catch (\Exception $e) {
            Log::error('Error exporting items to Excel: ' . $e->getMessage());
            $this->notificationType = 'error';
            $this->notificationMessage = 'Failed to export to Excel: ' . $e->getMessage();
            $this->showNotification = true;
        }
    }

    public function exportToPDF()
    {
        try {
            $data = $this->buildMonitoringQuery()->get();
            Log::info('Exporting ' . $data->count() . ' ProcurementMonitoring items to PDF.');

            $options = new Options();
            $options->set('defaultFont', 'DejaVu Sans');
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);

            $dompdf = new Dompdf($options);

            // Make sure the 'exports.monitoring_pdf' view exists and is correctly structured for PDF
            $html = view('exports.monitoring_pdf', compact('data'))->render();

            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();

            return response()->streamDownload(function () use ($dompdf) {
                echo $dompdf->output();
            }, 'procurement_monitoring.pdf');
        } catch (\Exception $e) {
            Log::error('Error exporting items to PDF: ' . $e->getMessage());
            $this->notificationType = 'error';
            $this->notificationMessage = 'Failed to export to PDF: ' . $e->getMessage();
            $this->showNotification = true;
        }
    }
}