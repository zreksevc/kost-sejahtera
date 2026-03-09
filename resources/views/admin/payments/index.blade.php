@extends('layouts.admin')
@section('title', 'Riwayat Transaksi')

@section('content')
<div style="padding:28px 32px;max-width:1400px;margin:0 auto;">

    <div class="d-flex justify-content-between align-items-start mb-4">
        <div class="page-header">
            <a href="{{ route('dashboard') }}" style="color:#6B7280;text-decoration:none;font-size:.875rem;">← Kembali</a>
            <h1 class="mt-1">Riwayat Transaksi</h1>
            <p>Pantau pembayaran sewa dan cetak invoice.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('payments.export') }}" class="btn d-inline-flex align-items-center gap-2" style="background:#16A34A;color:#fff;border-radius:8px;padding:9px 18px;font-weight:700;text-decoration:none;border:none;">
                📊 Export Excel (CSV)
            </a>
            <a href="{{ route('rentals.create') }}" class="btn-kost-primary" style="text-decoration:none;padding:9px 18px;border-radius:8px;">+ Booking Baru</a>
        </div>
    </div>

    {{-- Search --}}
    <form method="GET" class="d-flex gap-2 mb-4">
        <div class="d-flex align-items-center gap-2 flex-grow-1" style="background:#fff;border:1.5px solid #E5E7EB;border-radius:8px;padding:8px 14px;">
            🔍 <input name="search" value="{{ request('search') }}" placeholder="Cari nama penghuni..." style="border:none;outline:none;flex:1;font-size:.875rem;background:transparent;font-family:inherit;">
        </div>
        <select name="status" onchange="this.form.submit()" class="form-select" style="width:auto;border-radius:8px;font-size:.875rem;font-weight:600;">
            <option value="all" {{ request('status','all')==='all'?'selected':'' }}>Semua Status</option>
            <option value="paid" {{ request('status')==='paid'?'selected':'' }}>Lunas</option>
            <option value="unpaid" {{ request('status')==='unpaid'?'selected':'' }}>Belum Lunas</option>
        </select>
    </form>

    <div class="card-kost">
        <table class="w-100 table-kost">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>NAMA PENGHUNI</th>
                    <th>KAMAR</th>
                    <th>JATUH TEMPO</th>
                    <th>TOTAL BAYAR</th>
                    <th>STATUS</th>
                    <th>HAPUS</th>
                    <th>TAGIHAN & NOTA</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $i => $payment)
                <tr style="background:{{ $i%2===0 ? '#fff' : '#FAFAFA' }};">
                    <td style="color:#6B7280;">{{ $i+1 }}</td>
                    <td style="font-weight:700;">{{ $payment->tenant?->name ?? '-' }}</td>
                    <td style="color:#6B7280;">{{ $payment->room?->name ?? '-' }}</td>
                    <td>{{ $payment->due_date?->format('d M Y') }}</td>
                    <td style="font-weight:800;">Rp {{ number_format($payment->amount,0,',','.') }}</td>
                    <td>
                        <span class="badge-kost {{ $payment->status==='paid' ? 'badge-paid' : 'badge-unpaid' }}">
                            {{ $payment->status==='paid' ? 'Lunas' : 'Belum Lunas' }}
                        </span>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('payments.destroy', $payment) }}" onsubmit="return confirm('Hapus transaksi ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-icon" style="background:#FEE2E2;color:#DC2626;">🗑️</button>
                        </form>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            @if($payment->tenant)
                            <a href="https://wa.me/62{{ ltrim($payment->tenant->phone,'0') }}?text={{ urlencode($payment->whatsapp_message) }}" target="_blank" class="btn-icon" style="background:#D1FAE5;color:#16A34A;" title="Tagih via WhatsApp">💬</a>
                            @endif
                            <a href="{{ route('payments.pdf', $payment) }}" target="_blank" class="btn-icon" style="background:#FEF3C7;color:#92400E;" title="Download Invoice PDF">🖨️</a>
                            @if($payment->status !== 'paid')
                            <form method="POST" action="{{ route('payments.paid', $payment) }}">
                                @csrf
                                <button type="submit" class="btn-icon" style="background:#D1FAE5;color:#16A34A;" title="Tandai Lunas">✓</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-5" style="color:#6B7280;">Belum ada riwayat transaksi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
