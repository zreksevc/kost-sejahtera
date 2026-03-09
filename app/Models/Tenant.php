<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $fillable = [
        'name', 'phone', 'emergency_contact',
        'ktp_number', 'occupation', 'origin_address',
        'photo', 'status', 'joined_date',
    ];

    protected $casts = [
        'joined_date' => 'date',
    ];

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

    public function getWhatsappLinkAttribute(): string
    {
        $number = preg_replace('/^0/', '62', $this->phone);
        $number = preg_replace('/[^0-9]/', '', $number);
        return "https://wa.me/{$number}";
    }
}
