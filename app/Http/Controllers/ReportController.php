<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Expense;
use App\Models\Room;
use App\Models\Tenant;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $year = request('year', now()->year);

        // Monthly data for 12 months
        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $income  = Payment::where('status', 'paid')
                ->whereMonth('paid_date', $m)->whereYear('paid_date', $year)
                ->sum('amount');
            $expense = Expense::whereMonth('date', $m)->whereYear('date', $year)
                ->sum('amount');
            $monthlyData[] = [
                'month'   => Carbon::create($year, $m)->format('M'),
                'income'  => $income,
                'expense' => $expense,
                'profit'  => $income - $expense,
            ];
        }

        $totalIncome  = collect($monthlyData)->sum('income');
        $totalExpense = collect($monthlyData)->sum('expense');
        $netProfit    = $totalIncome - $totalExpense;

        // Expense by category
        $expenseByCategory = Expense::whereYear('date', $year)
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get();

        return view('admin.reports.index', compact(
            'monthlyData', 'totalIncome', 'totalExpense',
            'netProfit', 'expenseByCategory', 'year'
        ));
    }
}
