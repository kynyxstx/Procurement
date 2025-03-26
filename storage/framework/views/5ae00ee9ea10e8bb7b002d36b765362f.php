<?php if(session()->has('message')): ?>
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
<?php endif; ?>

<div>
    <?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
        <div>
             <?php $__env->slot('header', null, []); ?> 
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Summary of Items Procured
                </h2>
             <?php $__env->endSlot(); ?>
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="w-full flex justify-between items-center mb-4 pl-10">
                        <div class="flex items-center">
                            <input type="text" wire:model="search" placeholder="Search suppliers..."
                                class="w-full max-w-md p-2 border rounded-md shadow-md mr-2"
                                wire:keydown.enter="performSearch" />
                            <select wire:model="filterItems" wire:change="performSearch"
                                class="w-full p-2 border rounded-md shadow-md mr-2">
                                <option value="">Year</option>
                                <option value="Venue;Meals;Accommodation">2024</option>
                                <option value="Services;Catering;Maintenance">2025</option>
                            </select>
                            <button wire:click="performSearch"
                                class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700">Search</button>
                        </div>
                        <div class="flex flex-col items-start p-10">
                            <button wire:click="openAddModal"
                                class="px-5 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700">
                                Add Supplier
                            </button>
                        </div>
                    </div>

                    <!--Table-->
                    <div class="p-10 w-full overflow-x-auto">
                        <h1 style="font-size: 2em;">List of Items Summary</h1><br>

                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-600 border-collapse"
                            style="table-layout: fixed;">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
                                <tr>
                                    <th class="px-6 py-3">SUPPLIER</th>
                                    <th class="px-6 py-3">ITEM/PROJECT</th>
                                    <th class="px-6 py-3">UNIT COST</th>
                                </tr>
                            </thead>
                    </div>
                    </table>
                    <tbody>
                        <tr class="hover:bg-gray-100">
                            <td class="py-2 px-4 border break-words"></td>
                            <td class="py-2 px-4 border text-center"></td>
                        </tr>
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
</div><?php /**PATH C:\Users\Florence\Desktop\Procurement\Procurement\resources\views/livewire/items-procured-index.blade.php ENDPATH**/ ?>