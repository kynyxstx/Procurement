<?php

namespace App\Livewire;

use App\Models\ItemsProcured;
use Livewire\Component;
use Livewire\WithPagination;

class ItemsProcuredIndex extends Component
    {
    use WithPagination;
    public $itemName = "";
    public $supplier = "";
    public $item_project = "";
    public $unit_cost = "";

    public $search = "";
    public $isEditModalOpen = false;
    public $editSupplierId;
    public $isDeleteModalOpen = false;
    public $deletingSupplierId;
    public $isAddModalOpen = false;
    public $filterItems = '';

    protected $paginationTheme = 'tailwind';
    protected $perPage = 5;
    public function render()
    {
        return view('livewire.items-procured-index');
    }
}
