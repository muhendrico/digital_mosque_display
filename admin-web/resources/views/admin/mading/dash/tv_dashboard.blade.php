<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Masjid Multimedia</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>         
    <style>
        /* --- GLOBAL RESET --- */
        body { 
            font-family: 'Roboto', sans-serif; margin: 0; padding: 0; 
            overflow: hidden; background-color: #000; color: #fff; 
            width: 100vw; height: 100vh; position: relative;
        }

        .tv-wrapper {
            /* 1. Kecilkan skala ke 75% */
            transform: scale(0.75);

            /* 2. Pastikan titik mulainya dari pojok kiri atas */
            transform-origin: top left;

            /* 3. PERBAIKAN LEBAR:
            Karena diperkecil 0.75, kita harus memperlebar wadahnya 
            supaya visualnya tetap full screen.
            Rumus: 100% / 0.75 = 133.3333% */
            width: 133.3333%;

            /* 4. PERBAIKAN TINGGI (Solusi Masalah Anda):
            Paksa tingginya menjadi lebih panjang agar footer turun ke bawah.
            Rumus: 100vh / 0.75 = 133.3333vh */
            height: 133.3333vh;
            
            /* Pastikan flexbox/grid layout anda tetap berjalan (opsional, sesuaikan dgn codingan asli) */
            display: flex;
            flex-direction: column;
            justify-content: space-between; 
        }

        /* --- 1. BACKGROUND --- */
        #bg-container { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 0; }
        .bg-media { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; opacity: 0; transition: opacity 1s ease; }
        .media-active { opacity: 1 !important; }
        /* Gradient default untuk slide biasa agar header terbaca */
        .gradient-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(to bottom, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0) 30%, rgba(0,0,0,0) 100%); z-index: 1; }

        /* --- 2. HEADER --- */
        .header-bar { 
            position: absolute; top: 0; left: 0; width: 100%; height: 140px; 
            display: flex; justify-content: space-between; align-items: center; 
            padding: 0 50px; z-index: 100;
            
            /* Default: Gradient Tipis */
            background: linear-gradient(to bottom, rgba(0,0,0,0.9), transparent);
            transition: all 0.5s ease; 
        }
        
        /* [SOLUSI UTAMA] Class ini membuat Header Transparan Total (Menyatu dengan Overlay) */
        .header-bar.seamless-mode {
            background: transparent !important; /* Hilangkan background header */
            box-shadow: none !important;
            backdrop-filter: none !important;
        }

        .clock-big { font-size: 4rem; font-weight: 700; background: rgba(255,255,255,0.95); color: #000; padding: 0 35px; border-radius: 50px; box-shadow: 0 4px 15px rgba(0,0,0,0.5); min-width: 300px; text-align: center; }
        .masjid-info h1 { font-size: 2.5rem; font-weight: 800; margin: 0; text-shadow: 2px 2px 4px #000; }
        .masjid-info p { font-size: 1.3rem; margin: 0; text-shadow: 1px 1px 2px #000; }
        .date-info { text-align: right; text-shadow: 1px 1px 2px #000; }
        .date-hijri { font-size: 1.5rem; font-weight: bold; color: #f1c40f; }
        .date-masehi { font-size: 1.2rem; }

        /* --- 3. FOOTER --- */
        .footer-area { position: absolute; bottom: 0; left: 0; width: 100%; height: 180px; z-index: 100; }
        .schedule-bar { 
            position: absolute; bottom: 55px; left: 20px; right: 20px; height: 120px; 
            background: rgba(255, 255, 255, 0.95); border-radius: 15px; 
            display: flex; justify-content: space-around; align-items: center; 
            color: #333; box-shadow: 0 10px 30px rgba(0,0,0,0.5); 
        }
        .schedule-item { text-align: center; flex: 1; border-right: 1px solid #ccc; }
        .schedule-item:last-child { border-right: none; }
        .schedule-label { font-size: 1.1rem; color: #555; text-transform: uppercase; font-weight: 700; }
        .schedule-time { font-size: 2.2rem; font-weight: 800; color: #000; }
        .schedule-item.active { background: linear-gradient(135deg, #f39c12, #d35400); color: #fff !important; border-radius: 10px; padding: 20px 0; margin-top: -20px; z-index: 101; box-shadow: 0 5px 20px rgba(211, 84, 0, 0.5); border: none; }
        .countdown-text { display: none; font-size: 0.9rem; font-weight: bold; background: rgba(0,0,0,0.2); padding: 2px 10px; border-radius: 10px; margin-top: 5px; }
        .schedule-item.active .countdown-text { display: inline-block; }

        .news-ticker { position: absolute; bottom: 0; left: 0; width: 100%; height: 45px; background: #111; border-top: 3px solid #d4af37; display: flex; align-items: center; }
        .ticker-label { background: #d4af37; color: #000; height: 100%; padding: 0 30px; display: flex; align-items: center; font-weight: 800; }
        .ticker-content { flex: 1; color: #fff; font-size: 1.2rem; white-space: nowrap; overflow: hidden; padding-left: 10px; }

        /* --- 4. QRIS --- */
        .qris-floating {
            position: fixed; bottom: 200px; right: 30px; width: 200px;
            background: rgba(255, 255, 255, 0.95); border: 4px solid #d4af37; border-radius: 15px;
            padding: 10px; text-align: center; z-index: 95;
            box-shadow: 0 10px 40px rgba(0,0,0,0.8); display: none; animation: slideInUp 1s ease;
        }
        .qris-box img { width: 100%; height: auto; display: block; border-radius: 5px; }
        .qris-title { color: #000; font-weight: 900; font-size: 0.8rem; margin-top: 5px; letter-spacing: 1px; }
        .qris-bank { color: #333; font-size: 0.7rem; margin-top: 4px; border-top: 1px solid #ccc; padding-top: 4px; font-weight: 600; }
        @keyframes slideInUp { from { transform: translateY(50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        /* --- 5. OVERLAY FULLSCREEN (MENYATU DENGAN HEADER) --- */
        .safe-zone-wrapper {
            position: fixed;
            /* Ubah TOP jadi 0 agar gelapnya sampai ke ujung atas layar */
            top: 0; 
            bottom: 0; 
            left: 0; width: 100%;
            z-index: 50; /* Di bawah Header (z-100) tapi karena gelap, header terlihat menyatu */
            
            display: none; justify-content: center; align-items: center;
            
            /* Padding inilah yang menjaga KONTEN agar tidak menabrak Header/Footer */
            padding-top: 140px;    /* Jarak aman Header */
            padding-bottom: 180px; /* Jarak aman Footer */
            padding-right: 230px;  /* Jarak aman QR */
            padding-left: 30px; 
            box-sizing: border-box; 

            /* Background Gelap Global */
            background: rgba(0, 0, 0, 0.6); 
            backdrop-filter: blur(8px); /* Efek Blur Background Image */
            animation: fadeIn 0.8s ease;
        }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        /* STYLE KARTU (GLASS) */
        .glass-card {
            width: 100%; max-width: 1000px; max-height: 100%;  
            /* Background kartu dibuat agak bening karena sudah ada background global */
            background: rgba(0, 0, 0, 0.4); 
            border: 2px solid #d4af37; border-radius: 30px;
            padding: 30px 50px; text-align: center; color: #fff;
            box-shadow: 0 20px 60px rgba(0,0,0,0.6);
            display: flex; flex-direction: column; justify-content: center; align-items: center;
            overflow-y: auto; scrollbar-width: thin; scrollbar-color: #d4af37 transparent;
        }

        /* KHUSUS QUOTE */
        .quote-title-text {
            color: #f1c40f; font-size: clamp(2rem, 4vh, 3rem); font-weight: 900; text-transform: uppercase;
            text-shadow: 2px 2px 10px #000; margin-bottom: 20px; flex-shrink: 0;
        }
        .quote-text-box {
            width: 100%; border-left: 6px solid #d4af37; border-right: 6px solid #d4af37;
            background: rgba(255,255,255,0.05); border-radius: 20px;
            padding: 20px 30px; margin-bottom: 20px;
        }
        .quote-text {
            font-size: clamp(1.5rem, 3.5vh, 2.5rem); font-style: italic; line-height: 1.4; font-weight: 300;
            text-shadow: 2px 2px 5px #000; margin: 0;
        }

        /* KHUSUS FINANCE */
        .finance-title { color: #d4af37; font-size: clamp(2rem, 4vh, 3rem); font-weight: 800; margin-bottom: 10px; }
        .saldo-utama { font-size: clamp(3rem, 7vh, 6rem); font-weight: 900; margin: 0; line-height: 1.1; text-shadow: 0 4px 10px rgba(0,0,0,0.5); }
        .finance-details { width: 100%; display: flex; justify-content: space-around; margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.2); padding-top: 15px; }
        .f-label { font-size: 1.2rem; color: #ccc; display: block; margin-bottom: 5px; } .f-value { font-size: 2rem; font-weight: bold; } .text-in { color: #2ecc71; } .text-out { color: #e74c3c; }

        /* ALERTS */
        #overlay-iqomah, #overlay-sholat { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999; justify-content: center; align-items: center; flex-direction: column; }
        #overlay-iqomah { background: radial-gradient(circle, #222 0%, #000 100%); color: #d4af37; }
        #overlay-sholat { background: #000; color: #333; }
        .timer-container { position: relative; width: 400px; height: 400px; display: flex; justify-content: center; align-items: center; margin-bottom: 20px; }
        .progress-ring__circle { transition: stroke-dashoffset 1s linear; transform: rotate(-90deg); transform-origin: 50% 50%; }
        .timer-text { position: absolute; font-size: 8rem; font-weight: bold; color: #fff; }
        .shaf-instruction { font-size: 2.5rem; color: #fff; background: rgba(212,175,55,0.2); padding: 10px 40px; border-radius: 50px; border: 1px solid #d4af37; animation: pulse 2s infinite; }
        @keyframes pulse { 0% { box-shadow: 0 0 0 0 rgba(212,175,55,0.4); } 70% { box-shadow: 0 0 0 20px rgba(212,175,55,0); } 100% { box-shadow: 0 0 0 0 rgba(212,175,55,0); } }
        
        /* --- OVERLAY ARTIKEL (SPLIT SCREEN) --- */
        #overlay-article {
            display: none; /* Default Hidden */
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: #fff; z-index: 60; /* Di atas overlay safe-zone biasa */
            animation: fadeIn 0.8s ease;
        }
        
        .art-container { display: flex; width: 100%; height: 100%; }
        
        /* Kolom Kiri: Gambar */
        .art-left { 
            width: 40%; position: relative; overflow: hidden; 
            background: #000;
        }
        .art-left img { 
            width: 100%; height: 100%; object-fit: cover; 
            transition: transform 10s ease; transform: scale(1);
        }
        /* Efek Zoom in pelan pada gambar */
        .art-zoom .art-left img { transform: scale(1.1); }

        /* Kolom Kanan: Konten */
        .art-right { 
            width: 60%; padding: 140px 60px 180px 60px; /* Padding atas/bawah sesuaikan header/footer */
            display: flex; flex-direction: column; justify-content: center;
            color: #333; position: relative;
        }

        /* Agar Header Putih tetap terbaca di background putih, kita beri shadow atas */
        .art-header-shadow {
            position: absolute; top: 0; left: 0; width: 100%; height: 150px;
            background: linear-gradient(to bottom, rgba(0,0,0,0.9), transparent);
            z-index: 1; pointer-events: none;
        }

        .art-badge {
            background: #d4af37; color: #000; padding: 5px 15px; 
            border-radius: 20px; font-weight: 800; font-size: 1.2rem;
            width: fit-content; margin-bottom: 20px; display: flex; align-items: center;
        }
        .art-title {
            font-size: clamp(2.5rem, 5vh, 4rem); font-weight: 900; 
            line-height: 1.1; margin-bottom: 30px; color: #2c3e50;
        }
        .art-summary {
            font-size: clamp(1.2rem, 2.5vh, 1.8rem); line-height: 1.6; color: #555;
            margin-bottom: 40px; text-align: justify;
            border-left: 5px solid #d4af37; padding-left: 20px;
        }
        .art-qr-box {
            display: flex; align-items: center; background: #f8f9fa;
            padding: 15px; border-radius: 15px; width: fit-content;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1); border: 1px solid #eee;
        }

        /* Di dalam tag <style> */
        #art-qr {
            background-color: #ffffff; /* WAJIB PUTIH */
            padding: 15px;             /* WAJIB ADA JARAK (Quiet Zone) */
            border-radius: 8px;
            display: inline-block;     /* Agar ukuran menyesuaikan konten */
        }

        #art-qr img {
            display: block; /* Hilangkan gap bawah default image */
        }
    </style>
</head>
<body>
    <div class="tv-wrapper">
        <div id="bg-container">
            <div class="gradient-overlay"></div>
            <img id="bg-image" class="bg-media media-active" src="https://images.unsplash.com/photo-1564121211835-e88c852648ab?q=80&w=1920&auto=format&fit=crop">
            <video id="bg-video" class="bg-media" muted playsinline></video>
        </div>

        <div class="header-bar">
            <div class="clock-big" id="clock">00:00:00</div>
            <div class="masjid-info"><h1 id="masjid-name">Masjid Raya</h1><p id="masjid-address">Alamat...</p></div>
            <div class="date-info"><div class="date-hijri" id="date-hijri">Loading...</div><div class="date-masehi" id="date-masehi">Senin, 1 Jan 2024</div></div>
        </div>

        <div id="overlay-finance" class="safe-zone-wrapper">
            <div class="glass-card">
                <h2 class="finance-title"><i class="fas fa-coins"></i> KAS MASJID</h2>
                <div class="saldo-utama">Rp <span id="fin-saldo">0</span></div>
                <div class="finance-details">
                    <div><span class="f-label">Pemasukan</span><span class="f-value text-in">Rp <span id="fin-masuk">0</span></span></div>
                    <div><span class="f-label">Pengeluaran</span><span class="f-value text-out">Rp <span id="fin-keluar">0</span></span></div>
                </div>
            </div>
        </div>

        <div id="overlay-quote" class="safe-zone-wrapper">
            <div class="glass-card">
                <h1 id="quote-title" class="quote-title-text">MARI BERWAKAF</h1>
                <div class="quote-text-box">
                    <i class="fas fa-quote-left fa-lg text-white-50 mb-2"></i>
                    <p id="quote-text" class="quote-text">Isi kutipan...</p>
                    <i class="fas fa-quote-right fa-lg text-white-50 mt-2 float-end"></i>
                    <div class="clearfix"></div>
                </div>
                <div style="font-size: 1.2rem; color: #ccc;">
                    Salurkan Infaq Terbaik Anda <i class="fas fa-arrow-right text-warning ms-2"></i>
                    <span class="small ms-1">(Scan QR di Kanan)</span>
                </div>
            </div>
        </div>

        <div id="overlay-article">
            <div class="art-container">
                <div class="art-left">
                    <img id="art-img" src="">
                </div>
                
                <div class="art-right">
                    <div class="art-header-shadow"></div>

                    <div class="art-badge"><i class="fas fa-newspaper me-2"></i> ARTIKEL / KAJIAN</div>
                    <div id="art-title" class="art-title">Judul Artikel Disini</div>
                    <div id="art-summary" class="art-summary">Ringkasan artikel...</div>
                    
                    <div class="art-qr-box">
                        <div id="art-qr" style="background: #fff; padding: 5px; border-radius: 5px;"></div>
                        <div class="ms-4">
                            <div style="font-weight: 900; font-size: 1.2rem;">BACA SELENGKAPNYA</div>
                            <div style="color: #777;">Scan QR Code dengan HP</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="ui-qris" class="qris-floating">
            <div class="qris-box"><img id="qris-img" src="" alt="QRIS"></div>
            <div class="qris-title">INFAQ SCAN QRIS</div>
            <div id="qris-bank" class="qris-bank">-</div>
        </div>

        <div class="footer-area">
            <div class="schedule-bar">
                <div class="schedule-item"><div class="schedule-label">Terbit</div><div class="schedule-time" id="time-terbit">--:--</div></div>
                <div class="schedule-item" id="box-subuh"><div class="schedule-label">Subuh</div><div class="schedule-time" id="time-subuh">--:--</div><div class="countdown-text" id="cd-subuh"></div></div>
                <div class="schedule-item" id="box-dzuhur"><div class="schedule-label">Dzuhur</div><div class="schedule-time" id="time-dzuhur">--:--</div><div class="countdown-text" id="cd-dzuhur"></div></div>
                <div class="schedule-item" id="box-ashar"><div class="schedule-label">Ashar</div><div class="schedule-time" id="time-ashar">--:--</div><div class="countdown-text" id="cd-ashar"></div></div>
                <div class="schedule-item" id="box-maghrib"><div class="schedule-label">Maghrib</div><div class="schedule-time" id="time-maghrib">--:--</div><div class="countdown-text" id="cd-maghrib"></div></div>
                <div class="schedule-item" id="box-isya"><div class="schedule-label">Isya</div><div class="schedule-time" id="time-isya">--:--</div><div class="countdown-text" id="cd-isya"></div></div>
            </div>
            <div class="news-ticker">
                <div class="ticker-label"><i class="fas fa-bullhorn me-2"></i> INFO</div>
                <div class="ticker-content"><marquee id="running-text" scrollamount="6">Selamat Datang...</marquee></div>
            </div>
        </div>

        <div id="overlay-iqomah">
            <div style="font-size: 2rem; letter-spacing: 5px; margin-bottom: 20px;">MENUJU IQOMAH</div>
            <div class="timer-container">
                <svg class="progress-ring" width="400" height="400">
                    <circle class="progress-ring__bg" stroke="rgba(255,255,255,0.1)" stroke-width="20" fill="transparent" r="180" cx="200" cy="200"/>
                    <circle class="progress-ring__circle" stroke="#d4af37" stroke-width="20" fill="transparent" r="180" cx="200" cy="200"/>
                </svg>
                <div id="iqomah-timer" class="timer-text">00:00</div>
            </div>
            <div class="shaf-instruction">Luruskan & Rapatkan Shaf</div>
        </div>
        <div id="overlay-sholat"></div>
    </div>

    <script>
        const API_URL = 'http://localhost:8001'; // Sesuaikan Port Backend Lumen
        const PUBLIC_URL = 'http://localhost:8000'; // Sesuaikan Port Frontend Laravel
        
        let appSettings = {}, prayerTimes = {}, slidersData = [], financeData = {};
        let sliderIndex = 0; let rotationTimer = null;
        
        const formatRupiah = (n) => new Intl.NumberFormat('id-ID').format(n || 0);
        function safeText(id, t) { const el = document.getElementById(id); if(el) el.innerText = t || ''; }
    
        // --- JAM DIGITAL ---
        setInterval(() => { 
            const now = new Date(); 
            document.getElementById('clock').innerText = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }); 
            document.getElementById('date-masehi').innerText = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }); 
        }, 1000);
    
        // --- HELPER STRIP HTML ---
        function stripHtml(html){
            let tmp = document.createElement("DIV");
            tmp.innerHTML = html;
            return tmp.textContent || tmp.innerText || "";
        }
    
        // --- FETCH DATA ---
        async function fetchAllData() {
            try {
                // 1. Settings
                const s = await fetch(`${API_URL}/v1/mading/master/settings`).then(r => r.json()); appSettings = s; 
                safeText('masjid-name', s.nama_masjid); safeText('masjid-address', s.alamat); safeText('running-text', s.running_text);
                if (s.qr_infaq_url) {
                    document.getElementById('ui-qris').style.display = 'block';
                    document.getElementById('qris-img').src = s.qr_infaq_url;
                    safeText('qris-bank', s.bank_info || '-');
                } else { document.getElementById('ui-qris').style.display = 'none'; }
    
                // 2. Prayer Times
                const p = await fetch(`${API_URL}/v1/mading/dash/prayers`).then(r => r.json()); prayerTimes = p; updatePrayerUI(p);
                
                // 3. Finance
                const f = await fetch(`${API_URL}/v1/mading/trans/finances`).then(r => r.json()); financeData = f;
                if(f) { safeText('fin-saldo', formatRupiah(f.saldo)); safeText('fin-masuk', formatRupiah(f.pemasukan_total)); safeText('fin-keluar', formatRupiah(f.pengeluaran_total)); }
                
                // 4. Sliders (Looping Logic)
                const sl = await fetch(`${API_URL}/v1/mading/master/sliders`).then(r => r.json());
                
                if(JSON.stringify(sl) !== JSON.stringify(slidersData)) {
                    slidersData = sl;
                    if(!window.rotationStarted && slidersData.length > 0) { window.rotationStarted = true; runSliderRotation(); }
                }
            } catch (e) { console.error("API Error", e); }
        }
    
        // --- LOGIKA JADWAL SHOLAT UI ---
        function updatePrayerUI(d) {
            safeText('date-hijri', d.date_hijri); safeText('time-terbit', d.Terbit); safeText('time-subuh', d.Subuh); safeText('time-dzuhur', d.Dzuhur); safeText('time-ashar', d.Ashar); safeText('time-maghrib', d.Maghrib); safeText('time-isya', d.Isya);
            highlightNextPrayer(d);
        }

        function highlightNextPrayer(t) {
            if(!t || !t.Subuh) return;
            const now = new Date(); const curr = now.getHours() * 60 + now.getMinutes();
            const toMin = (s) => { if(!s) return 9999; const [h,m] = s.split(':'); return parseInt(h)*60 + parseInt(m); };
            ['subuh','dzuhur','ashar','maghrib','isya'].forEach(id => {
                const b = document.getElementById(`box-${id}`); if(b) b.classList.remove('active');
                const c = document.getElementById(`cd-${id}`); if(c) c.innerText = "";
            });
            let activeId = 'subuh'; 
            if (curr < toMin(t.Subuh)) activeId = 'subuh'; 
            else if (curr < toMin(t.Dzuhur)) activeId = 'dzuhur'; 
            else if (curr < toMin(t.Ashar)) activeId = 'ashar'; 
            else if (curr < toMin(t.Maghrib)) activeId = 'maghrib'; 
            else if (curr < toMin(t.Isya)) activeId = 'isya'; 
            const el = document.getElementById(`box-${activeId}`);
            if(el) { el.classList.add('active'); const diff = toMin(t[activeId.charAt(0).toUpperCase() + activeId.slice(1)]) - curr; const elCd = document.getElementById(`cd-${activeId}`); if(diff > 0 && diff < 60) elCd.innerText = `-${diff} Menit`; }
        }
    
        // --- ENGINE ROTASI SLIDER UTAMA ---
        function runSliderRotation() {
            const bgImage = document.getElementById('bg-image');
            const bgVideo = document.getElementById('bg-video');
            
            // Overlays
            const ovFinance = document.getElementById('overlay-finance');
            const ovQuote = document.getElementById('overlay-quote');
            const ovArticle = document.getElementById('overlay-article'); 
            
            const mainHeader = document.querySelector('.header-bar'); 
    
            function nextSlide() {
                if(rotationTimer) clearTimeout(rotationTimer);
                
                // Cek Mode Iqomah/Sholat (Pause Rotasi)
                if(document.getElementById('overlay-iqomah').style.display === 'flex' || document.getElementById('overlay-sholat').style.display === 'flex') {
                    if(bgVideo) bgVideo.pause(); 
                    rotationTimer = setTimeout(nextSlide, 5000); return;
                }
                
                // Reset UI State
                if(bgVideo) bgVideo.onended = null; 
                ovFinance.style.display = 'none';
                ovQuote.style.display = 'none';
                ovArticle.style.display = 'none'; 
                ovArticle.classList.remove('art-zoom');
                
                mainHeader.classList.remove('seamless-mode'); 
    
                if (sliderIndex < slidersData.length) {
                    const slide = slidersData[sliderIndex];
                    let dbInterval = parseInt(slide.interval);
                    
                    if (isNaN(dbInterval) || dbInterval < 1000) dbInterval = 10000; 
    
                    // --- TIPE 1: INFAQ / QUOTE ---
                    if (slide.type === 'infaq') {
                        if(bgVideo) { bgVideo.pause(); bgVideo.classList.remove('media-active'); }
                        
                        let imgUrl = '/default-slide.jpg';
                        if (slide.image_url && !slide.image_url.includes('USE_DEFAULT') && !slide.image_url.includes('null')) imgUrl = slide.image_url;
                        bgImage.src = imgUrl; bgImage.classList.add('media-active'); bgImage.style.filter = "brightness(0.6)"; 
    
                        safeText('quote-title', slide.title || 'MARI BERINFAQ');
                        safeText('quote-text', '');
                        if(slide.extra_data) {
                            let data = slide.extra_data;
                            if (typeof data === 'string') { try { data = JSON.parse(data); } catch(e){} }
                            if(data.quote) safeText('quote-text', data.quote);
                        }
                        
                        ovQuote.style.display = 'flex';
                        mainHeader.classList.add('seamless-mode'); 
    
                        sliderIndex++;

                        rotationTimer = setTimeout(() => { 
                            ovQuote.style.display = 'none'; 
                            mainHeader.classList.remove('seamless-mode'); 
                            bgImage.style.filter = "none"; 
                            nextSlide(); 
                        }, dbInterval);
    
                    } 
                    // --- TIPE 2: ARTIKEL ---
                    else if (slide.type === 'article') {
                        if(bgVideo) { bgVideo.pause(); bgVideo.classList.remove('media-active'); }
                            
                        // Setup Gambar & Konten (Code lama Anda)
                        const articleData = slide.article || {};
                        let artImgUrl = "";
                        if (articleData.image_url) { artImgUrl = articleData.image_url; } 
                        else if (articleData.image) { artImgUrl = `${API_URL}/storage/${articleData.image}`; } 
                        else { if (slide.image_url && !slide.image_url.includes('USE_DEFAULT')) { artImgUrl = slide.image_url; } }
                            
                        document.getElementById('art-img').src = artImgUrl || 'https://via.placeholder.com/800x600?text=No+Image';
                        safeText('art-title', articleData.title || slide.title);
                        
                        let summary = stripHtml(articleData.content || "");
                        if(summary.length > 250) summary = summary.substring(0, 250) + "...";
                        safeText('art-summary', summary);
    
                        // QR Code
                        const qrContainer = document.getElementById("art-qr");
                        qrContainer.innerHTML = ""; 
                        if(articleData.slug) {
                            new QRCode(qrContainer, {
                                text: `${PUBLIC_URL}/${articleData.slug}`,
                                width: 150, height: 150, colorDark : "#000000", colorLight : "#ffffff",
                                correctLevel : QRCode.CorrectLevel.M 
                            });
                        }
    
                        ovArticle.style.display = 'block';
                        setTimeout(() => ovArticle.classList.add('art-zoom'), 100);
                        mainHeader.classList.add('seamless-mode'); 
    
                        sliderIndex++;
                        
                        let articleTime = dbInterval < 15000 ? 15000 : dbInterval;
    
                        rotationTimer = setTimeout(() => {
                            ovArticle.style.display = 'none';
                            mainHeader.classList.remove('seamless-mode');
                            nextSlide();
                        }, articleTime);
                    }
                    // --- TIPE 3: VIDEO ---
                    else if (slide.type === 'video') {
                        bgImage.classList.remove('media-active');
                        bgVideo.src = slide.image_url; 
                        bgVideo.classList.add('media-active'); 
                        bgVideo.play().catch(e=>{});
                        
                        bgVideo.onended = function() { sliderIndex++; nextSlide(); };
                    } 
                    // --- TIPE 4: GAMBAR BIASA ---
                    else {
                        if(bgVideo) { bgVideo.pause(); bgVideo.classList.remove('media-active'); }
                        bgImage.src = slide.image_url; 
                        bgImage.classList.add('media-active'); 
                        bgImage.style.filter = "none";
                        
                        sliderIndex++; 
                        
                        rotationTimer = setTimeout(nextSlide, dbInterval);
                    }
                } else {
                    // --- SLIDER HABIS / RESET ---
                    if(bgVideo) { bgVideo.pause(); bgVideo.classList.remove('media-active'); }
                    
                    // Tampilkan Laporan Keuangan sejenak sebelum ulang loop
                    if(financeData && financeData.saldo) {
                        ovFinance.style.display = 'flex';
                        mainHeader.classList.add('seamless-mode');
                        sliderIndex = 0; 
                        rotationTimer = setTimeout(() => { 
                            ovFinance.style.display = 'none'; 
                            mainHeader.classList.remove('seamless-mode'); 
                            nextSlide(); 
                        }, 10000);
                    } else { sliderIndex = 0; nextSlide(); }
                }
            }
            nextSlide();
        }
    
        // --- CHECK JADWAL SHOLAT/IQOMAH ---
        function checkPrayerStatus() {
            if (!prayerTimes.Subuh || !appSettings.iqomah_minutes) return;
            const now = new Date(); const curr = now.getHours() * 60 + now.getMinutes(); const sec = now.getSeconds();
            const toMin = (s) => { if(!s) return 9999; const [h,m] = s.split(':'); return parseInt(h)*60 + parseInt(m); };
            const iqomahDur = parseInt(appSettings.iqomah_minutes || 10);
            const prayers = [ { t: toMin(prayerTimes.Subuh) }, { t: toMin(prayerTimes.Dzuhur) }, { t: toMin(prayerTimes.Ashar) }, { t: toMin(prayerTimes.Maghrib) }, { t: toMin(prayerTimes.Isya) } ];
            let mode = 'normal'; let targetIqomah = 0;
            prayers.forEach(p => {
                const adzan = p.t; const iqomah = adzan + iqomahDur; const selesai = iqomah + parseInt(appSettings.standby_minutes || 10);
                if (curr >= adzan && curr < iqomah) { mode = 'iqomah'; targetIqomah = iqomah; } 
                else if (curr >= iqomah && curr < selesai) { mode = 'sholat'; }
            });
            const elIqomah = document.getElementById('overlay-iqomah'); const elSholat = document.getElementById('overlay-sholat'); const elTimer = document.getElementById('iqomah-timer'); const circle = document.querySelector('.progress-ring__circle');
            if (mode === 'iqomah') {
                elIqomah.style.display = 'flex'; elSholat.style.display = 'none';
                const totalDur = iqomahDur * 60; const sisa = (targetIqomah - curr - 1) * 60 + (60 - sec);
                const dm = Math.floor(sisa / 60); const ds = sisa % 60; const ss = ds < 10 ? '0' + ds : ds;
                if(elTimer) elTimer.innerText = `${dm}:${ss}`;
                if(circle) { const r = circle.r.baseVal.value; const c = r * 2 * Math.PI; circle.style.strokeDasharray = `${c} ${c}`; circle.style.strokeDashoffset = c - (sisa / totalDur) * c; }
            } else if (mode === 'sholat') { elIqomah.style.display = 'none'; elSholat.style.display = 'flex'; } 
            else { elIqomah.style.display = 'none'; elSholat.style.display = 'none'; }
        }
    
        // --- INISIALISASI ---
        fetchAllData(); 
        setInterval(fetchAllData, 60000); 
        setInterval(checkPrayerStatus, 1000);
    </script>
</body>
</html>