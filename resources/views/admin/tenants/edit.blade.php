@extends('layouts.admin')
@section('title', 'Edit Penghuni')

@section('content')
<div style="padding:28px 32px;max-width:700px;margin:0 auto;">
    <div class="mb-4">
        <a href="{{ route('tenants.index') }}" style="color:#6B7280;text-decoration:none;font-size:.875rem;">← Kembali</a>
        <h1 style="font-size:1.6rem;font-weight:900;margin:8px 0 4px;">Edit Penghuni: {{ $tenant->name }}</h1>
    </div>
    <div class="card-kost">
        <form method="POST" action="{{ route('tenants.update', $tenant) }}" class="form-kost">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $tenant->name) }}" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">No. HP / WhatsApp</label>
                    <input type="text" name="phone" value="{{ old('phone', $tenant->phone) }}" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kontak Darurat</label>
                    <input type="text" name="emergency_contact" value="{{ old('emergency_contact', $tenant->emergency_contact) }}" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">No. KTP</label>
                    <input type="text" name="ktp_number" value="{{ old('ktp_number', $tenant->ktp_number) }}" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="active" {{ old('status',$tenant->status)==='active'?'selected':'' }}>Aktif</option>
                        <option value="alumni" {{ old('status',$tenant->status)==='alumni'?'selected':'' }}>Alumni</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Alamat Asal</label>
                    <textarea name="origin_address" class="form-control" rows="2">{{ old('origin_address', $tenant->origin_address) }}</textarea>
                </div>
                <div class="col-12 d-flex gap-2 justify-content-end mt-2">
                    <a href="{{ route('tenants.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;font-weight:600;">Batal</a>
                    <button type="submit" class="btn-kost-primary" style="border-radius:8px;padding:9px 24px;">✓ Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
