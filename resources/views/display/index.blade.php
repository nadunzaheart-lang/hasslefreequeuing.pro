<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public Display - Registrar Queuing System</title>
    <link rel="stylesheet" href="{{ asset('css/display.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo2.png') }}">
</head>
<body>

    <div id="sound-overlay" style="position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,0.75);color:#fff;font-family:sans-serif;text-align:center;cursor:pointer;">
        <div>
            <div style="font-size:64px;">🔊</div>
            <div style="font-size:32px;font-weight:700;margin-top:12px;">Click anywhere to enable voice announcements</div>
            <div style="font-size:18px;opacity:0.8;margin-top:8px;">Required once by the browser each time this page is opened</div>
        </div>
    </div>

    <div class="display-screen" id="display-screen">
        <header class="display-header">
            <div class="school-brand">
                <img src="{{ asset('images/logo2.png') }}" alt="TCC Logo" class="brand-icon">
                <div class="school-details">
                    <h1>TAGOLOAN COMMUNITY COLLEGE</h1>
                    <h3>SCHOOL REGISTRAR</h3>
                </div>
            </div>
            <button id="fullscreen-btn" class="fullscreen-btn" onclick="toggleFullScreen()">
                ⛶ FULL SCREEN
            </button>
            <div class="header-time">
                <div id="current-time">10:45 AM</div>
                <div id="current-date">Monday, September 20, 2026</div>
            </div>
        </header>

        <main class="display-main">
            <section class="serving-section">
                <div class="section-title">
                    <span class="title-line"></span>
                    <h2>NOW SERVING</h2>
                    <span class="title-line"></span>
                </div>
                <div class="windows-grid" id="windows-grid"></div>
            </section>

            <section class="announcement-section">
                <div class="announcement-label">NOW CALLING</div>
                <div class="announcement-content">
                    <div class="announcement-queue" id="latest-queue">—</div>
                    <div class="announcement-message">
                        <strong>Please proceed to</strong>
                        <span id="latest-window">REGISTRAR WINDOW</span>
                    </div>
                </div>
            </section>

            <section class="bottom-section">
                <div class="next-queue">
                    <div class="bottom-title">NEXT IN QUEUE</div>
                    <div class="next-list" id="next-list">
                        <div class="next-item" style="opacity: 0.5;"><span>—</span></div>
                    </div>
                </div>
                <div class="display-instruction">
                    <div class="instruction-icon">!</div>
                    <div>
                        <h3>Please wait for your queue number.</h3>
                        <h5>You may continue your activities around campus. Return when your number is called.</h5>
                    </div>
                </div>
            </section>
        </main>

        <footer class="display-footer">
            <h1>Please listen for your queue number announcement.</h1>
            <h1>Thank you for using the Tagoloan Community College Registrar Queuing System.</h1>
        </footer>
    </div>

    <script>
        let lastAnnounced = {};
        let isBusySpeaking = false;
        let voiceQueue = [];
        let audioUnlocked = false;
        let firstLoadDone = false;
        let cachedVoice = null;
        const liveUtterances = [];

        function toggleFullScreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch((err) => console.log(err));
            } else {
                if (document.exitFullscreen) document.exitFullscreen();
            }
        }

        // --- VOICE SETUP ---
        function scoreVoice(v) {
            let score = 0;
            if (/natural|neural/i.test(v.name)) score += 100; 
            if (/online/i.test(v.name)) score += 40;
            if (/^Google (US|UK) English/i.test(v.name)) score += 60;
            if (/Aria|Jenny|Guy|Davis|Ryan|Sonia|Samantha|Daniel|Karen|Moira/i.test(v.name)) score += 30;
            if (/desktop|espeak|compact/i.test(v.name)) score -= 50;
            if (/^en[-_](US|GB|PH|AU)/i.test(v.lang)) score += 10;
            if (v.localService === false) score += 5;
            return score;
        }

        function englishVoices() {
            return window.speechSynthesis.getVoices()
                .filter(v => /^en[-_]/i.test(v.lang))
                .sort((a, b) => scoreVoice(b) - scoreVoice(a));
        }

        function pickVoice() {
            const list = englishVoices();
            if (!list.length) return null;
            let saved = null;
            try { saved = localStorage.getItem('displayVoice'); } catch (e) {}
            return list.find(v => v.name === saved) || list[0];
        }

        function showToast(text) {
            let t = document.getElementById('voice-toast');
            if (!t) {
                t = document.createElement('div');
                t.id = 'voice-toast';
                t.style.cssText = 'position:fixed;bottom:16px;right:16px;z-index:9998;background:rgba(0,0,0,0.8);color:#fff;padding:10px 16px;border-radius:8px;font:16px sans-serif;';
                document.body.appendChild(t);
            }
            t.textContent = text;
            clearTimeout(t._h);
            t._h = setTimeout(() => t.remove(), 4000);
        }

        function cycleVoice() {
            const list = englishVoices();
            if (!list.length) { showToast('No English voices found on this computer'); return; }
            const i = list.findIndex(v => cachedVoice && v.name === cachedVoice.name);
            cachedVoice = list[(i + 1) % list.length];
            try { localStorage.setItem('displayVoice', cachedVoice.name); } catch (e) {}
            showToast('Voice: ' + cachedVoice.name);
            speak('Queue number A, Zero Zero One. Please proceed to Registrar Window 1.');
        }

        function refreshVoice() {
            cachedVoice = pickVoice();
        }

        if ('speechSynthesis' in window) {
            refreshVoice();
            window.speechSynthesis.onvoiceschanged = refreshVoice;
        }

        function unlockAudio() {
            if (audioUnlocked || !('speechSynthesis' in window)) return;
            audioUnlocked = true;
            speak('Voice announcements enabled.'); 
            const overlay = document.getElementById('sound-overlay');
            if (overlay) overlay.remove();
            processVoiceQueue();
        }
        window.addEventListener('click', unlockAudio);
        window.addEventListener('keydown', unlockAudio);
        window.addEventListener('keydown', (e) => {
            if (audioUnlocked && (e.key === 'v' || e.key === 'V')) cycleVoice();
        });
        window.addEventListener('touchstart', unlockAudio);

        window.addEventListener('load', () => {
            fetchData(); 
        });

        function fetchData() {
            fetch('/display/data')
                .then(response => response.json())
                .then(data => {
                    renderUI(data);
                    checkForNewCalls(data);
                    firstLoadDone = true;
                    setTimeout(fetchData, 1000);
                })
                .catch(err => {
                    console.log("Error fetching data:", err);
                    setTimeout(fetchData, 3000); 
                });
        }

        function renderUI(data) {
            const windowsGrid = document.getElementById('windows-grid');
            windowsGrid.innerHTML = '';

            for (let i = 1; i <= 5; i++) {
                const windowId = String(i).padStart(2, '0');
                const queue = data.windows[windowId];

                if (queue) {
                    windowsGrid.innerHTML += `
                        <div class="window-card active">
                            <div class="window-header"><span>REGISTRAR WINDOW</span><strong>${windowId}</strong></div>
                            <div class="queue-number">${queue.queue_number}</div>
                            <div class="window-status">NOW SERVING</div>
                        </div>`;
                } else {
                    windowsGrid.innerHTML += `
                        <div class="window-card available">
                            <div class="window-header"><span>REGISTRAR WINDOW</span><strong>${windowId}</strong></div>
                            <div class="queue-number">—</div>
                            <div class="window-status">AVAILABLE</div>
                        </div>`;
                }
            }

            const keys = Object.keys(data.windows);
            if (keys.length > 0) {
                const latestWin = keys[keys.length - 1];
                const latestQueue = data.windows[latestWin];
                document.getElementById('latest-queue').innerText = latestQueue.queue_number;
                document.getElementById('latest-window').innerText = `REGISTRAR WINDOW ${latestWin.replace(/^0+/, '')}`;
            } else {
                document.getElementById('latest-queue').innerText = '—';
                document.getElementById('latest-window').innerText = 'REGISTRAR WINDOW';
            }

            const nextList = document.getElementById('next-list');
            nextList.innerHTML = '';
            if (data.waiting.length > 0) {
                data.waiting.forEach(w => {
                    nextList.innerHTML += `<div class="next-item"><span>${w.queue_number}</span></div>`;
                });
            } else {
                nextList.innerHTML = `<div class="next-item" style="opacity: 0.5;"><span>—</span></div>`;
            }
        }

        function checkForNewCalls(data) {
            Object.entries(data.windows).forEach(([winNum, queue]) => {
                if (lastAnnounced[winNum] !== queue.queue_number) {
                    lastAnnounced[winNum] = queue.queue_number;
                    if (firstLoadDone && audioUnlocked) {
                        voiceQueue.push({ window: winNum, queue: queue.queue_number });
                    }
                }
            });

            Object.keys(lastAnnounced).forEach(winNum => {
                if (!data.windows[winNum]) delete lastAnnounced[winNum];
            });

            processVoiceQueue();
        }

        // "A-001" -> "A, Zero Zero One" (Forcing the AI to say Zero)
        function spellQueueNumber(queueNumber) {
            const m = String(queueNumber).trim().match(/^([A-Za-z]+)[\s-]*(\d+)$/);
            const numberMap = {'0':'Zero','1':'One','2':'Two','3':'Three','4':'Four','5':'Five','6':'Six','7':'Seven','8':'Eight','9':'Nine'};
            
            if (m) {
                // Spell out the letter and then convert each number to a word
                const letter = m[1].split('').join(' ');
                const numbers = m[2].split('').map(num => numberMap[num]).join(' ');
                return `${letter}, ${numbers}`;
            }
            
            // Fallback for weird formats
            return String(queueNumber).replace(/[^A-Za-z0-9]/g, '').split('').map(char => numberMap[char] || char).join(' ');
        }

        function speak(message) {
            return new Promise(resolve => {
                const synth = window.speechSynthesis;
                const utterance = new SpeechSynthesisUtterance(message);
                liveUtterances.push(utterance);

                if (!cachedVoice) refreshVoice();
                if (cachedVoice) {
                    utterance.voice = cachedVoice;
                    utterance.lang = cachedVoice.lang;
                } else {
                    utterance.lang = 'en-US';
                }

                utterance.rate = 0.85; 
                utterance.pitch = 0.95; 
                utterance.volume = 1;

                let done = false;
                const finish = () => {
                    if (done) return;
                    done = true;
                    clearTimeout(timer);
                    const i = liveUtterances.indexOf(utterance);
                    if (i !== -1) liveUtterances.splice(i, 1);
                    setTimeout(resolve, 300); 
                };
                const timer = setTimeout(finish, 15000); 

                utterance.onend = finish;
                utterance.onerror = (e) => { console.log('Speech error:', e.error); finish(); };

                try { synth.resume(); } catch (e) {}
                synth.speak(utterance);
            });
        }

        async function processVoiceQueue() {
            if (!('speechSynthesis' in window)) return;
            if (!audioUnlocked || isBusySpeaking || voiceQueue.length === 0) return;
            isBusySpeaking = true;

            try {
                const nextCall = voiceQueue.shift();
                const spokenNumber = spellQueueNumber(nextCall.queue);
                const spokenWindow = nextCall.window.replace(/^0+/, '');
                const message = `Queue number ${spokenNumber}. Please proceed to Registrar Window ${spokenWindow}.`;

                for (let i = 0; i < 3; i++) {
                    await speak(message);
                    if (i < 2) await new Promise(r => setTimeout(r, 1000));
                }
            } catch (err) {
                console.log('Voice announcement error:', err);
            } finally {
                isBusySpeaking = false;
            }

            processVoiceQueue(); 
        }

        setInterval(() => {
            const now = new Date();
            document.getElementById('current-time').innerText = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
            document.getElementById('current-date').innerText = now.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });
        }, 1000);
    </script>
</body>
</html>