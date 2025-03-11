<div class="flex flex-col items-center p-10">
    <h1 style="text-align: center; font-size: 2em;" class="mb-4">SUPPLIERS</h1>

    @if (session()->has('message'))
        <div class="flex justify-center">
            <div class="mt-4 px-6 py-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 dark:border-green-800 w-full max-w-md flex items-center"
                role="alert">
                <svg class="flex-shrink-0 inline w-5 h-5 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                </svg>
                <span>{{ session('message') }}</span>
            </div>
        </div>
    @endif

    <!-- Supplier Form -->
    <div class="mb-2" style="width: 400px;">
        <label for="supplier_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Supplier
            Name</label>
        <input wire:model="supplier_name" type="text" id="supplier_name"
            class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200" placeholder="Enter Supplier Name"
            required />
    </div>
    @error('supplier_name')    <p class="text-red-500 text-sm">{{ $message }}</p> @enderror

    <div class="mb-2" style="width: 400px;">
        <label for="address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Address</label>
        <input wire:model="address" type="text" id="address"
            class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200" placeholder="Enter Address"
            required />
    </div>
    @error('address')   <p class="text-red-500 text-sm">{{ $message }}</p> @enderror

    <div class="mb-2" style="width: 400px;">
        <label for="items" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Items Supplied</label>
        <textarea wire:model="items" id="items" class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
            placeholder="Enter Items"></textarea>
    </div>
    @error('items')  <p class="text-red-500 text-sm">{{ $message }}</p> @enderror

    <div class="mb-2" style="width: 400px;">
        <label for="contact_person" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contact
            Person</label>
        <input wire:model="contact_person" type="text" id="contact_person"
            class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200" placeholder="Enter Contact Person"
            required />
    </div>
    @error('contact_person')    <p class="text-red-500 text-sm">{{ $message }}</p> @enderror

    <div class="mb-2" style="width: 400px;">
        <label for="position" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Position</label>
        <input wire:model="position" type="text" id="position"
            class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200" placeholder="Enter Position"
            required />
    </div>
    @error('position')    <p class="text-red-500 text-sm">{{ $message }}</p> @enderror

    <div class="mb-2" style="width: 400px;">
        <label for="mobile_no" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mobile No.</label>
        <input wire:model="mobile_no" type="text" id="mobile_no"
            class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200" placeholder="Enter Mobile No."
            required />
    </div>
    @error('mobile_no')   <p class="text-red-500 text-sm">{{ $message }}</p> @enderror

    <div class="mb-2" style="width: 400px;">
        <label for="telephone_no" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Telephone
            No.</label>
        <input wire:model="telephone_no" type="text" id="telephone_no"
            class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
            placeholder="Enter Telephone No." />
    </div>

    <div class="mb-2" style="width: 400px;">
        <label for="email_address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email
            Address</label>
        <input wire:model="email_address" type="email" id="email_address"
            class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200" placeholder="Enter Email Address"
            required />
    </div>
    @error('email_address')    <p class="text-red-500 text-sm">{{ $message }}</p> @enderror

    <div class="mb-5">
        <button wire:click="addSupplier" class="px-5 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700">
            Add Supplier
        </button>
    </div>

    <!-- Supplier Table -->
    <div class="mt-8 p-10 w-full">
        <h1 style="text-align: center; font-size: 2em;">Supplier List</h1><br>
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-600">
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
        </table>
    </div>
</div>