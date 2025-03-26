<div class="flex flex-col items-center p-10">

    <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
        <div class="flex justify-center">
            <div class="mt-4 px-6 py-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 dark:border-green-800 w-full max-w-md flex items-center"
                role="alert">
                <svg class="flex-shrink-0 inline w-5 h-5 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                </svg>
                <span><?php echo e(session('message')); ?></span>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- Search Input, Search Button, tsaka Filter -->
    <div class="w-full flex justify-between items-center mb-4 pl-10">
        <!-- <div class="flex items-center"> Itong part na to maglalapit sila, if enable get it end hanggang search button to -->
        <select wire:model="filterSupplier" wire:change="performSearch" class="p-2 border rounded-md shadow-md mr-2"
            style="width: 300px;">
            <option value="" style="color: #ffffff; font-weight: bold; background-color: #636363;">
                All Supplier</option>
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
            class="w-full max-w-md p-2 border rounded-md shadow-md mr-2" />
        <button wire:click="performSearch"
            class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700">Search</button>
        <div class="flex flex-col items-start p-10">
            <button wire:click="openAddModal" class="px-5 py-2 bg-green-500 text-white rounded-lg hover:bg-green-700">
                Add Supplier
            </button>
        </div>
    </div>

    <!-- Supplier Table -->
    <div class="p-10 w-full overflow-x-auto">
        <h1 style="font-size: 2em;">Suppliers List</h1><br>

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
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-gray-100">
                        <td class="py-2 px-4 border break-words"><?php echo e($supplier->supplier_name); ?></td>
                        <td class="py-2 px-4 border break-words"><?php echo e($supplier->address); ?></td>
                        <td class="py-2 px-4 border break-words"><?php echo e($supplier->items); ?></td>
                        <td class="py-2 px-4 border break-words"><?php echo e($supplier->contact_person); ?></td>
                        <td class="py-2 px-4 border break-words"><?php echo e($supplier->position); ?></td>
                        <td class="py-2 px-4 border break-words"><?php echo e($supplier->mobile_no); ?></td>
                        <td class="py-2 px-4 border break-words"><?php echo e($supplier->telephone_no); ?></td>
                        <td class="py-2 px-4 border break-words"><?php echo e($supplier->email_address); ?></td>
                        <td class="py-2 px-4 border text-center">
                            <div class="flex justify-center space-x-2">
                                <button wire:click="openEditModal(<?php echo e($supplier->id); ?>)"
                                    class="text-blue-600 hover:underline">Edit
                                </button>
                                <button wire:click="openDeleteModal(<?php echo e($supplier->id); ?>)"
                                    class="text-red-600 hover:underline">Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </tbody>
        </table>
        <div class="mt-4 flex-wrap items-center">
            <div>
                <?php echo e($suppliers->links()); ?>

            </div>
        </div>
    </div>

    <!--[if BLOCK]><![endif]--><?php if($isAddModalOpen): ?>
        <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50" wire:ignore>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-lg w-full">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                        Add Supplier
                    </h3>
                    <button wire:click="closeModal" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-400">
                        &#x2715;
                    </button>
                </div>
                <div>
                    <form wire:submit.prevent="saveSupplier">
                        <div class="mb-2">
                            <label for="supplier_name"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Supplier
                                Name</label>
                            <input wire:model="supplier_name" type="text" id="supplier_name"
                                class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                placeholder="Enter Supplier Name" required />
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['supplier_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-sm"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <div class="mb-2">
                            <label for="address"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Address</label>
                            <input wire:model="address" type="text" id="address"
                                class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                placeholder="Enter Address" required />
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-sm"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <div class="mb-2">
                            <label for="items" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Items
                                Supplied</label>
                            <textarea wire:model="items" id="items"
                                class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                placeholder="Enter Items" required></textarea>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['items'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-sm"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <div class="mb-2">
                            <label for="contact_person"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contact
                                Person</label>
                            <input wire:model="contact_person" type="text" id="contact_person"
                                class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                placeholder="Enter Contact Person" required />
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['contact_person'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-sm"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <div class="mb-2">
                            <label for="position"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Position</label>
                            <input wire:model="position" type="text" id="position"
                                class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                placeholder="Enter Position" required />
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['position'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-sm"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <div class="mb-2">
                            <label for="mobile_no"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mobile
                                No.</label>
                            <input wire:model="mobile_no" type="text" id="mobile_no"
                                class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                placeholder="Enter Mobile No." />
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['mobile_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-sm"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <div class="mb-2">
                            <label for="telephone_no"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Telephone
                                No.</label>
                            <input wire:model="telephone_no" type="text" id="telephone_no"
                                class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                placeholder="Enter Telephone No." />
                        </div>
                        <div class="mb-2">
                            <label for="email_address"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email
                                Address</label>
                            <input wire:model="email_address" type="email" id="email_address"
                                class="w-full p-2 border rounded-md dark:bg-gray-700 dark:text-gray-200"
                                placeholder="Enter Email Address" />
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['email_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-sm"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <div class="flex justify-end space-x-2">
                            <button type="button" wire:click="closeModal"
                                class="px-4 py-2 text-white bg-gray-600 rounded-lg hover:bg-gray-700">Cancel</button>
                            <button type="submit"
                                class="px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-400">
                                Add Supplier
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!--[if BLOCK]><![endif]--><?php if($isEditModalOpen): ?>
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
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Supplier
                                Name</label>
                            <input wire:model="supplier_name" id="editSupplierName" type="text"
                                placeholder="Enter Supplier Name"
                                class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" />
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['supplier_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        <div class="mb-4">
                            <label for="editAddress"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                            <input wire:model="address" id="editAddress" type="text" placeholder="Enter Address"
                                class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" />
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        <div class="mb-4">
                            <label for="editItems"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Items</label>
                            <textarea wire:model="items" id="editItems" placeholder="Enter Items"
                                class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"></textarea>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['items'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        <div class="mb-4">
                            <label for="editContactPerson"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contact
                                Person</label>
                            <input wire:model="contact_person" id="editContactPerson" type="text"
                                placeholder="Enter Contact Person"
                                class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" />
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['contact_person'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        <div class="mb-4">
                            <label for="editPosition"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Position</label>
                            <input wire:model="position" id="editPosition" type="text" placeholder="Enter Position"
                                class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" />
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['position'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        <div class="mb-4">
                            <label for="editMobileNo"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mobile
                                No.</label>
                            <input wire:model="mobile_no" id="editMobileNo" type="text" placeholder="Enter Mobile No."
                                class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" />
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['mobile_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        <div class="mb-4">
                            <label for="editTelephoneNo"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Telephone
                                No.</label>
                            <input wire:model="telephone_no" id="editTelephoneNo" type="text"
                                placeholder="Enter Telephone No."
                                class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" />
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['telephone_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        <div class="mb-4">
                            <label for="editEmailAddress"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email
                                Address</label>
                            <input wire:model="email_address" id="editEmailAddress" type="email"
                                placeholder="Enter Email Address"
                                class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" />
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['email_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        <div class="flex justify-end space-x-2">
                            <button type="button" wire:click="closeModal"
                                class="px-4 py-2 text-white bg-gray-600 rounded-lg hover:bg-gray-700"> Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-400">
                                Save Changes </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!--[if BLOCK]><![endif]--><?php if($isDeleteModalOpen): ?>
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
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
        <div class="fixed top-4 right-4 bg-green-200 border border-green-400 text-green-700 px-4 py-3 rounded relative"
            role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline"><?php echo e(session('message')); ?></span>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <?php if(session()->has('error')): ?>
        <div class="fixed top-4 right-4 bg-red-200 border border-red-400 text-red-700 px-4 py-3 rounded relative"
            role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline"><?php echo e(session('error')); ?></span>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
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

</script><?php /**PATH C:\Users\Florence\Desktop\Procurement\Procurement\resources\views/livewire/supplier-directory-index.blade.php ENDPATH**/ ?>