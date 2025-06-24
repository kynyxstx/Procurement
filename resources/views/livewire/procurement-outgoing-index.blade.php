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
                    PROCUREMENT OUTGOING
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
                                <select wire:model="filterMonth" wire:change="performSearch"
                                    class="p-2 border rounded-md shadow-sm min-w-[120px] w-full sm:w-[300px]">
                                    <option value="">All Month</option>
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
                                <select wire:model="filterEndUser" wire:change="performSearch"
                                    class="p-2 border rounded-md shadow-md" style="min-width: 180px;">
                                    <option value="">All End-User</option>
                                    <option value="ONS-PMS">ONS-PMS</option>
                                    <option value="ESSS-SSD">ESSS-SSD</option>
                                    <option value="ITDS-KMCD">ITDS-KMCD</option>
                                    <option value="ITDS-RDMD">ITDS-RDMD</option>
                                    <option value="NCS-PHCD">NCS-PHCD</option>
                                    <option value="ONS-ICU">ONS-ICU</option>
                                    <option value="SS-SSD">SS-SSD</option>
                                    <option value="SSSS-LSSD">SSSS-LSSD</option>
                                    <option value="ONS-LS">ONS-LS</option>
                                    <option value="CRS-CRMD">CRS-CRMD</option>
                                    <option value="FAS-GSD">FAS-GSD</option>
                                    <option value="PRO-ISMD">PRO-ISMD</option>
                                    <option value="MAS-SAD">MAS-SAD</option>
                                    <option value="CTCO-CBSS">CTCO-CBSS</option>
                                    <option value="FAS-HRD">FAS-HRD</option>
                                    <option value="ONS-PMS">ONS-PMS</option>
                                    <option value="SSSS-LDRSSD">SSSS-LDRSSD</option>
                                    <option value="ESSS-LPSD">ESSS-LPSD</option>
                                    <option value="ESSS-CSD">ESSS-CSD</option>
                                    <option value="GSD-EMS">GSD-EMS</option>
                                    <option value="FAS-OANS">FAS-OANS</option>
                                </select>
                                <input type="text" wire:model.live="search" placeholder="Search monitoring..."
                                    class="p-2 border rounded-md shadow-md mr-2" style="min-width: 250px;" />
                                <button wire:click="exportToExcel"
                                    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700 ml-2">
                                    Export to Excel
                                </button>
                                <button wire:click="openAddModal"
                                    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700 ml-2">
                                    Add Outgoing
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="p-10 w-full">
                        <h1 style="font-size: 2em;">Procurement Outgoing List</h1><br>
                        <div class="overflow-x-auto">
                            <table
                                class="min-w-[900px] w-full text-sm text-left text-gray-500 dark:text-gray-600 border-collapse"
                                style="table-layout: auto;">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
                                    <tr>
                                        <th class="px-6 py-3 text-lg font-semibold">Actions</th>
                                        <th class="px-6 py-3 cursor-pointer whitespace-nowrap"
                                            wire:click="setSortBy('received_date')">
                                            <span class="text-lg font-semibold">Date Received</span>
                                            @if($sortBy === 'received_date')
                                                <span>
                                                    {!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}
                                                </span>
                                            @endif
                                        </th>
                                        <th class="px-6 py-3 cursor-pointer whitespace-nowrap"
                                            wire:click="setSortBy('end_user')">
                                            <span class="text-lg font-semibold">End-User</span>
                                            @if($sortBy === 'end_user')
                                                <span>
                                                    {!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}
                                                </span>
                                            @endif
                                        </th>
                                        <th class="px-6 py-3 cursor-pointer whitespace-nowrap"
                                            wire:click="setSortBy('pr_no')">
                                            <span class="text-lg font-semibold">PR-Number</span>
                                            @if($sortBy === 'pr_no')
                                                <span>
                                                    {!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}
                                                </span>
                                            @endif
                                        </th>
                                        <th class="px-6 py-3 cursor-pointer whitespace-nowrap"
                                            wire:click="setSortBy('particulars')">
                                            <span class="text-lg font-semibold">Particulars</span>
                                            @if($sortBy === 'particulars')
                                                <span>
                                                    {!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}
                                                </span>
                                            @endif
                                        </th>
                                        <th class="px-6 py-3 cursor-pointer whitespace-nowrap"
                                            wire:click="setSortBy('amount')">
                                            <span class="text-lg font-semibold">Amount</span>
                                            @if($sortBy === 'amount')
                                                <span>
                                                    {!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}
                                                </span>
                                            @endif
                                        </th>
                                        <th class="px-6 py-3 cursor-pointer whitespace-nowrap"
                                            wire:click="setSortBy('creditor')">
                                            <span class="text-lg font-semibold">Creditor</span>
                                            @if($sortBy === 'creditor')
                                                <span>
                                                    {!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}
                                                </span>
                                            @endif
                                        </th>
                                        <th class="px-6 py-3 cursor-pointer whitespace-nowrap"
                                            wire:click="setSortBy('remarks')">
                                            <span class="text-lg font-semibold">Remarks</span>
                                            @if($sortBy === 'remarks')
                                                <span>
                                                    {!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}
                                                </span>
                                            @endif
                                        </th>
                                        <th class="px-6 py-3 cursor-pointer whitespace-nowrap"
                                            wire:click="setSortBy('responsibility')">
                                            <span class="text-lg font-semibold">Responsibility</span>
                                            @if($sortBy === 'responsibility')
                                                <span>
                                                    {!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}
                                                </span>
                                            @endif
                                        </th>
                                        <th class="px-6 py-3 cursor-pointer whitespace-nowrap"
                                            wire:click="setSortBy('received_by')">
                                            <span class="text-lg font-semibold">Received by</span>
                                            @if($sortBy === 'received_by')
                                                <span>
                                                    {!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}
                                                </span>
                                            @endif
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($outgoings as $outgoing)
                                        <tr class="hover:bg-gray-100 text-base">
                                            <td
                                                class="py-2 px-4 border text-left align-top whitespace-nowrap break-words text-base">
                                                <div class="flex justify-center space-x-2">
                                                    <button wire:click="openEditModal({{ $outgoing->id }})"
                                                        class="text-blue-600 hover:underline">View/Edit</button>
                                                    <button wire:click="openDeleteModal({{ $outgoing->id }})"
                                                        class="text-red-600 hover:underline">Delete</button>
                                                </div>
                                            </td>
                                            <td
                                                class="py-2 px-4 border text-left align-top whitespace-nowrap break-words text-base">
                                                {{ \Carbon\Carbon::parse($outgoing->received_date)->format('m/d/Y h:i A') }}
                                            </td>
                                            <td
                                                class="py-2 px-4 border text-left align-top whitespace-nowrap break-words text-base">
                                                {{ $outgoing->end_user }}
                                            </td>
                                            <td
                                                class="py-2 px-4 border text-left align-top whitespace-nowrap break-words text-base">
                                                {{ $outgoing->pr_no }}
                                            </td>
                                            <td
                                                class="py-2 px-4 border text-left align-top whitespace-nowrap break-words text-base">
                                                {{ $outgoing->particulars }}
                                            </td>
                                            <td
                                                class="py-2 px-4 border text-left align-top whitespace-nowrap break-words text-base">
                                                {{ number_format((float) $outgoing->amount, 2) }}
                                            </td>
                                            <td
                                                class="py-2 px-4 border text-left align-top whitespace-nowrap break-words text-base">
                                                {{ $outgoing->creditor }}
                                            </td>
                                            <td
                                                class="py-2 px-4 border text-left align-top whitespace-nowrap break-words text-base">
                                                {{ $outgoing->remarks }}
                                            </td>
                                            <td
                                                class="py-2 px-4 border text-left align-top whitespace-nowrap break-words text-base">
                                                {{ $outgoing->responsibility }}
                                            </td>
                                            <td
                                                class="py-2 px-4 border text-left align-top whitespace-nowrap break-words text-base">
                                                {{ $outgoing->received_by }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if ($outgoings->isEmpty())
                                        <tr>
                                            <td colspan="9" class="text-center py-4">No procurement outgoing found.</td>
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
                                {{ $outgoings->links() }}
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
                        <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 px-2 sm:px-0"
                            wire:ignore>
                            <div
                                class="bg-white dark:bg-gray-800 p-2 sm:p-4 rounded-lg shadow-lg max-w-lg w-full mx-auto max-h-[95vh] overflow-y-auto">
                                <div class="flex justify-between items-center mb-2 sm:mb-4">
                                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-gray-100">
                                        Add Outgoing
                                    </h3>
                                    <button wire:click="closeModal"
                                        class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-400 text-xl">
                                        &#x2715;
                                    </button>
                                </div>
                                <div>
                                    <form wire:submit.prevent="saveOutgoing">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-4">
                                            <div>
                                                <label for="received_date"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date
                                                    & Time</label>
                                                <input wire:model="received_date" type="datetime-local" id="received_date"
                                                    class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-base sm:text-lg"
                                                    required />
                                                @error('received_date')
                                                    <p class="text-red-500 text-xs">{{ $errors->first('received_date') }}
                                                    </p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label for="end_user"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">End-User</label>
                                                <input wire:model="end_user" type="text" id="end_user"
                                                    class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-base sm:text-lg"
                                                    placeholder="Enter End-User" required />
                                                @error('end_user')
                                                    <p class="text-red-500 text-xs">{{ $errors->first('end_user') }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label for="pr_no"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">PR
                                                    Number</label>
                                                <input wire:model="pr_no" type="text" id="pr_no"
                                                    class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-base sm:text-lg"
                                                    placeholder="Enter PR Number" required />
                                                @error('pr_no')
                                                    <p class="text-red-500 text-xs">{{ $errors->first('pr_no') }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label for="creditor"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Creditor</label>
                                                <input wire:model="creditor" type="text" id="creditor"
                                                    class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-base sm:text-lg"
                                                    placeholder="Enter Creditor" />
                                                @error('creditor')
                                                    <p class="text-red-500 text-xs">{{ $errors->first('creditor') }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label for="amount"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount</label>
                                                <input wire:model="amount" type="text" id="amount"
                                                    class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-base sm:text-lg"
                                                    placeholder="Enter Amount" pattern="^\d*\.?\d*$"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" />
                                                @error('amount')
                                                    <p class="text-red-500 text-xs">{{ $errors->first('amount') }}</p>
                                                @enderror
                                                @if (!is_numeric($amount) && $amount !== null && $amount !== '')
                                                    <p class="text-red-500 text-xs">Amount must be a number only.</p>
                                                @endif
                                            </div>
                                            <div>
                                                <label for="responsibility"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Responsibility</label>
                                                <input wire:model="responsibility" type="text" id="responsibility"
                                                    class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-base sm:text-lg"
                                                    placeholder="Enter Responsibility" />
                                                @error('responsibility')
                                                    <p class="text-red-500 text-xs">{{ $errors->first('responsibility') }}
                                                    </p>
                                                @enderror
                                            </div>
                                            <div class="sm:col-span-2">
                                                <label for="particulars"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Particulars</label>
                                                <textarea wire:model="particulars" id="particulars"
                                                    class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200 text-base sm:text-lg"
                                                    placeholder="Enter Particulars" rows="2"></textarea>
                                                @error('particulars')
                                                    <p class="text-red-500 text-xs">{{ $errors->first('particulars') }}</p>
                                                @enderror
                                            </div>
                                            <div class="sm:col-span-2">
                                                <label for="remarks"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Remarks</label>
                                                <textarea wire:model="remarks" id="remarks"
                                                    class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200 text-base sm:text-lg"
                                                    placeholder="Enter Remarks" rows="2"></textarea>
                                                @error('remarks')
                                                    <p class="text-red-500 text-xs">{{ $errors->first('remarks') }}</p>
                                                @enderror
                                            </div>
                                            <div class="sm:col-span-2">
                                                <label for="received_by"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Received
                                                    By</label>
                                                <input wire:model="received_by" type="text" id="received_by"
                                                    class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200 text-base sm:text-lg"
                                                    placeholder="Enter Received By" />
                                                @error('received_by')
                                                    <p class="text-red-500 text-xs">{{ $errors->first('received_by') }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div
                                            class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-2 mt-4">
                                            <button type="button" wire:click="closeModal"
                                                class="px-6 py-3 text-white bg-gray-600 rounded-lg hover:bg-gray-700 w-full sm:w-auto text-base font-semibold">Cancel</button>
                                            <button type="submit"
                                                class="px-6 py-3 text-white bg-green-600 rounded-lg hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-400 w-full sm:w-auto text-base font-semibold">
                                                Add Outgoing
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
                            x-data="{
                                                        amountValue: @entangle('amount'), // Bind to Livewire's amount property
                                                        formatAmount(value) {
                                                            const numericValue = parseFloat(String(value).replace(/[^0-9.]/g, ''));
                                                            if (isNaN(numericValue)) {
                                                                return '';
                                                            }
                                                            return numericValue.toLocaleString('en-US', {
                                                                minimumFractionDigits: 2,
                                                                maximumFractionDigits: 2
                                                            });
                                                        },
                                                        init() {
                                                            // Listen for the event dispatched when modal opens to set initial formatted value
                                                            Livewire.on('outgoingEditModalOpened', ({ amount }) => {
                                                                this.$nextTick(() => { // Ensure DOM is updated before trying to set value
                                                                    const amountInput = document.getElementById('amount');
                                                                    if (amountInput) {
                                                                        amountInput.value = this.formatAmount(amount);
                                                                    }
                                                                });
                                                            });
                                                        }
                                                    }" x-init="init()">
                            <div
                                class="bg-white dark:bg-gray-800 p-2 sm:p-4 rounded-lg shadow-lg max-w-lg w-full mx-auto max-h-[95vh] overflow-y-auto">
                                <div class="flex justify-between items-center mb-2 sm:mb-4">
                                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">
                                        Edit Outgoing
                                    </h3>
                                    <button wire:click="closeModal"
                                        class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-400 text-xl">
                                        &#x2715;
                                    </button>
                                </div>
                                <div>
                                    <form wire:submit.prevent="updateOutgoing">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-4">
                                            <div>
                                                <label for="received_date"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date
                                                    & Time</label>
                                                <input wire:model="received_date" type="datetime-local" id="received_date"
                                                    class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-base sm:text-lg"
                                                    required />
                                                @error('received_date')
                                                    <p class="text-red-500 text-xs">{{ $errors->first('received_date') }}
                                                    </p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label for="end_user"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">End-User</label>
                                                <input wire:model="end_user" type="text" id="end_user"
                                                    class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-base sm:text-lg"
                                                    placeholder="Enter End-User" required />
                                                @error('end_user')
                                                    <p class="text-red-500 text-xs">{{ $errors->first('end_user') }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label for="pr_no"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">PR
                                                    Number</label>
                                                <input wire:model="pr_no" type="text" id="pr_no"
                                                    class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-base sm:text-lg"
                                                    placeholder="Enter PR Number" required />
                                                @error('pr_no')
                                                    <p class="text-red-500 text-xs">{{ $errors->first('pr_no') }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label for="creditor"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Creditor</label>
                                                <input wire:model="creditor" type="text" id="creditor"
                                                    class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-base sm:text-lg"
                                                    placeholder="Enter Creditor" />
                                                @error('creditor')
                                                    <p class="text-red-500 text-xs">{{ $errors->first('creditor') }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label for="amount"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount</label>
                                                <input x-model="amountValue" {{-- Use x-model for display, wire:model is
                                                    removed --}}
                                                    @blur="amountValue = formatAmount(amountValue); $wire.set('amount', parseFloat(String(amountValue).replace(/[^0-9.]/g, '')).toFixed(2))"
                                                    {{-- On blur, format for display and send clean to Livewire --}}
                                                    @focus="amountValue = String(amountValue).replace(/,/g, '')" {{-- On
                                                    focus, remove commas for editing --}} type="text" id="amount"
                                                    class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-base sm:text-lg"
                                                    placeholder="Enter Amount" />
                                                @error('amount')
                                                    <p class="text-red-500 text-xs">{{ $errors->first('amount') }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label for="responsibility"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Responsibility</label>
                                                <input wire:model="responsibility" type="text" id="responsibility"
                                                    class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-base sm:text-lg"
                                                    placeholder="Enter Responsibility" />
                                                @error('responsibility')
                                                    <p class="text-red-500 text-xs">{{ $errors->first('responsibility') }}
                                                    </p>
                                                @enderror
                                            </div>
                                            <div class="sm:col-span-2">
                                                <label for="particulars"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Particulars</label>
                                                <textarea wire:model="particulars" id="particulars"
                                                    class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200 text-base sm:text-lg"
                                                    placeholder="Enter Particulars" rows="2"></textarea>
                                                @error('particulars')
                                                    <p class="text-red-500 text-xs">{{ $errors->first('particulars') }}</p>
                                                @enderror
                                            </div>
                                            <div class="sm:col-span-2">
                                                <label for="remarks"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Remarks</label>
                                                <textarea wire:model="remarks" id="remarks"
                                                    class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200 text-base sm:text-lg"
                                                    placeholder="Enter Remarks" rows="2"></textarea>
                                                @error('remarks')
                                                    <p class="text-red-500 text-xs">{{ $errors->first('remarks') }}</p>
                                                @enderror
                                            </div>
                                            <div class="sm:col-span-2">
                                                <label for="received_by"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Received
                                                    By</label>
                                                <input wire:model="received_by" type="text" id="received_by"
                                                    class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200 text-base sm:text-lg"
                                                    placeholder="Enter Received By" />
                                                @error('received_by')
                                                    <p class="text-red-500 text-xs">{{ $errors->first('received_by') }}</p>
                                                @enderror
                                            </div>
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
                        </div>
                    @endif

                    @if ($isDeleteModalOpen)
                        <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 px-2 sm:px-0"
                            wire:ignore>
                            <div
                                class="bg-white dark:bg-gray-800 p-3 sm:p-6 rounded-lg shadow-lg max-w-lg w-full text-center mx-auto">
                                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-gray-100">Confirm
                                    Deletion</h3>
                                <p class="mt-2 text-gray-600 dark:text-gray-300 text-base sm:text-lg">
                                    Are you sure you want to delete this Outgoing Procurement Data?
                                </p>
                                <div
                                    class="mt-6 flex flex-col sm:flex-row justify-center space-y-2 sm:space-y-0 sm:space-x-4">
                                    <button wire:click="closeModal"
                                        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 w-full sm:w-auto">Cancel</button>
                                    <button wire:click="deleteOutgoing"
                                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 w-full sm:w-auto">Delete</button>
                                </div>
                            </div>
                        </div>
                        <style>
                            @media (max-width: 640px) {
                                .max-w-lg {
                                    max-width: 99vw !important;
                                }

                                .p-3,
                                .sm\:p-6 {
                                    padding: 0.75rem !important;
                                }

                                .text-base,
                                .sm\:text-lg {
                                    font-size: 1rem !important;
                                }

                                .text-lg,
                                .sm\:text-xl {
                                    font-size: 1.1rem !important;
                                }

                                .rounded-lg {
                                    border-radius: 0.75rem !important;
                                }
                            }
                        </style>
                    @endif
                </div>
            </div>
        </div>
        <footer class="mt-4 border-t pt-4 text-center text-gray-600 text-sm" style="min-height: 0.7in;">
            <span class="font-semibold">Procurement Management Section 2025</span>
        </footer>
    </div>
</div>

<script>
    window.addEventListener('notify', event => {
        @this.set('showNotification', true);
        @this.set('notificationMessage', event.detail.message);
        @this.set('notificationType', event.detail.type || 'success');
    });
</script>