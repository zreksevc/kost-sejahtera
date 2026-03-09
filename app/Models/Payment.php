<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'rental_id', 'tenant_id', 'room_id',
        'invoice_number', 'amount', 'due_date',
        'paid_date', 'status', 'payment_method', 'notes',
    ];

    protected $casts = [
        'due_date'  => 'date',
        'paid_date' => 'date',
        'amount'    => 'integer',
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public static function generateInvoiceNumber(): string
    {
        $latest = self::latest()->first();
        $next   = $latest ? $latest->id + 1 : 1;
        return 'INV-' . date('Ymd') . '-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    public function getWhatsappMessageAttribute(): string
    {
        $tenant = $this->tenant;
        $room   = $this->room;
        $msg    = "Halo {$tenant->name},\n\n";
        $msg   .= "Ini adalah tagihan sewa kamar *{$room->name}* di Kost Sejahtera.\n\n";
        $msg   .= "📋 Invoice: *{$this->invoice_number}*\n";
        $msg   .= "💰 Jumlah: *Rp " . number_format($this->amount, 0, ',', '.') . "*\n";
        $msg   .= "📅 Jatuh Tempo: *" . $this->due_date->format('d M Y') . "*\n\n";
        $msg   .= "Mohon segera melakukan pembayaran. Terima kasih 🙏\n\n";
        $msg   .= "Transfer ke:\n";
        $msg   .= "• BCA: 3274628342 (Admin Kost)\n";
        $msg   .= "• BSI: 234726387\n";
        $msg   .= "• DANA: 0821731731232";
        return $msg;
    }
}
