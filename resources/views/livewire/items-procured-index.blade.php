<div>
    @if ($showNotification)
        <div class="fixed top-4 right-4 z-50 p-4 rounded-md shadow-lg
            @if($notificationType === 'success') bg-green-500 text-white
            @elseif($notificationType === 'error') bg-red-500 text-white
            @else bg-blue-500 text-white @endif" x-data="{ open: @entangle('showNotification') }" x-show="open"
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
                    SUMMARY OF ITEMS PROCURED
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
                                <select wire:model.live="filterYear"
                                    class="p-2 border rounded-md shadow-sm min-w-[120px] w-full sm:w-auto">
                                    <option value="">All Year</option>
                                    <option value="2024" @if($filterYear === '2024') selected @endif>2024</option>
                                    <option value="2025" @if($filterYear === '2025') selected @endif>2025</option>
                                </select>
                                <select wire:model.live="filterMonth"
                                    class="p-2 border rounded-md shadow-sm min-w-[150px] w-full sm:w-auto">
                                    <option value="">All Month</option>
                                    <option value="January" @if($filterMonth === 'January') selected @endif>January
                                    </option>
                                    <option value="February" @if($filterMonth === 'February') selected @endif>February
                                    </option>
                                    <option value="March" @if($filterMonth === 'March') selected @endif>March</option>
                                    <option value="April" @if($filterMonth === 'April') selected @endif>April</option>
                                    <option value="May" @if($filterMonth === 'May') selected @endif>May</option>
                                    <option value="June" @if($filterMonth === 'June') selected @endif>June</option>
                                    <option value="July" @if($filterMonth === 'July') selected @endif>July</option>
                                    <option value="August" @if($filterMonth === 'August') selected @endif>August</option>
                                    <option value="September" @if($filterMonth === 'September') selected @endif>September
                                    </option>
                                    <option value="October" @if($filterMonth === 'October') selected @endif>October
                                    </option>
                                    <option value="November" @if($filterMonth === 'November') selected @endif>November
                                    </option>
                                    <option value="December" @if($filterMonth === 'December') selected @endif>December
                                    </option>
                                </select>
                                <input type="text" wire:model.live="search" placeholder="Search suppliers..."
                                    class="p-2 border rounded-md shadow-md min-w-[180px] sm:min-w-[300px]" />
                                <button wire:click="exportExcel"
                                    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring focus:border-blue-300 w-full sm:w-auto">
                                    Export to Excel
                                </button>
                                <button wire:click="openAddModal"
                                    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring focus:border-blue-300 w-full sm:w-auto">
                                    Add Item
                                </button>
                            </div>
                        </div>
                    </div>


                    <div class="p-10 w-full">
                        <h1 style="font-size: 2em;">Items Procured List</h1><br>
                        <div class="overflow-x-auto">
                            <table
                                class="min-w-[900px] w-full text-sm text-left text-gray-500 dark:text-gray-600 border-collapse"
                                style="table-layout: auto;">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
                                    <tr>
                                        <th class="px-6 py-3 text-lg font-semibold">Actions</th>
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
                                            wire:click="sortBy('item_project')">
                                            <span class="text-lg font-semibold">Item / Project</span>
                                            @if($sortField === 'item_project')
                                                @if($sortDirection === 'asc')
                                                    &uarr;
                                                @else
                                                    &darr;
                                                @endif
                                            @endif
                                        </th>
                                        <th class="px-6 py-3 cursor-pointer whitespace-nowrap"
                                            wire:click="sortBy('unit_cost')">
                                            <span class="text-lg font-semibold">Unit Cost</span>
                                            @if($sortField === 'unit_cost')
                                                @if($sortDirection === 'asc')
                                                    &uarr;
                                                @else
                                                    &darr;
                                                @endif
                                            @endif
                                        </th>
                                        <th class="px-6 py-3 cursor-pointer whitespace-nowrap"
                                            wire:click="sortBy('year')">
                                            <span class="text-lg font-semibold">Year</span>
                                            @if($sortField === 'year')
                                                @if($sortDirection === 'asc')
                                                    &uarr;
                                                @else
                                                    &darr;
                                                @endif
                                            @endif
                                        </th>
                                        <th class="px-6 py-3 cursor-pointer whitespace-nowrap"
                                            wire:click="sortBy('month')">
                                            <span class="text-lg font-semibold">Month</span>
                                            @if($sortField === 'month')
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
                                    @foreach ($items as $item)
                                        <tr class="hover:bg-gray-100 text-base">
                                            <td
                                                class="py-2 px-4 border text-left align-top whitespace-nowrap break-words text-base">
                                                <div class="flex justify-center space-x-2">
                                                    <button wire:click="openEditModal({{ $item->id }})"
                                                        class="text-blue-600 hover:underline">View/Edit
                                                    </button>
                                                    <button wire:click="openDeleteModal({{ $item->id }})"
                                                        class="text-red-600 hover:underline">Delete
                                                    </button>
                                                </div>
                                            </td>
                                            <td
                                                class="py-2 px-4 border text-left align-top whitespace-nowrap break-words text-base">
                                                {{ $item->supplier }}
                                            </td>
                                            <td
                                                class="py-2 px-4 border text-left align-top whitespace-nowrap break-words text-base">
                                                {{ $item->item_project }}
                                            </td>
                                            <td
                                                class="py-2 px-4 border text-left align-top whitespace-nowrap break-words text-base">
                                                @php
                                                    preg_match('/([\d,.]+)/', $item->unit_cost, $matches);
                                                    $number = isset($matches[1]) ? str_replace(',', '', $matches[1]) : null;
                                                    $formatted = $number !== null ? number_format((float) $number, 2) : '';
                                                    $display = $number !== null
                                                        ? preg_replace('/([\d,.]+)/', $formatted, $item->unit_cost, 1)
                                                        : $item->unit_cost;
                                                @endphp
                                                {{ $display }}
                                            </td>
                                            <td
                                                class="py-2 px-4 border text-left align-top whitespace-nowrap break-words text-base">
                                                {{ $item->year }}
                                            </td>
                                            <td
                                                class="py-2 px-4 border text-left align-top whitespace-nowrap break-words text-base">
                                                {{ $item->month }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if ($items->isEmpty())
                                        <tr>
                                            <td colspan="9" class="text-center py-4">No Item Procurement found.</td>
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
                                {{ $items->links() }}
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
                    <button id="backToTopBtn" class="fixed bottom-24 right-8 z-50 bg-blue-600 text-white px-4 py-2 rounded-full shadow-lg hover:bg-blue-800 transition-opacity opacity-0 pointer-events-none
                                        sm:bottom-24 sm:right-8
                                        bottom-16 right-4
                                        text-base sm:text-lg" style="transition: opacity 0.3s;"
                        onclick="window.scrollTo({top: 0, behavior: 'smooth'});">
                        ↑
                    </button>
                    <!-- Tap to Down Button -->
                    <button id="tapToDownBtn" class="fixed bottom-8 right-8 z-50 bg-blue-600 text-white px-4 py-2 rounded-full shadow-lg hover:bg-blue-800 transition-opacity opacity-0 pointer-events-none
                                        sm:bottom-8 sm:right-8
                                        bottom-4 right-4
                                        text-base sm:text-lg" style="transition: opacity 0.3s;"
                        onclick="window.scrollTo({top: document.body.scrollHeight, behavior: 'smooth'});">
                        ↓
                    </button>
                    <script>
                        // Responsive show/hide for Back to Top and Tap to Down buttons
                        function handleScrollBtns() {
                            const backBtn = document.getElementById('backToTopBtn');
                            const downBtn = document.getElementById('tapToDownBtn');
                            // Show Back to Top if scrolled down
                            if (window.scrollY > 200) {
                                backBtn.style.opacity = '1';
                                backBtn.style.pointerEvents = 'auto';
                            } else {
                                backBtn.style.opacity = '0';
                                backBtn.style.pointerEvents = 'none';
                            }
                            // Show Tap to Down if not at bottom
                            if (window.innerHeight + window.scrollY < document.body.offsetHeight - 200) {
                                downBtn.style.opacity = '1';
                                downBtn.style.pointerEvents = 'auto';
                            } else {
                                downBtn.style.opacity = '0';
                                downBtn.style.pointerEvents = 'none';
                            }
                        }
                        window.addEventListener('scroll', handleScrollBtns);
                        window.addEventListener('resize', handleScrollBtns);
                        document.addEventListener('DOMContentLoaded', handleScrollBtns);
                    </script>
                    <style>
                        @media (max-width: 640px) {

                            #backToTopBtn,
                            #tapToDownBtn {
                                right: 1rem !important;
                                padding: 0.5rem 0.75rem !important;
                                font-size: 1rem !important;
                            }

                            #backToTopBtn {
                                bottom: 4rem !important;
                            }

                            #tapToDownBtn {
                                bottom: 1rem !important;
                            }
                        }
                    </style>

                    @if ($isAddModalOpen)
                        <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 p-2 sm:p-0"
                            wire:ignore>
                            <div
                                class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-lg shadow-lg w-full max-w-lg mx-2 sm:mx-0">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-gray-100">Add
                                        Item
                                    </h3>
                                    <button wire:click="closeModal"
                                        class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-400">
                                        &#x2715;
                                    </button>
                                </div>
                                <form wire:submit.prevent="saveItem">
                                    <div class="mb-2">
                                        <label for="supplier"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Supplier</label>
                                        <input wire:model="supplier" type="text" id="supplier"
                                            class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-base sm:text-lg"
                                            placeholder="Enter Supplier Name" required />
                                        @error('supplier')
                                            <p class="text-red-500 text-sm">{{ $errors->first('supplier') }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-2">
                                        <label for="item_project"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Item/Project</label>
                                        <textarea wire:model="item_project" id="item_project"
                                            class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-base sm:text-lg"
                                            placeholder="Enter Item/Project" required></textarea>
                                        @error('item_project')
                                            <p class="text-red-500 text-sm">{{ $errors->first('item_project') }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-2">
                                        <label for="unit_cost"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Unit
                                            Cost</label>
                                        <input wire:model="unit_cost" type="text" id="unit_cost"
                                            class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-base sm:text-lg"
                                            placeholder="Enter Unit Cost" required />
                                        @error('unit_cost')
                                            <p class="text-red-500 text-sm">{{ $errors->first('unit_cost') }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-2">
                                        <label for="year"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Year</label>
                                        <select wire:model="year" id="year"
                                            class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-base sm:text-lg"
                                            required>
                                            <option value="">Select Year</option>
                                            <option value="2024">2024</option>
                                            <option value="2025">2025</option>
                                        </select>
                                        @error('year')
                                            <p class="text-red-500 text-sm">{{ $errors->first('year') }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-2">
                                        <label for="month"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Month</label>
                                        <select wire:model="month" id="month"
                                            class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-base sm:text-lg"
                                            required>
                                            <option value="">Select Month</option>
                                            <option value="January">January</option>
                                            <option value="February">February</option>
                                            <option value="March">March</option>
                                            <option value="April">April</option>
                                            <option value="May">May</option>
                                            <option value="June">June</option>
                                            <option value="July">July</option>
                                            <option value="August">August</option>
                                            <option value="September">September</option>
                                            <option value="October">October</option>
                                            <option value="November">November</option>
                                            <option value="December">December</option>
                                        </select>
                                        @error('month')
                                            <p class="text-red-500 text-sm">{{ $errors->first('month') }}</p>
                                        @enderror
                                    </div>

                                    <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-2">
                                        <button type="button" wire:click="closeModal"
                                            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 w-full sm:w-auto">Cancel</button>
                                        <button type="submit"
                                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-400 w-full sm:w-auto">
                                            Add Item
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if ($isEditModalOpen)
                        <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 px-2 sm:px-0"
                            wire:ignore>
                            <div
                                class="bg-white dark:bg-gray-800 p-2 sm:p-4 rounded-lg shadow-lg max-w-lg w-full mx-auto max-h-[95vh] overflow-y-auto">
                                <div class="flex justify-between items-center mb-2 sm:mb-4">
                                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">Edit
                                        Item
                                    </h3>
                                    <button wire:click="closeModal"
                                        class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-400 text-xl">
                                        &#x2715;
                                    </button>
                                </div>
                                <form wire:submit.prevent="updateItem">
                                    <div class="mb-2">
                                        <label for="edit_supplier"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Supplier</label>
                                        <input wire:model="supplier" type="text" id="edit_supplier"
                                            class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-base sm:text-lg"
                                            placeholder="Enter Supplier Name" required />
                                        @error('supplier')
                                            <p class="text-red-500 text-xs">{{ $errors->first('supplier') }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-2">
                                        <label for="edit_item_project"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Item/Project</label>
                                        <textarea wire:model="item_project" id="edit_item_project"
                                            class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-base sm:text-lg"
                                            placeholder="Enter Item/Project" required></textarea>
                                        @error('item_project')
                                            <p class="text-red-500 text-xs">{{ $errors->first('item_project') }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-2">
                                        <label for="edit_unit_cost"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Unit
                                            Cost</label>
                                        <input wire:model="unit_cost" type="text" id="edit_unit_cost"
                                            class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-base sm:text-lg"
                                            placeholder="Enter Unit Cost" required />
                                        @error('unit_cost')
                                            <p class="text-red-500 text-xs">{{ $errors->first('unit_cost') }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-2">
                                        <label for="edit_year"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Year</label>
                                        <select wire:model="year" id="edit_year"
                                            class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-base sm:text-lg"
                                            required>
                                            <option value="">Select Year</option>
                                            <option value="2024">2024</option>
                                            <option value="2025">2025</option>
                                        </select>
                                        @error('year')
                                            <p class="text-red-500 text-xs">{{ $errors->first('year') }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-2">
                                        <label for="edit_month"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Month</label>
                                        <select wire:model="month" id="edit_month"
                                            class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-base sm:text-lg"
                                            required>
                                            <option value="">Select Month</option>
                                            <option value="January">January</option>
                                            <option value="February">February</option>
                                            <option value="March">March</option>
                                            <option value="April">April</option>
                                            <option value="May">May</option>
                                            <option value="June">June</option>
                                            <option value="July">July</option>
                                            <option value="August">August</option>
                                            <option value="September">September</option>
                                            <option value="October">October</option>
                                            <option value="November">November</option>
                                            <option value="December">December</option>
                                        </select>
                                        @error('month')
                                            <p class="text-red-500 text-xs">{{ $errors->first('month') }}</p>
                                        @enderror
                                    </div>

                                    <div
                                        class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-2 mt-4">
                                        <button type="button" wire:click="closeModal"
                                            class="px-6 py-3 text-white bg-gray-600 rounded-lg hover:bg-gray-700 w-full sm:w-auto text-base font-semibold">Cancel</button>
                                        <button type="submit"
                                            class="px-6 py-3 text-white bg-blue-600 rounded-lg hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-400 w-full sm:w-auto text-base font-semibold">
                                            Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <style>
                            @media (max-width: 640px) {
                                .max-w-lg {
                                    max-width: 98vw !important;
                                }

                                .p-4,
                                .sm\:p-6 {
                                    padding: 0.75rem !important;
                                }

                                .text-lg,
                                .sm\:text-xl {
                                    font-size: 1rem !important;
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

                    @if ($isDeleteModalOpen)
                        <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50" wire:ignore>
                            <div
                                class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-lg mx-4 sm:mx-0 text-center">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Confirm Deletion</h3>
                                <p class="mt-2 text-gray-600 dark:text-gray-300">
                                    Are you sure you want to delete this Summary Item Procurement Data?
                                </p>
                                <div
                                    class="mt-6 flex flex-col sm:flex-row justify-center space-y-2 sm:space-y-0 sm:space-x-4">
                                    <button wire:click="closeModal"
                                        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 w-full sm:w-auto">Cancel</button>
                                    <button wire:click="deleteItem"
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