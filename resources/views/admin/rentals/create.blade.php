@extends('layouts.admin')
@section('title', 'Booking Baru')

@section('content')
<div style="padding:28px 32px;max-width:700px;margin:0 auto;">
    <div class="mb-4">
        <a href="{{ route('rentals.index') }}" style="color:#6B7280;text-decoration:none;font-size:.875rem;">← Kembali</a>
        <h1 style="font-size:1.6rem;font-weight:900;margin:8px 0 4px;">Booking / Sewa Baru</h1>
        <p style="color:#6B7280;margin:0;">Daftarkan penghuni baru ke kamar yang tersedia.</p>
    </div>

    <div class="card-kost">
        <form method="POST" action="{{ route('rentals.store') }}" class="form-kost" id="bookingForm">
            @csrf
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Pilih Penghuni</label>
                    <select name="tenant_id" class="form-select" required>
                        <option value="">-- Pilih Penghuni --</option>
                        @foreach($activeTenants as $t)
                        <option value="{{ $t->id }}" {{ old('tenant_id')==$t->id?'selected':'' }}>{{ $t->name }} ({{ $t->phone }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Pilih Kamar</label>
                    <select name="room_id" id="roomSelect" class="form-select" required onchange="updateTotal()">
                        <option value="">-- Pilih Kamar --</option>
                        @foreach($availableRooms as $r)
                        <option value="{{ $r->id }}" data-price="{{ $r->price }}" {{ old('room_id')==$r->id?'selected':'' }}>
                            {{ $r->name }} ({{ $r->type }}) - Rp {{ number_format($r->price,0,',','.') }}/bln
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="start_date" id="startDate" value="{{ old('start_date', date('Y-m-d')) }}" class="form-control" required onchange="updateTotal()">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jumlah Bulan</label>
                    <div class="d-flex gap-2">
                        <input type="number" name="months" id="monthsInput" value="{{ old('months', 1) }}" min="1" max="24" class="form-control" required onchange="updateTotal()">
                        <span class="btn-kost-yellow d-flex align-items-center px-3" style="border-radius:8px;white-space:nowrap;pointer-events:none;">Bulan</span>
                    </div>
                </div>
                <div class="col-12" id="dueDateCard" style="display:none;">
                    <div style="background:#FFFBEB;border:1px solid #E6B800;border-radius:10px;padding:14px 18px;">
                        <div style="font-size:.75rem;color:#6B7280;">📅 Jatuh Tempo</div>
                        <div id="dueDateText" style="font-weight:800;font-size:1.1rem;"></div>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label">Metode Pembayaran</label>
                    <select name="payment_method" class="form-select">
                        @foreach(['Transfer Bank','Tunai','DANA','OVO','GoPay','QRIS'] as $m)
                        <option>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan (Opsional)</label>
                    <textarea name="notes" class="form-control" rows="2" placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
                </div>
                <div class="col-12" id="totalCard" style="display:none;">
                    <div style="background:#F3F4F6;border-radius:8px;padding:14px 18px;display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-weight:600;font-size:.9rem;">Total Tagihan:</span>
                        <span id="totalAmount" style="font-weight:900;font-size:1.4rem;color:#16A34A;"></span>
                    </div>
                </div>
                <div class="col-12 d-flex gap-2 justify-content-end mt-2">
                    <a href="{{ route('rentals.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;font-weight:600;">Batal</a>
                    <button type="submit" class="btn-kost-primary" style="border-radius:8px;padding:9px 24px;">✓ Simpan Booking</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateTotal() {
    const select = document.getElementById('roomSelect');
    const months = parseInt(document.getElementById('monthsInput').value) || 0;
    const startDate = document.getElementById('startDate').value;
    const price = select.options[select.selectedIndex]?.dataset?.price || 0;

    if (price && months && startDate) {
        const total = parseInt(price) * months;
        document.getElementById('totalAmount').textContent = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('totalCard').style.display = '';

        const end = new Date(startDate);
        end.setMonth(end.getMonth() + months);
        document.getElementById('dueDateText').textContent = end.toLocaleDateString('id-ID',{day:'2-digit',month:'long',year:'numeric'});
        document.getElementById('dueDateCard').style.display = '';
    }
}
document.addEventListener('DOMContentLoaded', updateTotal);
</script>
@endpush
