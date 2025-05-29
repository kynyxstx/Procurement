@extends('layouts.app')

@section('content')
    <div>
        {{-- Notifications --}}
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
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    SUPPLIER DIRECTORY
                </h2>
            </x-slot>
        </div>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="pl-10 mb-4">
                        <div class="flex items-center space-x-4 mt-10">
                            <select wire:model="filterSupplier" wire:change="performSearch"
                                class="p-2 border rounded-md shadow-sm min-w-[250px]">
                                <option value="" style="color: #ffffff; font-weight: bold; background-color: #636363;">
                                    All Supplier
                                </option>
                                <option
                                    value="Fortune Gate Corporation;King Allied Food Corporation;Angelica's Catering;J. Carpio Catering Services;Jiamin Catering Services;Fitness Gourmet PH, Inc.;Calil G. Catering Service;La Marilena Dining Services;
                                                    Anthony's Restaurant;SD Publications, Inc.;Azafran Catering Services;Rosher's Catering Services;Lil's Cafe;Mamasgirl Catering Services;Spice Cuisine Food Services, Inc.;Angelica's Catering;Executive Gourtmet Catering Services;
                                                    PJG Food Services;Myrna Toledo's Food Services;Original 7 Lime Corporation;Newark Spring Water Corp.;Spice Cuisine Food Services, Inc.;Newark Spring Water Corp.">
                                    Services Catering & Maintenance</option>
                                <option
                                    value=" Maningning Trading;Token Avenue Trading;Grafiq Advertising System Corp.;Mathel
                                                    Enterprises;Macjab;Awards Central Philippines Inc.;TJ;Marbelle Consumer Goods Trading;Karen International Inc.;AJ">
                                    Tokens & Awards</option>
                                <option
                                    value="Kingsford Hotel Manila, Inc.;First Commonwealth Hotel Corp.;Park Inn by Radisson North Edsa;Century Park Hotel;La Breza Hotel;Harolds Evotel;Joy Nostalg;Hotel Lucky Chinatown;Luxent Hotel;Park inn by Raddison North EDSA;Seda Vertis North;Harolds Evotel Quezon City;GOH Management, Inc.;Eurotel Corporation;East Richmonde Hotel;Madison 101 Inc.;Go Hotels Timog;Northbelle Properties (B Hotel Quezon City);Camelot Hotel;Eastwood Richmonde Hotel;Y Hotels and Resorts Group, Inc.;Madison 101 Inc.;Prestige Hotels & Resort, Inc.;Sequoia Manila Corp;Hive Hotel and Convention Place;Erawan Philippines (Quezon City) Inc.;The Linden Suites;Paradigma International, Inc.;Astoria Plaza;Cocoon Boutique Hotel;Joy-Nostalg Hotel and Suites Manila;St. Francis Square Development Corp.;Azteco Corporation;Sequoia Manila Corp;Brentwood Suites;Novotel Manila;Madison 101 Tower & Hotel;Paramount Hotels & Facilities Mgt. Company Inc. (Microtel by Wyndham);Microtel by Wyndham;Crowne Plaza Manila Galleria;Seda Vertis North Hotel;USA Development Corp./ACE Hotel;Shercon Resort and Ecology Park;The Plaza Hotel - Balanga City;Wynwood Hotel Manila;Nanay Carmela's Kitchen Food Production;GOH Management, Inc.;Belmont Hotel Manila;Luxent Hotel;Novotel Manila Araneta City;Estancia de Lorenzo, Inc.;Abagatan Ti Manila;Timberland Highlands Resorts">
                                    Venue, Meals, and Accommodation</option>
                                <option
                                    value="Gorilla Trading;U-Bix Corporation;Innovation Printshoppe, Inc.;
                                                    Cover & Pages Corporation;K & L Core Consumer Goods and Trading;Arlechino Concepts Inc.;KQAA Consumer Good Trading;Azitsorog Inc.;Gilcor Printing Press;Awesome Graphics Center;VJ Graphics;LJB Printing Services;APO Production Unit;Emerj Printing Services;Ban Bee Commercial Co., Inc.;ATR Multi Trade Concept Inc.;Vellum Options Printing Services;24/7 Printing & Trading Cons. Corp.;Renalma Corporation;Dependable Packaging and Printing House Corp.;Java Press;Knit and Tuck Merchandising;Jarhens Trading;Synergygrafix Corp.;Coloredge Graphic Solution;Goodhand and Sons Global Image, Inc.;E and A Inkpress Prints and Enterprise;Trade Matters Trading;Anglowealth Enterprises;Ace Visual Solutions;EC-TEC Commercial;Proximatech Solutions Company">
                                    Printing</option>
                                <option
                                    value="RL Pabion Tire Supply;Veraliz Marketing;Pola's Consumer Goods Trading;ACL Consumer Goods Trading;Tabang Trophy, Center & General Merchandise;Tanjer Enterprises;Ceboom Enterprises;All Source Products Corporation;Zhujar Manufacturing Inc.;ATR Multi Trade Concept Inc.;Kayrie and Amaza Office and School Supplies Trading;E.E.L. Garments Manufacturing;EGMJ Trading;King of Kings">
                                    Accessories</option>
                                <option
                                    value="Girlteki, Inc;Exakt IT Services, Inc.;Total Information Management;Unison Computer System, Inc.;Quandatics, Inc.;ALLCARD, INC.;Masangkay Computer Services;Integrated Computer Systems, Inc.;Advance Solutions, Inc.;The Value Systems Integration Inc.;ACL Consumer Goods;The Manila Times Publishing Corporation;AGMS Information Technology Solution;Audio 4 Design N Technology Corp.;Avid Sales Corporation;Avesco Marketing Corporation;Techpoint IT Solution">
                                    IT Equipment, Software</option>
                                <option
                                    value="Chatans Office and School Supplies;Center Point Sales & Trading;AE Samonte Merchandise;Amada Enterprises;Competitive Card Solutions Phils. Inc.;Best Choice Enterprises;Papertree Marketing Group, Inc.;Andj Bright Printing Services;Xefar Enterprises;Philcopy Corporation;C & E Publishing Inc.;Motrade Inc.;AMADA Enterprises;GS Pontillas Bookstore;Avecilla Trading;347 School Office Supplies Inc.;Accessories and Supplies Depot Inc.;RFK General Merchandise;16/35 MM Production;Mind Mover Publishing House, Inc.;Accessories and Supplies Depot Inc.;Philicopy Corporation;Doña Alejandra Inc.">
                                    Office Supplies</option>
                                <option
                                    value="Arjelon Enterprises and Trading Corporation;East Richwood Safe Co. Inc.;Multibiz International Corporation;Gophertech Corporation;A.J.A.E Signage Printing Services;Arjelon Enterprises and Trading Corp.;8 Days a Week Consumer Goods Trading;CGMI Consumer Goods Trading;New AG Stylist Furniture;Innovalite;Maptech Information;Quartz Business Products Corp.;A4 Luck Marketing Corp.;Advance Microsystems Corp.;Computechologies Corporation;Davtech Marketing;
                                                    ZAB Enterprises;The Royal Empress General Merchandise;RPK3 Consumer Goods Wholesaling;Aspire Appliance Marketing;Tadashitec Global Corporation">
                                    Office Equipment</option>
                                <option
                                    value="VTSA International Inc;Maxcore Technologies Inc.;Sophie's Information Technology Services;Trends and Technologies;TUV Rheinland Philippines;Media Meter, Inc">
                                    IT-TECH</option>
                                <option
                                    value="CLEX E-Xtronics Trading Inc.;Metdrie Trading;Boston Builder's Center;Invictus-Ems Hardware & Construction Supplies;Valeriano Enterprise;Ideal Works Advertising and Event Management Corp.;Newburg Commercial Inc.;Kapitan Pepe Pest Control;King's Industrial Supply">
                                    Construction</option>
                                <option
                                    value="One Touch Electronics Lights and Sounds;Crossfade Video Alternatives;Smarter Multimedia Services;Kleal Entertainment Production;RN Events Organizing Services;Chronos & Kairos Events Management Services;Cityneon Philippines, Inc.;Grafia Enterprise;ADROW Creatives, Inc.;JPG Exhibition Booth Rentals;Gober Technologies Corporation;ADROW Creatives Inc">
                                    Equipment Rental & Services</option>
                                <option value="IJA Enterprises;Trainosys Training Services;Kaifashion Philippines Inc.">
                                    Training Kits</option>
                                <option value="FMR Corporation">Bus Rental</option>
                                <option
                                    value="Philippine Vision Group;Mayon Clinical Laboratory and Medical Service;Detoxicare Molecular Diagnostics Lab. Inc.;Clearbridge Medical Philippines Inc.;Diagnostica Trading">
                                    Medical Supplies</option>
                            </select>
                            <input type="text" wire:model.live="search" placeholder="Search suppliers..."
                                style="min-width: 300px;" class="p-2 border rounded-md shadow-sm mr-2" />
                            <button wire:click="exportToExcel"
                                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring focus:border-blue-300 ml-2">
                                Export to Excel
                            </button>
                            <button wire:click="exportToPDF"
                                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring focus:border-blue-300 ml-2">
                                Export to PDF
                            </button>
                            <button wire:click="openAddModal"
                                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring focus:border-blue-300 ml-2">
                                Add Supplier
                            </button>
                        </div>
                    </div>

                    <div class="p-10 w-full overflow-x-auto">
                        <h1 style="font-size: 2em;">Suppliers List</h1><br>
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-600 border-collapse"
                            style="table-layout: fixed;">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
                                <tr>
                                    <th class="px-6 py-3 cursor-pointer" wire:click="sortBy('supplier_name')">
                                        Supplier Name
                                        @if($sortField === 'supplier_name')
                                            @if($sortDirection === 'asc')
                                                &uarr;
                                            @else
                                                &darr;
                                            @endif
                                        @endif
                                    </th>
                                    <th class="px-6 py-3 cursor-pointer" wire:click="sortBy('address')">
                                        Address
                                        @if($sortField === 'address')
                                            @if($sortDirection === 'asc')
                                                &uarr;
                                            @else
                                                &darr;
                                            @endif
                                        @endif
                                    </th>
                                    <th class="px-6 py-3 cursor-pointer" wire:click="sortBy('items')">
                                        Items
                                        @if($sortField === 'items')
                                            @if($sortDirection === 'asc')
                                                &uarr;
                                            @else
                                                &darr;
                                            @endif
                                        @endif
                                    </th>
                                    <th class="px-6 py-3 cursor-pointer" wire:click="sortBy('contact_person')">
                                        Contact Person
                                        @if($sortField === 'contact_person')
                                            @if($sortDirection === 'asc')
                                                &uarr;
                                            @else
                                                &darr;
                                            @endif
                                        @endif
                                    </th>
                                    <th class="px-6 py-3 cursor-pointer" wire:click="sortBy('position')">
                                        Position
                                        @if($sortField === 'position')
                                            @if($sortDirection === 'asc')
                                                &uarr;
                                            @else
                                                &darr;
                                            @endif
                                        @endif
                                    </th>
                                    <th class="px-6 py-3 cursor-pointer" wire:click="sortBy('mobile_no')">
                                        Mobile No.
                                        @if($sortField === 'mobile_no')
                                            @if($sortDirection === 'asc')
                                                &uarr;
                                            @else
                                                &darr;
                                            @endif
                                        @endif
                                    </th>
                                    <th class="px-6 py-3 cursor-pointer" wire:click="sortBy('telephone_no')">
                                        Telephone No.
                                        @if($sortField === 'telephone_no')
                                            @if($sortDirection === 'asc')
                                                &uarr;
                                            @else
                                                &darr;
                                            @endif
                                        @endif
                                    </th>
                                    <th class="px-6 py-3 cursor-pointer" wire:click="sortBy('email_address')">
                                        Email Address
                                        @if($sortField === 'email_address')
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
                                @foreach ($suppliers as $supplier)
                                    <tr class="hover:bg-gray-100">
                                        <td class="py-2 px-4 border break-words">{{ $supplier->supplier_name }}</td>
                                        <td class="py-2 px-4 border break-words">{{ $supplier->address }}</td>
                                        <td class="py-2 px-4 border break-words">{{ $supplier->items }}</td>
                                        <td class="py-2 px-4 border break-words">{{ $supplier->contact_person }}</td>
                                        <td class="py-2 px-4 border break-words">{{ $supplier->position }}</td>
                                        <td class="py-2 px-4 border break-words">{{ $supplier->mobile_no }}</td>
                                        <td class="py-2 px-4 border break-words">{{ $supplier->telephone_no }}</td>
                                        <td class="py-2 px-4 border break-words">{{ $supplier->email_address }}</td>
                                        <td class="py-2 px-4 border text-center">
                                            <div class="flex justify-center space-x-2">
                                                <button wire:click="openEditModal({{ $supplier->id }})"
                                                    class="text-blue-600 hover:underline">Edit</button>
                                                <button wire:click="openDeleteModal({{ $supplier->id }})"
                                                    class="text-red-600 hover:underline">Delete</button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($suppliers->isEmpty())
                                    <tr>
                                        <td colspan="9" class="text-center py-4">No suppliers found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <div class="mt-4 flex-wrap items-center">
                            <div>
                                {{ $suppliers->links() }}
                            </div>
                        </div>
                    </div>

                    {{-- Add Modal --}}
                    @if ($isAddModalOpen)
                        <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50" wire:ignore>
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-lg w-full">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Add Supplier</h3>
                                    <button wire:click="closeModal"
                                        class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-400">
                                        &#x2715;
                                    </button>
                                </div>
                                <form wire:submit.prevent="saveSupplier">
                                    {{-- ... (keep your form fields here) ... --}}
                                    @include('livewire.supplier-form-fields')
                                    <div class="flex justify-end space-x-2">
                                        <button type="button" wire:click="closeModal"
                                            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">Cancel</button>
                                        <button type="submit"
                                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-400">
                                            Add Supplier
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    {{-- Edit Modal --}}
                    @if ($isEditModalOpen)
                        <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50" wire:ignore>
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-lg w-full">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Edit Supplier</h3>
                                    <button wire:click="closeModal"
                                        class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-400">
                                        &#x2715;
                                    </button>
                                </div>
                                <form wire:submit.prevent="saveSupplier">
                                    {{-- ... (keep your form fields here) ... --}}
                                    @include('livewire.supplier-form-fields')
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

                    {{-- Delete Modal --}}
                    @if ($isDeleteModalOpen)
                        <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50" wire:ignore>
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-lg w-full text-center">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Confirm Deletion</h3>
                                <p class="mt-2 text-gray-600 dark:text-gray-300">Are you sure you want to delete this supplier?
                                    This action cannot be undone.</p>
                                <div class="mt-6 flex justify-center space-x-4">
                                    <button wire:click="closeModal"
                                        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">Cancel</button>
                                    <button wire:click="deleteSupplier"
                                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Delete</button>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection