<div class="flex flex-col items-center p-10">
<h1 style="text-align: center; font-size: 2em;" class="mb-4">SUPPLIERS</h1>

    @if (session()->has('message'))
        <div class="flex justify-center">
            <div class="mt-4 px-6 py-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 dark:border-green-800 w-full max-w-md flex items-center"
                 role="alert">
                <svg class="flex-shrink-0 inline w-5 h-5 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                     fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                </svg>
                <span>{{ session('message') }}</span>
            </div>
        </div>
    @endif

    <div class="mb-2" style="width: 400px;">
        <label for="supplier_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Supplier Name</label>
        <input wire:model="supplier_name" type="text" id="supplier_name"
               class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200" placeholder="Enter Supplier Name"
               required/>
    </div>
    @error('supplier_name')
    <p class="text-red-500 text-sm">{{ $message }}</p>
    @enderror

    <div class="mb-2" style="width: 400px;">
        <label for="address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Address</label>
        <input wire:model="address" type="text" id="address"
               class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200" placeholder="Enter Address"
               required/>
    </div>
    @error('address')
    <p class="text-red-500 text-sm">{{ $message }}</p>
    @enderror

    <div class="mb-2" style="width: 400px;">
        <label for="items" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Items Supplied</label>
        <textarea wire:model="items" id="items" class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                  placeholder="Enter Items"></textarea>
    </div>
    @error('items')
    <p class="text-red-500 text-sm">{{ $message }}</p>
    @enderror

    <div class="mb-2" style="width: 400px;">
        <label for="contact_person" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contact
            Person</label>
        <input wire:model="contact_person" type="text" id="contact_person"
               class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200" placeholder="Enter Contact Person"
               required/>
    </div>
    @error('contact_person')
    <p class="text-red-500 text-sm">{{ $message }}</p>
    @enderror

    <div class="mb-2" style="width: 400px;">
        <label for="position" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Position</label>
        <input wire:model="position" type="text" id="position"
               class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200" placeholder="Enter Position"
               required/>
    </div>
    @error('position')
    <p class="text-red-500 text-sm">{{ $message }}</p>
    @enderror

    <div class="mb-2" style="width: 400px;">
        <label for="mobile_no" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mobile No.</label>
        <input wire:model="mobile_no" type="text" id="mobile_no"
               class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200" placeholder="Enter Mobile No."
               required/>
    </div>
    @error('mobile_no')
    <p class="text-red-500 text-sm">{{ $message }}</p>
    @enderror

    <div class="mb-2" style="width: 400px;">
        <label for="telephone_no" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Telephone
            No.</label>
        <input wire:model="telephone_no" type="text" id="telephone_no"
               class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
               placeholder="Enter Telephone No."/>
    </div>

    <div class="mb-2" style="width: 400px;">
        <label for="email_address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email
            Address</label>
        <input wire:model="email_address" type="email" id="email_address"
               class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200" placeholder="Enter Email Address"
               required/>
    </div>
    @error('email_address')
    <p class="text-red-500 text-sm">{{ $message }}</p>
    @enderror
    <br>
    <div class="mb-5">
        <button wire:click="saveSupplier" class="px-5 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700">
            {{ $editSupplierId ? 'Update Supplier' : 'Add Supplier' }}
        </button>
    </div>
    <br>
    <!-- Search Input -->
    <div class="flex flex-col items-center p-10">
    <br>
    <div class="flex items-center mb-4">
        <input type="text" wire:model="search" placeholder="Search suppliers..."
               class="w-full max-w-md p-2 border rounded-md shadow-md mr-2"/>
        <button wire:click="performSearch" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700">
            Search
        </button>
    </div>

           

    <!-- Supplier Table -->
    <div class="mt-8 p-10 w-full overflow-x-auto">
        <h1 style="text-align: center; font-size: 2em;">Supplier List</h1><br>
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-600 border-collapse"
               style="table-layout: fixed;">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
            <tr>
                <th class="px-6 py-3">Supplier Name</th>
                <th class="px-6 py-3">Address</th>
                <th class="px-6 py-3">Items</th>
                <th class="px-6 py-3">Contact Person</th>
                <th class="px-6 py-3">Position</th>
                <th class="px-6 py-3">Mobile No.</th>
                <th class="px-6 py-3">Telephone No.</th>
                <th class="px-6 py-3">Email Address</th>
                <th class="px-6 py-3">Actions</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($suppliers as $supplier)
            <tr class="hover:bg-gray-100">
    <td class="py-2 px-4 border">{{ $supplier->supplier_name }}</td>
    <td class="py-2 px-4 border">{{ $supplier->address }}</td>
    <td class="py-2 px-4 border break-words">{{ $supplier->items }}</td>
    <td class="py-2 px-4 border">{{ $supplier->contact_person }}</td>
    <td class="py-2 px-4 border">{{ $supplier->position }}</td>
    <td class="py-2 px-4 border">{{ $supplier->mobile_no }}</td>
    <td class="py-2 px-4 border">{{ $supplier->telephone_no }}</td>
    <td class="py-2 px-4 border break-words">{{ $supplier->email_address }}</td>
    <td class="py-2 px-4 border text-center">
        <div class="flex justify-center space-x-2">
            <button wire:click="openEditModal({{ $supplier->id }})"
                    class="text-blue-600 hover:underline">Edit
            </button>
            <button wire:click="openDeleteModal({{ $supplier->id }})"
                    class="text-red-600 hover:underline">Delete
            </button>
        </div>
    </td>
</tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4 flex flex-col items-center">
    <div>
        {{ $suppliers->links() }}
    </div>
</div>

    <div>
        @if ($isEditModalOpen)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-lg w-full">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                            Edit Supplier
                        </h3>
                        <button wire:click="closeModal" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-400">
                            &#x2715;
                        </button>
                    </div>

                    <div>
                        <form wire:submit.prevent="updateSupplier">
                            <div class="mb-4">
                                <label for="editSupplierName"
                                       class="block text-sm font-medium text-gray-700 dark:text-gray-300">Supplier Name</label>
                                <input wire:model="supplier_name" id="editSupplierName" type="text"
                                       placeholder="Enter Supplier Name"
                                       class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"/>
                                @error('supplier_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label for="editAddress"
                                       class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                                <input wire:model="address" id="editAddress" type="text" placeholder="Enter Address"
                                       class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"/>
                                @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label for="editItems"
                                       class="block text-sm font-medium text-gray-700 dark:text-gray-300">Items</label>
                                <textarea wire:model="items" id="editItems" placeholder="Enter Items"
                                          class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"></textarea>
                                @error('items') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label for="editContactPerson"
                                       class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contact Person</label>
                                <input wire:model="contact_person" id="editContactPerson" type="text"
                                       placeholder="Enter Contact Person"
                                       class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"/>
                                @error('contact_person') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label for="editPosition"
                                       class="block text-sm font-medium text-gray-700 dark:text-gray-300">Position</label>
                                <input wire:model="position" id="editPosition" type="text" placeholder="Enter Position"
                                       class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"/>
                                @error('position') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label for="editMobileNo"
                                       class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mobile No.</label>
                                <input wire:model="mobile_no" id="editMobileNo" type="text" placeholder="Enter Mobile No."
                                       class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"/>
                                @error('mobile_no') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label for="editTelephoneNo"
                                       class="block text-sm font-medium text-gray-700 dark:text-gray-300">Telephone No.</label>
                                <input wire:model="telephone_no" id="editTelephoneNo" type="text"
                                       placeholder="Enter Telephone No."
                                       class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"/>
                                @error('telephone_no') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label for="editEmailAddress"
                                       class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address</label>
                                <input wire:model="email_address" id="editEmailAddress" type="email"
                                       placeholder="Enter Email Address"
                                       class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"/>
                                @error('email_address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex justify-end space-x-2">
                                <button type="button" wire:click="closeModal"
                                        class="px-4 py-2 text-white bg-gray-600 rounded-lg hover:bg-gray-700">
                                    Cancel
                                </button>
                                <button type="submit"
                                        class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-400">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        @if ($isDeleteModalOpen)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
                <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full text-center">
                    <h3 class="text-xl font-semibold mb-4">Confirm Deletion</h3>
                    <p>Are you sure you want to delete this supplier? This action cannot be undone.</p>
                    <div class="mt-6 flex justify-center space-x-4">
                        <button wire:click="closeModal" class="px-4 py-2 bg-gray-600 text-white rounded-lg">Cancel</button>
                        <button wire:click="deleteSupplier" class="px-4 py-2 bg-red-600 text-white rounded-lg">Delete</button>
                    </div>
                </div>
            </div>
        @endif

        @if (session()->has('message'))
            <div class="fixed top-4 right-4 bg-green-200 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                 role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="fixed top-4 right-4 bg-red-200 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                 role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
    </div>
    <script>
        document.addEventListener('livewire:load', function () {
            window.livewire.on('supplierUpdated', () => {
                setTimeout(() => {
                    document.querySelectorAll('.bg-green-200').forEach(el => el.remove());
                }, 3000);
            });
            window.livewire.on('supplierDeleted', () => {
                setTimeout(() => {
                    document.querySelectorAll('.bg-green-200').forEach(el => el.remove());
                }, 3000);
            });
            window.livewire.on('supplierUpdateFailed', () => {
                setTimeout(() => {
                    document.querySelectorAll('.bg-red-200').forEach(el => el.remove());
                }, 3000);
            });
            window.livewire.on('supplierDeleteFailed', () => {
                setTimeout(() => {
                    document.querySelectorAll('.bg-red-200').forEach(el => el.remove());
                }, 3000);
            });
            window.livewire.on('supplierAdded', () => {
                setTimeout(() => {
                    document.querySelectorAll('.bg-green-200').forEach(el => el.remove());
                }, 3000);
            });
            window.livewire.on('supplierAddFailed', () => {
                setTimeout(() => {
                    document.querySelectorAll('.bg-red-200').forEach(el => el.remove());
                }, 3000);
            });
        });
    </script>
</div>
                                