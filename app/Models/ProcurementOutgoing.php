<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcurementOutgoing extends Model
{
    use HasFactory;
    protected $table = 'procurement_outgoing';
    protected $fillable = [
        'received_date',
        'end_user',
        'pr_no',
        'particulars',
        'amount',
        'creditor',
        'remarks',
        'responsibility',
        'received_by'
    ];
    protected $casts = [
        'received_date' => 'datetime',
    ];

}
