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
use Illuminate\Validation\ValidationException;

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
    public $originalMonitoringData = [];

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
            'date_endorsement' => 'nullable|date',
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
            'deletingMonitoringId',
            'originalMonitoringData',
        ]);
        $this->resetValidation();
        $this->dismissNotification();
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
        $this->reset(['pr_no', 'title', 'processor', 'supplier', 'end_user', 'status', 'date_endorsement', 'specific_notes']);
    }

    public function addMonitoring()
    {
        Log::info('Attempting to add monitoring: ' . json_encode($this->only(['pr_no', 'title', 'processor', 'supplier', 'end_user', 'status', 'date_endorsement', 'specific_notes'])));
        try {
            $validatedData = $this->validate();

            ProcurementMonitoring::create($validatedData);

            $this->closeModal();
            $this->notificationType = 'success';
            $this->notificationMessage = 'Procurement Monitoring Added Successfully!';
            $this->showNotification = true;
            $this->dispatch('refreshProcurementMonitoring');
            $this->dispatch('monitoringAdded');
        } catch (ValidationException $e) {
            Log::error('Validation error adding procurement record: ' . json_encode($e->errors()));
            $this->notificationType = 'error';
            $this->notificationMessage = 'Validation failed: Please check the form for errors.';
            $this->showNotification = true;
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error adding procurement record: ' . $e->getMessage());
            $this->notificationType = 'error';
            $this->notificationMessage = 'Error adding procurement record: ' . $e->getMessage();
            $this->showNotification = true;
            $this->dispatch('monitoringAddFailed');
        }
    }

    public function updateMonitoring()
    {
        try {
            $validatedData = $this->validate();

            $monitoring = ProcurementMonitoring::findOrFail($this->editMonitoringId);

            // Build current form data for comparison
            $currentData = [
                'pr_no' => $this->pr_no,
                'title' => $this->title,
                'processor' => $this->processor,
                'supplier' => $this->supplier,
                'end_user' => $this->end_user,
                'status' => $this->status,
                'date_endorsement' => $this->date_endorsement,
                'specific_notes' => $this->specific_notes,
            ];

            // Check if there are any changes
            if ($currentData === $this->originalMonitoringData) {
                $this->notificationType = 'info';
                $this->notificationMessage = 'No changes were made to the procurement monitoring record.';
                $this->showNotification = true;
                return;
            }

            $monitoring->update($validatedData);

            $this->resetFields();
            $this->closeModal();
            $this->notificationType = 'success';
            $this->notificationMessage = 'Procurement Monitoring Updated Successfully!';
            $this->showNotification = true;
            $this->dispatch('refreshProcurementMonitoring');
            $this->dispatch('monitoringUpdated');
        } catch (ValidationException $e) {
            Log::error('Validation error during update: ' . json_encode($e->errors()));
            $this->notificationType = 'error';
            $this->notificationMessage = 'Validation failed: Please check the form for errors.';
            $this->showNotification = true;
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error updating procurement record: ' . $e->getMessage());
            $this->notificationType = 'error';
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
            $this->notificationType = 'success';
            $this->notificationMessage = 'Procurement Monitoring Deleted Successfully!';
            $this->showNotification = true;
            $this->dispatch('refreshProcurementMonitoring');
            $this->dispatch('monitoringDeleted');
        } catch (\Exception $e) {
            Log::error('Error deleting procurement record: ' . $e->getMessage());
            $this->notificationType = 'error';
            $this->notificationMessage = 'Error deleting procurement record: ' . $e->getMessage();
            $this->showNotification = true;
            $this->dispatch('monitoringDeleteFailed');
        }
    }

    public function openAddModal()
    {
        $this->resetFields();
        $this->resetValidation();
        $this->dismissNotification();
        $this->isAddModalOpen = true;
    }

    public function editMonitoring($id)
    {
        $monitoring = ProcurementMonitoring::findOrFail($id);
        $this->monitoringId = $monitoring->id;
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
        $this->dismissNotification();
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
        $this->date_endorsement = $monitoring->date_endorsement ? Carbon::parse($monitoring->date_endorsement)->format('Y-m-d') : '';
        $this->specific_notes = $monitoring->specific_notes;

        // Store original values for change detection
        $this->originalMonitoringData = [
            'pr_no' => $this->pr_no,
            'title' => $this->title,
            'processor' => $this->processor,
            'supplier' => $this->supplier,
            'end_user' => $this->end_user,
            'status' => $this->status,
            'date_endorsement' => $this->date_endorsement,
            'specific_notes' => $this->specific_notes,
        ];

        $this->resetValidation();
        $this->dismissNotification();
        $this->isEditModalOpen = true;
    }


    public function openDeleteModal($monitoringId)
    {
        Log::info('Opening delete modal for ID: ' . $monitoringId);
        $this->deletingMonitoringId = $monitoringId;
        $this->isDeleteModalOpen = true;
        $this->dismissNotification();
    }

    public function dismissNotification()
    {
        $this->showNotification = false;
        $this->notificationMessage = '';
        $this->notificationType = 'success';
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
        $this->date_endorsement = '';
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