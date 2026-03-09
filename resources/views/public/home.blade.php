<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kost Sejahtera - Temukan Hunian Impianmu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --yellow: #E6B800; --dark: #111827; --gray: #6B7280; }
        * { box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #F3F4F6; margin: 0; }

        /* Navbar */
        .pub-nav { background: var(--dark); height: 64px; display: flex; align-items: center; justify-content: space-between; padding: 0 40px; position: sticky; top: 0; z-index: 100; }
        .pub-brand { font-weight: 900; font-size: 1.1rem; color: #fff; text-decoration: none; display: flex; align-items: center; gap: 10px; }
        .pub-brand-icon { background: var(--yellow); border-radius: 8px; padding: 6px 9px; font-size: 1.2rem; }
        .pub-nav-link { color: rgba(255,255,255,.8); text-decoration: none; font-size: .9rem; font-weight: 500; transition: color .15s; }
        .pub-nav-link:hover { color: #fff; }
        .btn-login { border: 1.5px solid var(--yellow); color: var(--yellow); background: transparent; border-radius: 24px; padding: 7px 18px; font-weight: 600; font-size: .875rem; text-decoration: none; transition: all .15s; }
        .btn-login:hover { background: var(--yellow); color: var(--dark); }

        /* Hero */
        .hero {
            min-height: 500px;
            background: linear-gradient(rgba(0,0,0,.72), rgba(0,0,0,.72)),
                url('https://images.unsplash.com/photo-1555854877-bab0e564b8d5?w=1400&h=700&fit=crop') center/cover;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            padding: 80px 40px; text-align: center;
        }
        .hero h1 { font-family: 'Playfair Display', Georgia, serif; font-size: clamp(2.5rem, 5vw, 4rem); font-weight: 900; color: var(--yellow); margin: 0 0 16px; line-height: 1.1; }
        .hero p  { color: rgba(255,255,255,.85); font-size: 1.1rem; margin-bottom: 40px; }
        .hero-search { background: #fff; border-radius: 40px; padding: 10px 10px 10px 24px; display: flex; align-items: center; gap: 10px; max-width: 560px; width: 100%; box-shadow: 0 8px 30px rgba(0,0,0,.3); }
        .hero-search input { flex: 1; border: none; outline: none; font-size: .95rem; background: transparent; font-family: inherit; color: var(--dark); }
        .btn-search { background: var(--yellow); color: var(--dark); border: none; border-radius: 30px; padding: 10px 22px; font-weight: 700; cursor: pointer; font-family: inherit; }

        /* Stats bar */
        .stats-bar { background: var(--dark); padding: 20px 40px; }
        .stat-num { font-size: 1.75rem; font-weight: 900; color: var(--yellow); }
        .stat-lbl { color: rgba(255,255,255,.6); font-size: .8rem; }

        /* Section titles */
        .section-label { font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .15em; color: var(--yellow); margin-bottom: 8px; }
        .section-title { font-family: 'Playfair Display', Georgia, serif; font-size: 2.2rem; font-weight: 900; color: var(--dark); margin: 0; }

        /* Feature card */
        .feat-card { background: #fff; border-radius: 14px; padding: 28px; text-align: center; box-shadow: 0 1px 6px rgba(0,0,0,.07); height: 100%; }
        .feat-icon { font-size: 2.5rem; margin-bottom: 12px; }

        /* Room card */
        .pub-room-card { background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.08); transition: transform .2s, box-shadow .2s; height: 100%; display: flex; flex-direction: column; }
        .pub-room-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,.12); }
        .pub-room-card img { width: 100%; height: 220px; object-fit: cover; }
        .room-price-tag { position: absolute; bottom: 12px; right: 12px; background: var(--dark); color: var(--yellow); padding: 6px 14px; border-radius: 20px; font-weight: 800; font-size: .85rem; }
        .room-avail-tag { position: absolute; top: 12px; left: 12px; background: var(--yellow); color: var(--dark); padding: 4px 12px; border-radius: 20px; font-weight: 700; font-size: .8rem; }
        .btn-pesan { background: var(--yellow); color: var(--dark); border: none; border-radius: 10px; padding: 10px; font-weight: 700; width: 100%; cursor: pointer; font-family: inherit; text-decoration: none; display: block; text-align: center; transition: opacity .15s; }
        .btn-pesan:hover { opacity: .88; color: var(--dark); }

        /* Testimonial */
        .testi-card { background: #fff; border-radius: 14px; padding: 24px; box-shadow: 0 1px 6px rgba(0,0,0,.07); height: 100%; }

        /* CTA */
        .cta-section { background: var(--dark); padding: 70px 40px; text-align: center; }
        .cta-section h2 { font-family: 'Playfair Display', Georgia, serif; font-size: 2.5rem; font-weight: 900; color: var(--yellow); margin-bottom: 12px; }

        /* Footer */
        footer { background: #0A0A0A; padding: 20px 40px; text-align: center; color: rgba(255,255,255,.4); font-size: .8rem; }

        @media (max-width: 768px) { .pub-nav { padding: 0 20px; } .hero { padding: 60px 20px; } }
    </style>
</head>
<body>

{{-- NAVBAR --}}
<nav class="pub-nav">
    <a href="{{ route('home') }}" class="pub-brand">
        <span class="pub-brand-icon">🏠</span> KOST SEJAHTERA
    </a>
    <div class="d-flex align-items-center gap-4">
        <a href="#katalog" class="pub-nav-link">Katalog Kamar</a>
        <a href="#fasilitas" class="pub-nav-link">Fasilitas</a>
        <a href="#testimoni" class="pub-nav-link">Testimoni</a>
        <a href="{{ route('login') }}" class="btn-login">Login</a>
    </div>
</nav>

{{-- HERO --}}
<section class="hero">
    <h1>Temukan Hunian Impianmu</h1>
    <p>Kenyamanan eksekutif dengan harga mahasiswa. Aman, Bersih, Strategis.</p>
    <div class="hero-search">
        <span>🔍</span>
        <input type="text" id="searchInput" placeholder="Cari nama kamar, tipe, atau harga..." oninput="filterRooms()">
        <button class="btn-search">Cari Kamar</button>
    </div>
</section>

{{-- STATS BAR --}}
<div class="stats-bar">
    <div class="d-flex justify-content-center gap-5 flex-wrap">
        <div class="text-center"><div class="stat-num">{{ $totalRooms }}</div><div class="stat-lbl">Total Unit Kamar</div></div>
        <div class="text-center"><div class="stat-num">{{ $occupiedRooms }}</div><div class="stat-lbl">Penghuni Aktif</div></div>
        <div class="text-center"><div class="stat-num">{{ $availableCount }}</div><div class="stat-lbl">Unit Tersedia</div></div>
        <div class="text-center"><div class="stat-num">100%</div><div class="stat-lbl">Kepuasan Penghuni</div></div>
    </div>
</div>

{{-- FASILITAS --}}
<section id="fasilitas" style="padding:70px 40px;max-width:1200px;margin:0 auto;">
    <div class="text-center mb-5">
        <div class="section-label">KENAPA MEMILIH KAMI?</div>
        <h2 class="section-title">Fasilitas & Keunggulan</h2>
    </div>
    <div class="row g-3">
        @foreach([
            ['🔒','Keamanan 24/7','CCTV dan sistem keamanan aktif sepanjang waktu'],
            ['📶','WiFi Cepat','Koneksi internet fiber optic hingga 100 Mbps'],
            ['❄️','AC di Kamar VVIP','Pendingin ruangan di setiap kamar VVIP'],
            ['🚿','Air 24 Jam','Air bersih tersedia sepanjang waktu'],
            ['🧹','Kebersihan Terjaga','Petugas kebersihan rutin setiap hari'],
            ['📍','Lokasi Strategis','Dekat kampus, mall, dan transportasi umum'],
        ] as [$icon, $title, $desc])
        <div class="col-md-4 col-lg-2">
            <div class="feat-card">
                <div class="feat-icon">{{ $icon }}</div>
                <div style="font-weight:700;font-size:.9rem;margin-bottom:6px;">{{ $title }}</div>
                <div style="color:#6B7280;font-size:.8rem;line-height:1.6;">{{ $desc }}</div>
            </div>
        </div>
        @endforeach
    </div>
</section>

{{-- KATALOG KAMAR --}}
<section id="katalog" style="padding:40px 40px 70px;background:#fff;">
    <div style="max-width:1200px;margin:0 auto;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <div class="section-label">PILIHAN KAMAR</div>
                <h2 class="section-title">Kamar Tersedia</h2>
            </div>
            <a href="{{ route('public.rooms') }}" style="border:1.5px solid #E5E7EB;border-radius:24px;padding:8px 20px;text-decoration:none;color:#111827;font-weight:600;font-size:.875rem;">Lihat Semua</a>
        </div>
        <div class="row g-3" id="roomGrid">
            @foreach($availableRooms as $room)
            <div class="col-md-6 col-lg-4 room-item" data-name="{{ strtolower($room->name) }}" data-type="{{ strtolower($room->type) }}">
                <div class="pub-room-card">
                    <div style="position:relative;">
                        @if($room->photo)
                            <img src="{{ asset('storage/'.$room->photo) }}" alt="{{ $room->name }}">
                        @else
                            <div style="width:100%;height:220px;background:#F3F4F6;display:flex;align-items:center;justify-content:center;font-size:4rem;">🛏️</div>
                        @endif
                        <span class="room-avail-tag">Tersedia</span>
                        <span class="room-price-tag">Rp {{ number_format($room->price,0,',','.') }} /bln</span>
                    </div>
                    <div style="padding:18px;flex:1;display:flex;flex-direction:column;">
                        <div style="font-weight:800;font-size:1.1rem;margin-bottom:2px;">{{ $room->name }}</div>
                        <div style="color:#6B7280;font-size:.8rem;margin-bottom:10px;">{{ $room->type }} Class</div>
                        <div class="d-flex gap-2 flex-wrap mb-2">
                            @foreach($room->facilities_array as $f)
                            <span style="background:#F3F4F6;color:#374151;padding:3px 8px;border-radius:6px;font-size:.7rem;">{{ $f }}</span>
                            @endforeach
                        </div>
                        <div style="margin-top:auto;padding-top:12px;">
                            <a href="https://wa.me/6281234567890?text={{ urlencode('Halo, saya tertarik dengan kamar '.$room->name.' di Kost Sejahtera. Apakah masih tersedia?') }}" target="_blank" class="btn-pesan">💬 Pesan Sekarang</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- TESTIMONIALS --}}
<section id="testimoni" style="padding:70px 40px;max-width:1200px;margin:0 auto;">
    <div class="text-center mb-5">
        <div class="section-label">APA KATA MEREKA?</div>
        <h2 class="section-title">Testimoni Penghuni</h2>
    </div>
    <div class="row g-3">
        @foreach([
            ['Ahmad Fauzi','VIP 01',5,'Kamarnya bersih, fasilitas lengkap. Pemilik kost sangat responsif!'],
            ['Sari Dewi','VVIP 02',5,'Kost terbaik yang pernah saya huni. Strategis dan nyaman.'],
            ['Budi Setiawan','Reguler 03',4,'Harga terjangkau, lokasi bagus. Sangat direkomendasikan.'],
        ] as [$name, $room, $rating, $text])
        <div class="col-md-4">
            <div class="testi-card">
                <div style="font-size:1.2rem;margin-bottom:10px;">{{ str_repeat('⭐', $rating) }}</div>
                <p style="color:#6B7280;font-size:.875rem;line-height:1.7;margin-bottom:14px;">"{{ $text }}"</p>
                <div style="font-weight:700;color:#111827;">{{ $name }}</div>
                <div style="font-size:.75rem;color:#E6B800;">Penghuni Kamar {{ $room }}</div>
            </div>
        </div>
        @endforeach
    </div>
</section>

{{-- CTA --}}
<div class="cta-section">
    <h2>Siap Pindah ke Kost Sejahtera?</h2>
    <p style="color:rgba(255,255,255,.7);font-size:1rem;margin-bottom:28px;">Hubungi kami sekarang dan dapatkan informasi ketersediaan kamar terbaru.</p>
    <a href="https://wa.me/6281234567890" target="_blank" style="background:#E6B800;color:#111827;border:none;border-radius:40px;padding:14px 40px;font-weight:800;font-size:1rem;text-decoration:none;display:inline-block;">💬 Hubungi WhatsApp</a>
</div>

<footer>© {{ date('Y') }} Kost Sejahtera. Semua Hak Dilindungi. | Sistem Manajemen Kost Profesional</footer>

<script>
function filterRooms() {
    const val = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('.room-item').forEach(el => {
        const match = el.dataset.name.includes(val) || el.dataset.type.includes(val);
        el.style.display = match ? '' : 'none';
    });
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
