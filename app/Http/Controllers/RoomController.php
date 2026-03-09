<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::query();
        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }
        if ($request->type && $request->type !== 'all') {
            $query->where('type', $request->type);
        }
        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        $rooms = $query->latest()->get();
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('admin.rooms.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'type'        => 'required|in:Reguler,VIP,VVIP',
            'price'       => 'required|integer|min:0',
            'status'      => 'required|in:available,occupied,maintenance',
            'facilities'  => 'nullable|array',
            'description' => 'nullable|string',
            'photo'       => 'nullable|image|max:2048',
        ]);

        $data['facilities'] = json_encode($request->facilities ?? []);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('rooms', 'public');
        }

        Room::create($data);
        return redirect()->route('rooms.index')->with('success', 'Kamar berhasil ditambahkan!');
    }

    public function edit(Room $room)
    {
        return view('admin.rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'type'        => 'required|in:Reguler,VIP,VVIP',
            'price'       => 'required|integer|min:0',
            'status'      => 'required|in:available,occupied,maintenance',
            'facilities'  => 'nullable|array',
            'description' => 'nullable|string',
            'photo'       => 'nullable|image|max:2048',
        ]);

        $data['facilities'] = json_encode($request->facilities ?? []);

        if ($request->hasFile('photo')) {
            if ($room->photo) Storage::disk('public')->delete($room->photo);
            $data['photo'] = $request->file('photo')->store('rooms', 'public');
        }

        $room->update($data);
        return redirect()->route('rooms.index')->with('success', 'Kamar berhasil diperbarui!');
    }

    public function destroy(Room $room)
    {
        if ($room->status === 'occupied') {
            return back()->with('error', 'Kamar yang sedang terisi tidak bisa dihapus!');
        }
        if ($room->photo) Storage::disk('public')->delete($room->photo);
        $room->delete();
        return redirect()->route('rooms.index')->with('success', 'Kamar berhasil dihapus!');
    }
}
