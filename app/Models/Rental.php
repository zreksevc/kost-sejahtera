<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Rental extends Model
{
    protected $fillable = [
        'tenant_id', 'room_id', 'start_date',
        'end_date', 'months', 'total_price',
        'status', 'notes',
    ];

    protected $casts = [
        'start_date'  => 'date',
        'end_date'    => 'date',
        'total_price' => 'integer',
        'months'      => 'integer',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getDaysUntilDueAttribute(): int
    {
        return (int) Carbon::today()->diffInDays($this->end_date, false);
    }

    public function getIsNearDueAttribute(): bool
    {
        $days = $this->days_until_due;
        return $days >= 0 && $days <= 7;
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->days_until_due < 0;
    }
}
