<div>
    @if ($showNotification)
        <div class="fixed top-4 right-4 z-50 p-4 rounded-md shadow-lg
                                                                                                                                        @if($notificationType === 'success') bg-green-500 text-white
                                                                                                                                        @elseif($notificationType === 'error') bg-red-500 text-white
                                                                                                                                        @else bg-blue-500 text-white @endif"
            x-data=" { open: @entangle('showNotification') }" x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-2" @click.away="open = false"
            x-init="setTimeout(() => { open = false; }, 5000);">
            <div class="flex items-center justify-between">
                <span>{{ $notificationMessage }}</span>
                <button @click="open = false" class="ml-4 text-white hover:text-gray-200 focus:outline-none">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
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
                    <div class="pl-4 pr-4 sm:pl-10 sm:pr-10 mb-4">
                        <div
                            class="flex flex-col md:flex-row md:items-center md:space-x-4 space-y-4 md:space-y-0 mt-10 md:mt-20">
                            <div
                                class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 space-y-4 sm:space-y-0 w-full">
                                <select wire:model="filterDays" wire:change="performSearch"
                                    class="p-2 border rounded-md shadow-md w-full sm:w-auto min-w-[180px]">
                                    <option value="">Endorsement Days</option>
                                    <option value="within_3_days">Within 3 days</option>
                                    <option value="3_to_8_days">3 to 8 days</option>
                                    <option value="more_than_8_days">More than 8 days</option>
                                </select>
                                <select wire:model="filterProcessor" wire:change="performSearch"
                                    class="p-2 border rounded-md shadow-md w-full sm:w-auto min-w-[200px]">
                                    <option value="">All Processor</option>
                                    <option value="Bernadette De Castro">Bernadette</option>
                                    <option value="Chester Aranda">Chester</option>
                                    <option value="Darryl Ivan Bernardo">DADA</option>
                                    <option value="Jeremiah Canlas">Jeremy</option>
                                    <option value="Joshua Mhir Aviñante">JM</option>
                                    <option value="Ma. Christina Millan">MC</option>
                                    <option value="Norven Abejuela">Norven</option>
                                    <option value="Marycar Masilang">YCAR</option>
                                    <option value="Rheymart Bangcoyo">Rheymart</option>
                                    <option value="Ryne Christian Cruz">Ryne</option>
                                </select>
                                <input type="text" wire:model.live="search" placeholder="Search suppliers..."
                                    class="p-2 border rounded-md shadow-md min-w-[180px] sm:min-w-[300px]" />
                                <button wire:click="exportToExcel"
                                    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring focus:border-blue-300 w-full sm:w-auto">
                                    Export to Excel
                                </button>
                                <button wire:click="openAddModal"
                                    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring focus:border-blue-300 w-full sm:w-auto">
                                    Add Monitoring
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="p-10 w-full">
                        <h1 style="font-size: 2em;">Procurement Monitoring List</h1><br>
                        <div class="overflow-x-auto">
                            <table
                                class="min-w-[900px] w-full text-sm text-left text-gray-500 dark:text-gray-600 border-collapse"
                                style="table-layout: auto;">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
                                    <tr>
                                        <th class="px-6 py-3 text-lg font-semibold">Actions</th>
                                        <th class="px-6 py-3 cursor-pointer whitespace-nowrap"
                                            wire:click="sortBy('pr_no')">
                                            <span class="text-lg font-semibold">PR No.</span>
                                            @if($sortField === 'pr_no')
                                                @if($sortDirection === 'asc')
                                                    &uarr;
                                                @else
                                                    &darr;
                                                @endif
                                            @endif
                                        </th>
                                        <th class="px-6 py-3 cursor-pointer whitespace-nowrap"
                                            wire:click="sortBy('title')">
                                            <span class="text-lg font-semibold">Title</span>
                                            @if($sortField === 'title')
                                                @if($sortDirection === 'asc')
                                                    &uarr;
                                                @else
                                                    &darr;
                                                @endif
                                            @endif
                                        </th>
                                        <th class="px-6 py-3 cursor-pointer whitespace-nowrap"
                                            wire:click="sortBy('processor')">
                                            <span class="text-lg font-semibold">Processor</span>
                                            @if($sortField === 'processor')
                                                @if($sortDirection === 'asc')
                                                    &uarr;
                                                @else
                                                    &darr;
                                                @endif
                                            @endif
                                        </th>
                                        <th class="px-6 py-3 cursor-pointer whitespace-nowrap"
                                            wire:click="sortBy('supplier')">
                                            <span class="text-lg font-semibold">Supplier</span>
                                            @if($sortField === 'supplier')
                                                @if($sortDirection === 'asc')
                                                    &uarr;
                                                @else
                                                    &darr;
                                                @endif
                                            @endif
                                        </th>
                                        <th class="px-6 py-3 cursor-pointer whitespace-nowrap"
                                            wire:click="sortBy('end_user')">
                                            <span class="text-lg font-semibold">End User</span>
                                            @if($sortField === 'end_user')
                                                @if($sortDirection === 'asc')
                                                    &uarr;
                                                @else
                                                    &darr;
                                                @endif
                                            @endif
                                        </th>
                                        <th class="px-6 py-3 cursor-pointer whitespace-nowrap"
                                            wire:click="sortBy('status')">
                                            <span class="text-lg font-semibold">Status</span>
                                            @if($sortField === 'status')
                                                @if($sortDirection === 'asc')
                                                    &uarr;
                                                @else
                                                    &darr;
                                                @endif
                                            @endif
                                        </th>
                                        <th class="px-6 py-3 cursor-pointer whitespace-nowrap"
                                            wire:click="sortBy('date_endorsement')">
                                            <span class="text-lg font-semibold">Date of Endorsement</span>
                                            @if($sortField === 'date_endorsement')
                                                @if($sortDirection === 'asc')
                                                    &uarr;
                                                @else
                                                    &darr;
                                                @endif
                                            @endif
                                        </th>
                                        <th class="px-6 py-3 cursor-pointer whitespace-nowrap"
                                            wire:click="sortBy('specific_notes')">
                                            <span class="text-lg font-semibold">Specific Notes</span>
                                            @if($sortField === 'specific_notes')
                                                @if($sortDirection === 'asc')
                                                    &uarr;
                                                @else
                                                    &darr;
                                                @endif
                                            @endif
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($monitorings->isNotEmpty())
                                        @foreach ($monitorings as $monitoring)
                                            <tr class="hover:bg-gray-100 text-base">
                                                <td
                                                    class="py-2 px-4 border text-left align-top whitespace-nowrap break-words text-base">
                                                    <div class="flex justify-center space-x-2">
                                                        <button wire:click="openEditModal({{ $monitoring->id }})"
                                                            class="text-blue-600 hover:underline">View/Edit</button>
                                                        <button wire:click="openDeleteModal({{ $monitoring->id }})"
                                                            class="text-red-600 hover:underline">Delete</button>
                                                    </div>
                                                </td>
                                                <td
                                                    class="py-2 px-4 border text-left align-top whitespace-nowrap break-words text-base">
                                                    {{ $monitoring->pr_no }}
                                                </td>
                                                <td
                                                    class="py-2 px-4 border text-left align-top whitespace-nowrap break-words text-base">
                                                    {{ $monitoring->title }}
                                                </td>
                                                <td
                                                    class="py-2 px-4 border text-left align-top whitespace-nowrap break-words text-base">
                                                    {{ $monitoring->processor }}
                                                </td>
                                                <td
                                                    class="py-2 px-4 border text-left align-top whitespace-nowrap break-words text-base">
                                                    {{ $monitoring->supplier }}
                                                </td>
                                                <td
                                                    class="py-2 px-4 border text-left align-top whitespace-nowrap break-words text-base">
                                                    {{ $monitoring->end_user }}
                                                </td>
                                                <td
                                                    class="py-2 px-4 border text-left align-top whitespace-nowrap break-words text-base">
                                                    {{ $monitoring->status }}
                                                </td>
                                                <td
                                                    class="py-2 px-4 border text-left align-top whitespace-nowrap break-words text-base">
                                                    {{ $monitoring->date_endorsement }}
                                                </td>
                                                <td
                                                    class="py-2 px-4 border text-left align-top whitespace-nowrap break-words text-base">
                                                    {{ $monitoring->specific_notes }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="8" class="py-4 text-center">No procurement monitoring data
                                                available.
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <style>
                            .max-w-7xl.mx-auto.sm\:px-6.lg\:px-8,
                            .bg-white.overflow-hidden.shadow-xl.sm\:rounded-lg,
                            .p-10.w-full {
                                overflow-x: visible !important;
                            }

                            body,
                            html {
                                overflow-x: hidden;
                            }
                        </style>
                        <div class="mt-4 flex-wrap items-center">
                            <div>
                                {{ $monitorings->links() }}
                            </div>
                        </div>
                    </div>

                    <style>
                        @media (max-width: 640px) {
                            table.min-w-\[700px\] {
                                min-width: 0 !important;
                            }

                            table th,
                            table td {
                                font-size: 0.85rem !important;
                                padding-left: 0.5rem !important;
                                padding-right: 0.5rem !important;
                            }

                            .sm\:px-6 {
                                padding-left: 0.5rem !important;
                                padding-right: 0.5rem !important;
                            }

                            .sm\:p-10 {
                                padding: 0.5rem !important;
                            }

                            .sm\:mt-20 {
                                margin-top: 1rem !important;
                            }

                            .sm\:pl-10,
                            .sm\:pr-10 {
                                padding-left: 1rem !important;
                                padding-right: 1rem !important;
                            }
                        }
                    </style>

                    <!-- Back to Top Button -->
                    <button id="backToTopBtn"
                        class="fixed bottom-24 right-8 z-50 bg-blue-600 text-white px-4 py-2 rounded-full shadow-lg hover:bg-blue-800 transition-opacity opacity-0 pointer-events-none"
                        style="transition: opacity 0.3s;" onclick="window.scrollTo({top: 0, behavior: 'smooth'});">
                        ↑
                    </button>
                    <script>
                        // Show/hide Back to Top button on scroll
                        window.addEventListener('scroll', function () {
                            const btn = document.getElementById('backToTopBtn');
                            if (window.scrollY > 200) {
                                btn.style.opacity = '1';
                                btn.style.pointerEvents = 'auto';
                            } else {
                                btn.style.opacity = '0';
                                btn.style.pointerEvents = 'none';
                            }
                        });
                    </script>

                    <!-- Tap to Down Button -->
                    <button id="tapToDownBtn"
                        class="fixed bottom-8 right-8 z-50 bg-blue-600 text-white px-4 py-2 rounded-full shadow-lg hover:bg-blue-800 transition-opacity opacity-0 pointer-events-none"
                        style="transition: opacity 0.3s;"
                        onclick="window.scrollTo({top: document.body.scrollHeight, behavior: 'smooth'});">
                        ↓
                    </button>
                    <script>
                        // Show/hide Tap to Down button on scroll (show when not at bottom)
                        window.addEventListener('scroll', function () {
                            const btn = document.getElementById('tapToDownBtn');
                            if (window.innerHeight + window.scrollY < document.body.offsetHeight - 200) {
                                btn.style.opacity = '1';
                                btn.style.pointerEvents = 'auto';
                            } else {
                                btn.style.opacity = '0';
                                btn.style.pointerEvents = 'none';
                            }
                        });
                    </script>

                    @if ($isAddModalOpen)
                        <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 p-2 sm:p-0"
                            wire:ignore>
                            <div
                                class="bg-white dark:bg-gray-800 p-2 sm:p-4 rounded-lg shadow-lg max-w-lg w-full mx-auto max-h-[95vh] overflow-y-auto">
                                <div class="flex justify-between items-center mb-2 sm:mb-4">
                                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-gray-100">
                                        Add Procurement
                                    </h3>
                                    <button wire:click="closeModal"
                                        class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-400 text-xl">
                                        &#x2715;
                                    </button>
                                </div>
                                <div>
                                    <form wire:submit.prevent="saveMonitoring">
                                        <div class="mb-2">
                                            <label for="pr_no"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">PR
                                                No.</label>
                                            <input wire:model="pr_no" type="text" id="pr_no"
                                                class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200 text-base sm:text-lg"
                                                placeholder="Enter PR No." required />
                                            @error('pr_no')
                                                <p class="text-red-500 text-sm">{{ $errors->first('pr_no') }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-2">
                                            <label for="title"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                                            <textarea wire:model="title" id="title"
                                                class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200 text-base sm:text-lg"
                                                placeholder="Enter Title" required></textarea>
                                            @error('title')
                                                <p class="text-red-500 text-sm">{{ $errors->first('title') }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-2">
                                            <label for="processor"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Processor</label>
                                            <input wire:model="processor" id="processor"
                                                class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200 text-base sm:text-lg"
                                                placeholder="Enter Processor" required />
                                            @error('processor')
                                                <p class="text-red-500 text-sm">{{ $errors->first('processor') }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-2">
                                            <label for="supplier"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Supplier</label>
                                            <input wire:model="supplier" type="text" id="supplier"
                                                class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200 text-base sm:text-lg"
                                                placeholder="Enter Supplier" />
                                            @error('supplier')
                                                <p class="text-red-500 text-sm">{{ $errors->first('supplier') }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-2">
                                            <label for="end_user"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">End
                                                User</label>
                                            <input wire:model="end_user" type="text" id="end_user"
                                                class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200 text-base sm:text-lg"
                                                placeholder="Enter End-User" />
                                            @error('end_user')
                                                <p class="text-red-500 text-sm">{{ $errors->first('end_user') }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-2">
                                            <label for="status"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                            <textarea wire:model="status" id="status"
                                                class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200 text-base sm:text-lg"
                                                placeholder="Enter Status"></textarea>
                                            @error('status')
                                                <p class="text-red-500 text-sm">{{ $errors->first('status') }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-2">
                                            <label for="date_endorsement"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date
                                                of Endorsement</label>
                                            <input wire:model.lazy="date_endorsement" type="date" id="date_endorsement"
                                                class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200 text-base sm:text-lg"
                                                min="1900-01-01" max="2099-12-31" pattern="\d{4}-\d{2}-\d{2}"
                                                oninput="if(this.value.length > 10) this.value = this.value.slice(0, 10);" />
                                            <button type="button" class="mt-1 text-xs text-blue-600 underline"
                                                wire:click="$set('date_endorsement', '')">
                                                Clear Date
                                            </button>
                                            @error('date_endorsement')
                                                <p class="text-red-500 text-sm">
                                                    {{ $errors->first('date_endorsement') }}
                                                </p>
                                            @enderror
                                        </div>
                                        <div class="mb-2">
                                            <label for="specific_notes"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Specific
                                                Notes</label>
                                            <textarea wire:model="specific_notes" id="specific_notes"
                                                class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200 text-base sm:text-lg"
                                                placeholder="Enter Specific Notes"></textarea>
                                            @error('specific_notes')
                                                <p class="text-red-500 text-sm">{{ $errors->first('specific_notes') }}</p>
                                            @enderror
                                        </div>
                                        <div
                                            class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-2 mt-4">
                                            <button type="button" wire:click="closeModal"
                                                class="px-6 py-3 text-white bg-gray-600 rounded-lg hover:bg-gray-700 w-full sm:w-auto text-base font-semibold">Cancel</button>
                                            <button type="submit"
                                                class="px-6 py-3 text-white bg-green-600 rounded-lg hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-400 w-full sm:w-auto text-base font-semibold">
                                                Add Procurement
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <style>
                            @media (max-width: 640px) {
                                .max-w-lg {
                                    max-width: 99vw !important;
                                }

                                .p-2,
                                .sm\:p-4 {
                                    padding: 0.5rem !important;
                                }

                                .text-base,
                                .sm\:text-lg {
                                    font-size: 1rem !important;
                                }

                                .text-lg,
                                .sm\:text-xl {
                                    font-size: 1.1rem !important;
                                }

                                input,
                                textarea {
                                    font-size: 1rem !important;
                                }

                                label {
                                    font-size: 0.95rem !important;
                                }

                                .rounded-lg {
                                    border-radius: 0.75rem !important;
                                }
                            }
                        </style>
                    @endif

                    @if ($isEditModalOpen)
                        <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 px-2 sm:px-0"
                            wire:ignore>
                            <div
                                class="bg-white dark:bg-gray-800 p-2 sm:p-4 rounded-lg shadow-lg max-w-lg w-full mx-auto max-h-[95vh] overflow-y-auto">
                                <div class="flex justify-between items-center mb-2 sm:mb-4">
                                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">
                                        Edit Procurement
                                    </h3>
                                    <button wire:click="closeModal"
                                        class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-400 text-xl">
                                        &#x2715;
                                    </button>
                                </div>
                                <div>
                                    <form wire:submit.prevent="updateMonitoring">
                                        <div class="mb-2">
                                            <label for="pr_no"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">PR
                                                No.</label>
                                            <input wire:model="pr_no" type="text" id="pr_no"
                                                class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-base sm:text-lg"
                                                placeholder="Enter PR No." required />
                                            @error('pr_no')
                                                <p class="text-red-500 text-sm">{{ $errors->first('pr_no') }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-2">
                                            <label for="title"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                                            <textarea wire:model="title" id="title"
                                                class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-base sm:text-lg"
                                                placeholder="Enter Title" required></textarea>
                                            @error('title')
                                                <p class="text-red-500 text-sm">{{ $errors->first('title') }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-2">
                                            <label for="processor"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Processor</label>
                                            <input wire:model="processor" type="text" id="processor"
                                                class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-base sm:text-lg"
                                                placeholder="Enter Processor" required />
                                            @error('processor')
                                                <p class="text-red-500 text-sm">{{ $errors->first('processor') }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-2">
                                            <label for="supplier"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Supplier</label>
                                            <input wire:model="supplier" type="text" id="supplier"
                                                class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-base sm:text-lg"
                                                placeholder="Enter Supplier" />
                                            @error('supplier')
                                                <p class="text-red-500 text-sm">{{ $errors->first('supplier') }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-2">
                                            <label for="end_user"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">End_User</label>
                                            <input wire:model="end_user" type="text" id="end_user"
                                                class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-base sm:text-lg"
                                                placeholder="Enter End_User" />
                                            @error('end_user')
                                                <p class="text-red-500 text-sm">{{ $errors->first('end_user') }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-2">
                                            <label for="status"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                            <textarea wire:model="status" id="status"
                                                class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-base sm:text-lg"
                                                placeholder="Enter Status"></textarea>
                                            @error('status')
                                                <p class="text-red-500 text-sm">{{ $errors->first('status') }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-2">
                                            <label for="date_endorsement"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date
                                                of Endorsement</label>
                                            <input wire:model="date_endorsement" type="date" id="date_endorsement"
                                                class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-base sm:text-lg"
                                                min="1900-01-01" max="2099-12-31" pattern="\d{4}-\d{2}-\d{2}"
                                                oninput="if(this.value.length > 10) this.value = this.value.slice(0, 10);" />
                                            <button type="button" class="mt-1 text-xs text-blue-600 underline"
                                                wire:click="$set('date_endorsement', null)">
                                                Clear Date
                                            </button>
                                            @error('date_endorsement')
                                                <p class="text-red-500 text-sm">
                                                    {{ $errors->first('date_endorsement') }}
                                                </p>
                                            @enderror
                                        </div>
                                        <div class="mb-2">
                                            <label for="specific_notes"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Specific
                                                Notes</label>
                                            <textarea wire:model="specific_notes" id="specific_notes"
                                                class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-base sm:text-lg"
                                                placeholder="Enter Specific Notes"></textarea>
                                            @error('specific_notes')
                                                <p class="text-red-500 text-sm">{{ $errors->first('specific_notes') }}</p>
                                            @enderror
                                        </div>
                                        <div
                                            class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-2">
                                            <button type="button" wire:click="closeModal"
                                                class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 w-full sm:w-auto">Cancel</button>
                                            <button type="submit"
                                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-400 w-full sm:w-auto">
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
                            <div
                                class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-lg mx-4 sm:mx-0 text-center">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Confirm Deletion</h3>
                                <p class="mt-2 text-gray-600 dark:text-gray-300 text-sm sm:text-base">
                                    Are you sure you want to delete this Monitoring Procurement Data?
                                </p>
                                <div
                                    class="mt-6 flex flex-col sm:flex-row justify-center space-y-2 sm:space-y-0 sm:space-x-4">
                                    <button wire:click="closeModal"
                                        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 w-full sm:w-auto">Cancel</button>
                                    <button wire:click="deleteMonitoring"
                                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 w-full sm:w-auto">Delete</button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <footer class="mt-4 border-t pt-4 text-center text-gray-600 text-sm" style="min-height: 0.7in;">
            <span class="font-semibold">Procurement Management Section 2025</span>
        </footer>
    </div>
</div>