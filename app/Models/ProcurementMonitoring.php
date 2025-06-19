<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; // Make sure Carbon is imported for the mutator

class ProcurementMonitoring extends Model
{
    use HasFactory;

    protected $table = 'procurement_monitoring';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pr_no',
        'title',
        'processor',
        'supplier',
        'end_user',
        'status',
        'date_endorsement',
        'specific_notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_endorsement' => 'datetime',
    ];

    /**
     * Mutator to set the date_endorsement attribute.
     * Converts empty strings to null before saving to the database.
     */
    public function setDateEndorsementAttribute($value)
    {
        // If the value is an empty string, set it to null. Otherwise, use the provided value.
        $this->attributes['date_endorsement'] = !empty($value) ? $value : null;
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }
}