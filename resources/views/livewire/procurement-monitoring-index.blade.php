<div>
    @if ($showNotification)
        <div class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-md z-50"
            role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ $notificationMessage }}</span>
            <div class="mt-2 flex justify-end">
                <button wire:click="dismissNotification"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    OK
                </button>
            </div>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-md z-50"
            role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
            <div class="mt-2 flex justify-end">
                <button onclick="this.parentNode.parentNode.remove();"
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    OK
                </button>
            </div>
        </div>
    @endif

    <div>
        <div>
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    PROCUREMENT MONITORING
                </h2>
            </x-slot>
        </div>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="pl-10 mb-4">
                        <div class="flex items-center space-x-2">
                        </div>
                        <div class="flex items-center space-x-4 mt-20">
                            <select wire:model="filterDays" wire:change="performSearch"
                                class="p-2 border rounded-md shadow-md" style="min-width: 180px;">
                                <option value="">Endorsement Days</option>
                                <option value="within_3_days">Within 3 days</option>
                                <option value="3_to_8_days">3 to 8 days</option>
                                <option value="more_than_8_days">More than 8 days</option>
                            </select>
                            <select wire:model="filterProcessor" wire:change="performSearch"
                                class="p-2 border rounded-md shadow-md" style="min-width: 200px;">
                                <option value="">All Processor</option>
                                <option value="Bernadette De Castro">Bernadette</option>
                                <option value="Chester Aranda">Chester</option>
                                <option value="Darryl Ivan Bernardo">DADA</option>
                                <option value="Jeremiah Canlas">Jeremiah</option>
                                <option value="Joshua Mhir Aviñante">JM</option>
                                <option value="Ma. Christina Millan">MC</option>
                                <option value="Marycar Masilang">YCAR</option>
                                <option value="Rheymart Bangcoyo">Rheymart</option>
                                <option value="Ryne Christian Cruz">Ryne</option>
                            </select>
                            <input type="text" wire:model.live="search" placeholder="Search monitoring..."
                                class="p-2 border rounded-md shadow-md mr-2" style="min-width: 250px;" />
                            <button wire:click="exportToExcel"
                                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700 ml-2">
                                Export to Excel
                            </button>
                            <button wire:click="exportToPDF"
                                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700 ml-2">
                                Export to PDF
                            </button>
                            <button wire:click="openAddModal"
                                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700 ml-2">
                                Add Monitoring
                            </button>
                        </div>
                    </div>
                    <div class="p-10 w-full overflow-x-auto">
                        <h1 style="font-size: 2em;">Procurement Monitoring List</h1><br>
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-600 border-collapse"
                            style="table-layout: fixed;">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
                                <tr>
                                    <th class="px-6 py-3 cursor-pointer" wire:click="sortBy('pr_no')">
                                        PR No.
                                        @if($sortField === 'pr_no')
                                            @if($sortDirection === 'asc')
                                                &uarr;
                                            @else
                                                &darr;
                                            @endif
                                        @endif
                                    </th>
                                    <th class="px-6 py-3 cursor-pointer" wire:click="sortBy('title')">
                                        Title
                                        @if($sortField === 'title')
                                            @if($sortDirection === 'asc')
                                                &uarr;
                                            @else
                                                &darr;
                                            @endif
                                        @endif
                                    </th>
                                    <th class="px-6 py-3 cursor-pointer" wire:click="sortBy('processor')">
                                        Processor
                                        @if($sortField === 'processor')
                                            @if($sortDirection === 'asc')
                                                &uarr;
                                            @else
                                                &darr;
                                            @endif
                                        @endif
                                    </th>
                                    <th class="px-6 py-3 cursor-pointer" wire:click="sortBy('supplier')">
                                        Supplier
                                        @if($sortField === 'supplier')
                                            @if($sortDirection === 'asc')
                                                &uarr;
                                            @else
                                                &darr;
                                            @endif
                                        @endif
                                    </th>
                                    <th class="px-6 py-3 cursor-pointer" wire:click="sortBy('end_user')">
                                        End User
                                        @if($sortField === 'end_user')
                                            @if($sortDirection === 'asc')
                                                &uarr;
                                            @else
                                                &darr;
                                            @endif
                                        @endif
                                    </th>
                                    <th class="px-6 py-3 cursor-pointer" wire:click="sortBy('status')">
                                        Status
                                        @if($sortField === 'status')
                                            @if($sortDirection === 'asc')
                                                &uarr;
                                            @else
                                                &darr;
                                            @endif
                                        @endif
                                    </th>
                                    <th class="px-6 py-3 cursor-pointer" wire:click="sortBy('date_endorsement')">
                                        Date of Endorsement
                                        @if($sortField === 'date_endorsement')
                                            @if($sortDirection === 'asc')
                                                &uarr;
                                            @else
                                                &darr;
                                            @endif
                                        @endif
                                    </th>
                                    <th class="px-6 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($monitorings->isNotEmpty())
                                    @foreach ($monitorings as $monitoring)
                                        <tr class="hover:bg-gray-100">
                                            <td class="py-2 px-4 border break-words">{{ $monitoring->pr_no }}</td>
                                            <td class="py-2 px-4 border break-words">{{ $monitoring->title }}</td>
                                            <td class="py-2 px-4 border break-words">{{ $monitoring->processor }}</td>
                                            <td class="py-2 px-4 border break-words">{{ $monitoring->supplier }}</td>
                                            <td class="py-2 px-4 border break-words">{{ $monitoring->end_user }}</td>
                                            <td class="py-2 px-4 border break-words">{{ $monitoring->status }}</td>
                                            <td class="py-2 px-4 border break-words">{{ $monitoring->date_endorsement }}</td>
                                            <td class="py-2 px-4 border text-center">
                                                <div class="flex justify-center space-x-2">
                                                    <button wire:click="openEditModal({{ $monitoring->id }})"
                                                        class="text-blue-600 hover:underline">Edit</button>
                                                    <button wire:click="openDeleteModal({{ $monitoring->id }})"
                                                        class="text-red-600 hover:underline">Delete</button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" class="py-4 text-center">No procurement monitoring data available.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <div class="mt-4 flex-wrap items-center">
                            <div>
                                {{ $monitorings->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($showNotification)
            <div class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-md z-50"
                role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ $notificationMessage }}</span>
                <div class="mt-2 flex justify-end">
                    <button wire:click="dismissNotification"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        OK
                    </button>
                </div>
            </div>
        @endif

        @if ($isAddModalOpen)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50" wire:ignore>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-lg w-full">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                            Add Procurement
                        </h3>
                        <button wire:click="closeModal" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-400">
                            &#x2715;
                        </button>
                    </div>
                    <div>
                        <form wire:submit.prevent="saveMonitoring">
                            <div class="mb-2">
                                <label for="pr_no" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">PR
                                    No.</label>
                                <input wire:model="pr_no" type="text" id="pr_no"
                                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                    placeholder="Enter PR No." required />
                                @error('pr_no')
                                    <p class="text-red-500 text-sm">{{ $errors->first('pr_no') }}</p>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="title"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Title</label>
                                <input wire:model="title" type="text" id="title"
                                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                    placeholder="Enter Title" required />
                                @error('title')
                                    <p class="text-red-500 text-sm">{{ $errors->first('title') }}</p>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="processor"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Processor</label>
                                <textarea wire:model="processor" id="processor"
                                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                    placeholder="Enter Processor" required></textarea>
                                @error('processor')
                                    <p class="text-red-500 text-sm">{{ $errors->first('processor') }}</p>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="supplier"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Supplier</label>
                                <input wire:model="supplier" type="text" id="supplier"
                                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                    placeholder="Enter Supplier" required />
                                @error('supplier')
                                    <p class="text-red-500 text-sm">{{ $errors->first('supplier') }}</p>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="end_user"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End_User</label>
                                <input wire:model="end_user" type="text" id="end_user"
                                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                    placeholder="Enter End_User" required />
                                @error('end_user')
                                    <p class="text-red-500 text-sm">{{ $errors->first('end_user') }}</p>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="status"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                                <input wire:model="status" type="text" id="status"
                                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                    placeholder="Enter Status" required />
                                @error('status')
                                    <p class="text-red-500 text-sm">{{ $errors->first('status') }}</p>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="date_endorsement"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date
                                    of
                                    Endorsement</label>
                                <input wire:model="date_endorsement" type="date" id="date_endorsement"
                                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200" required />
                                @error('date_endorsement')
                                    <p class="text-red-500 text-sm">{{ $errors->first('date_endorsement') }}</p>
                                @enderror
                            </div>
                            <div class="flex justify-end space-x-2">
                                <button type="button" wire:click="closeModal"
                                    class="px-4 py-2 text-white bg-gray-600 rounded-lg hover:bg-gray-700">Cancel</button>
                                <button type="submit"
                                    class="px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-400">
                                    Add Procurement
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        @if ($isEditModalOpen)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50" wire:ignore>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-lg w-full">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                            Edit Procurement
                        </h3>
                        <button wire:click="closeModal" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-400">
                            &#x2715;
                        </button>
                    </div>
                    <div>
                        <form wire:submit.prevent="updateMonitoring">
                            <div class="mb-2">
                                <label for="pr_no" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">PR
                                    No.</label>
                                <input wire:model="pr_no" type="text" id="pr_no"
                                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                    placeholder="Enter PR No." required />
                                @error('pr_no')
                                    <p class="text-red-500 text-sm">{{ $errors->first('pr_no') }}</p>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="title"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Title</label>
                                <input wire:model="title" type="text" id="title"
                                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                    placeholder="Enter Title" required />
                                @error('title')
                                    <p class="text-red-500 text-sm">{{ $errors->first('title') }}</p>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="processor"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Processor</label>
                                <input wire:model="processor" type="text" id="processor"
                                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                    placeholder="Enter Processor" required />
                                @error('processor')
                                    <p class="text-red-500 text-sm">{{ $errors->first('processor') }}</p>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="supplier"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Supplier</label>
                                <input wire:model="supplier" type="text" id="supplier"
                                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                    placeholder="Enter Supplier" required />
                                @error('supplier')
                                    <p class="text-red-500 text-sm">{{ $errors->first('supplier') }}</p>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="end_user"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End_User</label>
                                <input wire:model="end_user" type="text" id="end_user"
                                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                    placeholder="Enter End_User" required />
                                @error('end_user')
                                    <p class="text-red-500 text-sm">{{ $errors->first('end_user') }}</p>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="status"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                                <input wire:model="status" type="text" id="status"
                                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                    placeholder="Enter Status" required />
                                @error('status')
                                    <p class="text-red-500 text-sm">{{ $errors->first('status') }}</p>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="date_endorsement"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date
                                    of
                                    Endorsement</label>
                                <input wire:model="date_endorsement" type="date" id="date_endorsement"
                                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200" required />
                                @error('date_endorsement')
                                    <p class="text-red-500 text-sm">{{ $errors->first('date_endorsement') }}</p>
                                @enderror
                            </div>
                            <div class="flex justify-end space-x-2">
                                <button type="button" wire:click="closeModal"
                                    class="px-4 py-2 text-white bg-gray-600 rounded-lg hover:bg-gray-700">Cancel</button>
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
            <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50" wire:ignore>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-lg w-full text-center">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Confirm Deletion</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-300">Are you sure you want to delete this
                        Monitoring Procurement?
                    </p>
                    <div class="mt-6 flex justify-center space-x-4">
                        <button wire:click="closeModal"
                            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">Cancel</button>
                        <button wire:click="deleteMonitoring"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Delete</button>
                    </div>
                </div>
            </div>
        @endif