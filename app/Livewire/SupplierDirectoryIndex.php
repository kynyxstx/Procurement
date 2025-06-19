<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SupplierDirectory;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SuppliersExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\ValidationException; // IMPORTANT: Add this import

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

    // Notification properties
    public $no_changes; // This will hold the "no changes" message
    public $showNotification = false;
    public $notificationMessage = '';
    public $notificationType = 'success'; // Add this property: 'success' or 'error'

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

    // Property to store original data for comparison during edit
    protected $originalSupplierData = [];

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
            'mobile_no' => 'nullable|digits:11', // This rule is correct
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
        'mobile_no.digits' => 'Mobile number must be 11 digits.', // This message is crucial
        'email_address.email' => 'Enter a valid email address.',
    ];

    // Listeners for events (if you have them)
    protected $listeners = ['refreshSupplier' => '$refresh'];

    public function mount(): void
    {
        $this->resetPage();

        // Initialize search from query string if present
        if (request()->has('search')) {
            $this->search = request()->query('search');
        }
        // Initialize sort field and direction from query string if present
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
        $this->validateOnly($propertyName);
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
        $this->isAddModalOpen = false;
        $this->isEditModalOpen = false;
        $this->isDeleteModalOpen = false;
        $this->resetFields(); // Reset all form-related properties
        $this->resetValidation(); // Clear validation errors
        $this->no_changes = null; // Clear the "no changes" message
        $this->showNotification = false; // Hide success/error notifications
        $this->notificationMessage = ''; // Clear notification message
        $this->notificationType = 'success'; // Reset notification type to default
    }

    // Save supplier (Create or Update)
    public function saveSupplier()
    {
        if ($this->editSupplierId) {
            $this->updateSupplier();
        } else {
            $this->addSupplier();
        }
    }

    public function addSupplier()
    {
        try {
            $this->validate();

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

            $this->closeModal();
            $this->notificationType = 'success'; // Set notification type
            $this->notificationMessage = 'Supplier added successfully!';
            $this->showNotification = true;
            $this->dispatch('supplierAdded');
        } catch (ValidationException $e) { // Catch validation exceptions specifically
            $this->notificationType = 'error'; // Set notification type
            $this->notificationMessage = $e->validator->errors()->first('mobile_no') ?? 'Validation failed. Please check the form for errors.';
            $this->showNotification = true;
            \Log::error('Validation error adding Supplier: ' . json_encode($e->errors()));
            throw $e; // Re-throw to make Livewire display errors next to fields
        } catch (\Exception $e) { // Catch other general exceptions
            $this->notificationType = 'error'; // Set notification type
            $this->notificationMessage = 'Error adding Supplier: ' . $e->getMessage();
            $this->showNotification = true;
            \Log::error('Error adding Supplier: ' . $e->getMessage());
            $this->dispatch('supplierAddFailed');
        }
    }

    public function openAddModal()
    {
        $this->resetFields(); // Clear fields when opening add modal
        $this->resetValidation(); // Clear any previous validation errors
        $this->no_changes = null; // Clear "no changes" message
        $this->showNotification = false; // Ensure notification is hidden
        $this->notificationMessage = ''; // Clear notification message
        $this->notificationType = 'success'; // Reset notification type
        $this->isAddModalOpen = true;
    }

    public function openEditModal($supplierId)
    {
        $supplier = SupplierDirectory::find($supplierId);

        if ($supplier) {
            $this->editSupplierId = $supplierId;
            $this->supplier_name = $supplier->supplier_name;
            $this->address = $supplier->address;
            $this->items = $supplier->items;
            $this->contact_person = $supplier->contact_person;
            $this->position = $supplier->position;
            $this->mobile_no = $supplier->mobile_no;
            $this->telephone_no = $supplier->telephone_no;
            $this->email_address = $supplier->email_address;

            // Store original data for comparison
            $this->originalSupplierData = $supplier->toArray();

            $this->isEditModalOpen = true;
            $this->resetValidation();
            $this->no_changes = null;
            $this->showNotification = false; // Ensure notification is hidden
            $this->notificationMessage = ''; // Clear notification message
            $this->notificationType = 'success'; // Reset notification type
        } else {
            $this->notificationType = 'error'; // Set notification type
            $this->notificationMessage = 'Supplier not found.';
            $this->showNotification = true;
        }
    }

    public function updateSupplier()
    {
        try {
            $validatedData = $this->validate();

            $supplier = SupplierDirectory::find($this->editSupplierId);

            if (!$supplier) {
                $this->notificationType = 'error'; // Set notification type
                $this->notificationMessage = 'Supplier not found.';
                $this->showNotification = true;
                $this->dispatch('supplierUpdateFailed');
                return;
            }

            // Prepare current data from Livewire properties
            $currentData = [
                'supplier_name' => $this->supplier_name,
                'address' => $this->address,
                'items' => $this->items,
                'contact_person' => $this->contact_person,
                'position' => $this->position,
                'mobile_no' => $this->mobile_no,
                'telephone_no' => $this->telephone_no,
                'email_address' => $this->email_address,
            ];

            // Compare current data with original data
            $changesMade = false;
            foreach ($currentData as $key => $value) {
                // Important: Cast to string for comparison to handle null vs empty string consistently
                // and ensure all values are comparable types.
                $originalValue = (string) ($this->originalSupplierData[$key] ?? ''); // Use empty string if key doesn't exist
                $currentValue = (string) ($value ?? '');

                if ($originalValue !== $currentValue) {
                    $changesMade = true;
                    break;
                }
            }

            if (!$changesMade) {
                $this->no_changes = 'No changes were made to the supplier information.'; // This will be displayed if you have a specific element for it
                $this->notificationType = 'info'; // You might want an 'info' type for no changes
                $this->notificationMessage = 'No changes were made to the supplier information.';
                $this->showNotification = true;
                return; // Stop execution if no changes
            }

            // Only update if changes were detected
            $supplier->update($validatedData);

            $this->resetFields();
            $this->closeModal();
            $this->notificationType = 'success'; // Set notification type
            $this->notificationMessage = 'Supplier updated successfully!';
            $this->showNotification = true;
            $this->dispatch('supplierUpdated');
        } catch (ValidationException $e) { // Catch validation exceptions specifically
            $this->notificationType = 'error'; // Set notification type
            $this->notificationMessage = $e->validator->errors()->first('mobile_no') ?? 'Validation failed. Please check the form for errors.';
            $this->showNotification = true;
            \Log::error('Validation error updating Supplier: ' . json_encode($e->errors()));
            throw $e; // Re-throw to make Livewire display errors next to fields
        } catch (\Exception $e) { // Catch other general exceptions
            $this->notificationType = 'error'; // Set notification type
            $this->notificationMessage = 'Error updating Supplier: ' . $e->getMessage();
            $this->showNotification = true;
            \Log::error('Error updating Supplier: ' . $e->getMessage());
            $this->dispatch('supplierUpdateFailed');
        }
    }

    public function openDeleteModal($supplierId)
    {
        $this->deletingSupplierId = $supplierId;
        $this->isDeleteModalOpen = true;
    }

    public function deleteSupplier()
    {
        try {
            $supplier = SupplierDirectory::find($this->deletingSupplierId);

            if ($supplier) {
                $supplier->delete();
                $this->closeModal();
                $this->notificationType = 'success'; // Set notification type
                $this->notificationMessage = 'Supplier deleted successfully!';
                $this->showNotification = true;
                $this->dispatch('supplierDeleted');
            } else {
                $this->notificationType = 'error'; // Set notification type
                $this->notificationMessage = 'Supplier not found.';
                $this->showNotification = true;
                $this->dispatch('supplierDeleteFailed');
            }
        } catch (\Exception $e) {
            $this->notificationType = 'error'; // Set notification type
            $this->notificationMessage = 'Error deleting supplier: ' . $e->getMessage();
            $this->showNotification = true;
            \Log::error('Error deleting supplier: ' . $e->getMessage());
            $this->dispatch('supplierDeleteFailed');
        }
    }

    public function dismissNotification()
    {
        $this->showNotification = false;
        $this->notificationMessage = '';
        $this->notificationType = 'success'; // Reset notification type when dismissed
    }

    public function updatedSearch()
    {
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
        $query = SupplierDirectory::query(); // Start a fresh query instance
        $this->applyFilters($query);        // Apply your search/filters to this query

        // Pass the query builder instance directly to your export class
        return Excel::download(new SuppliersExport($query), 'suppliers directory.xlsx');
    }

    // For PDF
    public function exportToPDF()
    {
        $query = SupplierDirectory::query();
        $this->applyFilters($query); // Apply the same filters
        $suppliers = $query->get();  // Get the collection of filtered data

        $pdf = Pdf::loadView('exports.suppliers_pdf', ['suppliers' => $suppliers]);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'suppliers directory.pdf');
    }

    public function performSearch()
    {
        $this->resetPage();
    }

    private function resetFields()
    {
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