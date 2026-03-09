@extends('layouts.admin')
@section('title', 'Manajemen Kamar')

@section('content')
<div style="padding:28px 32px;max-width:1400px;margin:0 auto;">

    <div class="d-flex justify-content-between align-items-start mb-4">
        <div class="page-header">
            <a href="{{ route('dashboard') }}" style="color:#6B7280;text-decoration:none;font-size:.875rem;">← Kembali</a>
            <h1 class="mt-1">Manajemen Kamar</h1>
            <p>Kelola unit kamar, harga, dan fasilitas.</p>
        </div>
        <a href="{{ route('rooms.create') }}" class="btn-kost-primary" style="text-decoration:none;padding:9px 18px;border-radius:8px;">+ Tambah Kamar</a>
    </div>

    {{-- Filters --}}
    <form method="GET" class="d-flex gap-2 mb-4 flex-wrap">
        <div class="d-flex align-items-center gap-2 flex-grow-1" style="background:#fff;border:1.5px solid #E5E7EB;border-radius:10px;padding:8px 16px;">
            🔍 <input name="search" value="{{ request('search') }}" placeholder="Cari nama kamar..." style="border:none;outline:none;flex:1;font-size:.875rem;background:transparent;font-family:inherit;">
        </div>
        @foreach(['all'=>'Semua','VVIP'=>'VVIP','VIP'=>'VIP','Reguler'=>'Reguler'] as $val=>$label)
        <button type="submit" name="type" value="{{ $val }}" style="padding:9px 16px;border-radius:8px;border:1.5px solid {{ request('type',$val)===$val&&request('type') ? '#111827' : '#E5E7EB' }};background:{{ request('type')===$val ? '#111827' : '#fff' }};color:{{ request('type')===$val ? '#fff' : '#111827' }};font-weight:600;cursor:pointer;font-family:inherit;font-size:.875rem;">{{ $label }}</button>
        @endforeach
    </form>

    {{-- Room Grid --}}
    <div class="row g-3">
        @forelse($rooms as $room)
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="room-card">
                <div style="position:relative;">
                    @if($room->photo)
                        <img src="{{ asset('storage/'.$room->photo) }}" alt="{{ $room->name }}" style="width:100%;height:160px;object-fit:cover;">
                    @else
                        <div style="width:100%;height:160px;background:#F3F4F6;display:flex;align-items:center;justify-content:center;font-size:3rem;">🛏️</div>
                    @endif
                    <span class="badge-kost {{ $room->status === 'available' ? 'badge-available' : ($room->status === 'occupied' ? 'badge-occupied' : 'badge-maintenance') }}" style="position:absolute;top:10px;right:10px;">
                        {{ $room->status === 'available' ? '✓ Tersedia' : ($room->status === 'occupied' ? '● Terisi' : '🔧 Maintenance') }}
                    </span>
                </div>
                <div style="padding:16px;">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <div>
                            <div style="font-weight:800;font-size:1.1rem;">{{ $room->name }}</div>
                            <span style="font-size:.75rem;font-weight:700;padding:2px 10px;border-radius:12px;
                                {{ $room->type==='VVIP' ? 'background:#FEF3C7;color:#92400E;' : ($room->type==='VIP' ? 'background:#DBEAFE;color:#2563EB;' : 'background:#F3F4F6;color:#6B7280;') }}">
                                {{ $room->type }} Class
                            </span>
                        </div>
                    </div>
                    <div style="font-weight:800;font-size:1.1rem;color:#E6B800;margin:10px 0;">
                        Rp {{ number_format($room->price,0,',','.') }}<span style="font-weight:400;font-size:.75rem;color:#6B7280;">/bln</span>
                    </div>
                    <div class="d-flex flex-wrap gap-1 mb-3">
                        @foreach($room->facilities_array as $f)
                        <span style="background:#F3F4F6;color:#374151;padding:3px 8px;border-radius:6px;font-size:.7rem;">{{ $f }}</span>
                        @endforeach
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('rooms.edit', $room) }}" class="btn btn-outline-secondary btn-sm flex-grow-1" style="border-radius:7px;font-weight:600;font-size:.8rem;">✏️ Edit</a>
                        <form method="POST" action="{{ route('rooms.destroy', $room) }}" onsubmit="return confirm('Hapus kamar ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-icon" style="background:#FEE2E2;color:#DC2626;">🗑️</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5" style="color:#6B7280;">
            <div style="font-size:3rem;margin-bottom:12px;">🚪</div>
            <div style="font-weight:600;">Belum ada kamar. Tambahkan kamar pertama Anda!</div>
        </div>
        @endforelse
    </div>

</div>
@endsection
