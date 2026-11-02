<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Window - Staff</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/staff.css') }}">
    <link rel="stylesheet" href="{{ asset('css/signout.css') }}">
</head>
<body>

<div class="staff-app">
    <aside class="staff-sidebar">
        <div class="staff-brand">
            <img src="{{ asset('images/logo.png') }}" alt="TCC Logo" width="50" height="50">
            <div><h1>Registrar</h1><span>Queuing System</span></div>
        </div>

        <div class="window-profile">
            <span class="profile-label">ASSIGNED WINDOW</span>
            <div class="window-profile-number" id="side-window-num">WINDOW --</div>
            <div class="staff-online"><span></span> Online</div>
        </div>

        <nav class="staff-navigation">
            <span class="navigation-label">MENU</span>
            <!-- FIXED: Using buttons instead of links to prevent URL changes -->
            <button onclick="scrollToSection('dashboard')" class="staff-nav-item active" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer;">
                <span class="nav-symbol">▦</span> Dashboard
            </button>
            <button onclick="scrollToSection('queue')" class="staff-nav-item" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer;">
                <span class="nav-symbol">☷</span> My Queue
            </button>
            <button onclick="scrollToSection('history')" class="staff-nav-item" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer;">
                <span class="nav-symbol">◷</span> My History
            </button>
        </nav>

        <div class="staff-sidebar-bottom">
            <div class="staff-user">
                <div class="staff-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                <div class="staff-user-info">
                    <strong>{{ Auth::user()->name }}</strong>
                    <span>Window Staff</span>
                </div>
            </div>
            @include('partials.signout')
        </div>
    </aside>

    <main class="staff-main" id="dashboard">
        <header class="staff-topbar">
            <div>
                <span class="topbar-label">REGISTRAR WINDOW</span>
                <h2 id="top-window-num">Window --</h2>
            </div>
            <div class="topbar-right">
                <div class="topbar-clock">
                    <strong id="staffTime">--:--</strong>
                    <span id="staffDate">Loading...</span>
                </div>
                <div class="staff-top-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
            </div>
        </header>

        <section class="staff-content">
            <div class="staff-page-heading">
                <div>
                    <h2>Good day, {{ Auth::user()->name }}</h2>
                    <p>Manage the students assigned to your window.</p>
                </div>
                <div class="window-status-large"><span></span> Window Online</div>
            </div>

            <div class="current-section">
                <div class="section-title">
                    <div><span>CURRENTLY SERVING</span><h3>Student Transaction</h3></div>
                    <span class="serving-badge">SERVING</span>
                </div>

                <div id="serving-container">
                    <div class="current-card" style="justify-content: center; padding: 40px; text-align: center; color: var(--color-muted);">
                        <h3>Loading...</h3>
                    </div>
                </div>

                <div class="service-actions">
                    <form action="{{ route('staff.skip') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" id="btn-skip" class="service-button skip" disabled>
                            <span>→</span> SKIP
                        </button>
                    </form>
                    <form action="{{ route('staff.complete') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" id="btn-complete" class="service-button complete" disabled>
                            <span>✓</span> COMPLETE TRANSACTION
                        </button>
                    </form>
                </div>
            </div>

            <div class="queue-section" id="queue">
                <div class="section-title">
                    <div><span>UP NEXT</span><h3>Waiting Queue</h3></div>
                    <span class="waiting-count" id="waiting-count">-- waiting</span>
                </div>

                <div class="queue-list" id="waiting-list">
                    <p style="padding: 20px; color: var(--color-muted);">Loading...</p>
                </div>

                <form action="{{ route('staff.call-next') }}" method="POST">
                    @csrf
                    <button type="submit" id="btn-call-next" class="call-next-button" style="width: 100%;" disabled>
                        <span class="call-icon">→</span>
                        <span style="text-align: left;">
                            <strong>CALL NEXT STUDENT</strong>
                            <small id="call-next-text">Loading...</small>
                        </span>
                    </button>
                </form>
            </div>

            <div class="history-section" id="history">
                <div class="section-title">
                    <div><span>TODAY</span><h3>My Activity</h3></div>
                </div>
                <div class="activity-grid">
                    <div class="activity-card">
                        <div class="activity-icon completed">✓</div>
                        <div><strong>18</strong><span>Completed</span></div>
                    </div>
                    <div class="activity-card">
                        <div class="activity-icon skipped">→</div>
                        <div><strong>2</strong><span>Skipped</span></div>
                    </div>
                    <div class="activity-card">
                        <div class="activity-icon time">◷</div>
                        <div><strong>6 min</strong><span>Avg. Service Time</span></div>
                    </div>
                    <div class="activity-card">
                        <div class="activity-icon total">☷</div>
                        <div><strong>20</strong><span>Total Served</span></div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>

