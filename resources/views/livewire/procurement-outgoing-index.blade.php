<div>
    @if ($showNotification)
        <div class="fixed top-4 right-4 bg-{{ $notificationType === 'error' ? 'red' : 'green' }}-100 border border-{{ $notificationType === 'error' ? 'red' : 'green' }}-400 text-{{ $notificationType === 'error' ? 'red' : 'green' }}-700 px-4 py-3 rounded shadow-md z-50"
            role="alert">
            <strong class="font-bold">{{ $notificationType === 'error' ? 'Error!' : 'Success!' }}</strong>
            <span class="block sm:inline">{{ $notificationMessage }}</span>
            <div class="mt-2 flex justify-end">
                <button wire:click="dismissNotification"
                    class="bg-{{ $notificationType === 'error' ? 'red' : 'green' }}-500 hover:bg-{{ $notificationType === 'error' ? 'red' : 'green' }}-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    OK
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
                    <div class="pl-10 mb-4">
                        <div class="flex items-center space-x-2">
                        </div>
                        <div class="flex items-center space-x-4 mt-20">
                            <select wire:model="filterMonth" wire:change="performSearch"
                                class="p-2 border rounded-md shadow-md" style="min-width: 150px;">
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
                    <div class="p-10 w-full overflow-x-auto">
                        <h1 style="font-size: 2em;">Procurement Outgoing List</h1><br>
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-600 border-collapse"
                            style="table-layout: fixed;">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
                                <tr>
                                    <th class="px-6 py-3 cursor-pointer select-none"
                                        wire:click="setSortBy('received_date')">
                                        Date Received
                                        @if($sortBy === 'received_date')
                                            <span>
                                                {!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}
                                            </span>
                                        @endif
                                    </th>
                                    <th class="px-6 py-3 cursor-pointer select-none" wire:click="setSortBy('end_user')">
                                        End-User
                                        @if($sortBy === 'end_user')
                                            <span>
                                                {!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}
                                            </span>
                                        @endif
                                    </th>
                                    <th class="px-6 py-3 cursor-pointer select-none" wire:click="setSortBy('pr_no')">
                                        PR-Number
                                        @if($sortBy === 'pr_no')
                                            <span>
                                                {!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}
                                            </span>
                                        @endif
                                    </th>
                                    <th class="px-6 py-3 cursor-pointer select-none"
                                        wire:click="setSortBy('particulars')">
                                        Particulars
                                        @if($sortBy === 'particulars')
                                            <span>
                                                {!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}
                                            </span>
                                        @endif
                                    </th>
                                    <th class="px-6 py-3 cursor-pointer select-none" wire:click="setSortBy('amount')">
                                        Amount
                                        @if($sortBy === 'amount')
                                            <span>
                                                {!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}
                                            </span>
                                        @endif
                                    </th>
                                    <th class="px-6 py-3 cursor-pointer select-none" wire:click="setSortBy('creditor')">
                                        Creditor
                                        @if($sortBy === 'creditor')
                                            <span>
                                                {!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}
                                            </span>
                                        @endif
                                    </th>
                                    <th class="px-6 py-3 cursor-pointer select-none" wire:click="setSortBy('remarks')">
                                        Remarks
                                        @if($sortBy === 'remarks')
                                            <span>
                                                {!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}
                                            </span>
                                        @endif
                                    </th>
                                    <th class="px-6 py-3 cursor-pointer select-none"
                                        wire:click="setSortBy('responsibility')">
                                        Responsibility
                                        @if($sortBy === 'responsibility')
                                            <span>
                                                {!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}
                                            </span>
                                        @endif
                                    </th>
                                    <th class="px-6 py-3 cursor-pointer select-none"
                                        wire:click="setSortBy('received_by')">
                                        Received by
                                        @if($sortBy === 'received_by')
                                            <span>
                                                {!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}
                                            </span>
                                        @endif
                                    </th>
                                    <th class="px-6 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($outgoings as $outgoing)
                                    <tr class="hover:bg-gray-100">
                                        <td class="py-2 px-4 border break-words">
                                            {{ \Carbon\Carbon::parse($outgoing->received_date)->format('m/d/Y h:i A') }}
                                        </td>
                                        <td class="py-2 px-4 border break-words">{{ $outgoing->end_user }}</td>
                                        <td class="py-2 px-4 border break-words">{{ $outgoing->pr_no }}</td>
                                        <td class="py-2 px-4 border break-words">{{ $outgoing->particulars }}</td>
                                        <td class="py-2 px-4 border break-words">
                                            {{ number_format((float) $outgoing->amount, 2) }}
                                        </td>
                                        <td class="py-2 px-4 border break-words">{{ $outgoing->creditor }}</td>
                                        <td class="py-2 px-4 border break-words">{{ $outgoing->remarks }}</td>
                                        <td class="py-2 px-4 border break-words">{{ $outgoing->responsibility }}</td>
                                        <td class="py-2 px-4 border break-words">{{ $outgoing->received_by }}</td>
                                        <td class="py-2 px-4 border text-center">
                                            <div class="flex justify-center space-x-2">
                                                <button wire:click="openEditModal({{ $outgoing->id }})"
                                                    class="text-blue-600 hover:underline">Edit</button>
                                                <button wire:click="openDeleteModal({{ $outgoing->id }})"
                                                    class="text-red-600 hover:underline">Delete</button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($outgoings->isEmpty())
                                    <tr>
                                        <td colspan="10" class="text-center py-4">No outgoing procurement found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <div class="mt-4 flex-wrap items-center">
                            <div>
                                {{ $outgoings->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back to Top Button -->
        <button id="backToTopBtn"
            class="fixed bottom-8 right-8 z-50 bg-blue-600 text-white px-4 py-2 rounded-full shadow-lg hover:bg-blue-800 transition-opacity opacity-0 pointer-events-none"
            style="transition: opacity 0.3s;" onclick="window.scrollTo({top: 0, behavior: 'smooth'});">
            ↑ Back to Top
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


        @if ($isAddModalOpen)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50" wire:ignore>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-lg w-full">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                            Add Outgoing
                        </h3>
                        <button wire:click="closeModal" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-400">
                            &#x2715;
                        </button>
                    </div>
                    <div>
                        <form wire:submit.prevent="saveOutgoing" novalidate>
                            <div class="mb-2">
                                <label for="received_date"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date & Time</label>
                                <input wire:model="received_date" type="datetime-local" id="received_date"
                                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200" required />
                                @error('received_date')
                                    <p class="text-red-500 text-sm">{{ $errors->first('received_date') }}</p>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="end_user"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End-User</label>
                                <input wire:model="end_user" type="text" id="end_user"
                                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                    placeholder="Enter End-User" required />
                                @error('end_user')
                                    <p class="text-red-500 text-sm">{{ $errors->first('end_user') }}</p>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="pr_no" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">PR
                                    Number</label>
                                <input wire:model="pr_no" type="text" id="pr_no"
                                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                    placeholder="Enter PR Number" required />
                                @error('pr_no')
                                    <p class="text-red-500 text-sm">{{ $errors->first('pr_no') }}</p>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="particulars"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Particulars</label>
                                <textarea wire:model="particulars" id="particulars"
                                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                    placeholder="Enter Particulars" required></textarea>
                                @error('particulars')
                                    <p class="text-red-500 text-sm">{{ $errors->first('particulars') }}</p>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="amount"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Amount</label>
                                <input wire:model="amount" type="text" id="amount"
                                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                    placeholder="Enter Amount" required pattern="^\d*\.?\d*$"
                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" />
                                @error('amount')
                                    <p class="text-red-500 text-sm">{{ $errors->first('amount') }}</p>
                                @enderror
                                @if (!is_numeric($amount) && $amount !== null && $amount !== '')
                                    <p class="text-red-500 text-sm">Amount must be a number only.</p>
                                @endif
                            </div>
                            <div class="mb-2">
                                <label for="creditor"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Creditor</label>
                                <input wire:model="creditor" type="text" id="creditor"
                                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                    placeholder="Enter Creditor" required />
                                @error('creditor')
                                    <p class="text-red-500 text-sm">{{ $errors->first('creditor') }}</p>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="remarks"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Remarks</label>
                                <textarea wire:model="remarks" id="remarks"
                                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                    placeholder="Enter Remarks" required></textarea>
                                @error('remarks')
                                    <p class="text-red-500 text-sm">{{ $errors->first('remarks') }}</p>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="responsibility"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Responsibility</label>
                                <input wire:model="responsibility" type="text" id="responsibility"
                                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                    placeholder="Enter Responsibility" required />
                                @error('responsibility')
                                    <p class="text-red-500 text-sm">{{ $errors->first('responsibility') }}</p>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="received_by"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Received
                                    By</label>
                                <input wire:model="received_by" type="text" id="received_by"
                                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                    placeholder="Enter Received By" required />
                                @error('received_by')
                                    <p class="text-red-500 text-sm">{{ $errors->first('received_by') }}</p>
                                @enderror
                            </div>
                            <div class="flex justify-end space-x-2">
                                <button type="button" wire:click="closeModal"
                                    class="px-4 py-2 text-white bg-gray-600 rounded-lg hover:bg-gray-700">Cancel</button>
                                <button type="submit"
                                    class="px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-400">
                                    Add Outgoing
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
        <script>
            document.addEventListener('livewire:load', function () {
                const amountInput = document.getElementById('amount');

                if (amountInput) {
                    amountInput.addEventListener('blur', function () {
                        this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
                        @this.set('amount', this.value);
                    });
                }
            });
        </script>

        @if ($isEditModalOpen)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50" wire:ignore>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-lg w-full">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                            Edit Outgoing
                        </h3>
                        <button wire:click="closeModal" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-400">
                            &#x2715;
                        </button>
                    </div>
                    <div>
                        <form wire:submit.prevent="updateOutgoing" novalidate>
                            <div class="mb-2">
                                <label for="received_date"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date & Time</label>
                                <input wire:model="received_date" type="datetime-local" id="received_date"
                                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200" required />
                                @error('received_date')
                                    <p class="text-red-500 text-sm">{{ $errors->first('received_date') }}</p>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="end_user"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End-User</label>
                                <input wire:model="end_user" type="text" id="end_user"
                                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                    placeholder="Enter End-User" required />
                                @error('end_user')
                                    <p class="text-red-500 text-sm">{{ $errors->first('end_user') }}</p>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="pr_no" class="block mb-2 text-sm font-medium text -gray-900 dark:text-white">PR
                                    Number</label>
                                <input wire:model="pr_no" type="text" id="pr_no"
                                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                    placeholder="Enter PR Number" required />
                                @error('pr_no')
                                    <p class="text-red-500 text-sm">{{ $errors->first('pr_no') }}</p>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="particulars"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Particulars</label>
                                <textarea wire:model="particulars" id="particulars"
                                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                    placeholder="Enter Particulars" required></textarea>
                                @error('particulars')
                                    <p class="text-red-500 text-sm">{{ $errors->first('particulars') }}</p>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="amount"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Amount</label>
                                <input wire:model="amount" type="text" id="amount"
                                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                    placeholder="Enter Amount" required pattern="^\d*\.?\d*$"
                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" />
                                @error('amount')
                                    <p class="text-red-500 text-sm">{{ $errors->first('amount') }}</p>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="creditor"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Creditor</label>
                                <input wire:model="creditor" type="text" id="creditor"
                                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                    placeholder="Enter Creditor" required />
                                @error('creditor')
                                    <p class="text-red-500 text-sm">{{ $errors->first('creditor') }}</p>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="remarks"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Remarks</label>
                                <textarea wire:model="remarks" id="remarks"
                                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                    placeholder="Enter Remarks" required></textarea>
                                @error('remarks')
                                    <p class="text-red-500 text-sm">{{ $errors->first('remarks') }}</p>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="responsibility"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Responsibility</label>
                                <input wire:model="responsibility" type="text" id="responsibility"
                                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                    placeholder="Enter Responsibility" required />
                                @error('responsibility')
                                    <p class="text-red-500 text-sm">{{ $errors->first('responsibility') }}</p>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="received_by"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Received
                                    By</label>
                                <input wire:model="received_by" type="text" id="received_by"
                                    class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                    placeholder="Enter Received By" required />
                                @error('received_by')
                                    <p class="text-red-500 text-sm">{{ $errors->first('received_by') }}</p>
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
                    <p class="mt-2 text-gray-600 dark:text-gray-300">Are you sure you want to delete this Outgoing
                        Procurement Data?
                    </p>
                    <div class="mt-6 flex justify-center space-x-4">
                        <button wire:click="closeModal"
                            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">Cancel</button>
                        <button wire:click="deleteOutgoing"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Delete</button>
                    </div>
                </div>
            </div>
        @endif
        <footer class="mt-4 border-t pt-4 text-center text-gray-600 text-sm" style="min-height: 0.7in;">
            <span class="font-semibold">Procurement Management Section 2025</span>
        </footer>
    </div>

    <script>
        window.addEventListener('notify', event => {
            @this.set('showNotification', true);
            @this.set('notificationMessage', event.detail.message);
            @this.set('notificationType', event.detail.type || 'success'); // Default to 'success'
        });
    </script>