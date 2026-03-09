@extends('layouts.admin')
@section('title', 'Manajemen Sewa')

@section('content')
<div style="padding:28px 32px;max-width:1400px;margin:0 auto;">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div class="page-header">
            <a href="{{ route('dashboard') }}" style="color:#6B7280;text-decoration:none;font-size:.875rem;">← Kembali</a>
            <h1 class="mt-1">Manajemen Sewa</h1>
            <p>Kelola kontrak sewa aktif dan riwayat penyewaan.</p>
        </div>
        <a href="{{ route('rentals.create') }}" class="btn-kost-primary" style="text-decoration:none;padding:9px 18px;border-radius:8px;">+ Booking Baru</a>
    </div>

    <div class="card-kost">
        <table class="w-100 table-kost">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>PENGHUNI</th>
                    <th>KAMAR</th>
                    <th>MULAI</th>
                    <th>SELESAI</th>
                    <th>DURASI</th>
                    <th>TOTAL</th>
                    <th>STATUS</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rentals as $i => $rental)
                <tr>
                    <td style="color:#6B7280;">{{ $i+1 }}</td>
                    <td style="font-weight:700;">{{ $rental->tenant->name }}</td>
                    <td style="color:#6B7280;">{{ $rental->room->name }}</td>
                    <td>{{ $rental->start_date->format('d M Y') }}</td>
                    <td class="{{ $rental->is_overdue ? 'due-overdue' : ($rental->is_near_due ? 'due-warning' : '') }}">
                        {{ $rental->end_date->format('d M Y') }}
                    </td>
                    <td>{{ $rental->months }} Bulan</td>
                    <td style="font-weight:700;">Rp {{ number_format($rental->total_price,0,',','.') }}</td>
                    <td>
                        <span class="badge-kost {{ $rental->status==='active' ? 'badge-active' : 'badge-alumni' }}">
                            {{ ucfirst($rental->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            @if($rental->status === 'active')
                            <a href="{{ route('rentals.extend', $rental) }}" class="btn-icon" style="background:#DBEAFE;color:#2563EB;" title="Perpanjang">🔄</a>
                            <form method="POST" action="{{ route('rentals.terminate', $rental) }}" onsubmit="return confirm('Akhiri sewa ini?')">
                                @csrf
                                <button type="submit" class="btn-icon" style="background:#FEE2E2;color:#DC2626;" title="Akhiri">⏹️</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center py-5" style="color:#6B7280;">Belum ada data sewa.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
