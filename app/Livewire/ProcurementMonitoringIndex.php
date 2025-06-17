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
            'processor' => 'nullable|string|max:255',
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
    ];

    // Close modals
    public function closeModal()
    {
        $this->isAddModalOpen = false;
        $this->isEditModalOpen = false;
        $this->isDeleteModalOpen = false;
        $this->reset(['pr_no', 'title', 'processor', 'supplier', 'end_user', 'status', 'date_endorsement', 'specific_notes', 'editMonitoringId', 'isEditModalOpen', 'isDeleteModalOpen', 'deletingMonitoringId']);
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
            $this->validate();

            ProcurementMonitoring::create([
                'pr_no' => $this->pr_no,
                'title' => $this->title,
                'processor' => $this->processor,
                'supplier' => $this->supplier,
                'end_user' => $this->end_user,
                'status' => $this->status,
                'date_endorsement' => $this->date_endorsement,
                'specific_notes' => $this->specific_notes,
            ]);

            $this->closeModal();
            $this->notificationMessage = 'Procurement Monitoring Added Successfully!';
            $this->showNotification = true;
            $this->resetFields();
            $this->dispatch('refreshProcurementMonitoring'); // Use $this->dispatch()
            $this->dispatch('monitoringAdded');
        } catch (\Exception $e) {
            Log::error('Error adding procurement record: ' . $e->getMessage());
            session()->flash('error', 'Error adding procurement record.');
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
            $monitoring->update($validatedData);

            $this->resetFields();
            $this->closeModal();
            $this->notificationMessage = 'Procurement Monitoring Updated Successfully!';
            $this->showNotification = true;
            $this->dispatch('refreshProcurementMonitoring');
            $this->dispatch('monitoringUpdated');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error during update: ' . $e->getMessage());
            session()->flash('error', 'Validation error during update.');
            $this->dispatch('monitoringUpdateFailed');
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error updating procurement record: ' . $e->getMessage());
            session()->flash('error', 'Error updating procurement record.');
            $this->dispatch('monitoringUpdateFailed');
        }
    }
    public function deleteMonitoring()
    {
        Log::info('Attempting to delete monitoring ID: ' . $this->deletingMonitoringId);
        try {
            $monitoring = ProcurementMonitoring::findOrFail($this->deletingMonitoringId); // Use findOrFail
            $monitoring->delete();

            $this->closeModal();
            $this->notificationMessage = 'Procurement Monitoring Deleted Successfully!';
            $this->showNotification = true;
            $this->emitSelf('$refresh'); // Consider replacing emitSelf with dispatch for Livewire 3
            $this->forceRender(); // This might not be necessary with refresh, test without first
            $this->dispatch('monitoringDeleted');
        } catch (\Exception $e) {
            Log::error('Error deleting procurement record: ' . $e->getMessage());
            session()->flash('error', 'Error deleting procurement record.');
            $this->dispatch('monitoringDeleteFailed');
        }
    }
    public function openAddModal()
    {
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
        $this->date_endorsement = $monitoring->date_endorsement ? $monitoring->date_endorsement->format('Y-m-d') : null;
        $this->specific_notes = $monitoring->specific_notes;
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
        $this->date_endorsement = $monitoring->date_endorsement ? $monitoring->date_endorsement->format('Y-m-d') : null;
        $this->specific_notes = $monitoring->specific_notes;
        $this->isEditModalOpen = true;
    }

    public function openDeleteModal($monitoringId)
    {
        Log::info('Opening delete modal for ID: ' . $monitoringId);
        $this->deletingMonitoringId = $monitoringId;
        $this->isDeleteModalOpen = true;
    }

    public function dismissNotification()
    {
        $this->showNotification = false;
        $this->notificationMessage = '';
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
        $this->specific_notes = '';
    }

    // --- Helper method to build the base query with all filters ---
    // --- Helper method to build the base query with all filters ---
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
                    ->orWhereDate('date_endorsement', 'like', "%{$searchMonitoring}%")
                    ->orWhere('specific_notes', 'like', "%{$searchMonitoring}%");
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

        // New filtering logic based on endorsement days
        if ($this->filterDays === 'within_3_days') {
            $query->where('date_endorsement', '>=', $today->copy()->subDays(2)->toDateString())
                ->where('date_endorsement', '<=', Carbon::now()->toDateString());
        } elseif ($this->filterDays === '3_to_8_days') {
            $query->where('date_endorsement', '<', $today->copy()->subDays(2)->toDateString())
                ->where('date_endorsement', '>=', $today->copy()->subDays(7)->toDateString());
        } elseif ($this->filterDays === 'more_than_8_days') {
            $query->where('date_endorsement', '<', $today->copy()->subDays(7)->toDateString());
        }

        // IMPORTANT: Only apply year/month filters if they are actually selected AND valid.
        // If 'date_endorsement' can be null or empty, these might filter out valid data.
        // I've removed the default 'date('Y')' and 'date('m')' from mount() if you don't use these filters in your UI.
        // Let's make sure they are only applied if the properties are explicitly set by user interaction.

        // Re-added the checks for selectedYear and selectedMonth, but ensure they are ONLY
        // applied if the property is not null and has a meaningful value from UI input.
        // If your UI doesn't explicitly set these, data might disappear.
        if ($this->selectedYear && $this->selectedYear !== 'all') { // Assuming 'all' could be an option in your UI
            $query->whereYear('date_endorsement', $this->selectedYear);
        }
        if ($this->selectedMonth && $this->selectedMonth !== 'all') { // Assuming 'all' could be an option in your UI
            $query->whereMonth('date_endorsement', $this->selectedMonth);
        }

        // Apply sorting
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

    // --- EXCEL EXPORT ---
    public function exportToExcel()
    {
        // Get the filtered query
        $exportQuery = $this->buildMonitoringQuery();

        Log::info('Exporting ' . $exportQuery->count() . ' ProcurementMonitoring items to Excel.');

        // Use the MonitoringExport class with the filtered query
        return Excel::download(new MonitoringExport($exportQuery), 'procurement_monitoring.xlsx');
    }

    // --- PDF EXPORT ---
    public function exportToPDF()
    {
        // Get the filtered query and retrieve the data
        $data = $this->buildMonitoringQuery()->get();

        Log::info('Exporting ' . $data->count() . ' ProcurementMonitoring items to PDF.');

        // Setup Dompdf options
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans'); // Recommended for better UTF-8 support
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true); // Enable loading remote assets (images, CSS)

        $dompdf = new Dompdf($options);

        // Load the view for the PDF content
        // Make sure you create this Blade view: resources/views/exports/procurement_monitoring_pdf.blade.php
        $html = view('exports.monitoring_pdf', compact('data'))->render();

        $dompdf->loadHtml($html);

        // (Optional) Set paper size and orientation
        $dompdf->setPaper('A4', 'landscape'); // or 'portrait'

        // Render the HTML as PDF
        $dompdf->render();

        // Stream the file to the browser
        return response()->streamDownload(function () use ($dompdf) {
            echo $dompdf->output();
        }, 'procurement_monitoring.pdf');
    }
}

