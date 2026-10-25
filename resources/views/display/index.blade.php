<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public Display - Registrar Queuing System</title>
    <link rel="stylesheet" href="{{ asset('css/display.css') }}">
</head>
<body>

    <div class="display-screen">
        <header class="display-header">
            <div class="school-brand">
                <img src="{{ asset('images/logo2.jpeg') }}" alt="TCC Logo" class="brand-icon">
                <div class="school-details">
                    <h1>TAGOLOAN COMMUNITY COLLEGE</h1>
                    <h3>SCHOOL REGISTRAR</h3>
                </div>
            </div>
            <button id="fullscreen-btn" class="fullscreen-btn" onclick="toggleFullscreen()">
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

                <div class="windows-grid">
                    @for ($i = 1; $i <= 5; $i++)
                        @php
                            $windowId = str_pad($i, 2, '0', STR_PAD_LEFT);
                            $isServing = isset($windows[$windowId]);
                            $queue = $isServing ? $windows[$windowId] : null;
                        @endphp

                        @if($isServing)
                            <div class="window-card active">
                                <div class="window-header">
                                    <span>REGISTRAR WINDOW</span>
                                    <strong>{{ $windowId }}</strong>
                                </div>
                                <div class="queue-number">
                                    {{ $queue->queue_number }}
                                </div>
                                <div class="window-status">
                                    NOW SERVING
                                </div>
                            </div>
                        @else
                            <div class="window-card available">
                                <div class="window-header">
                                    <span>REGISTRAR WINDOW</span>
                                    <strong>{{ $windowId }}</strong>
                                </div>
                                <div class="queue-number">
                                    —
                                </div>
                                <div class="window-status">
                                    AVAILABLE
                                </div>
                            </div>
                        @endif
                    @endfor
                </div>
            </section>

            @php
                $latestCall = count($windows) > 0 ? end($windows) : null;
            @endphp
            <section class="announcement-section">
                <div class="announcement-label">
                    NOW CALLING
                </div>
                <div class="announcement-content">
                    <div class="announcement-queue">
                        {{ $latestCall ? $latestCall->queue_number : '—' }}
                    </div>
                    <div class="announcement-message">
                        <strong>Please proceed to</strong>
                        <span>REGISTRAR WINDOW {{ $latestCall ? array_search($latestCall, $windows) : '01' }}</span>
                    </div>
                </div>
            </section>

            <section class="bottom-section">
                <div class="next-queue">
                    <div class="bottom-title">
                        NEXT IN QUEUE
                    </div>
                    <div class="next-list">
                        @forelse($waiting as $w)
                            <div class="next-item">
                                <span>{{ $w->queue_number }}</span>
                            </div>
                        @empty
                            <div class="next-item" style="opacity: 0.5;"><span>—</span></div>
                        @endforelse
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
        const activeWindows = [
            @foreach($windows as $winNum => $queue)
                { window: "{{ $winNum }}", queue: "{{ $queue->queue_number }}" },
            @endforeach
        ];
        
        let lastAnnounced = {};
        let isBusySpeaking = false;
        let audioUnlocked = false;

        // Unlock audio on first click
        document.body.addEventListener('click', () => {
            if (!audioUnlocked) {
                audioUnlocked = true;
                // Play a silent utterance to wake up the browser's audio engine
                let silent = new SpeechSynthesisUtterance(" ");
                silent.volume = 0;
                window.speechSynthesis.speak(silent);
            }
        });

        async function speakAnnouncement(winNum, queueNum) {
            if (!audioUnlocked || isBusySpeaking) return;
            isBusySpeaking = true;

            const spokenNumber = queueNum.split('').join(' ');
            
            for (let i = 0; i < 3; i++) {
                const message = `Queue number ${spokenNumber}, please proceed to Registrar Window ${winNum}`;
                const utterance = new SpeechSynthesisUtterance(message);
                
                // Find Male Voice
                const voices = window.speechSynthesis.getVoices();
                let selectedVoice = voices.find(v => 
                    v.name === 'Google UK English Male' || 
                    v.name === 'Microsoft David - English (United States)' ||
                    v.name.includes('Male')
                );
                
                if (selectedVoice) utterance.voice = selectedVoice;
                
                utterance.rate = 0.85;
                utterance.pitch = 0.85;
                utterance.volume = 1;

                await new Promise(resolve => {
                    utterance.onend = resolve;
                    window.speechSynthesis.speak(utterance);
                });

                if (i < 2) await new Promise(r => setTimeout(r, 1500));
            }
            
            isBusySpeaking = false;
        }

        function checkForNewCalls() {
            activeWindows.forEach(active => {
                const winNum = active.window;
                const queueNum = active.queue;

                if (lastAnnounced[winNum] !== queueNum) {
                    lastAnnounced[winNum] = queueNum;
                    speakAnnouncement(winNum, queueNum);
                }
            });
        }

        window.onload = function() {
            setTimeout(checkForNewCalls, 1000);
        };

        // Auto-Refresh (Waits if speaking so it doesn't cut off)
        setInterval(() => {
            if (isBusySpeaking || window.speechSynthesis.speaking) return; 

            fetch(window.location.href)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const scripts = doc.querySelectorAll('script');
                    let newActiveWindows = [];
                    
                    scripts.forEach(s => {
                        if (s.textContent.includes('const activeWindows =')) {
                            const match = s.textContent.match(/const activeWindows = (\[[\s\S]*?\]);/);
                            if (match) newActiveWindows = eval(match[1]);
                        }
                    });

                    if(JSON.stringify(activeWindows) !== JSON.stringify(newActiveWindows)) {
                        window.location.reload(); 
                    }
                });
        }, 5000);
    </script>
</body>
</html>