<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemsProcured extends Model
{
    use HasFactory;
    protected $fillable = ['supplier', 'item_project', 'unit_cost', 'year', 'month'];
    protected $table = 'items_procured';
}