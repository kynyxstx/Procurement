<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SupplierDirectory;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SuppliersExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log; // Import the Log facade

class SupplierDirectoryIndex extends Component
{
    use WithPagination;

    // Public properties that are bound to the form fields
    public $supplier_name = '';
    public $address = '';
    public $items = '';
    public $contact_person = '';
    public $position = '';
    public $mobile_no = '';
    public $telephone_no = '';
    public $email_address = '';

    // Search and filter properties
    public $search = '';
    public $filterSupplier = '';

    // Modal state properties
    public $isEditModalOpen = false;
    public $editSupplierId;
    public $isDeleteModalOpen = false;
    public $deletingSupplierId;
    public $isAddModalOpen = false;

    // Notification properties (keep these two central)
    public $showNotification = false;
    public $notificationMessage = '';
    public $notificationType = 'success'; // 'success', 'error', 'info'

    // Sorting properties
    public $sortFields = [
        'supplier_name',
        'address',
        'items',
        'contact_person',
        'position',
        'mobile_no',
        'telephone_no',
        'email_address',
    ];
    public $sortDirection = 'asc';
    public $sortField = 'supplier_name';

    public $originalSupplierData = [];

    protected $paginationTheme = 'tailwind';
    protected $perPage = 20;

    protected $queryString = [
        'search',
        'sortField',
        'sortDirection',
    ];

    public function rules()
    {
        return [
            'supplier_name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'items' => 'required|string|max:500',
            'contact_person' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'mobile_no' => 'nullable|digits:11',
            'telephone_no' => 'nullable|string|max:15',
            'email_address' => 'nullable|email',
        ];
    }

    protected $messages = [
        'supplier_name.required' => 'Supplier name is required.',
        'address.required' => 'Address is required.',
        'items.required' => 'Please specify the items.',
        'contact_person.required' => 'Contact person is required.',
        'position.required' => 'Position is required.',
        'mobile_no.digits' => 'Mobile number must be 11 digits.',
        'email_address.email' => 'Enter a valid email address.',
    ];

    protected $listeners = ['refreshSupplier' => '$refresh'];

    public function mount(): void
    {
        $this->resetPage();

        if (request()->has('search')) {
            $this->search = request()->query('search');
        }
        if (request()->has('sortField') && in_array(request()->query('sortField'), $this->sortFields)) {
            $this->sortField = request()->query('sortField');
        }
        if (request()->has('sortDirection') && in_array(request()->query('sortDirection'), ['asc', 'desc'])) {
            $this->sortDirection = request()->query('sortDirection');
        }
    }

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

    public function updated($propertyName)
    {
        // Only validate if a validation rule exists for the property
        if (array_key_exists($propertyName, $this->rules())) {
            $this->validateOnly($propertyName);
        }
        if ($propertyName === 'search') {
            $this->resetPage();
        }
    }

