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
use Illuminate\Validation\ValidationException;

class ProcurementMonitoringIndex extends Component
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
    public $no_changes = null; // Add this property for the "no changes" message

    public $sortBy = 'received_date';
    public $sortDirection = 'asc';

    // *** Add this public property for storing original data ***
    public $originalOutgoingData = [];

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

    public function updated(string $propertyName)
    {
        // Only validate if a validation rule exists for the property
        if (array_key_exists($propertyName, $this->rules())) {
            $this->validateOnly($propertyName);
        }
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
        // Reset properties explicitly or use reset method
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
            'originalOutgoingData', // *** Ensure this is reset too ***
        ]);
        $this->resetValidation(); // Clear validation errors
        $this->no_changes = null; // Clear the no changes message
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
        // *** REMOVED: $this->resetFields(); ***
        $this->reset([ // Reset all relevant properties here, same as in closeModal
            'received_date',
            'end_user',
            'pr_no',
            'particulars',
            'amount',
            'creditor',
            'remarks',
            'responsibility',
            'received_by',
            'editOutgoingId', // Reset edit ID in case it was somehow set
            'deletingOutgoingId', // Reset delete ID
            'originalOutgoingData', // Reset original data
        ]);
        $this->resetValidation(); // Clear any previous validation errors
        $this->dismissNotification(); // Clear and hide notification
        $this->isAddModalOpen = true;
    }

    public function openEditModal($outgoingId)
    {
        $outgoing = ProcurementOutgoing::find($outgoingId);
        if ($outgoing) {
            $this->editOutgoingId = $outgoingId;

            // Assign values to public properties
            $this->received_date = $outgoing->received_date
                ? (Carbon::parse($outgoing->received_date))->format('Y-m-d\TH:i')
                : null; // Use null for empty date to correctly display in datetime-local input
            $this->end_user = $outgoing->end_user;
            $this->pr_no = $outgoing->pr_no;
            $this->particulars = $outgoing->particulars;
            // Format amount for display, but keep original if not numeric for string comparison
            $this->amount = is_numeric($outgoing->amount) ? number_format($outgoing->amount, 2, '.', '') : (string) $outgoing->amount;
            $this->creditor = $outgoing->creditor;
            $this->remarks = $outgoing->remarks;
            $this->responsibility = $outgoing->responsibility;
            $this->received_by = $outgoing->received_by;

            // *** Store original data for comparison ***
            $this->originalOutgoingData = [
                'received_date' => (string) ($outgoing->received_date ? Carbon::parse($outgoing->received_date)->format('Y-m-d H:i:s') : ''),
                'end_user' => (string) $outgoing->end_user,
                'pr_no' => (string) $outgoing->pr_no,
                'particulars' => (string) $outgoing->particulars,
                'amount' => (string) $outgoing->amount, // Store raw amount for accurate comparison with validated amount
                'creditor' => (string) $outgoing->creditor,
                'remarks' => (string) $outgoing->remarks,
                'responsibility' => (string) $outgoing->responsibility,
                'received_by' => (string) $outgoing->received_by,
            ];

            Log::debug('Original Outgoing Data stored: ', $this->originalOutgoingData);

            $this->resetValidation(); // Clear any previous validation errors
            $this->dismissNotification(); // Clear and hide notification
            $this->no_changes = null; // Clear any previous "no changes" message
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
            $outgoing = ProcurementOutgoing::find($this->editOutgoingId);

            if (!$outgoing) {
                $this->notificationType = 'error';
                $this->notificationMessage = 'Procurement record not found.';
                $this->showNotification = true;
                return;
            }

            // Prepare current data for comparison, ensuring nulls are handled consistently as empty strings
            // and amount has commas removed for comparison with original DB value
            $currentData = [
                'received_date' => (string) ($this->received_date ? Carbon::parse($this->received_date)->format('Y-m-d H:i:s') : ''),
                'end_user' => (string) $this->end_user,
                'pr_no' => (string) $this->pr_no,
                'particulars' => (string) $this->particulars,
                'amount' => (string) str_replace(',', '', $this->amount), // Remove commas for comparison
                'creditor' => (string) $this->creditor,
                'remarks' => (string) $this->remarks,
                'responsibility' => (string) $this->responsibility,
                'received_by' => (string) $this->received_by,
            ];

            Log::debug('Current Outgoing Data for comparison: ', $currentData);
            Log::debug('Original Outgoing Data for comparison: ', $this->originalOutgoingData);

            $changesMade = false;
            foreach ($currentData as $key => $currentValue) {
                $originalValue = (string) ($this->originalOutgoingData[$key] ?? '');
                $currentValue = (string) ($currentValue ?? '');

                // Special handling for date comparison:
                // If both are empty strings, they are considered same.
                // Otherwise, compare as strings.
                if ($key === 'received_date') {
                    if (empty($originalValue) && empty($currentValue)) {
                        // Both are empty, no change
                        continue;
                    }
                }

                Log::debug("Comparing {$key}: Original='{$originalValue}' | Current='{$currentValue}'");

                if ($originalValue !== $currentValue) {
                    Log::debug("Difference detected for {$key}: Original='{$originalValue}' vs Current='{$currentValue}'");
                    $changesMade = true;
                    break;
                }
            }

            if (!$changesMade) {
                $this->no_changes = 'No changes were made to the procurement record.';
                $this->notificationType = 'info';
                $this->notificationMessage = 'No changes were made to the procurement record.';
                $this->showNotification = true;
                return;
            }

            // Remove commas before final validation and saving
            $this->amount = str_replace(',', '', $this->amount);

            // Format received_date again just before validation, in case it was re-typed
            if (!empty($this->received_date)) {
                $this->received_date = Carbon::parse($this->received_date)->format('Y-m-d H:i:s');
            } else {
                $this->received_date = null;
            }

            $validatedData = $this->validate(); // Validate after cleaning amount and date

            $outgoing->update($validatedData);

            $this->closeModal();
            $this->notificationType = 'success'; // Set success type
            $this->notificationMessage = 'Procurement record updated successfully!';
            $this->showNotification = true;
            $this->dispatch('refreshProcurementOutgoing');
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

    // --- Helper method to build the base query with all filters ---
    private function buildOutgoingQuery()
    {
        $query = ProcurementOutgoing::query()
            ->when($this->search, function ($query) {
                $searchLower = strtolower($this->search);
                $query->where(function ($query) use ($searchLower) {
                    $query->where('received_date', 'like', '%' . $searchLower . '%') // Dates should be handled carefully with LIKE if not exact
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
                try {
                    $date = Carbon::parse($this->filterMonth);
                    $query->whereYear('received_date', $date->year)
                        ->whereMonth('received_date', $date->month);
                } catch (\Exception $e) {
                    Log::warning('Invalid filterMonth format: ' . $this->filterMonth);
                }
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