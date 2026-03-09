<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Room;
use App\Models\Tenant;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RentalController extends Controller
{
    public function index()
    {
        $rentals = Rental::with(['tenant', 'room'])
            ->orderBy('status')
            ->latest()
            ->get();
        return view('admin.rentals.index', compact('rentals'));
    }

    public function create()
    {
        $availableRooms = Room::where('status', 'available')->get();
        $activeTenants  = Tenant::where('status', 'active')
            ->whereDoesntHave('rentals', fn($q) => $q->where('status', 'active'))
            ->get();
        return view('admin.rentals.create', compact('availableRooms', 'activeTenants'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tenant_id'  => 'required|exists:tenants,id',
            'room_id'    => 'required|exists:rooms,id',
            'start_date' => 'required|date',
            'months'     => 'required|integer|min:1|max:24',
            'notes'      => 'nullable|string',
        ]);

        $room      = Room::findOrFail($data['room_id']);
        $startDate = Carbon::parse($data['start_date']);
        $endDate   = $startDate->copy()->addMonths($data['months']);
        $total     = $room->price * $data['months'];

        $rental = Rental::create([
            'tenant_id'   => $data['tenant_id'],
            'room_id'     => $data['room_id'],
            'start_date'  => $startDate,
            'end_date'    => $endDate,
            'months'      => $data['months'],
            'total_price' => $total,
            'status'      => 'active',
            'notes'       => $data['notes'] ?? null,
        ]);

        // Create payment record
        Payment::create([
            'rental_id'      => $rental->id,
            'tenant_id'      => $data['tenant_id'],
            'room_id'        => $data['room_id'],
            'invoice_number' => Payment::generateInvoiceNumber(),
            'amount'         => $total,
            'due_date'       => $endDate,
            'paid_date'      => now()->toDateString(),
            'status'         => 'paid',
            'payment_method' => $request->payment_method ?? 'Transfer Bank',
        ]);

        // Update room status
        $room->update(['status' => 'occupied']);

        return redirect()->route('rentals.index')
            ->with('success', 'Booking berhasil! Kamar ' . $room->name . ' telah disewa.');
    }

    public function extend(Request $request, Rental $rental)
    {
        $request->validate(['months' => 'required|integer|min:1|max:24']);

        $months  = (int) $request->months;
        $newEnd  = Carbon::parse($rental->end_date)->addMonths($months);
        $total   = $rental->room->price * $months;

        $rental->update([
            'end_date' => $newEnd,
            'months'   => $rental->months + $months,
            'total_price' => $rental->total_price + $total,
        ]);

        Payment::create([
            'rental_id'      => $rental->id,
            'tenant_id'      => $rental->tenant_id,
            'room_id'        => $rental->room_id,
            'invoice_number' => Payment::generateInvoiceNumber(),
            'amount'         => $total,
            'due_date'       => $newEnd,
            'paid_date'      => now()->toDateString(),
            'status'         => 'paid',
            'payment_method' => $request->payment_method ?? 'Transfer Bank',
        ]);

        return redirect()->back()
            ->with('success', "Sewa berhasil diperpanjang {$months} bulan hingga " . $newEnd->format('d M Y'));
    }

    public function showExtend(Rental $rental)
    {
        $rental->load(['tenant', 'room']);
        return view('admin.rentals.extend', compact('rental'));
    }

    public function terminate(Rental $rental)
    {
        $rental->update(['status' => 'terminated']);
        $rental->room->update(['status' => 'available']);
        $rental->tenant->update(['status' => 'alumni']);
        return redirect()->route('rentals.index')
            ->with('success', 'Sewa berhasil diakhiri.');
    }
}
