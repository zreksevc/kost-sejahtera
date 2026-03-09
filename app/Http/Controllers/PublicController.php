<?php

namespace App\Http\Controllers;

use App\Models\Room;

class PublicController extends Controller
{
    public function home()
    {
        $availableRooms = Room::where('status', 'available')->get();
        $allRooms       = Room::all();
        $totalRooms     = $allRooms->count();
        $occupiedRooms  = $allRooms->where('status', 'occupied')->count();
        $availableCount = $allRooms->where('status', 'available')->count();

        return view('public.home', compact(
            'availableRooms', 'totalRooms',
            'occupiedRooms', 'availableCount'
        ));
    }

    public function rooms()
    {
        $rooms = Room::all();
        return view('public.rooms', compact('rooms'));
    }
}
