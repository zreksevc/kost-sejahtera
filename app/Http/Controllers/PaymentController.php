<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Rental;
use App\Models\Tenant;
use App\Models\Room;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['tenant', 'room', 'rental']);

        if ($request->search) {
            $query->whereHas('tenant', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
            });
        }
        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $payments = $query->latest()->get();
        return view('admin.payments.index', compact('payments'));
    }

    public function invoice(Payment $payment)
    {
        $payment->load(['tenant', 'room', 'rental']);
        $admin = auth()->user();
        return view('admin.payments.invoice', compact('payment', 'admin'));
    }

    public function downloadPdf(Payment $payment)
    {
        $payment->load(['tenant', 'room', 'rental']);
        $admin = auth()->user();

        $pdf = Pdf::loadView('admin.payments.invoice-pdf', compact('payment', 'admin'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('invoice-' . $payment->invoice_number . '.pdf');
    }

    public function markPaid(Payment $payment)
    {
        $payment->update([
            'status'    => 'paid',
            'paid_date' => now()->toDateString(),
        ]);
        return back()->with('success', 'Pembayaran berhasil dikonfirmasi!');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Transaksi berhasil dihapus!');
    }

    public function exportCsv()
    {
        $payments = Payment::with(['tenant', 'room'])->latest()->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="riwayat-transaksi-' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($payments) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['NO', 'INVOICE', 'NAMA PENGHUNI', 'KAMAR', 'JUMLAH', 'JATUH TEMPO', 'TANGGAL BAYAR', 'STATUS', 'METODE']);
            foreach ($payments as $i => $p) {
                fputcsv($file, [
                    $i + 1,
                    $p->invoice_number,
                    $p->tenant?->name ?? '-',
                    $p->room?->name ?? '-',
                    $p->amount,
                    $p->due_date?->format('d/m/Y') ?? '-',
                    $p->paid_date?->format('d/m/Y') ?? '-',
                    $p->status === 'paid' ? 'Lunas' : 'Belum Lunas',
                    $p->payment_method ?? '-',
                ]);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function create()
    {
        $tenants = Tenant::where('status', 'active')->with('activeRental.room')->get();
        return view('admin.payments.create', compact('tenants'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rental_id'      => 'required|exists:rentals,id',
            'amount'         => 'required|integer|min:1',
            'due_date'       => 'required|date',
            'payment_method' => 'nullable|string',
        ]);

        $rental = Rental::with(['tenant', 'room'])->findOrFail($request->rental_id);

        Payment::create([
            'rental_id'      => $rental->id,
            'tenant_id'      => $rental->tenant_id,
            'room_id'        => $rental->room_id,
            'invoice_number' => Payment::generateInvoiceNumber(),
            'amount'         => $request->amount,
            'due_date'       => $request->due_date,
            'paid_date'      => now()->toDateString(),
            'status'         => 'paid',
            'payment_method' => $request->payment_method ?? 'Transfer Bank',
        ]);

        return redirect()->route('payments.index')->with('success', 'Pembayaran berhasil dicatat!');
    }
}
