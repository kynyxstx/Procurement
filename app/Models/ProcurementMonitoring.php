<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcurementMonitoring extends Model
{

    use HasFactory;
    protected $table = 'procurement_monitoring';
    protected $fillable = [
        'pr_no',
        'title',
        'processor',
        'supplier',
        'end-user',
        'status',
        'date_endorsement'
    ];
    protected $casts = [
        'date_endorsement' => 'datetime',
    ];
    public function getDateEndorsementAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
    }
    public function setDateEndorsementAttribute($value)
    {
        $this->attributes['date_endorsement'] = \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
    }
    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
    }
}
