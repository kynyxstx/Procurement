<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SupplierDirectory;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SuppliersExport;
use Barryvdh\DomPDF\Facade\Pdf;

class SupplierDirectoryIndex extends Component
{
    use WithPagination;

    public $supplier_name = '';
    public $address = '';
    public $items = '';
    public $contact_person = '';
    public $position = '';
    public $mobile_no = '';
    public $telephone_no = '';
    public $email_address = '';

    public $search = '';
    public $filterSupplier = '';
    public $isEditModalOpen = false;
    public $editSupplierId;
    public $isDeleteModalOpen = false;
    public $deletingSupplierId;
    public $isAddModalOpen = false;

    public $showNotification = false;
    public $notificationMessage = '';

    protected $paginationTheme = 'tailwind';
    protected $perPage = 5;

    /**
     * The properties that should be included in the query string.
     *
     * @var array
     */

    protected $queryString = [
        'search',
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

    protected $listeners = ['refreshSupplier' => '$refresh'];

    public function mount(): void
    {
        $this->resetPage();

        // Check if 'search' exists in the query string and update the property
        if (request()->has('search')) {
            $this->search = request()->query('search');
        }
    }

    public function applyFilters($query)
    {
        \Log::info('applyFilters called');
        \Log::info('Search: ' . $this->search);
        \Log::info('Filter Supplier: ' . $this->filterSupplier);

        if ($this->search) {
            \Log::info('Search condition applied');
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
            \Log::info('filterSupplier condition applied');
            $supplierNames = explode(';', $this->filterSupplier);
            $query->where(function ($query) use ($supplierNames) {
                foreach ($supplierNames as $supplierName) {
                    $query->orWhere('supplier_name', 'like', '%' . trim($supplierName) . '%');
                }
            });
        }
        \Log::info(get_class($query)); // Check the class of $query
        return $query;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
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

    // Close modals
    public function closeModal()
    {
        $this->isAddModalOpen = false;
        $this->isEditModalOpen = false;
        $this->isDeleteModalOpen = false;
        $this->reset(['supplier_name', 'address', 'items', 'contact_person', 'position', 'mobile_no', 'telephone_no', 'email_address', 'editSupplierId', 'isEditModalOpen', 'isDeleteModalOpen']);
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
            $this->notificationMessage = 'Supplier added successfully!';
            $this->showNotification = true;
            $this->resetFields();
            $this->dispatch('supplierAdded');
        } catch (\Exception $e) {
            session()->flash('error', 'Error adding Supplier.');
            \Log::error('Error adding Supplier: ' . $e->getMessage());
            $this->dispatch('supplierAddFailed');
        }
    }
    public function openAddModal()
    {
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

            $this->isEditModalOpen = true;
        } else {
            session()->flash('error', 'Supplier not found.');
        }
    }

    public function updateSupplier()
    {
        try {
            $validatedData = $this->validate();

            $supplier = SupplierDirectory::find($this->editSupplierId);
            if ($supplier) {
                $supplier->update($validatedData);
                $this->resetFields();
                $this->closeModal();
                $this->notificationMessage = 'Supplier updated successfully!';
                $this->showNotification = true;
                $this->dispatch('supplierUpdated');
            } else {
                session()->flash('error', 'Supplier not found.');
                $this->dispatch('supplierUpdateFailed');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error updating Supplier.');
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
                $this->notificationMessage = 'Supplier deleted successfully!';
                $this->showNotification = true;
                $this->dispatch('supplierDeleted');
            } else {
                session()->flash('error', 'Supplier not found.');
                $this->dispatch('supplierDeleteFailed');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting supplier.');
            \Log::error('Error deleting supplier: ' . $e->getMessage());
            $this->dispatch('supplierDeleteFailed');
        }
    }

    public function dismissNotification()
    {
        $this->showNotification = false;
        $this->notificationMessage = '';
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

        // Filter by supplier name
        if ($this->filterSupplier) {
            $supplierNames = explode(';', $this->filterSupplier);
            $query->where(function ($query) use ($supplierNames) {
                foreach ($supplierNames as $supplierName) {
                    $query->orWhere('supplier_name', 'like', '%' . trim($supplierName) . '%');
                }
            });
        }

        $suppliers = $query->paginate(20);

        return view('livewire.supplier-directory-index', [
            'suppliers' => $suppliers,
        ]);
    }

    // For Excel
    public function exportToExcel()
    {
        $query = SupplierDirectory::query();
        $this->applyFilters($query); // Apply the same filters used for the table
        return Excel::download(new SuppliersExport($query->get()), 'suppliers.xlsx'); // Pass the collection
    }

    // For PDF
    public function exportToPDF()
    {
        $query = SupplierDirectory::query();
        $this->applyFilters($query); // Apply the same filters
        $suppliers = $query->get(); // Get the collection of filtered data

        $pdf = Pdf::loadView('exports.suppliers_pdf', ['suppliers' => $suppliers]); // Corrected view path
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'suppliers.pdf');
    }

    public function performSearch()
    {
        $this->resetPage(); // Reset pagination when searching
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
    }
}