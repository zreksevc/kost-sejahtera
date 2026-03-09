<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'name', 'type', 'price', 'status',
        'facilities', 'description', 'photo',
    ];

    protected $casts = [
        'price' => 'integer',
    ];

    public function getFacilitiesArrayAttribute(): array
    {
        return json_decode($this->facilities ?? '[]', true) ?? [];
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    public function activeRental()
    {
        return $this->hasOne(Rental::class)->where('status', 'active')->latest();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'available'   => '<span class="badge badge-available">Tersedia</span>',
            'occupied'    => '<span class="badge badge-occupied">Terisi</span>',
            'maintenance' => '<span class="badge badge-maintenance">Maintenance</span>',
            default       => '',
        };
    }
}
