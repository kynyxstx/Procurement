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
<div>
    <div>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                SUMMARY OF ITEMS PROCURED
            </h2>
        </x-slot>
    </div>
    <div class="mt-4 flex-wrap items-center">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="w-full flex justify-between items-center mb-4 pl-10">
                        <div class="flex items-center">
                            <select wire:model="filterItems" wire:change="performSearch"
                                class="w-full p-2 border rounded-md shadow-md mr-2">
                                <option value="">Year</option>
                                <option value="Venue;Meals;Accommodation">2024</option>
                                <option value="Services;Catering;Maintenance">2025</option>
                            </select>
                            <input type="text" wire:model.live="search" wire:model.live="performSearch"
                                placeholder="Search suppliers..."
                                class="w-full max-w-md p-2 border rounded-md shadow-md mr-2" />
                        </div>
                        <div class="flex flex-col items-start p-10">
                            <button wire:click="openAddModal"
                                class="px-5 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700">
                                Add Item
                            </button>
                        </div>
                    </div>
                    <!-- Items Table -->
                    <div class="p-10 w-full overflow-x-auto">
                        <h1 style="font-size: 2em;">Items Procured List</h1><br>

                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-600 border-collapse"
                            style="table-layout: fixed;">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
                                <tr>
                                    <th class="px-6 py-3">Supplier</th>
                                    <th class="px-6 py-3">Item / Project</th>
                                    <th class="px-6 py-3">Unit Cost</th>
                                    <th class="px-6 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr class="hover:bg-gray-100">
                                        <td class="py-2 px-4 border break-words">{{ $item->supplier }}</td>
                                        <td class="py-2 px-4 border break-words">{{ $item->item_project }}</td>
                                        <td class="py-2 px-4 border break-words">{{ $item->unit_cost }}</td>
                                        <td class="py-2 px-4 border text-center">
                                            <div class="flex justify-center space-x-2">
                                                <button wire:click="openEditModal({{ $item->id }})"
                                                    class="text-blue-600 hover:underline">Edit</button>
                                                <button wire:click="openDeleteModal({{ $item->id }})"
                                                    class="text-red-600 hover:underline">Delete</button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-4 flex-wrap items-center">
                            <div>
                                {{ $items->links() }}
                            </div>
                        </div>
                    </div>
                    <!-- Add Item Modal -->
                    @if ($isAddModalOpen)
                        <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50" wire:ignore>
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-lg w-full">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Add Item</h3>
                                    <button wire:click="closeModal"
                                        class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-400">
                                        &#x2715;
                                    </button>
                                </div>
                                <form wire:submit.prevent="saveItem">
                                    <div class="mb-2">
                                        <label for="supplier"
                                            class="block text-sm font-medium text-gray-900 dark:text-white">Supplier</label>
                                        <input wire:model="supplier" type="text" id="supplier"
                                            class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                            placeholder="Enter Supplier Name" required />
                                        @error('supplier')
                                            <p class="text-red-500 text-sm">{{ $errors->first('supplier') }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-2">
                                        <label for="item_project"
                                            class="block text-sm font-medium text-gray-900 dark:text-white">Item/Project</label>
                                        <input wire:model="item_project" type="text" id="item_project"
                                            class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                            placeholder="Enter Item/Project" required />
                                        @error('item_project')
                                            <p class="text-red-500 text-sm">{{ $errors->first('item_project') }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-2">
                                        <label for="unit_cost"
                                            class="block text-sm font-medium text-gray-900 dark:text-white">Unit
                                            Cost</label>
                                        <input wire:model="unit_cost" type="text" id="unit_cost"
                                            class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                            placeholder="Enter Unit Cost" required />
                                        @error('unit_cost')
                                            <p class="text-red-500 text-sm">{{ $errors->first('unit_cost') }}</p>
                                        @enderror
                                    </div>
                                    <div class="flex justify-end space-x-2">
                                        <button type="button" wire:click="closeModal"
                                            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">Cancel</button>
                                        <button type="submit"
                                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-400">
                                            Add Item
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    <!-- Edit Item Modal -->
                    @if ($isEditModalOpen)
                        <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50" wire:ignore>
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-lg w-full">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Edit Item</h3>
                                    <button wire:click="closeModal"
                                        class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-400">
                                        &#x2715;
                                    </button>
                                </div>
                                <form wire:submit.prevent="updateItem">
                                    <div class="mb-2">
                                        <label for="edit_supplier"
                                            class="block text-sm font-medium text-gray-900 dark:text-white">Supplier</label>
                                        <input wire:model="supplier" type="text" id="edit_supplier"
                                            class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                            placeholder="Enter Supplier Name" required />
                                        @error('supplier')
                                            <p class="text-red-500 text-sm">{{ $errors->first('supplier') }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-2">
                                        <label for="edit_item_project"
                                            class="block text-sm font-medium text-gray-900 dark:text-white">Item/Project</label>
                                        <input wire:model="item_project" type="text" id="edit_item_project"
                                            class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                            placeholder="Enter Item/Project" required />
                                        @error('item_project')
                                            <p class="text-red-500 text-sm">{{ $errors->first('item_project') }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-2">
                                        <label for="edit_unit_cost"
                                            class="block text-sm font-medium text-gray-900 dark:text-white">Unit
                                            Cost</label>
                                        <input wire:model="unit_cost" type="text" id="edit_unit_cost"
                                            class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                            placeholder="Enter Unit Cost" required />
                                        @error('unit_cost')
                                            <p class="text-red-500 text-sm">{{ $errors->first('unit_cost') }}</p>
                                        @enderror
                                    </div>
                                    <div class="flex justify-end space-x-2">
                                        <button type="button" wire:click="closeModal"
                                            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">Cancel</button>
                                        <button type="submit"
                                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-400">
                                            Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    <!-- Delete Confirmation Modal -->
                    @if ($isDeleteModalOpen)
                        <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50" wire:ignore>
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-lg w-full text-center">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Confirm Deletion</h3>
                                <p class="mt-2 text-gray-600 dark:text-gray-300">Are you sure you want to delete this item?
                                </p>
                                <div class="mt-6 flex justify-center space-x-4">
                                    <button wire:click="closeModal"
                                        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">Cancel</button>
                                    <button wire:click="deleteItem"
                                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Delete</button>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (session()->has('message'))
                        <div class="fixed top-4 right-4 bg-green-200 border border-green-400 text-green-700 px-4 py-3 rounded relative z-50"
                            role="alert">
                            <strong class="font-bold">Success!</strong>
                            <span class="block sm:inline">{{ session('message') }}</span>
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="fixed top-4 right-4 bg-red-200 border border-red-400 text-red-700 px-4 py-3 rounded relative z-50"
                            role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

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