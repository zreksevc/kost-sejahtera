@extends('layouts.admin')
@section('title', 'Data Penghuni')

@section('content')
<div style="padding:28px 32px;max-width:1400px;margin:0 auto;">

    <div class="d-flex justify-content-between align-items-start mb-4">
        <div class="page-header">
            <a href="{{ route('dashboard') }}" style="color:#6B7280;text-decoration:none;font-size:.875rem;">← Kembali</a>
            <h1 class="mt-1">Data Penghuni Kost</h1>
            <p>Kelola informasi penyewa aktif dan alumni.</p>
        </div>
        <a href="{{ route('tenants.create') }}" class="btn-kost-primary" style="text-decoration:none;padding:9px 18px;border-radius:8px;">👤+ Tambah Penghuni</a>
    </div>

    {{-- Search --}}
    <div class="card-kost mb-4">
        <form method="GET" class="d-flex gap-2">
            <div class="d-flex align-items-center gap-2 flex-grow-1" style="border:1.5px solid #E5E7EB;border-radius:8px;padding:8px 14px;">
                🔍 <input name="search" value="{{ request('search') }}" placeholder="Cari nama penghuni atau nomor HP..." style="border:none;outline:none;flex:1;font-size:.875rem;background:transparent;font-family:inherit;">
            </div>
            <button type="submit" class="btn-kost-yellow" style="border-radius:8px;padding:9px 20px;">Cari</button>
            <select name="status" onchange="this.form.submit()" class="form-select" style="width:auto;border-radius:8px;font-size:.875rem;font-weight:600;">
                <option value="all" {{ request('status','all')==='all'?'selected':'' }}>Semua Status</option>
                <option value="active" {{ request('status')==='active'?'selected':'' }}>Aktif</option>
                <option value="alumni" {{ request('status')==='alumni'?'selected':'' }}>Alumni</option>
            </select>
        </form>
    </div>

    <div class="card-kost">
        <table class="w-100 table-kost">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>NAMA PENGHUNI</th>
                    <th>NO. HP (WA)</th>
                    <th>KONTAK DARURAT</th>
                    <th>KAMAR</th>
                    <th>STATUS</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tenants as $i => $tenant)
                <tr>
                    <td style="color:#6B7280;">{{ $i + 1 }}</td>
                    <td style="font-weight:700;">{{ $tenant->name }}</td>
                    <td>
                        <span class="d-flex align-items-center gap-2" style="color:#6B7280;">
                            <span style="color:#25D366;">💬</span> {{ $tenant->phone }}
                        </span>
                    </td>
                    <td style="color:#6B7280;">{{ $tenant->emergency_contact ?? '-' }}</td>
                    <td style="color:#6B7280;">
                        @if($tenant->activeRental)
                            <span style="font-weight:600;color:#111827;">{{ $tenant->activeRental->room->name }}</span><br>
                            <span style="font-size:.75rem;">s/d {{ $tenant->activeRental->end_date->format('d M Y') }}</span>
                        @else — @endif
                    </td>
                    <td>
                        <span class="badge-kost {{ $tenant->status === 'active' ? 'badge-active' : 'badge-alumni' }}">
                            {{ $tenant->status === 'active' ? '✓ Aktif' : 'Alumni' }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1 flex-wrap">
                            @if($tenant->status === 'active' && $tenant->activeRental)
                            <a href="{{ route('rentals.extend', $tenant->activeRental) }}" class="btn-icon" style="background:#DBEAFE;color:#2563EB;" title="Perpanjang Sewa">🔄</a>
                            @endif
                            @if($tenant->status === 'active')
                            <form method="POST" action="{{ route('tenants.checkout', $tenant) }}" onsubmit="return confirm('Checkout {{ $tenant->name }}?')">
                                @csrf
                                <button type="submit" class="btn-icon" style="background:#F3F4F6;color:#6B7280;" title="Check Out">↩️</button>
                            </form>
                            @endif
                            <a href="{{ route('tenants.edit', $tenant) }}" class="btn-icon" style="background:#FEF9C3;color:#92400E;" title="Edit">✏️</a>
                            <form method="POST" action="{{ route('tenants.destroy', $tenant) }}" onsubmit="return confirm('Hapus penghuni ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-icon" style="background:#FEE2E2;color:#DC2626;" title="Hapus">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-5" style="color:#6B7280;">Belum ada penghuni terdaftar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
