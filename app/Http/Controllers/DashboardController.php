<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Tenant;
use App\Models\Rental;
use App\Models\Payment;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Room stats
        $totalRooms   = Room::count();
        $occupiedRooms = Room::where('status', 'occupied')->count();
        $availableRooms = Room::where('status', 'available')->count();

        // Tenant stats
        $activeTenants = Tenant::where('status', 'active')->count();

        // Financial - current month
        $now = Carbon::now();
        $totalIncome  = Payment::where('status', 'paid')
            ->whereMonth('paid_date', $now->month)
            ->whereYear('paid_date', $now->year)
            ->sum('amount');

        $totalExpense = Expense::whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->sum('amount');

        // All-time totals for display
        $allIncome  = Payment::where('status', 'paid')->sum('amount');
        $allExpense = Expense::sum('amount');

        // Due alerts: rentals ending within 7 days
        $dueAlerts = Rental::with(['tenant', 'room'])
            ->where('status', 'active')
            ->whereBetween('end_date', [
                Carbon::today(),
                Carbon::today()->addDays(7),
            ])
            ->orderBy('end_date')
            ->get();

        // Overdue rentals
        $overdueRentals = Rental::with(['tenant', 'room'])
            ->where('status', 'active')
            ->where('end_date', '<', Carbon::today())
            ->get();

        // Monthly chart data (last 6 months)
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $inc = Payment::where('status', 'paid')
                ->whereMonth('paid_date', $month->month)
                ->whereYear('paid_date', $month->year)
                ->sum('amount');
            $exp = Expense::whereMonth('date', $month->month)
                ->whereYear('date', $month->year)
                ->sum('amount');
            $chartData[] = [
                'month'   => $month->format('M'),
                'income'  => $inc,
                'expense' => $exp,
                'profit'  => $inc - $exp,
            ];
        }

        return view('admin.dashboard.index', compact(
            'totalRooms', 'occupiedRooms', 'availableRooms',
            'activeTenants', 'allIncome', 'allExpense',
            'dueAlerts', 'overdueRentals', 'chartData'
        ));
    }
}
