<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Room;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index(Request $request)
    {
        $query = Tenant::with('activeRental.room');
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }
        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        $tenants = $query->latest()->get();
        return view('admin.tenants.index', compact('tenants'));
    }

    public function create()
    {
        $availableRooms = Room::where('status', 'available')->get();
        return view('admin.tenants.create', compact('availableRooms'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:100',
            'phone'             => 'required|string|max:20',
            'emergency_contact' => 'nullable|string|max:20',
            'ktp_number'        => 'nullable|string|max:20',
            'occupation'        => 'nullable|string|max:100',
            'origin_address'    => 'nullable|string',
            'joined_date'       => 'nullable|date',
        ]);
        $data['status']      = 'active';
        $data['joined_date'] = $data['joined_date'] ?? now()->toDateString();

        Tenant::create($data);
        return redirect()->route('tenants.index')->with('success', 'Penghuni berhasil ditambahkan!');
    }

    public function edit(Tenant $tenant)
    {
        return view('admin.tenants.edit', compact('tenant'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:100',
            'phone'             => 'required|string|max:20',
            'emergency_contact' => 'nullable|string|max:20',
            'ktp_number'        => 'nullable|string|max:20',
            'occupation'        => 'nullable|string|max:100',
            'origin_address'    => 'nullable|string',
            'status'            => 'required|in:active,alumni',
            'joined_date'       => 'nullable|date',
        ]);
        $tenant->update($data);
        return redirect()->route('tenants.index')->with('success', 'Data penghuni berhasil diperbarui!');
    }

    public function destroy(Tenant $tenant)
    {
        if ($tenant->activeRental) {
            return back()->with('error', 'Penghuni yang masih aktif sewa tidak bisa dihapus!');
        }
        $tenant->delete();
        return redirect()->route('tenants.index')->with('success', 'Penghuni berhasil dihapus!');
    }

    public function checkout(Tenant $tenant)
    {
        $tenant->update(['status' => 'alumni']);
        if ($rental = $tenant->activeRental) {
            $rental->update(['status' => 'ended']);
            $rental->room->update(['status' => 'available']);
        }
        return redirect()->route('tenants.index')->with('success', "{$tenant->name} berhasil di-checkout!");
    }
}
