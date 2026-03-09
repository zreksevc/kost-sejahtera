@extends('layouts.admin')
@section('title', 'Pengeluaran')

@section('content')
<div style="padding:28px 32px;max-width:1200px;margin:0 auto;">

    <div class="d-flex justify-content-between align-items-start mb-4">
        <div class="page-header">
            <a href="{{ route('dashboard') }}" style="color:#6B7280;text-decoration:none;font-size:.875rem;">← Kembali</a>
            <h1 class="mt-1">Pencatatan Pengeluaran</h1>
            <p>Catat biaya operasional untuk menghitung laba bersih.</p>
        </div>
    </div>

    <div class="row g-3 mb-4">
        {{-- Summary --}}
        <div class="col-md-4">
            <div class="card-kost" style="background:#E6B800;">
                <div style="font-size:.65rem;font-weight:700;text-transform:uppercase;color:#7A5900;margin-bottom:6px;">TOTAL PENGELUARAN DITAMPILKAN</div>
                <div style="font-size:1.75rem;font-weight:900;">Rp {{ number_format($totalExpense,0,',','.') }}</div>
            </div>
        </div>

        {{-- Add form --}}
        <div class="col-md-8">
            <div class="card-kost">
                <div style="font-weight:800;font-size:.95rem;margin-bottom:14px;">+ Catat Pengeluaran Baru</div>
                <form method="POST" action="{{ route('expenses.store') }}" class="form-kost">
                    @csrf
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="date" value="{{ date('Y-m-d') }}" class="form-control" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Deskripsi</label>
                            <input type="text" name="description" class="form-control" placeholder="Contoh: Tagihan listrik" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kategori</label>
                            <select name="category" class="form-select">
                                @foreach(['Utilitas','Maintenance','Kebersihan','Keamanan','Lain-lain'] as $cat)
                                <option>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Jumlah (Rp)</label>
                            <input type="number" name="amount" class="form-control" placeholder="500000" min="1" required>
                        </div>
                        <div class="col-md-7 d-flex align-items-end">
                            <button type="submit" class="btn-kost-primary w-100" style="border-radius:8px;padding:10px;">✓ Simpan Pengeluaran</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card-kost">
        <table class="w-100 table-kost">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>TANGGAL</th>
                    <th>DESKRIPSI</th>
                    <th>KATEGORI</th>
                    <th>JUMLAH</th>
                    <th>HAPUS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $i => $expense)
                <tr>
                    <td style="color:#6B7280;">{{ $i+1 }}</td>
                    <td>{{ $expense->date->format('d M Y') }}</td>
                    <td style="font-weight:600;">{{ $expense->description }}</td>
                    <td>
                        <span class="badge-kost badge-blue" style="background:#DBEAFE;color:#2563EB;">{{ $expense->category }}</span>
                    </td>
                    <td style="font-weight:700;color:#DC2626;">Rp {{ number_format($expense->amount,0,',','.') }}</td>
                    <td>
                        <form method="POST" action="{{ route('expenses.destroy', $expense) }}" onsubmit="return confirm('Hapus pengeluaran ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-icon" style="background:#FEE2E2;color:#DC2626;">🗑️</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-5" style="color:#6B7280;">Belum ada catatan pengeluaran.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
