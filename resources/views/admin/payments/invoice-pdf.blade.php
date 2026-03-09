<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 20mm 18mm; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #111827; line-height: 1.5; }

        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; }
        .company-name { font-size: 18px; font-weight: 900; letter-spacing: .02em; }
        .company-detail { font-size: 11px; color: #6B7280; margin-top: 4px; }
        .invoice-label { font-size: 22px; font-weight: 900; color: #E6B800; text-align: right; }
        .invoice-meta { text-align: right; font-size: 11px; color: #6B7280; }
        .status-badge {
            display: inline-block;
            background: #D1FAE5;
            color: #166534;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }
        .divider { border: none; border-top: 2px solid #E6B800; margin: 16px 0; }
        .bill-section { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .bill-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: #6B7280; margin-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        thead tr { background: #111827; color: #fff; }
        thead th { padding: 10px 12px; text-align: left; font-size: 11px; }
        thead th:last-child { text-align: right; }
        tbody td { padding: 12px; border-bottom: 1px solid #E5E7EB; font-size: 11px; }
        tbody td:last-child { text-align: right; font-weight: 700; }
        .total-section { display: flex; justify-content: flex-end; margin-bottom: 20px; }
        .total-inner { min-width: 260px; }
        .total-row { display: flex; justify-content: space-between; padding: 5px 0; font-size: 12px; }
        .total-final { background: #E6B800; border-radius: 8px; padding: 10px 14px; display: flex; justify-content: space-between; font-weight: 900; font-size: 14px; }
        .footer-note { color: #6B7280; font-size: 11px; text-align: center; margin-bottom: 14px; }
        .bank-box { background: #FFFBEB; border: 1px solid #E6B800; border-radius: 8px; padding: 12px 16px; font-size: 11px; margin-bottom: 20px; }
        .bank-box b { font-weight: 700; }
        .signature { text-align: right; font-size: 11px; color: #6B7280; }
        .paid-stamp {
            position: absolute;
            top: 50px;
            right: 30px;
            border: 3px solid #16A34A;
            color: #16A34A;
            font-size: 22px;
            font-weight: 900;
            padding: 8px 16px;
            border-radius: 6px;
            transform: rotate(-15deg);
            opacity: .7;
            letter-spacing: .05em;
        }
    </style>
</head>
<body>
    <div style="position:relative;">
        @if($payment->status === 'paid')
        <div class="paid-stamp">LUNAS</div>
        @endif

        {{-- Header --}}
        <div class="header">
            <div>
                <div class="company-name">{{ $admin->kost_name ?? 'KOST SEJAHTERA' }}</div>
                <div class="company-detail">{{ $admin->kost_address ?? 'Jl. Setia Budi No. 123, Medan' }}</div>
                <div class="company-detail">WhatsApp: {{ $admin->phone ?? '0812-3456-7890' }}</div>
                <div class="company-detail">Email: admin@kostsejahtera.com</div>
            </div>
            <div>
                <div class="invoice-label">INVOICE</div>
                <div class="invoice-meta">No: {{ $payment->invoice_number }}</div>
                <div class="invoice-meta">Tanggal: {{ $payment->created_at->format('d M Y') }}</div>
                <div style="text-align:right;margin-top:6px;">
                    <span class="status-badge">{{ $payment->status === 'paid' ? '✓ Lunas' : 'Belum Lunas' }}</span>
                </div>
            </div>
        </div>

        <hr class="divider">

        {{-- Bill To --}}
        <div class="bill-section">
            <div>
                <div class="bill-label">Ditagihkan Kepada:</div>
                <div style="font-weight:700;font-size:14px;">{{ $payment->tenant->name }}</div>
                <div style="color:#6B7280;font-size:11px;">{{ $payment->tenant->phone }}</div>
                <div style="color:#6B7280;font-size:11px;">Kamar: {{ $payment->room->name }}</div>
            </div>
            <div style="text-align:right;">
                <div class="bill-label">Periode Sewa:</div>
                <div style="font-weight:700;font-size:13px;">
                    {{ $payment->rental->start_date->format('d M Y') }} s/d {{ $payment->rental->end_date->format('d M Y') }}
                </div>
                <div style="color:#6B7280;font-size:11px;">({{ $payment->rental->months }} Bulan)</div>
            </div>
        </div>

        {{-- Table --}}
        <table>
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th style="text-align:center;">Durasi</th>
                    <th style="text-align:right;">Harga Satuan</th>
                    <th style="text-align:right;">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div style="font-weight:600;">Sewa Kamar Kost - Tipe {{ $payment->room->type }}</div>
                        <div style="color:#6B7280;font-size:10px;">
                            Fasilitas: {{ implode(', ', $payment->room->facilities_array) }}
                        </div>
                    </td>
                    <td style="text-align:center;">{{ $payment->rental->months }} Bulan</td>
                    <td style="text-align:right;">Rp {{ number_format($payment->room->price, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        {{-- Totals --}}
        <div class="total-section">
            <div class="total-inner">
                <div class="total-row">
                    <span style="color:#6B7280;">Subtotal:</span>
                    <span style="font-weight:600;">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                </div>
                <div class="total-final">
                    <span>TOTAL BAYAR:</span>
                    <span>Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <p class="footer-note">Terima kasih atas kepercayaan Anda tinggal di {{ $admin->kost_name ?? 'Kost Sejahtera' }}.</p>

        {{-- Bank Info --}}
        <div class="bank-box">
            <div style="font-weight:700;margin-bottom:6px;">Pembayaran dapat ditransfer ke:</div>
            <div><b>BCA:</b> 3274628342 (a.n Admin Kost)</div>
            <div><b>BSI:</b> 234726387</div>
            <div><b>DANA:</b> 0821731731232</div>
        </div>

        {{-- Signature --}}
        <div class="signature">
            <div>Hormat Kami,</div>
            <div style="margin-top:36px;font-weight:700;color:#111827;">( Admin {{ $admin->kost_name ?? 'Kost Sejahtera' }} )</div>
        </div>
    </div>
</body>
</html>
