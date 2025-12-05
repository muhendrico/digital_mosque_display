<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Masjid Multimedia</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* BASE */
        body { font-family: 'Roboto', sans-serif; margin: 0; padding: 0; overflow: hidden; background-color: #000; color: #fff; }

        /* LAYER 0: BACKGROUND */
        #bg-container { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 0; }
        .bg-media { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; opacity: 0; transition: opacity 1s ease-in-out; }
        .media-active { opacity: 1 !important; z-index: 1; }
        .gradient-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); z-index: 2; pointer-events: none; }

        /* LAYER 100: HEADER & FOOTER */
        .header-bar { position: absolute; top: 0; width: 100%; display: flex; justify-content: space-between; align-items: center; padding: 25px 50px; z-index: 100; }
        .clock-big { font-size: 4.5rem; font-weight: 700; background: rgba(255,255,255,0.95); color: #000; padding: 5px 35px; border-radius: 50px; box-shadow: 0 4px 15px rgba(0,0,0,0.5); min-width: 320px; text-align: center; }
        .masjid-info h1 { font-size: 2.8rem; font-weight: 800; margin: 0; text-shadow: 2px 2px 4px #000; }
        .masjid-info p { font-size: 1.4rem; margin: 0; text-shadow: 1px 1px 2px #000; }
        .date-info { text-align: right; text-shadow: 1px 1px 2px #000; }
        .date-hijri { font-size: 1.6rem; font-weight: bold; color: #f1c40f; }
        .date-masehi { font-size: 1.3rem; }

        .schedule-bar { position: fixed; bottom: 60px; left: 20px; right: 20px; height: 130px; background: rgba(255, 255, 255, 0.95); border-radius: 20px; display: flex; justify-content: space-around; align-items: center; color: #333; z-index: 100; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        .schedule-item { text-align: center; flex: 1; border-right: 1px solid #ccc; position: relative; }
        .schedule-item:last-child { border-right: none; }
        .schedule-label { font-size: 1.2rem; color: #555; text-transform: uppercase; font-weight: 700; }
        .schedule-time { font-size: 2.5rem; font-weight: 800; color: #000; }
        .schedule-item.active { background: linear-gradient(135deg, #f39c12, #d35400); color: #fff !important; border-radius: 15px; transform: scale(1.15); border: none; z-index: 101; box-shadow: 0 5px 20px rgba(211, 84, 0, 0.5); padding: 25px 0; margin-top: -15px; }
        .countdown-text { display: none; margin-top: 5px; font-size: 1rem; font-weight: bold; background: rgba(0,0,0,0.2); padding: 2px 12px; border-radius: 10px; }
        .schedule-item.active .countdown-text { display: inline-block; }

        .news-ticker { position: fixed; bottom: 0; left: 0; width: 100%; height: 50px; background: #111; border-top: 3px solid #d4af37; display: flex; align-items: center; z-index: 100; overflow: hidden; }
        .ticker-label { background: #d4af37; color: #000; height: 100%; padding: 0 30px; display: flex; align-items: center; font-weight: 900; font-size: 1.2rem; z-index: 2; box-shadow: 5px 0 15px rgba(0,0,0,0.5); }
        .ticker-content { flex: 1; color: #fff; font-size: 1.4rem; white-space: nowrap; overflow: hidden; }

        /* QRIS STICKY (Pastikan Z-Index lebih tinggi dari overlay quote tapi lebih rendah dari alert iqomah) */
        .qris-floating { 
            position: fixed; bottom: 220px; right: 30px; width: 200px; 
            background: rgba(255, 255, 255, 0.95); border: 4px solid #d4af37; border-radius: 15px; 
            padding: 10px; text-align: center; 
            z-index: 90; /* Di atas overlay quote (80) */
            box-shadow: 0 10px 40px rgba(0,0,0,0.8); 
            display: none; animation: slideInUp 1s ease; 
        }
        .qris-box img { width: 100%; height: auto; display: block; border-radius: 5px; }
        .qris-title { color: #000; font-weight: 900; font-size: 0.9rem; margin-top: 8px; letter-spacing: 1px; text-transform: uppercase; }
        .qris-bank { color: #333; font-size: 0.75rem; margin-top: 4px; border-top: 1px solid #ccc; padding-top: 4px; font-weight: 600; }
        @keyframes slideInUp { from { transform: translateY(50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        /* LAYER 50: CONTENT OVERLAYS */
        .overlay-fullscreen { 
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
            z-index: 50; /* Di bawah Header/QR/Footer */
            justify-content: center; align-items: center; 
            background: rgba(0, 0, 0, 0.3); /* Transparansi ditingkatkan (lebih bening) */
            backdrop-filter: blur(3px); 
            animation: fadeIn 0.8s ease;
            
            /* PENTING: Jarak aman agar kartu naik ke atas menjauhi jadwal sholat */
            padding-bottom: 150px; 
            padding-top: 100px;
        }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        /* STYLE KARTU UMUM */
        .glass-card {
            /* PENTING: Lebar dibatasi 65% agar tidak menabrak QR di kanan */
            width: 65%; max-width: 900px;
            
            background: rgba(0, 0, 0, 0.6); 
            border: 2px solid #d4af37; border-radius: 30px; 
            padding: 40px; text-align: center; 
            box-shadow: 0 10px 50px rgba(0,0,0,0.5); 
            color: #fff;
            position: relative;
        }

        /* KHUSUS QUOTE CARD */
        /* Menggunakan style yang mirip glass-card tapi disesuaikan untuk teks */
        .quote-container { 
            /* PENTING: Lebar dibatasi 65% agar aman dari QR Code di kanan */
            width: 65%; 
            display: flex; flex-direction: column; align-items: center; 
        }
        .quote-title-text { 
            color: #f1c40f; font-size: 2.5rem; font-weight: 900; text-transform: uppercase; 
            text-shadow: 2px 2px 10px #000; margin-bottom: 20px; 
        }
        .quote-box { 
            padding: 30px 50px; 
            border-left: 6px solid #d4af37; border-right: 6px solid #d4af37; 
            background: rgba(0,0,0,0.6); border-radius: 20px; 
            position: relative; width: 100%;
        }
        .quote-text { 
            font-size: clamp(1.5rem, 2vw, 2.5rem); /* Font responsif agar tidak kepanjangan */
            font-style: italic; line-height: 1.4; font-weight: 300; 
            text-shadow: 2px 2px 5px #000; color: #fff; 
        }
        .scan-hint {
            margin-top: 20px; font-size: 1.2rem; color: #fff; 
            background: rgba(212, 175, 55, 0.2); padding: 8px 25px; 
            border-radius: 50px; border: 1px solid #d4af37;
            animation: bounce 2s infinite;
        }
        /* Ikon panah */
        .animate-arrow { display: inline-block; margin-left: 10px; }
        @keyframes bounce { 0%, 20%, 50%, 80%, 100% {transform: translateX(0);} 40% {transform: translateX(10px);} 60% {transform: translateX(5px);} }

        /* FINANCE CARD SPECIFIC */
        .finance-card { 
            width: 70%; max-width: 1000px;
            background: rgba(0, 0, 0, 0.7); /* Gelap Transparan */
            border: 2px solid #d4af37; border-radius: 30px; 
            padding: 40px; text-align: center; 
            box-shadow: 0 10px 50px rgba(0,0,0,0.5); 
            color: #fff;
            
            /* PERBAIKAN: Reset posisi agar pas di tengah */
            position: relative; 
            top: 0; 
        }
        .finance-title { color: #d4af37; font-size: 2.5rem; font-weight: 800; text-transform: uppercase; margin-bottom: 10px; letter-spacing: 2px; }
        .saldo-utama { font-size: 6rem; font-weight: 900; color: #fff; margin: 5px 0; text-shadow: 0 4px 10px rgba(0,0,0,0.5); line-height: 1; }
        .finance-details { display: flex; justify-content: space-around; margin-top: 30px; border-top: 1px solid rgba(255,255,255,0.2); padding-top: 20px; }
        .f-label { font-size: 1.2rem; color: #ccc; display: block; margin-bottom: 5px; } 
        .f-value { font-size: 2rem; font-weight: bold; } 
        .text-in { color: #2ecc71; } .text-out { color: #e74c3c; }

        /* ALERTS */
        #overlay-iqomah { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999; background: radial-gradient(circle at center, #1a1a1a 0%, #000000 100%); justify-content: center; align-items: center; flex-direction: column; color: #d4af37; }
        .timer-container { position: relative; width: 450px; height: 450px; display: flex; justify-content: center; align-items: center; margin-bottom: 30px; }
        .progress-ring__circle { transition: stroke-dashoffset 1s linear; transform: rotate(-90deg); transform-origin: 50% 50%; }
        .timer-text { position: absolute; font-size: 9rem; font-weight: bold; color: #fff; text-shadow: 0 0 30px rgba(212, 175, 55, 0.6); }
        .shaf-instruction { font-size: 3rem; color: #fff; margin-top: 30px; background: rgba(212, 175, 55, 0.15); padding: 15px 50px; border-radius: 50px; border: 2px solid #d4af37; animation: pulse 2s infinite; font-weight: bold; }
        @keyframes pulse { 0% { box-shadow: 0 0 0 0 rgba(212, 175, 55, 0.4); } 70% { box-shadow: 0 0 0 20px rgba(212, 175, 55, 0); } 100% { box-shadow: 0 0 0 0 rgba(212, 175, 55, 0); } }
        #overlay-sholat { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 10000; background: #000; color: #333; justify-content: center; align-items: center; }
    </style>
</head>
<body>

    <div id="bg-container">
        <div class="gradient-overlay"></div>
        <img id="bg-image" class="bg-media media-active" src="https://images.unsplash.com/photo-1564121211835-e88c852648ab?q=80&w=1920&auto=format&fit=crop">
        <video id="bg-video" class="bg-media" muted playsinline></video>
    </div>

    <div class="header-bar">
        <div class="clock-big" id="clock">00:00:00</div>
        <div class="masjid-info"><h1 id="masjid-name">Masjid Raya</h1><p id="masjid-address">Jalan...</p></div>
        <div class="date-info"><div class="date-hijri" id="date-hijri">Loading...</div><div class="date-masehi" id="date-masehi">Senin, 1 Jan 2024</div></div>
    </div>

    <div id="ui-qris" class="qris-floating">
        <div class="qris-box"><img id="qris-img" src="" alt="QRIS"></div>
        <div class="qris-info"><div class="qris-title">INFAQ SCAN QRIS</div><div id="qris-bank" class="qris-bank">-</div></div>
    </div>

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

    <div id="overlay-finance" class="overlay-fullscreen" style="display: none;">
        <div class="finance-card">
            <h2 class="finance-title"><i class="fas fa-coins"></i> LAPORAN KAS MASJID</h2>
            <div class="saldo-utama">Rp <span id="fin-saldo">0</span></div>
            <div class="finance-details">
                <div class="f-item"><span class="f-label">Pemasukan</span><span class="f-value text-in">Rp <span id="fin-masuk">0</span></span></div>
                <div class="f-item"><span class="f-label">Pengeluaran</span><span class="f-value text-out">Rp <span id="fin-keluar">0</span></span></div>
            </div>
        </div>
    </div>

    <div id="overlay-quote" class="overlay-fullscreen" style="display: none;">
        <div class="quote-container">
            <h1 id="quote-title" class="quote-title-text">JUDUL PROGRAM</h1>
            <div class="quote-box">
                <i class="fas fa-quote-left fa-2x text-white-50 mb-2"></i>
                <p id="quote-text" class="quote-text">Isi Kutipan...</p>
                <i class="fas fa-quote-right fa-2x text-white-50 mt-2 float-end"></i>
                <div class="clearfix"></div>
            </div>
            <div class="scan-hint">
                Salurkan Infaq Terbaik Anda <i class="fas fa-arrow-right ms-2"></i> 
            </div>
        </div>
    </div>

    <div id="overlay-iqomah">
        <div style="font-size: 2rem; letter-spacing: 5px; color: #d4af37; margin-bottom: 20px;">MENUJU IQOMAH</div>
        <div class="timer-container">
            <svg class="progress-ring" width="450" height="450">
                <circle class="progress-ring__bg" stroke="rgba(255,255,255,0.1)" stroke-width="25" fill="transparent" r="200" cx="225" cy="225"/>
                <circle class="progress-ring__circle" stroke="#d4af37" stroke-width="25" fill="transparent" r="200" cx="225" cy="225"/>
            </svg>
            <div id="iqomah-timer" class="timer-text">00:00</div>
        </div>
        <div class="shaf-instruction">Luruskan & Rapatkan Shaf</div>
    </div>
    <div id="overlay-sholat"></div>

    <script>
        const API_URL = 'http://localhost:8001';
        let appSettings = {}, prayerTimes = {}, slidersData = [], financeData = {};
        let sliderIndex = 0;
        let rotationTimer = null;
        const formatRupiah = (angka) => new Intl.NumberFormat('id-ID').format(angka || 0);
        function safeText(id, text) { const el = document.getElementById(id); if(el) el.innerText = text || ''; }

        // CLOCK
        setInterval(() => { 
            const now = new Date(); 
            document.getElementById('clock').innerText = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }); 
            document.getElementById('date-masehi').innerText = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }); 
        }, 1000);

        // FETCH DATA
        async function fetchAllData() {
            try {
                const s = await fetch(`${API_URL}/settings`).then(r => r.json()); appSettings = s; 
                safeText('masjid-name', s.nama_masjid); safeText('masjid-address', s.alamat); safeText('running-text', s.running_text);
                
                if (s.qr_infaq_url) {
                    document.getElementById('ui-qris').style.display = 'block';
                    document.getElementById('qris-img').src = s.qr_infaq_url;
                    safeText('qris-bank', s.bank_info || '-');
                } else { document.getElementById('ui-qris').style.display = 'none'; }

                const p = await fetch(`${API_URL}/prayers`).then(r => r.json()); prayerTimes = p; updatePrayerUI(p);
                
                const f = await fetch(`${API_URL}/finances`).then(r => r.json()); financeData = f;
                if(f) { safeText('fin-saldo', formatRupiah(f.saldo)); safeText('fin-masuk', formatRupiah(f.pemasukan_total)); safeText('fin-keluar', formatRupiah(f.pengeluaran_total)); }

                const sl = await fetch(`${API_URL}/sliders`).then(r => r.json());
                if(JSON.stringify(sl) !== JSON.stringify(slidersData)) {
                    slidersData = sl;
                    if(!window.rotationStarted && slidersData.length > 0) { window.rotationStarted = true; runSliderRotation(); }
                }
            } catch (e) { console.error("API Error", e); }
        }

        // PRAYER UPDATE
        function updatePrayerUI(data) {
            safeText('date-hijri', data.date_hijri); safeText('time-terbit', data.Terbit); safeText('time-subuh', data.Subuh); safeText('time-dzuhur', data.Dzuhur); safeText('time-ashar', data.Ashar); safeText('time-maghrib', data.Maghrib); safeText('time-isya', data.Isya);
            highlightNextPrayer(data);
        }
        function highlightNextPrayer(times) {
            if(!times || !times.Subuh) return;
            const now = new Date(); const curr = now.getHours() * 60 + now.getMinutes();
            const toMin = (s) => { if(!s) return 9999; const [h,m] = s.split(':'); return parseInt(h)*60 + parseInt(m); };
            ['subuh','dzuhur','ashar','maghrib','isya'].forEach(id => {
                const b = document.getElementById(`box-${id}`); if(b) b.classList.remove('active');
                const c = document.getElementById(`cd-${id}`); if(c) c.innerText = "";
            });
            let activeId = 'subuh'; 
            if (curr < toMin(times.Subuh)) activeId = 'subuh'; 
            else if (curr < toMin(times.Dzuhur)) activeId = 'dzuhur'; 
            else if (curr < toMin(times.Ashar)) activeId = 'ashar'; 
            else if (curr < toMin(times.Maghrib)) activeId = 'maghrib'; 
            else if (curr < toMin(times.Isya)) activeId = 'isya'; 
            
            const el = document.getElementById(`box-${activeId}`);
            if(el) { el.classList.add('active'); const diff = toMin(times[activeId.charAt(0).toUpperCase() + activeId.slice(1)]) - curr; const elCd = document.getElementById(`cd-${activeId}`); if(diff > 0 && diff < 60) elCd.innerText = `-${diff} Menit`; }
        }

        // SLIDER LOGIC
        function runSliderRotation() {
            const bgImage = document.getElementById('bg-image');
            const bgVideo = document.getElementById('bg-video');
            const overlayFinance = document.getElementById('overlay-finance');
            const overlayQuote = document.getElementById('overlay-quote');

            function nextSlide() {
                if(rotationTimer) clearTimeout(rotationTimer);
                const iqomah = document.getElementById('overlay-iqomah').style.display;
                const sholat = document.getElementById('overlay-sholat').style.display;
                if(iqomah === 'flex' || sholat === 'flex') { if(bgVideo) bgVideo.pause(); rotationTimer = setTimeout(nextSlide, 5000); return; }
                if(bgVideo) bgVideo.onended = null; 

                if (sliderIndex < slidersData.length) {
                    overlayFinance.style.display = 'none'; overlayQuote.style.display = 'none';
                    const currentSlide = slidersData[sliderIndex];

                    if (currentSlide.type === 'infaq') {
                        // --- MODE INFAQ ---
                        if(bgVideo) { bgVideo.pause(); bgVideo.classList.remove('media-active'); }
                        
                        // Perbaikan Logic Image (Gunakan Path Relatif)
                        let imgUrl = '/default-slide.jpg';
                        if (currentSlide.image_url && 
                            !currentSlide.image_url.includes('USE_DEFAULT') && 
                            !currentSlide.image_url.includes('null') && 
                            currentSlide.image_url !== '') {
                            imgUrl = currentSlide.image_url;
                        }
                        
                        console.log('Infaq BG:', imgUrl); // Debugging

                        bgImage.src = imgUrl;
                        bgImage.classList.add('media-active');
                        // Brightness jangan terlalu gelap agar background terlihat (0.7)
                        bgImage.style.filter = "brightness(0.7)"; 

                        safeText('quote-title', currentSlide.title || 'Mutiara Hikmah');
                        safeText('quote-text', '');
                        if(currentSlide.extra_data) {
                            let data = currentSlide.extra_data;
                            if (typeof data === 'string') { try { data = JSON.parse(data); } catch(e){} }
                            if(data.quote) safeText('quote-text', data.quote);
                        }
                        
                        overlayQuote.style.display = 'flex';
                        
                        sliderIndex++;
                        // Durasi slide infaq diperlama jadi 15 detik
                        rotationTimer = setTimeout(() => { 
                            overlayQuote.style.display = 'none'; 
                            bgImage.style.filter = "none"; 
                            nextSlide(); 
                        }, 10000);

                    } else if (currentSlide.type === 'video') {
                        bgImage.classList.remove('media-active');
                        bgVideo.src = currentSlide.image_url;
                        bgVideo.classList.add('media-active'); 
                        bgVideo.play().catch(e => console.log("Autoplay blocked")); 
                        bgVideo.onended = function() { sliderIndex++; nextSlide(); };
                    } else {
                        if(bgVideo) { bgVideo.pause(); bgVideo.classList.remove('media-active'); }
                        bgImage.src = currentSlide.image_url;
                        bgImage.classList.add('media-active'); 
                        bgImage.style.filter = "none";
                        sliderIndex++; rotationTimer = setTimeout(nextSlide, 10000);
                    }
                } else {
                    if(bgVideo) { bgVideo.pause(); bgVideo.classList.remove('media-active'); }
                    if(financeData && financeData.saldo) {
                        overlayFinance.style.display = 'flex';
                        sliderIndex = 0; 
                        rotationTimer = setTimeout(() => { overlayFinance.style.display = 'none'; nextSlide(); }, 10000); 
                    } else {
                        sliderIndex = 0; nextSlide();
                    }
                }
            }
            nextSlide();
        }

        // IQOMAH CHECK
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
            } else if (mode === 'sholat') {
                elIqomah.style.display = 'none'; elSholat.style.display = 'flex';
            } else {
                elIqomah.style.display = 'none'; elSholat.style.display = 'none';
            }
        }

        // START
        fetchAllData(); 
        setInterval(fetchAllData, 60000); 
        setInterval(checkPrayerStatus, 1000);
    </script>
</body>
</html>