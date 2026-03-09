@extends('layouts.admin')
@section('title', 'Edit Kamar')

@section('content')
<div style="padding:28px 32px;max-width:800px;margin:0 auto;">
    <div class="mb-4">
        <a href="{{ route('rooms.index') }}" style="color:#6B7280;text-decoration:none;font-size:.875rem;">← Kembali ke Daftar Kamar</a>
        <h1 style="font-size:1.6rem;font-weight:900;margin:8px 0 4px;">Edit Kamar: {{ $room->name }}</h1>
    </div>

    <div class="card-kost">
        <form method="POST" action="{{ route('rooms.update', $room) }}" enctype="multipart/form-data" class="form-kost">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Kamar</label>
                    <input type="text" name="name" value="{{ old('name', $room->name) }}" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tipe Kamar</label>
                    <select name="type" class="form-select">
                        @foreach(['Reguler','VIP','VVIP'] as $t)
                        <option value="{{ $t }}" {{ old('type',$room->type)===$t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Harga per Bulan (Rp)</label>
                    <input type="number" name="price" value="{{ old('price', $room->price) }}" class="form-control" min="0" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        @foreach(['available'=>'Tersedia','occupied'=>'Terisi','maintenance'=>'Maintenance'] as $v=>$l)
                        <option value="{{ $v }}" {{ old('status',$room->status)===$v ? 'selected' : '' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Fasilitas</label>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach(['AC','WiFi','Spring Bed','Lemari','Kipas','Air','Listrik','Kamar Mandi Dalam','TV','Kulkas'] as $f)
                        <label style="display:flex;align-items:center;gap:6px;background:#F9FAFB;border:1.5px solid #E5E7EB;border-radius:8px;padding:8px 12px;cursor:pointer;font-size:.875rem;">
                            <input type="checkbox" name="facilities[]" value="{{ $f }}" {{ in_array($f, old('facilities', $room->facilities_array)) ? 'checked' : '' }}>
                            {{ $f }}
                        </label>
                        @endforeach
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $room->description) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Foto Kamar (Kosongkan jika tidak ingin mengubah)</label>
                    @if($room->photo)
                    <div class="mb-2">
                        <img src="{{ asset('storage/'.$room->photo) }}" style="height:80px;border-radius:8px;object-fit:cover;">
                    </div>
                    @endif
                    <input type="file" name="photo" class="form-control" accept="image/*">
                </div>
                <div class="col-12 d-flex gap-2 justify-content-end mt-2">
                    <a href="{{ route('rooms.index') }}" class="btn btn-outline-secondary" style="border-radius:8px;font-weight:600;">Batal</a>
                    <button type="submit" class="btn-kost-primary" style="border-radius:8px;padding:9px 24px;">✓ Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
