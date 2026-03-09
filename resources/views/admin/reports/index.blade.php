@extends('layouts.admin')
@section('title', 'Laporan Keuangan')

@section('content')
<div style="padding:28px 32px;max-width:1400px;margin:0 auto;">

    <div class="d-flex justify-content-between align-items-start mb-4">
        <div class="page-header">
            <a href="{{ route('dashboard') }}" style="color:#6B7280;text-decoration:none;font-size:.875rem;">← Kembali</a>
            <h1 class="mt-1">Laporan Keuangan</h1>
            <p>Ringkasan pemasukan, pengeluaran dan laba bersih.</p>
        </div>
        <form method="GET" class="d-flex gap-2 align-items-center">
            <select name="year" onchange="this.form.submit()" class="form-select" style="width:auto;border-radius:8px;font-size:.875rem;font-weight:600;">
                @for($y = date('Y'); $y >= date('Y')-3; $y--)
                <option value="{{ $y }}" {{ $year==$y?'selected':'' }}>Tahun {{ $y }}</option>
                @endfor
            </select>
            <a href="{{ route('payments.export') }}" class="btn d-inline-flex align-items-center gap-2" style="background:#16A34A;color:#fff;border-radius:8px;padding:9px 18px;font-weight:700;text-decoration:none;border:none;">
                📊 Export CSV
            </a>
        </form>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card-kost" style="border-top:3px solid #16A34A;">
                <div style="font-size:.65rem;font-weight:700;text-transform:uppercase;color:#6B7280;margin-bottom:6px;">TOTAL PEMASUKAN {{ $year }}</div>
                <div style="font-size:1.75rem;font-weight:900;color:#16A34A;">Rp {{ number_format($totalIncome,0,',','.') }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-kost" style="border-top:3px solid #DC2626;">
                <div style="font-size:.65rem;font-weight:700;text-transform:uppercase;color:#6B7280;margin-bottom:6px;">TOTAL PENGELUARAN {{ $year }}</div>
                <div style="font-size:1.75rem;font-weight:900;color:#DC2626;">Rp {{ number_format($totalExpense,0,',','.') }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-kost" style="background:#E6B800;">
                <div style="font-size:.65rem;font-weight:700;text-transform:uppercase;color:#7A5900;margin-bottom:6px;">LABA BERSIH {{ $year }}</div>
                <div style="font-size:1.75rem;font-weight:900;">Rp {{ number_format($netProfit,0,',','.') }}</div>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card-kost">
                <div style="font-weight:800;font-size:1rem;margin-bottom:16px;">📊 Tren Bulanan {{ $year }}</div>
                <canvas id="annualChart" height="100"></canvas>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card-kost">
                <div style="font-weight:800;font-size:1rem;margin-bottom:16px;">🥧 Pengeluaran per Kategori</div>
                <canvas id="expensePieChart" height="220"></canvas>
                <div style="margin-top:14px;">
                    @foreach($expenseByCategory as $ec)
                    <div class="d-flex justify-content-between align-items-center mb-1" style="font-size:.8rem;">
                        <span>{{ $ec->category }}</span>
                        <span style="font-weight:700;">Rp {{ number_format($ec->total,0,',','.') }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Monthly table --}}
    <div class="card-kost">
        <div style="font-weight:800;font-size:1rem;margin-bottom:16px;">📅 Detail Bulanan {{ $year }}</div>
        <table class="w-100 table-kost">
            <thead>
                <tr>
                    <th>BULAN</th>
                    <th>PEMASUKAN</th>
                    <th>PENGELUARAN</th>
                    <th>LABA BERSIH</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                @foreach($monthlyData as $d)
                <tr>
                    <td style="font-weight:700;">{{ $d['month'] }}</td>
                    <td style="color:#16A34A;font-weight:600;">Rp {{ number_format($d['income'],0,',','.') }}</td>
                    <td style="color:#DC2626;font-weight:600;">Rp {{ number_format($d['expense'],0,',','.') }}</td>
                    <td style="font-weight:800;">Rp {{ number_format($d['profit'],0,',','.') }}</td>
                    <td>
                        @if($d['profit'] > 0)
                        <span class="badge-kost badge-paid">✅ Positif</span>
                        @elseif($d['profit'] < 0)
                        <span class="badge-kost badge-unpaid">❌ Negatif</span>
                        @else
                        <span class="badge-kost badge-alumni">— Break Even</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
const md = @json($monthlyData);
const labels = md.map(d => d.month);

new Chart(document.getElementById('annualChart'), {
    type: 'bar',
    data: {
        labels,
        datasets: [
            { label: 'Pemasukan', data: md.map(d=>d.income), backgroundColor: '#E6B800', borderRadius: 6 },
            { label: 'Pengeluaran', data: md.map(d=>d.expense), backgroundColor: '#DC2626', borderRadius: 6 },
            { label: 'Laba', data: md.map(d=>d.profit), backgroundColor: '#16A34A', borderRadius: 6, type: 'line', borderColor: '#16A34A', fill: false }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top' } },
        scales: { y: { ticks: { callback: v => 'Rp ' + (v/1000000).toFixed(1) + 'jt' } } }
    }
});

@if($expenseByCategory->count() > 0)
const cats = @json($expenseByCategory);
new Chart(document.getElementById('expensePieChart'), {
    type: 'doughnut',
    data: {
        labels: cats.map(c => c.category),
        datasets: [{ data: cats.map(c => c.total), backgroundColor: ['#E6B800','#2563EB','#16A34A','#DC2626','#9333EA'], borderWidth: 0 }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { font: { size: 11 } } } }, cutout: '55%' }
});
@endif
</script>
@endpush
