<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public Display - Registrar Queuing System</title>
    <link rel="stylesheet" href="{{ asset('css/display.css') }}">
</head>
<body>

    <div class="display-screen" id="display-screen">
        <header class="display-header">
            <div class="school-brand">
                <img src="{{ asset('images/logo2.jpeg') }}" alt="TCC Logo" class="brand-icon">
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

        function toggleFullScreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch((err) => console.log(err));
            } else {
                if (document.exitFullscreen) document.exitFullscreen();
            }
        }

        // Unlock audio on first click
        window.addEventListener('load', () => {
            let silentUtterance = new SpeechSynthesisUtterance(" ");
            silentUtterance.volume = 0;
            window.speechSynthesis.speak(silentUtterance);
            fetchData(); // Start fetching immediately
        });

        // --- FAST FETCH DATA ---
        function fetchData() {
            fetch('/display/data')
                .then(response => response.json())
                .then(data => {
                    renderUI(data);
                    checkForNewCalls(data);
                    setTimeout(fetchData, 1000); // Fetch again 1 second later
                })
                .catch(err => {
                    console.log("Error fetching data:", err);
                    setTimeout(fetchData, 3000); // Retry after 3 seconds if error
                });
        }

        // --- UPDATE THE UI ---
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

        // --- VOICE ANNOUNCEMENT ---
        function checkForNewCalls(data) {
            Object.entries(data.windows).forEach(([winNum, queue]) => {
                if (lastAnnounced[winNum] !== queue.queue_number) {
                    lastAnnounced[winNum] = queue.queue_number;
                    voiceQueue.push({ window: winNum, queue: queue.queue_number });
                }
            });
            processVoiceQueue();
        }

        async function processVoiceQueue() {
            if (isBusySpeaking || voiceQueue.length === 0) return;
            isBusySpeaking = true;

            const nextCall = voiceQueue.shift();
            // Add spaces between letters so it says "A - 0 - 0 - 1"
            const spokenNumber = nextCall.queue.split('').join(' ');
            const spokenWindow = nextCall.window.replace(/^0+/, '');

            for (let i = 0; i < 3; i++) {
                // Natural phrasing
                const message = `Queue number ${spokenNumber}, please proceed to Registrar Window ${spokenWindow}`;
                const utterance = new SpeechSynthesisUtterance(message);
                
                // Find the best male voice available on the system
                const voices = window.speechSynthesis.getVoices();
                let selectedVoice = voices.find(v => 
                    v.name === 'Google UK English Male' || 
                    v.name === 'Microsoft David - English (United States)' ||
                    v.name.includes('Male')
                );
                
                if (selectedVoice) utterance.voice = selectedVoice;
                
                // --- HUMAN VOICE TUNING ---
                utterance.rate = 0.8;    // Speaks a bit slower (more professional)
                utterance.pitch = 0.85;  // Slightly deeper tone
                utterance.volume = 1;    // Maximum volume

                await new Promise(resolve => {
                    utterance.onend = resolve;
                    window.speechSynthesis.speak(utterance);
                });

                // Pause for 1.5 seconds between the 3 repeats
                if (i < 2) await new Promise(r => setTimeout(r, 1500));
            }
            
            isBusySpeaking = false;
            processVoiceQueue(); // Check if there is another number waiting to be announced
        }

        // Clock Update
        setInterval(() => {
            const now = new Date();
            document.getElementById('current-time').innerText = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
            document.getElementById('current-date').innerText = now.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });
        }, 1000);
    </script>
</body>
</html>