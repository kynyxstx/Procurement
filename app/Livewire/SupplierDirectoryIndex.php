<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SupplierDirectory;
use Livewire\WithPagination;

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
    public $isEditModalOpen = false;
    public $editSupplierId;
    public $isDeleteModalOpen = false;
    public $deletingSupplierId;


    protected $paginationTheme = 'tailwind';
    protected $perPage = 5;


    protected $rules = [
        'supplier_name' => 'required|string|max:255',
        'address' => 'required|string|max:500',
        'items' => 'required|string|max:500',
        'contact_person' => 'required|string|max:255',
        'position' => 'required|string|max:255',
        'mobile_no' => 'required|digits:11',
        'telephone_no' => 'nullable|string|max:15',
        'email_address' => 'required|email|unique:supplier_directories,email_address',
    ];

    protected $listeners = ['refreshSupplier' => '$refresh'];

    public function mount(): void
    {
        $this->resetPage();
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
        'mobile_no.required' => 'Mobile number is required.',
        'mobile_no.digits' => 'Mobile number must be 11 digits.',
        'email_address.required' => 'Email is required.',
        'email_address.email' => 'Enter a valid email address.',
        'email_address.unique' => 'This email is already in use.',
    ];

    // Close modals
    public function closeModal()
    {
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
            session()->flash('message', 'Supplier added successfully!');
            $this->resetFields();
        } catch (\Exception $e) {
            session()->flash('error', 'There was an error adding the supplier.');
            \Log::error('Error adding supplier: ' . $e->getMessage());
        }
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
                $this->closeModal();
                session()->flash('message', 'Supplier updated successfully!');
            } else {
                session()->flash('error', 'Supplier not found.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error updating Supplier.');
            \Log::error('Error updating Supplier: ' . $e->getMessage());
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
                session()->flash('message', 'Supplier deleted successfully!');
            } else {
                session()->flash('error', 'Supplier not found.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting supplier.');
            \Log::error('Error deleting supplier: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.supplier-directory-index', [
            'suppliers' => SupplierDirectory::where('supplier_name', 'like', '%' . $this->search . '%')
                ->orWhere('address', 'like', '%' . $this->search . '%')
                ->orWhere('items', 'like', '%' . $this->search . '%')
                ->orWhere('contact_person', 'like', '%' . $this->search . '%')
                ->orWhere('mobile_no', 'like', '%' . $this->search . '%')
                ->paginate(2),
        ]);
    }

}
