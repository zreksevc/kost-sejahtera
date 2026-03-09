@extends('layouts.admin')
@section('title', 'Perpanjang Sewa')

@section('content')
<div style="padding:28px 32px;max-width:600px;margin:0 auto;">
    <div class="mb-4">
        <a href="{{ route('tenants.index') }}" style="color:#6B7280;text-decoration:none;font-size:.875rem;display:inline-flex;align-items:center;gap:6px;">
            <span style="width:32px;height:32px;border:1.5px solid #E5E7EB;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;">←</span>
        </a>
        <h1 style="font-size:1.75rem;font-weight:900;margin:12px 0 0;">Perpanjang Sewa</h1>
    </div>

    <div class="card-kost">
        {{-- Tenant info --}}
        <div class="mb-4">
            <label style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#6B7280;display:block;margin-bottom:4px;">NAMA PENGHUNI</label>
            <div style="font-weight:900;font-size:1.25rem;">{{ $rental->tenant->name }}</div>
        </div>
        <div class="row mb-4">
            <div class="col-6">
                <label style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#6B7280;display:block;margin-bottom:4px;">KAMAR SAAT INI</label>
                <div style="font-weight:700;font-size:1rem;">{{ $rental->room->name }}</div>
            </div>
            <div class="col-6">
                <label style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#6B7280;display:block;margin-bottom:4px;">HARGA / BULAN</label>
                <div style="font-weight:700;font-size:1rem;">Rp {{ number_format($rental->room->price,0,',','.') }}</div>
            </div>
        </div>

        {{-- New start date --}}
        <div style="background:#FFFBEB;border:1px solid #E6B800;border-radius:10px;padding:16px 18px;margin-bottom:24px;display:flex;align-items:center;gap:14px;">
            <span style="font-size:2rem;">📅</span>
            <div>
                <div style="font-size:.75rem;color:#6B7280;">Mulai Sewa Baru (Otomatis)</div>
                <div style="font-weight:900;font-size:1.15rem;">{{ $rental->end_date->format('d M Y') }}</div>
            </div>
        </div>

        <form method="POST" action="{{ route('rentals.extend.store', $rental) }}" class="form-kost">
            @csrf
            <div style="margin-bottom:20px;">
                <label class="form-label">Mau Perpanjang Berapa Bulan?</label>
                <div class="d-flex gap-2">
                    <input type="number" name="months" id="extMonths" value="1" min="1" max="24" class="form-control" required onchange="calcExt()">
                    <span class="btn-kost-yellow d-flex align-items-center px-4" style="border-radius:8px;white-space:nowrap;pointer-events:none;">Bulan</span>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label">Metode Pembayaran</label>
                <select name="payment_method" class="form-select">
                    @foreach(['Transfer Bank','Tunai','DANA','OVO','GoPay','QRIS'] as $m)
                    <option>{{ $m }}</option>
                    @endforeach
                </select>
            </div>

            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:28px;">
                <span style="font-weight:600;font-size:.95rem;">Total Tagihan:</span>
                <span id="extTotal" style="font-weight:900;font-size:1.5rem;color:#16A34A;">Rp {{ number_format($rental->room->price,0,',','.') }}</span>
            </div>

            <button type="submit" class="btn-kost-primary w-100 d-flex align-items-center justify-content-center gap-2" style="border-radius:12px;padding:14px;font-size:.95rem;">
                ✓ SIMPAN PERPANJANGAN
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
const pricePerMonth = {{ $rental->room->price }};
function calcExt() {
    const months = parseInt(document.getElementById('extMonths').value) || 1;
    const total = pricePerMonth * months;
    document.getElementById('extTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
}
</script>
@endpush
