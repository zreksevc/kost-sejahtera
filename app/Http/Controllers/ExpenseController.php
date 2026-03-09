<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::query();
        if ($request->month) {
            $query->whereMonth('date', $request->month);
        }
        if ($request->year) {
            $query->whereYear('date', $request->year);
        }
        if ($request->category && $request->category !== 'all') {
            $query->where('category', $request->category);
        }
        $expenses = $query->latest('date')->get();
        $totalExpense = $expenses->sum('amount');
        return view('admin.expenses.index', compact('expenses', 'totalExpense'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date'        => 'required|date',
            'description' => 'required|string|max:255',
            'category'    => 'required|in:Utilitas,Maintenance,Kebersihan,Keamanan,Lain-lain',
            'amount'      => 'required|integer|min:1',
            'notes'       => 'nullable|string',
        ]);
        Expense::create($data);
        return redirect()->back()->with('success', 'Pengeluaran berhasil dicatat!');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->back()->with('success', 'Pengeluaran berhasil dihapus!');
    }
}
