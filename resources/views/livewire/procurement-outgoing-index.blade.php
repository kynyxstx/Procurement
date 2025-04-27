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

<link rel="icon" href="{{ asset('Images/favicon.ico') }}" type="image/x-icon">

<div>
    <div>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                2025 PROCUREMENT OUTGOING
            </h2>
        </x-slot>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="w-full flex justify-between items-center mb-4 pl-10">
                    <div class="flex items-center">
                        <select wire:model="filterItems" wire:change="performSearch"
                            class="w-full p-2 border rounded-md shadow-md mr-2">
                            <option value="">Month </option>
                            <option value="Venue;Meals;Accommodation">January</option>
                            <option value="Services;Catering;Maintenance">February</option>
                            <option value="">March</option>
                            <option value="">April</option>
                            <option value="">May</option>
                            <option value="">June</option>
                            <option value="">July</option>
                            <option value="">August</option>
                            <option value="">September</option>
                            <option value="">October</option>
                            <option value="">November</option>
                            <option value="">December</option>
                        </select>
                        <input type="text" wire:model="search" placeholder="Search suppliers..."
                            class="w-full max-w-md p-2 border rounded-md shadow-md mr-2"
                            wire:keydown.enter="performSearch" />
                    </div>
                    <div class="flex flex-col items-start p-10">
                        <button wire:click="openAddModal"
                            class="px-5 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700">
                            Add Supplier
                        </button>
                    </div>
                </div>

                <div class="p-10 w-full overflow-x-auto">
                    <h1 style="font-size: 2em;">Procurement Outgoing</h1><br>
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-600 border-collapse"
                        style="table-layout: fixed;">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="px-6 py-3">Date</th>
                                <th class="px-6 py-3">End-User</th>
                                <th class="px-6 py-3">PR-Number</th>
                                <th class="px-6 py-3">Particulars</th>
                                <th class="px-6 py-3">Amount</th>
                                <th class="px-6 py-3">Creditor</th>
                                <th class="px-6 py-3">Remarks</th>
                                <th class="px-6 py-3">Responsibility</th>
                                <th class="px-6 py-3">Received by</th>
                                <th class="px-6 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($outgoings as $outgoing)
                                <tr class="hover:bg-gray-100">
                                    <td class="py-2 px-4 border break-words">{{ $outgoing->received_date }}</td>
                                    <td class="py-2 px-4 border break-words">{{ $outgoing->end_user }}</td>
                                    <td class="py-2 px-4 border break-words">{{ $outgoing->pr_no }}</td>
                                    <td class="py-2 px-4 border break-words">{{ $outgoing->particulars }}</td>
                                    <td class="py-2 px-4 border break-words">{{ $outgoing->amount }}</td>
                                    <td class="py-2 px-4 border break-words">{{ $outgoing->creditor }}</td>
                                    <td class="py-2 px-4 border break-words">{{ $outgoing->remarks }}</td>
                                    <td class="py-2 px-4 border break-words">{{ $outgoing->responsibility }}</td>
                                    <td class="py-2 px-4 border break-words">{{ $outgoing->received_by }}</td>
                                    <td class="py-2 px-4 border text-center">
                                        <div class="flex justify-center space-x-2">
                                            <button wire:click="openEditModal({{ $outgoing->id }})"
                                                class="text-blue-600 hover:underline">Edit
                                            </button>
                                            <button wire:click="openDeleteModal({{ $outgoing->id }})"
                                                class="text-red-600 hover:underline">Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>