<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ProcurementOutgoing;
use Livewire\WithPagination;
use App\Exports\OutgoingExport;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException; // Import ValidationException

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
    public string $notificationType = 'success'; // Already present, which is great!

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
    protected $perPage = 100;

    public function rules()
    {
        return [
            'received_date' => 'nullable|date',
            'end_user' => 'required|string|max:255',
            'pr_no' => 'required|string|max:255',
            'particulars' => 'nullable|string|max:500',
            'amount' => 'nullable|string', // Keeping as string due to possibility of non-numeric values or commas
            'creditor' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:500',
            'responsibility' => 'nullable|string|max:255',
            'received_by' => 'nullable|string|max:255',
        ];
    }

    protected $listeners = [
        'refreshProcurementOutgoing' => '$refresh', // Changed listener name for consistency
        // If you were dispatching 'notify' from other components and want it to trigger this Livewire component's notification,
        // you would add a method like public function notify($message, $type = 'success') and listen to 'notify' here.
        // For now, we're making this component self-contained for its notifications.
    ];

    public function mount(): void
    {
        $this->resetPage();
    }

    public function updated(string $propertyName)
    {
        $this->validateOnly($propertyName);
        if (in_array($propertyName, ['search', 'filterMonth', 'filterEndUser', 'filterReceivedby'])) {
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
        $this->notificationType = 'success'; // Reset to default success type
    }

    // Close modals and reset form/notification state
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
            'deletingOutgoingId',
        ]);
        $this->resetValidation(); // Clear validation errors
        $this->dismissNotification(); // Clear and hide notification
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
            // Remove commas from amount before validation and saving
            $this->amount = str_replace(',', '', $this->amount);

            // Format received_date for consistency if not empty
            if (!empty($this->received_date)) {
                $this->received_date = Carbon::parse($this->received_date)->format('Y-m-d H:i:s');
            } else {
                $this->received_date = null; // Ensure null if empty for nullable database column
            }

            $validatedData = $this->validate();

            ProcurementOutgoing::create($validatedData);

            $this->closeModal();
            $this->notificationType = 'success'; // Set success type
            $this->notificationMessage = 'Procurement record added successfully!';
            $this->showNotification = true;
            // No need for resetFields() here, closeModal() already calls reset on properties
            $this->dispatch('refreshProcurementOutgoing'); // Dispatch to refresh table
        } catch (ValidationException $e) {
            Log::error('Validation error adding outgoing record: ' . json_encode($e->errors()));
            $this->notificationType = 'error'; // Set error type
            $this->notificationMessage = 'Validation failed. Please check the form for errors.';
            $this->showNotification = true;
            throw $e; // Re-throw to make Livewire display errors next to fields
        } catch (\Exception $e) {
            Log::error('Error adding procurement record: ' . $e->getMessage());
            $this->notificationType = 'error'; // Set error type
            $this->notificationMessage = 'Error adding procurement record: ' . $e->getMessage();
            $this->showNotification = true;
        }
    }

    public function openAddModal()
    {
        $this->resetFields(); // Ensure fields are clear
        $this->resetValidation(); // Clear any previous validation errors
        $this->dismissNotification(); // Clear and hide notification
        $this->isAddModalOpen = true;
    }

    public function openEditModal($outgoingId)
    {
        $outgoing = ProcurementOutgoing::find($outgoingId);
        if ($outgoing) {
            $this->editOutgoingId = $outgoingId;
            $this->received_date = $outgoing->received_date
                ? (Carbon::parse($outgoing->received_date))->format('Y-m-d\TH:i')
                : null; // Use null for empty date to correctly display in datetime-local input
            $this->end_user = $outgoing->end_user;
            $this->pr_no = $outgoing->pr_no;
            $this->particulars = $outgoing->particulars;
            $this->amount = is_numeric($outgoing->amount) ? number_format($outgoing->amount, 2) : $outgoing->amount;
            $this->creditor = $outgoing->creditor;
            $this->remarks = $outgoing->remarks;
            $this->responsibility = $outgoing->responsibility;
            $this->received_by = $outgoing->received_by;

            $this->resetValidation(); // Clear any previous validation errors
            $this->dismissNotification(); // Clear and hide notification
            $this->isEditModalOpen = true;
        } else {
            $this->notificationType = 'error'; // Set error type
            $this->notificationMessage = 'Procurement record not found.';
            $this->showNotification = true;
        }
    }

    // Update Outgoing (Edit)
    public function updateOutgoing()
    {
        try {
            // Format received_date for consistency if not empty
            if (!empty($this->received_date)) {
                $this->received_date = Carbon::parse($this->received_date)->format('Y-m-d H:i:s');
            } else {
                $this->received_date = null; // Ensure null if empty for nullable database column
            }

            // Remove commas before saving to DB
            $this->amount = str_replace(',', '', $this->amount);
            $validatedData = $this->validate();
            $outgoing = ProcurementOutgoing::find($this->editOutgoingId);

            if ($outgoing) {
                $outgoing->update($validatedData);
                $this->resetFields();
                $this->closeModal();
                $this->notificationType = 'success'; // Set success type
                $this->notificationMessage = 'Procurement record updated successfully!';
                $this->showNotification = true;
                $this->dispatch('refreshProcurementOutgoing');
            } else {
                $this->notificationType = 'error'; // Set error type
                $this->notificationMessage = 'Procurement record not found.';
                $this->showNotification = true;
            }
        } catch (ValidationException $e) {
            Log::error('Validation error updating outgoing record: ' . json_encode($e->errors()));
            $this->notificationType = 'error'; // Set error type
            $this->notificationMessage = 'Validation failed. Please check the form for errors.';
            $this->showNotification = true;
            throw $e; // Re-throw to make Livewire display errors next to fields
        } catch (\Exception $e) {
            Log::error('Error updating procurement record: ' . $e->getMessage());
            $this->notificationType = 'error'; // Set error type
            $this->notificationMessage = 'Error updating procurement record: ' . $e->getMessage();
            $this->showNotification = true;
        }
    }

    public function openDeleteModal($outgoingId)
    {
        $this->deletingOutgoingId = $outgoingId;
        $this->isDeleteModalOpen = true;
        $this->dismissNotification(); // Clear and hide notification
    }

    public function deleteOutgoing()
    {
        try {
            $outgoing = ProcurementOutgoing::find($this->deletingOutgoingId);
            if ($outgoing) {
                $outgoing->delete();
                $this->closeModal();
                $this->notificationType = 'success'; // Set success type
                $this->notificationMessage = 'Procurement record deleted successfully!';
                $this->showNotification = true;
                $this->dispatch('refreshProcurementOutgoing'); // Dispatch to refresh table
            } else {
                $this->notificationType = 'error'; // Set error type
                $this->notificationMessage = 'Procurement record not found.';
                $this->showNotification = true;
            }
        } catch (\Exception $e) {
            Log::error('Error deleting procurement record: ' . $e->getMessage());
            $this->notificationType = 'error'; // Set error type
            $this->notificationMessage = 'Error deleting procurement record: ' . $e->getMessage();
            $this->showNotification = true;
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
                $searchLower = strtolower($this->search);
                $query->where(function ($query) use ($searchLower) {
                    $query->where('received_date', 'like', '%' . $searchLower . '%')
                        ->orWhereRaw('LOWER(end_user) LIKE ?', ['%' . $searchLower . '%'])
                        ->orWhereRaw('LOWER(pr_no) LIKE ?', ['%' . $searchLower . '%'])
                        ->orWhereRaw('LOWER(particulars) LIKE ?', ['%' . $searchLower . '%'])
                        ->orWhereRaw('LOWER(amount) LIKE ?', ['%' . $searchLower . '%'])
                        ->orWhereRaw('LOWER(creditor) LIKE ?', ['%' . $searchLower . '%'])
                        ->orWhereRaw('LOWER(remarks) LIKE ?', ['%' . $searchLower . '%'])
                        ->orWhereRaw('LOWER(responsibility) LIKE ?', ['%' . $searchLower . '%'])
                        ->orWhereRaw('LOWER(received_by) LIKE ?', ['%' . $searchLower . '%']);
                });
            })
            ->when($this->filterMonth, function ($query) {
                // Assuming filterMonth is 'YYYY-MM' format, or just 'MM'
                // For 'MM', you'd need the current year context if applicable
                $query->whereMonth('received_date', Carbon::parse($this->filterMonth)->month);
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
        try {
            // Get the filtered query
            $exportQuery = $this->buildOutgoingQuery();

            Log::info('Exporting ' . $exportQuery->count() . ' ProcurementOutgoing items to Excel.');

            // Use the OutgoingExport class with the filtered query
            return Excel::download(new OutgoingExport($exportQuery), 'procurement_outgoing.xlsx');
        } catch (\Exception $e) {
            Log::error('Error exporting items to Excel: ' . $e->getMessage());
            $this->notificationType = 'error';
            $this->notificationMessage = 'Failed to export to Excel: ' . $e->getMessage();
            $this->showNotification = true;
        }
    }

    // --- PDF EXPORT ---
    public function exportToPDF()
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('Error exporting items to PDF: ' . $e->getMessage());
            $this->notificationType = 'error';
            $this->notificationMessage = 'Failed to export to PDF: ' . $e->getMessage();
            $this->showNotification = true;
        }
    }
}