<script src="{{ asset('js/signout.js') }}"></script>
<script>
    let lastQueueCount = 0;
    let audioContext = null;

    function scrollToSection(id) {
        const element = document.getElementById(id);
        if (element) {
            element.scrollIntoView({ behavior: 'smooth' });
        }
    }

    function playDing() {
        try {
            if (!audioContext) {
                audioContext = new (window.AudioContext || window.webkitAudioContext)();
            }
            if (audioContext.state === 'suspended') {
                audioContext.resume();
            }

            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();

            oscillator.type = 'sine';
            oscillator.frequency.setValueAtTime(880, audioContext.currentTime);
            gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.001, audioContext.currentTime + 0.5);

            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);

            oscillator.start();
            oscillator.stop(audioContext.currentTime + 0.5);
        } catch (e) {
            console.log("Audio blocked by browser");
        }
    }

    setInterval(() => {
        const now = new Date();
        document.getElementById('staffTime').innerText = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
        document.getElementById('staffDate').innerText = now.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });
    }, 1000);

    function fetchData() {
        // FIXED: Explicitly tell the browser exactly where to fetch data
        fetch(window.location.origin + '/staff/data')
            .then(res => res.json())
            .then(data => {
                renderUI(data);
                setTimeout(fetchData, 1000);
            })
            .catch(err => {
                console.log("Error fetching data:", err);
                setTimeout(fetchData, 3000);
            });
    }

    function renderUI(data) {
        document.getElementById('side-window-num').innerText = `WINDOW ${data.windowNumber}`;
        document.getElementById('top-window-num').innerText = `Window ${data.windowNumber}`;

        const servingContainer = document.getElementById('serving-container');
        const btnSkip = document.getElementById('btn-skip');
        const btnComplete = document.getElementById('btn-complete');

        if (data.waitingCount > lastQueueCount && lastQueueCount !== 0) {
            playDing();
        }
        lastQueueCount = data.waitingCount;

        if (data.serving) {
            const initials = data.serving.student_name.substring(0, 2).toUpperCase();
            servingContainer.innerHTML = `
                <div class="current-card">
                    <div class="current-queue"><span>QUEUE NUMBER</span><strong>${data.serving.queue_number}</strong></div>
                    <div class="current-student">
                        <div class="large-student-avatar">${initials}</div>
                        <div>
                            <span>STUDENT</span>
                            <h3>${data.serving.student_name}</h3>
                            <p>Student ID: <strong>${data.serving.student_id}</strong></p>
                        </div>
                    </div>
                    <div class="current-transaction"><span>TRANSACTION</span><strong>${data.serving.transaction}</strong></div>
                </div>`;
            btnSkip.disabled = false;
            btnComplete.disabled = false;
        } else {
            servingContainer.innerHTML = `
                <div class="current-card" style="justify-content: center; padding: 40px; text-align: center; color: var(--color-muted);">
                    <h3>No student currently serving.</h3>
                    <p>Click "Call Next Student" below to start.</p>
                </div>`;
            btnSkip.disabled = true;
            btnComplete.disabled = true;
        }

        const waitingList = document.getElementById('waiting-list');
        const waitingCount = document.getElementById('waiting-count');
        const btnCallNext = document.getElementById('btn-call-next');
        const callNextText = document.getElementById('call-next-text');

        waitingCount.innerText = `${data.waitingCount} waiting`;

        if (data.waiting.length > 0) {
            waitingList.innerHTML = '';
            data.waiting.forEach(w => {
                waitingList.innerHTML += `
                    <div class="next-queue-item">
                        <div class="next-number">${w.queue_number}</div>
                        <div class="next-student"><strong>${w.student_name}</strong><span>${w.student_id}</span></div>
                        <div class="next-service">${w.transaction}</div>
                        <div class="next-wait">Waiting</div>
                    </div>`;
            });
            btnCallNext.disabled = false;
            callNextText.innerText = `${data.waiting[0].queue_number} will be called to Window ${data.windowNumber}`;
        } else {
            waitingList.innerHTML = `<p style="padding: 20px; color: var(--color-muted);">No students waiting.</p>`;
            btnCallNext.disabled = true;
            callNextText.innerText = `No students waiting`;
        }
    }

    window.onload = fetchData;
</script>
</body>
</html>
