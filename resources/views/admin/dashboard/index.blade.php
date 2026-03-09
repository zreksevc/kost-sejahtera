@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div style="padding:28px 32px;max-width:1400px;margin:0 auto;">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div class="page-header">
            <h1>Dashboard Owner</h1>
            <p>Ringkasan performa bisnis kost Anda bulan ini.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary btn-sm fw-600" style="border-radius:8px;padding:9px 16px;font-weight:600;">
                📋 Catat Pengeluaran
            </a>
            <a href="{{ route('rentals.create') }}" class="btn-kost-primary d-inline-flex align-items-center gap-2" style="text-decoration:none;padding:9px 18px;border-radius:8px;">
                + Booking Baru
            </a>
        </div>
    </div>

    {{-- Due Alerts --}}
    @if($dueAlerts->count() > 0 || $overdueRentals->count() > 0)
    <div class="alert-kost-due mb-4">
        <div class="fw-800 mb-3" style="color:#92400E;font-size:1rem;">
            🔔 Peringatan Jatuh Tempo
        </div>
        <table class="w-100" style="border-collapse:collapse;">
            <thead>
                <tr style="font-size:.7rem;font-weight:700;text-transform:uppercase;color:#92400E;letter-spacing:.05em;">
                    <th style="padding:6px 10px;text-align:left;">Penghuni</th>
                    <th style="padding:6px 10px;text-align:left;">Kamar</th>
                    <th style="padding:6px 10px;text-align:left;">Jatuh Tempo</th>
                    <th style="padding:6px 10px;text-align:left;">Sisa</th>
                    <th style="padding:6px 10px;text-align:left;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($overdueRentals as $r)
                <tr style="border-top:1px solid rgba(230,184,0,.3);">
                    <td style="padding:10px;font-weight:700;">{{ $r->tenant->name }}</td>
                    <td style="padding:10px;color:#6B7280;">{{ $r->room->name }}</td>
                    <td style="padding:10px;" class="due-overdue">{{ $r->end_date->format('d M Y') }}</td>
                    <td style="padding:10px;" class="due-overdue">Lewat {{ abs($r->days_until_due) }} hari</td>
                    <td style="padding:10px;">
                        <a href="https://wa.me/62{{ ltrim($r->tenant->phone,'0') }}?text={{ urlencode('Halo '.$r->tenant->name.', masa sewa kamar '.$r->room->name.' sudah berakhir. Mohon segera hubungi kami. Terima kasih 🙏') }}" target="_blank" class="btn-kost-yellow btn-sm" style="text-decoration:none;padding:6px 14px;border-radius:20px;font-size:.8rem;">
                            💬 Tagih WA
                        </a>
                    </td>
                </tr>
                @endforeach
                @foreach($dueAlerts as $r)
                <tr style="border-top:1px solid rgba(230,184,0,.3);">
                    <td style="padding:10px;font-weight:700;">{{ $r->tenant->name }}</td>
                    <td style="padding:10px;color:#6B7280;">{{ $r->room->name }}</td>
                    <td style="padding:10px;" class="due-warning">{{ $r->end_date->format('d M Y') }}</td>
                    <td style="padding:10px;" class="due-warning">{{ $r->days_until_due }} hari lagi</td>
                    <td style="padding:10px;">
                        <a href="https://wa.me/62{{ ltrim($r->tenant->phone,'0') }}?text={{ urlencode('Halo '.$r->tenant->name.', pengingat: masa sewa kamar '.$r->room->name.' akan berakhir '.$r->end_date->format('d M Y').'. Mohon perpanjang atau hubungi kami. Terima kasih 🙏') }}" target="_blank" class="btn-kost-yellow btn-sm" style="text-decoration:none;padding:6px 14px;border-radius:20px;font-size:.8rem;">
                            💬 Tagih WA
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Stats Cards Row 1 --}}
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card-kost">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#6B7280;">TOTAL PEMASUKAN</span>
                    <span style="background:#FEF3C7;border-radius:8px;padding:4px 8px;">💰</span>
                </div>
                <div style="font-size:1.75rem;font-weight:900;">Rp {{ number_format($allIncome,0,',','.') }}</div>
                <div style="color:#6B7280;font-size:.8rem;margin-top:4px;">Omzet kotor keseluruhan</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-kost" style="border-top:3px solid #FEE2E2;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#6B7280;">TOTAL PENGELUARAN</span>
                    <span style="background:#FEE2E2;border-radius:8px;padding:4px 8px;">📉</span>
                </div>
                <div style="font-size:1.75rem;font-weight:900;">Rp {{ number_format($allExpense,0,',','.') }}</div>
                <a href="{{ route('expenses.index') }}" style="color:#2563EB;font-size:.85rem;text-decoration:none;font-weight:500;">Lihat Detail →</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-kost" style="background:#E6B800;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#7A5900;">KEUNTUNGAN BERSIH</span>
                    <span style="background:rgba(255,255,255,.3);border-radius:8px;padding:4px 8px;">📈</span>
                </div>
                <div style="font-size:1.75rem;font-weight:900;">Rp {{ number_format($allIncome - $allExpense,0,',','.') }}</div>
                <div style="color:#5A4000;font-size:.85rem;font-weight:600;margin-top:4px;">✅ Cashflow Positif</div>
            </div>
        </div>
    </div>

    {{-- Stats Cards Row 2 --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card-kost d-flex align-items-center gap-3">
                <div style="width:48px;height:48px;background:#F3F4F6;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0;">🚪</div>
                <div>
                    <div style="font-size:2rem;font-weight:900;">{{ $availableRooms }}</div>
                    <div style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#6B7280;">KAMAR KOSONG</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-kost d-flex align-items-center gap-3">
                <div style="width:48px;height:48px;background:#FEF3C7;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0;">🛏️</div>
                <div>
                    <div style="font-size:2rem;font-weight:900;">{{ $occupiedRooms }}</div>
                    <div style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#6B7280;">KAMAR TERISI</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-kost d-flex align-items-center gap-3">
                <div style="width:48px;height:48px;background:#DBEAFE;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0;">👥</div>
                <div>
                    <div style="font-size:2rem;font-weight:900;">{{ $activeTenants }}</div>
                    <div style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#6B7280;">PENGHUNI AKTIF</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card-kost">
                <div style="font-weight:800;font-size:1rem;margin-bottom:20px;">📊 Tren Keuangan 6 Bulan Terakhir</div>
                <canvas id="financialChart" height="100"></canvas>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card-kost">
                <div style="font-weight:800;font-size:1rem;margin-bottom:12px;">🥧 Rasio Okupansi</div>
                <canvas id="occupancyChart" height="200"></canvas>
                <div class="d-flex gap-3 justify-content-center mt-3">
                    <div class="d-flex align-items-center gap-2" style="font-size:.8rem;">
                        <div style="width:12px;height:12px;background:#E6B800;border-radius:3px;"></div> Terisi ({{ $occupiedRooms }})
                    </div>
                    <div class="d-flex align-items-center gap-2" style="font-size:.8rem;">
                        <div style="width:12px;height:12px;background:#E5E7EB;border-radius:3px;"></div> Kosong ({{ $availableRooms }})
                    </div>
                </div>
                <div style="text-align:center;margin-top:12px;">
                    <div style="font-size:2rem;font-weight:900;">{{ $totalRooms > 0 ? round($occupiedRooms/$totalRooms*100) : 0 }}%</div>
                    <div style="font-size:.8rem;color:#6B7280;">Tingkat Hunian</div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
// Chart data from server
const chartData = @json($chartData);
const labels  = chartData.map(d => d.month);
const incomes  = chartData.map(d => d.income);
const expenses = chartData.map(d => d.expense);

// Financial Chart
new Chart(document.getElementById('financialChart'), {
    type: 'line',
    data: {
        labels,
        datasets: [
            { label: 'Pemasukan', data: incomes,  borderColor: '#E6B800', backgroundColor: 'rgba(230,184,0,.1)', borderWidth: 3, fill: true, tension: .4, pointRadius: 4 },
            { label: 'Pengeluaran', data: expenses, borderColor: '#DC2626', backgroundColor: 'rgba(220,38,38,.05)', borderWidth: 2, fill: true, tension: .4, pointRadius: 4 }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top' } },
        scales: {
            y: { ticks: { callback: v => 'Rp ' + (v/1000000).toFixed(1) + 'jt' }, grid: { color: '#F3F4F6' } },
            x: { grid: { display: false } }
        }
    }
});

// Occupancy Chart
new Chart(document.getElementById('occupancyChart'), {
    type: 'doughnut',
    data: {
        labels: ['Terisi', 'Kosong'],
        datasets: [{ data: [{{ $occupiedRooms }}, {{ $availableRooms }}], backgroundColor: ['#E6B800', '#E5E7EB'], borderWidth: 0, hoverOffset: 4 }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, cutout: '65%' }
});
</script>
@endpush