    public function applyFilters($query)
    {
        if ($this->search) {
            $query->where(function ($query) {
                $query->where('supplier_name', 'like', '%' . $this->search . '%')
                    ->orWhere('address', 'like', '%' . $this->search . '%')
                    ->orWhere('items', 'like', '%' . $this->search . '%')
                    ->orWhere('contact_person', 'like', '%' . $this->search . '%')
                    ->orWhere('position', 'like', '%' . $this->search . '%')
                    ->orWhere('mobile_no', 'like', '%' . $this->search . '%')
                    ->orWhere('telephone_no', 'like', '%' . $this->search . '%')
                    ->orWhere('email_address', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterSupplier) {
            $supplierNames = explode(';', $this->filterSupplier);
            $query->where(function ($query) use ($supplierNames) {
                foreach ($supplierNames as $supplierName) {
                    $query->orWhere('supplier_name', 'like', '%' . trim($supplierName) . '%');
                }
            });
        }
        return $query;
    }

    // Close modals and reset form fields
    public function closeModal()
    {
        Log::info('closeModal method called.');
        $this->isAddModalOpen = false;
        $this->isEditModalOpen = false;
        $this->isDeleteModalOpen = false;
        $this->resetFields(); // This calls the reset that clears originalSupplierData
        $this->resetValidation();
        // Crucially, reset notification state here
        $this->showNotification = false;
        $this->notificationMessage = '';
        $this->notificationType = 'success';
    }

    // Save supplier (Create or Update)
    public function saveSupplier()
    {
        Log::info('saveSupplier method called. editSupplierId: ' . ($this->editSupplierId ?? 'null'));
        if ($this->editSupplierId) {
            $this->updateSupplier();
        } else {
            $this->addSupplier();
        }
    }

    public function addSupplier()
    {
        Log::info('addSupplier method called. Attempting to add new supplier.');
        // Reset notification state at the beginning of the action
        $this->showNotification = false;
        $this->notificationMessage = '';
        $this->notificationType = 'success';

        try {
            $this->validate();
            Log::info('addSupplier: Validation successful.');

            SupplierDirectory::create([
                'supplier_name' => $this->supplier_name,
                'address' => $this->address,
                'items' => $this->items,
                'contact_person' => $this->contact_person,
                'position' => $this->position,
                'mobile_no' => $this->mobile_no,
                'telephone_no' => $this->telephone_no,
                'email_address' => $this->email_address,
            ]);

            Log::info('addSupplier: Supplier created successfully.');

            $this->closeModal(); // This will also reset the notification state
            $this->notificationType = 'success';
            $this->notificationMessage = 'Supplier added successfully!';
            $this->showNotification = true;
            $this->dispatch('supplierAdded');
        } catch (ValidationException $e) {
            $this->notificationType = 'error';
            $this->notificationMessage = $e->validator->errors()->first() ?? 'Validation failed. Please check the form for errors.';
            $this->showNotification = true;
            Log::error('Validation error adding Supplier: ' . json_encode($e->errors()));
            // No need to throw $e if you're showing a notification, Livewire handles the validation errors.
        } catch (\Exception $e) {
            $this->notificationType = 'error';
            $this->notificationMessage = 'Error adding Supplier: ' . $e->getMessage();
            $this->showNotification = true;
            Log::error('Error adding Supplier: ' . $e->getMessage());
            $this->dispatch('supplierAddFailed');
        }
    }

    public function openAddModal()
    {
        Log::info('openAddModal method called. Setting isAddModalOpen to true.');
        $this->resetFields();
        $this->resetValidation();
        // Ensure notification is cleared when opening the modal
        $this->showNotification = false;
        $this->notificationMessage = '';
        $this->notificationType = 'success';
        $this->isAddModalOpen = true;
    }

    public function openEditModal($supplierId)
    {
        Log::info('openEditModal method called for supplier ID: ' . $supplierId);
        $supplier = SupplierDirectory::find($supplierId);

        if ($supplier) {
            $this->editSupplierId = $supplierId;

            // Assign values to public properties
            $this->supplier_name = $supplier->supplier_name;
            $this->address = $supplier->address;
            $this->items = $supplier->items;
            $this->contact_person = $supplier->contact_person;
            $this->position = $supplier->position;
            $this->mobile_no = $supplier->mobile_no;
            $this->telephone_no = $supplier->telephone_no;
            $this->email_address = $supplier->email_address;

            // Store original data from the *Livewire properties themselves*
            // Cast to string to ensure consistent comparison with input values later.
            $this->originalSupplierData = [
                'supplier_name' => (string) $this->supplier_name,
                'address' => (string) $this->address,
                'items' => (string) $this->items,
                'contact_person' => (string) $this->contact_person,
                'position' => (string) $this->position,
                'mobile_no' => (string) ($this->mobile_no ?? ''),
                'telephone_no' => (string) ($this->telephone_no ?? ''),
                'email_address' => (string) ($this->email_address ?? ''),
            ];

            Log::debug('Original Supplier Data stored (after property assignment): ', $this->originalSupplierData);

            $this->isEditModalOpen = true;
            $this->resetValidation();
            // Ensure notification is cleared when opening the modal
            $this->showNotification = false;
            $this->notificationMessage = '';
            $this->notificationType = 'success';
        } else {
            $this->notificationType = 'error';
            $this->notificationMessage = 'Supplier not found.';
            $this->showNotification = true;
            // No need to open modal if supplier not found
        }
    }

    public function updateSupplier()
    {
        Log::info('updateSupplier method called.');
        // Reset notification state at the beginning of the action
        $this->showNotification = false;
        $this->notificationMessage = '';
        $this->notificationType = 'success';

        try {
            $validatedData = $this->validate();

            $supplier = SupplierDirectory::find($this->editSupplierId);

            if (!$supplier) {
                $this->notificationType = 'error';
                $this->notificationMessage = 'Supplier not found.';
                $this->showNotification = true;
                $this->dispatch('supplierUpdateFailed');
                return;
            }

            Log::debug('Original Supplier Data in updateSupplier (before comparison): ', $this->originalSupplierData);

            // Prepare current data for comparison, ensuring nulls are handled consistently as empty strings
            $currentData = [
                'supplier_name' => (string) $this->supplier_name,
                'address' => (string) $this->address,
                'items' => (string) $this->items,
                'contact_person' => (string) $this->contact_person,
                'position' => (string) $this->position,
                'mobile_no' => (string) $this->mobile_no,
                'telephone_no' => (string) $this->telephone_no,
                'email_address' => (string) $this->email_address,
            ];

            $changesMade = false;
            foreach ($currentData as $key => $currentValue) {
                $originalValue = (string) ($this->originalSupplierData[$key] ?? ''); // Ensure it's a string, default to empty
                $currentValue = (string) ($currentValue ?? ''); // Ensure it's a string, default to empty

                Log::debug("Comparing {$key}: Original='{$originalValue}' | Current='{$currentValue}'");

                if ($originalValue !== $currentValue) {
                    Log::debug("Difference detected for {$key}: Original='{$originalValue}' vs Current='{$currentValue}'");
                    $changesMade = true;
                    break;
                }
            }

            if (!$changesMade) {
                // Only set one notification type here
                $this->notificationType = 'info';
                $this->notificationMessage = 'No changes were made to the supplier information.';
                $this->showNotification = true;
                return; // Keep modal open and show notification
            }

            $supplier->update($validatedData);

            $this->resetFields();
            $this->closeModal(); // This will also reset the notification state
            $this->notificationType = 'success';
            $this->notificationMessage = 'Supplier updated successfully!';
            $this->showNotification = true;
            $this->dispatch('supplierUpdated');
        } catch (ValidationException $e) {
            $this->notificationType = 'error';
            // Get the first validation error message to display
            $this->notificationMessage = $e->validator->errors()->first() ?? 'Validation failed. Please check the form for errors.';
            $this->showNotification = true;
            Log::error('Validation error updating Supplier: ' . json_encode($e->errors()));
            // No need to throw $e if you're showing a notification, Livewire handles the validation errors.
        } catch (\Exception $e) {
            $this->notificationType = 'error';
            $this->notificationMessage = 'Error updating Supplier: ' . $e->getMessage();
            $this->showNotification = true;
            Log::error('Error updating Supplier: ' . $e->getMessage());
            $this->dispatch('supplierUpdateFailed');
        }
    }

    public function openDeleteModal($supplierId)
    {
        Log::info('openDeleteModal method called for supplier ID: ' . $supplierId);
        $this->deletingSupplierId = $supplierId;
        $this->isDeleteModalOpen = true;
        // Ensure notification is cleared when opening the modal
        $this->showNotification = false;
        $this->notificationMessage = '';
        $this->notificationType = 'success';
    }

    public function deleteSupplier()
    {
        Log::info('deleteSupplier method called for supplier ID: ' . $this->deletingSupplierId);
        // Reset notification state at the beginning of the action
        $this->showNotification = false;
        $this->notificationMessage = '';
        $this->notificationType = 'success';

        try {
            $supplier = SupplierDirectory::find($this->deletingSupplierId);

            if ($supplier) {
                $supplier->delete();
                $this->closeModal(); // This will also reset the notification state
                $this->notificationType = 'success';
                $this->notificationMessage = 'Supplier deleted successfully!';
                $this->showNotification = true;
                $this->dispatch('supplierDeleted');
            } else {
                $this->notificationType = 'error';
                $this->notificationMessage = 'Supplier not found.';
                $this->showNotification = true;
                $this->dispatch('supplierDeleteFailed');
            }
        } catch (\Exception $e) {
            $this->notificationType = 'error';
            $this->notificationMessage = 'Error deleting supplier: ' . $e->getMessage();
            $this->showNotification = true;
            Log::error('Error deleting supplier: ' . $e->getMessage());
            $this->dispatch('supplierDeleteFailed');
        }
    }

    public function dismissNotification()
    {
        Log::info('dismissNotification method called.');
        $this->showNotification = false;
        $this->notificationMessage = '';
        $this->notificationType = 'success';
    }

    public function updatedSearch()
    {
        Log::info('updatedSearch method called. Search term: ' . $this->search);
        $this->resetPage();
    }

    public function render()
    {
        $query = SupplierDirectory::query();

        // Apply filters using the refactored method
        $this->applyFilters($query);

        // Apply sorting
        if (in_array($this->sortField, $this->sortFields)) {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        $suppliers = $query->paginate($this->perPage);

        return view('livewire.supplier-directory-index', [
            'suppliers' => $suppliers,
        ])->layout('layouts.app');
    }

    // For Excel
    public function exportToExcel()
    {
        Log::info('exportToExcel method called.');
        $query = SupplierDirectory::query(); // Start a fresh query instance
        $this->applyFilters($query);         // Apply your search/filters to this query

        // Pass the query builder instance directly to your export class
        return Excel::download(new SuppliersExport($query), 'suppliers directory.xlsx');
    }

    // For PDF
    public function exportToPDF()
    {
        Log::info('exportToPDF method called.');
        $query = SupplierDirectory::query();
        $this->applyFilters($query);
        $suppliers = $query->get();

        $pdf = Pdf::loadView('exports.suppliers_pdf', ['suppliers' => $suppliers]);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'suppliers directory.pdf');
    }

    public function performSearch()
    {
        Log::info('performSearch method called. Search term: ' . $this->search);
        $this->resetPage();
    }

    private function resetFields()
    {
        Log::info('resetFields method called.');
        $this->supplier_name = '';
        $this->address = '';
        $this->items = '';
        $this->contact_person = '';
        $this->position = '';
        $this->mobile_no = '';
        $this->telephone_no = '';
        $this->email_address = '';
        $this->editSupplierId = null;
        $this->originalSupplierData = [];
    }
}