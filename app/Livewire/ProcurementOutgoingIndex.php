<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ProcurementOutgoing;
use Livewire\WithPagination;
use App\Exports\OutgoingExport; // Import your Excel export class
use Maatwebsite\Excel\Facades\Excel; // Import the Excel facade
use Dompdf\Dompdf;                    // Import Dompdf (if not globally configured)
use Dompdf\Options;                   // Import Dompdf Options
use Illuminate\Support\Facades\Log;   // Added for logging
use Carbon\Carbon;

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
    public $filterEndUser = '';
    public $filterReceivedby = '';

    public $isEditModalOpen = false;
    public $editOutgoingId;
    public $isDeleteModalOpen = false;
    public $deletingOutgoingId;
    public $isAddModalOpen = false;

    public $showNotification = false;
    public string $notificationMessage = '';
    public string $notificationType = 'success';

    public $sortBy = 'received_date';
    public $sortDirection = 'asc';

    public function setSortBy($sortField)
    {
        $allowedFields = ['received_date', 'end_user', 'pr_no', 'particulars', 'amount', 'creditor', 'remarks', 'responsibility', 'received_by'];
        if (in_array($sortField, $allowedFields) && $this->sortBy === $sortField) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $sortField;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }


    protected $paginationTheme = 'tailwind';
    protected $perPage = 10;

    public function rules()
    {
        return [
            'received_date' => 'nullable|date',
            'end_user' => 'required|string|max:255',
            'pr_no' => 'required|string|max:255',
            'particulars' => 'nullable|string|max:500',
            'amount' => 'nullable|string',
            'creditor' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:500',
            'responsibility' => 'nullable|string|max:255',
            'received_by' => 'nullable|string|max:255',
        ];
    }

    protected $listeners = ['refreshProcurementMonitoring' => '$refresh']; // Note: This listener name seems to be for Monitoring, not Outgoing. You might want to update it if you use dispatch on the outgoing side.

    public function mount(): void
    {
        $this->resetPage();
    }

    public function updated(string $propertyName)
    {
        $this->validateOnly($propertyName);
        if (in_array($propertyName, ['search', 'filterMonth', 'filterEndUser', 'filterReceivedby'])) { // Added other filters to trigger resetPage
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
            'deletingOutgoingId', // Added this to reset on close
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
            $amount = str_replace(',', '', $this->amount);
            $this->amount = $amount;

            // Always parse and format the received_date for consistency
            // The datetime-local input sends YYYY-MM-DDTHH:MM, Carbon::parse handles 'T'
            if (!empty($this->received_date)) {
                $this->received_date = Carbon::parse($this->received_date)->format('Y-m-d H:i:s'); // Added seconds for full precision
            }

            $validatedData = $this->validate();

            ProcurementOutgoing::create($validatedData);

            $this->closeModal();
            $this->resetFields();
            $this->dispatch('notify', message: 'Procurement record added successfully!');
            $this->dispatch('refreshProcurementOutgoing');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('notify', message: 'Validation error occurred.', type: 'error');
            Log::error('Validation error adding outgoing record: ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Error adding procurement record: ' . $e->getMessage());
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
            $this->received_date = $outgoing->received_date
                ? (Carbon::parse($outgoing->received_date))->format('Y-m-d\TH:i')
                : null;
            $this->end_user = $outgoing->end_user;
            $this->pr_no = $outgoing->pr_no;
            $this->particulars = $outgoing->particulars;
            $this->amount = is_numeric($outgoing->amount) ? number_format($outgoing->amount, 2) : $outgoing->amount;
            $this->creditor = $outgoing->creditor;
            $this->remarks = $outgoing->remarks;
            $this->responsibility = $outgoing->responsibility;
            $this->received_by = $outgoing->received_by;

            $this->isEditModalOpen = true;
        } else {
            $this->dispatch('notify', message: 'Procurement record not found.', type: 'error');
        }
    }

    // Update Outgoing (Edit)
    public function updateOutgoing()
    {
        try {
            if (!empty($this->received_date)) {
                $this->received_date = Carbon::parse($this->received_date)->format('Y-m-d H:i:s');
            }

            // Remove commas before saving to DB
            $this->amount = str_replace(',', '', $this->amount);
            $validatedData = $this->validate();
            $outgoing = ProcurementOutgoing::find($this->editOutgoingId);

            if ($outgoing) {
                $outgoing->update($validatedData);
                $this->resetFields();
                $this->closeModal();
                $this->dispatch('notify', message: 'Procurement record updated successfully!');
                $this->dispatch('refreshProcurementOutgoing');
            } else {
                $this->dispatch('notify', message: 'Procurement record not found.', type: 'error');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('notify', message: 'Validation error occurred.', type: 'error');
            Log::error('Validation error updating outgoing record: ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Error updating procurement record: ' . $e->getMessage());
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
                $this->dispatch('refreshProcurementOutgoing'); // Added dispatch for refresh
            } else {
                $this->dispatch('notify', message: 'Procurement record not found.', type: 'error');
            }
        } catch (\Exception $e) {
            Log::error('Error deleting procurement record: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Error deleting procurement record.', type: 'error');
        }
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

    // --- Helper method to build the base query with all filters ---
    private function buildOutgoingQuery()
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
            });

        if ($this->filterEndUser) {
            $endUserNames = explode(';', $this->filterEndUser);
            $query->where(function ($query) use ($endUserNames) {
                foreach ($endUserNames as $endUserName) {
                    $query->orWhere('end_user', 'like', '%' . trim($endUserName) . '%');
                }
            });
        }

        if ($this->filterReceivedby) {
            $receivedByNames = explode(';', $this->filterReceivedby);
            $query->where(function ($query) use ($receivedByNames) {
                foreach ($receivedByNames as $receivedByName) {
                    $query->orWhere('received_by', 'like', '%' . trim($receivedByName) . '%');
                }
            });
        }

        // Apply sorting based on sortBy and sortDirection
        $query->orderBy($this->sortBy, $this->sortDirection);

        return $query;
    }

    public function render()
    {
        $outgoings = $this->buildOutgoingQuery()->paginate($this->perPage);

        return view('livewire.procurement-outgoing-index', [
            'outgoings' => $outgoings,
        ])->layout('layouts.app');
    }

    // --- EXCEL EXPORT ---
    public function exportToExcel()
    {
        // Get the filtered query
        $exportQuery = $this->buildOutgoingQuery();

        Log::info('Exporting ' . $exportQuery->count() . ' ProcurementOutgoing items to Excel.');

        // Use the OutgoingExport class with the filtered query
        // Ensure that Maatwebsite\Excel is correctly imported: use Maatwebsite\Excel\Facades\Excel;
        return Excel::download(new OutgoingExport($exportQuery), 'procurement_outgoing.xlsx');
    }

    // --- PDF EXPORT ---
    public function exportToPDF()
    {
        // Get the filtered query and retrieve the data
        $data = $this->buildOutgoingQuery()->get();

        Log::info('Exporting ' . $data->count() . ' ProcurementOutgoing items to PDF.');

        // Setup Dompdf options
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true); // Enable loading remote assets (images, CSS)

        $dompdf = new Dompdf($options);

        // Load the view for the PDF content
        // Make sure you create this Blade view: resources/views/exports/outgoing_pdf.blade.php
        $html = view('exports.outgoing_pdf', compact('data'))->render();

        $dompdf->loadHtml($html);

        // (Optional) Set paper size and orientation
        $dompdf->setPaper('A4', 'landscape'); // or 'portrait' - adjust as needed for many columns

        // Render the HTML as PDF
        $dompdf->render();

        // Stream the file to the browser
        return response()->streamDownload(function () use ($dompdf) {
            echo $dompdf->output();
        }, 'procurement_outgoing.pdf');
    }
}