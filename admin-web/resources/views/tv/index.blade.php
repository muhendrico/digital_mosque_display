<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Masjid Multimedia</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body { font-family: 'Roboto', sans-serif; margin: 0; padding: 0; overflow: hidden; background-color: #000; color: #fff; }

        /* BACKGROUND LAYER */
        #bg-container { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 0; background: #000; }
        .bg-media { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; opacity: 0; transition: opacity 1s ease-in-out; }
        .media-active { opacity: 1 !important; z-index: 1; }
        .gradient-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.3); z-index: 2; pointer-events: none; }

        /* HEADER & FOOTER UI */
        .header-bar { position: absolute; top: 0; width: 100%; display: flex; justify-content: space-between; align-items: center; padding: 25px 50px; z-index: 100; }
        .clock-big { font-size: 4.5rem; font-weight: 700; background: rgba(255,255,255,0.9); color: #000; padding: 5px 35px; border-radius: 50px; box-shadow: 0 4px 15px rgba(0,0,0,0.3); }
        .masjid-info h1 { font-size: 2.8rem; font-weight: bold; margin: 0; text-shadow: 2px 2px 4px #000; }
        .masjid-info p { font-size: 1.4rem; margin: 0; text-shadow: 1px 1px 2px #000; opacity: 0.9; }
        .date-info { text-align: right; text-shadow: 1px 1px 2px #000; }
        .date-hijri { font-size: 1.6rem; font-weight: bold; color: #d4af37; }
        .date-masehi { font-size: 1.3rem; }

        /* JADWAL SHOLAT */
        .schedule-bar { position: fixed; bottom: 60px; left: 20px; right: 20px; height: 130px; background: rgba(255, 255, 255, 0.95); border-radius: 15px; display: flex; justify-content: space-around; align-items: center; color: #333; z-index: 100; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        .schedule-item { text-align: center; flex: 1; border-right: 1px solid #ccc; position: relative; }
        .schedule-item:last-child { border-right: none; }
        .schedule-label { font-size: 1.2rem; color: #555; text-transform: uppercase; letter-spacing: 1px; }
        .schedule-time { font-size: 2.2rem; font-weight: 800; color: #000; }
        .schedule-item.active { background: linear-gradient(135deg, #f39c12, #d35400); color: #fff !important; border-radius: 15px; transform: scale(1.15); border: none; z-index: 101; box-shadow: 0 5px 20px rgba(211, 84, 0, 0.5); padding: 20px 0; margin-top: -10px; }
        .schedule-item.active .schedule-label { color: #fff; font-size: 1.3rem; }
        .schedule-item.active .schedule-time { color: #fff; font-size: 3rem; }
        .countdown-text { display: none; margin-top: 0px; font-size: 1rem; font-weight: bold; background: rgba(0,0,0,0.2); padding: 2px 10px; border-radius: 10px; display: inline-block; }
        .schedule-item.active .countdown-text { display: inline-block; }

        /* RUNNING TEXT */
        .news-ticker { position: fixed; bottom: 0; left: 0; width: 100%; height: 50px; background: #111; border-top: 3px solid #d4af37; display: flex; align-items: center; z-index: 100; overflow: hidden; }
        .ticker-label { background: #d4af37; color: #000; height: 100%; padding: 0 30px; display: flex; align-items: center; font-weight: bold; font-size: 1.2rem; z-index: 2; box-shadow: 5px 0 15px rgba(0,0,0,0.5); }
        .ticker-content { flex: 1; color: #fff; font-size: 1.4rem; white-space: nowrap; overflow: hidden; }

        /* FINANCE OVERLAY */
        #overlay-finance { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 50; justify-content: center; align-items: center; backdrop-filter: blur(10px); background: rgba(0, 0, 0, 0.6); animation: fadeIn 0.5s ease; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .finance-card { width: 70%; background: rgba(255, 255, 255, 0.1); border: 2px solid #d4af37; border-radius: 20px; padding: 40px; text-align: center; box-shadow: 0 0 50px rgba(0,0,0,0.8); position: relative; top: -30px; }
        .finance-title { color: #d4af37; font-size: 2.5rem; font-weight: bold; text-transform: uppercase; margin-bottom: 20px; letter-spacing: 2px; }
        .saldo-utama { font-size: 6rem; font-weight: 800; color: #fff; margin: 10px 0; text-shadow: 0 4px 10px rgba(0,0,0,0.5); }
        .finance-details { display: flex; justify-content: space-around; margin-top: 40px; border-top: 1px solid rgba(255,255,255,0.2); padding-top: 20px; }
        .f-label { font-size: 1.5rem; color: #ccc; display: block; margin-bottom: 5px; }
        .f-value { font-size: 2.5rem; font-weight: bold; }
        .text-in { color: #2ecc71; } .text-out { color: #e74c3c; }

        /* IQOMAH & SHOLAT */
        #overlay-iqomah, #overlay-sholat { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999; justify-content: center; align-items: center; flex-direction: column; }
        #overlay-iqomah { background: rgba(0,0,0,0.95); color: #d4af37; }
        #overlay-sholat { background: #000; color: #333; }
    </style>
</head>
<body>

    <div id="bg-container">
        <div class="gradient-overlay"></div>
        <img id="bg-image" class="bg-media media-active" src="https://images.unsplash.com/photo-1564121211835-e88c852648ab?q=80&w=1920&auto=format&fit=crop">
        <video id="bg-video" class="bg-media" muted playsinline></video>
    </div>

    <div id="overlay-finance">
        <div class="finance-card">
            <h2 class="finance-title"><i class="fas fa-coins"></i> LAPORAN KAS MASJID</h2>
            <div class="saldo-utama">Rp <span id="fin-saldo">0</span></div>
            <div class="finance-details">
                <div class="f-item"><span class="f-label">Pemasukan</span><span class="f-value text-in">Rp <span id="fin-masuk">0</span></span></div>
                <div class="f-item"><span class="f-label">Pengeluaran</span><span class="f-value text-out">Rp <span id="fin-keluar">0</span></span></div>
            </div>
        </div>
    </div>

    <div class="header-bar">
        <div class="clock-big" id="clock">00:00</div>
        <div class="masjid-info">
            <h1 id="masjid-name">Masjid Raya</h1>
            <p id="masjid-address">Jalan...</p>
        </div>
        <div class="date-info">
            <div class="date-hijri" id="date-hijri">Loading...</div>
            <div class="date-masehi" id="date-masehi">Senin, 1 Jan 2024</div>
        </div>
    </div>

    <div class="schedule-bar">
        <div class="schedule-item"><div class="schedule-label">Terbit</div><div class="schedule-time" id="time-terbit">00:00</div></div>
        <div class="schedule-item" id="box-subuh"><div class="schedule-label">Subuh</div><div class="schedule-time" id="time-subuh">00:00</div><div class="countdown-text" id="cd-subuh"></div></div>
        <div class="schedule-item" id="box-dzuhur"><div class="schedule-label">Dzuhur</div><div class="schedule-time" id="time-dzuhur">00:00</div><div class="countdown-text" id="cd-dzuhur"></div></div>
        <div class="schedule-item" id="box-ashar"><div class="schedule-label">Ashar</div><div class="schedule-time" id="time-ashar">00:00</div><div class="countdown-text" id="cd-ashar"></div></div>
        <div class="schedule-item" id="box-maghrib"><div class="schedule-label">Maghrib</div><div class="schedule-time" id="time-maghrib">00:00</div><div class="countdown-text" id="cd-maghrib"></div></div>
        <div class="schedule-item" id="box-isya"><div class="schedule-label">Isya</div><div class="schedule-time" id="time-isya">00:00</div><div class="countdown-text" id="cd-isya"></div></div>
    </div>

    <div class="news-ticker">
        <div class="ticker-label"><i class="fas fa-bullhorn me-2"></i> INFO</div>
        <div class="ticker-content">
            <marquee id="running-text" scrollamount="6">Selamat Datang...</marquee>
        </div>
    </div>

    <div id="overlay-iqomah"><h1 style="font-size: 5rem;">WAKTU IQOMAH</h1><div id="iqomah-timer" style="font-size: 12rem; font-weight: bold;">00:00</div><p class="fs-2">Mohon Luruskan dan Rapatkan Shaf</p></div>
    <div id="overlay-sholat"></div>

    <script>
        const API_URL = 'http://localhost:8001';
        let appSettings = {}, prayerTimes = {}, slidersData = [], financeData = {};
        let sliderIndex = 0;
        const formatRupiah = (angka) => new Intl.NumberFormat('id-ID').format(angka);

        // --- 1. CLOCK ---
        setInterval(() => { 
            const now = new Date(); 
            document.getElementById('clock').innerText = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }); 
            document.getElementById('date-masehi').innerText = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }); 
        }, 1000);

        // --- 2. FETCH DATA ---
        async function fetchAllData() {
            try {
                // Settings
                const s = await fetch(`${API_URL}/settings`).then(r => r.json()); 
                appSettings = s; 
                if(s.nama_masjid) document.getElementById('masjid-name').innerText = s.nama_masjid; 
                if(s.alamat) document.getElementById('masjid-address').innerText = s.alamat; 
                if(s.running_text) document.getElementById('running-text').innerText = s.running_text;

                // Prayers
                const p = await fetch(`${API_URL}/prayers`).then(r => r.json()); 
                prayerTimes = p; 
                updatePrayerUI(p);

                // Finance
                financeData = await fetch(`${API_URL}/finances`).then(r => r.json()); 
                if(financeData) { 
                    document.getElementById('fin-saldo').innerText = formatRupiah(financeData.saldo); 
                    document.getElementById('fin-masuk').innerText = formatRupiah(financeData.pemasukan_total); 
                    document.getElementById('fin-keluar').innerText = formatRupiah(financeData.pengeluaran_total); 
                }

                // Sliders
                slidersData = await fetch(`${API_URL}/sliders`).then(r => r.json());
                
                // Cek apakah rotasi sudah jalan?
                if(!window.rotationStarted && slidersData.length > 0) { 
                    window.rotationStarted = true; 
                    runSliderRotation(); 
                }
            } catch (e) { console.error("Fetch Error:", e); }
        }

        // --- 3. PRAYER UI UPDATER ---
        function updatePrayerUI(data) {
            if(data.date_hijri) {
                document.getElementById('date-hijri').innerText = data.date_hijri;
            }
            document.getElementById('time-terbit').innerText = data.Terbit || '--:--';
            document.getElementById('time-subuh').innerText = data.Subuh;
            document.getElementById('time-dzuhur').innerText = data.Dzuhur;
            document.getElementById('time-ashar').innerText = data.Ashar;
            document.getElementById('time-maghrib').innerText = data.Maghrib;
            document.getElementById('time-isya').innerText = data.Isya;
            highlightNextPrayer(data);
        }

        // --- 4. HIGHLIGHT SCHEDULE ---
        function highlightNextPrayer(times) {
            const now = new Date();
            const curr = now.getHours() * 60 + now.getMinutes();
            const toMin = (s) => { if(!s) return 9999; const [h,m] = s.split(':'); return parseInt(h)*60 + parseInt(m); };

            ['subuh','dzuhur','ashar','maghrib','isya'].forEach(id => {
                document.getElementById(`box-${id}`).classList.remove('active');
                document.getElementById(`cd-${id}`).innerText = "";
            });

            let activeId = 'subuh'; 
            let targetTime = toMin(times.Subuh);

            if (curr < toMin(times.Subuh)) { activeId = 'subuh'; targetTime = toMin(times.Subuh); }
            else if (curr < toMin(times.Dzuhur)) { activeId = 'dzuhur'; targetTime = toMin(times.Dzuhur); }
            else if (curr < toMin(times.Ashar)) { activeId = 'ashar'; targetTime = toMin(times.Ashar); }
            else if (curr < toMin(times.Maghrib)) { activeId = 'maghrib'; targetTime = toMin(times.Maghrib); }
            else if (curr < toMin(times.Isya)) { activeId = 'isya'; targetTime = toMin(times.Isya); }

            const el = document.getElementById(`box-${activeId}`);
            if(el) {
                el.classList.add('active');
                const diff = targetTime - curr; 
                const elCd = document.getElementById(`cd-${activeId}`);
                if(diff > 0 && diff < 60) elCd.innerText = `-${diff} Menit`;
            }
        }

        // --- 5. LOGIKA IQOMAH CHECK ---
        function checkPrayerStatus() {
            if (!prayerTimes.Subuh || !appSettings.iqomah_minutes) return;
            const now = new Date();
            const curr = now.getHours() * 60 + now.getMinutes();
            const sec = now.getSeconds();
            const toMin = (s) => { const [h,m] = s.split(':'); return parseInt(h)*60 + parseInt(m); };
            
            const iqomahDur = parseInt(appSettings.iqomah_minutes);
            const sholatDur = parseInt(appSettings.standby_minutes);
            
            const prayers = [
                { t: toMin(prayerTimes.Subuh) }, { t: toMin(prayerTimes.Dzuhur) },
                { t: toMin(prayerTimes.Ashar) }, { t: toMin(prayerTimes.Maghrib) },
                { t: toMin(prayerTimes.Isya) }
            ];

            let mode = 'normal'; let targetIqomah = 0;

            prayers.forEach(p => {
                const adzan = p.t; const iqomah = adzan + iqomahDur; const selesai = iqomah + sholatDur;
                if (curr >= adzan && curr < iqomah) { mode = 'iqomah'; targetIqomah = iqomah; } 
                else if (curr >= iqomah && curr < selesai) { mode = 'sholat'; }
            });

            const elIqomah = document.getElementById('overlay-iqomah');
            const elSholat = document.getElementById('overlay-sholat');
            const elTimer = document.getElementById('iqomah-timer');

            if (mode === 'iqomah') {
                elIqomah.style.display = 'flex'; elSholat.style.display = 'none';
                const diffMin = targetIqomah - curr - 1;
                const diffSec = 60 - sec;
                const strSec = diffSec === 60 ? '00' : (diffSec < 10 ? '0'+diffSec : diffSec);
                elTimer.innerText = `${diffMin}:${strSec}`;
            } else if (mode === 'sholat') {
                elIqomah.style.display = 'none'; elSholat.style.display = 'flex';
            } else {
                elIqomah.style.display = 'none'; elSholat.style.display = 'none';
            }
        }

        // --- 6. LOGIKA ROTASI MULTIMEDIA ---
        function runSliderRotation() {
            const bgImage = document.getElementById('bg-image');
            const bgVideo = document.getElementById('bg-video');
            const overlayFinance = document.getElementById('overlay-finance');
            let sliderTimeout; 

            function nextSlide() {
                clearTimeout(sliderTimeout);

                // Pause jika sedang mode Iqomah/Sholat
                if(document.getElementById('overlay-iqomah').style.display === 'flex' || 
                   document.getElementById('overlay-sholat').style.display === 'flex') {
                    bgVideo.pause(); 
                    sliderTimeout = setTimeout(nextSlide, 5000); 
                    return;
                }

                bgVideo.onended = null; 

                if (sliderIndex < slidersData.length) {
                    overlayFinance.style.display = 'none';
                    
                    // === PERBAIKAN DI SINI (Variable yang tadi typo) ===
                    const currentSlide = slidersData[sliderIndex];

                    if (currentSlide.type === 'video') {
                        // MODE VIDEO
                        bgImage.classList.remove('media-active');
                        bgVideo.src = currentSlide.image_url;
                        bgVideo.classList.add('media-active'); 
                        bgVideo.play().catch(e => console.log("Autoplay blocked")); 

                        bgVideo.onended = function() {
                            sliderIndex++;
                            nextSlide(); 
                        };
                    } else {
                        // MODE GAMBAR
                        bgVideo.pause();
                        bgVideo.classList.remove('media-active'); 
                        bgImage.src = currentSlide.image_url;
                        bgImage.classList.add('media-active'); 
                        
                        sliderIndex++;
                        sliderTimeout = setTimeout(nextSlide, 10000);
                    }
                } else {
                    // MODE FINANCE
                    bgVideo.pause();
                    bgVideo.classList.remove('media-active');

                    if(financeData && financeData.saldo) {
                        overlayFinance.style.display = 'flex';
                        sliderIndex = 0; 
                        sliderTimeout = setTimeout(() => {
                            overlayFinance.style.display = 'none';
                            nextSlide();
                        }, 10000); 
                    } else {
                        sliderIndex = 0;
                        nextSlide();
                    }
                }
            }
            nextSlide();
        }

        // --- START ---
        fetchAllData();
        setInterval(fetchAllData, 60000);
        setInterval(checkPrayerStatus, 1000);

    </script>
</body>
</html>