<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierDirectory extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_name',
        'address',
        'items',
        'contact_person',
        'position',
        'mobile_no',
        'telephone_no',
        'email_address'
    ];

}
