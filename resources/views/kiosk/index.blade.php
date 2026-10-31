<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Registrar Queuing System</title>
    <link rel="stylesheet" href="{{ asset('css/kiosk.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <style>
        body, html { margin: 0; padding: 0; height: 100%; overflow: hidden; }
        .kiosk-screen { height: 100vh; display: flex; flex-direction: column; justify-content: space-between; }
    </style>
</head>
<body class="kiosk-body">

    <main class="kiosk-screen kiosk-index-screen">
        <header class="kiosk-header">
            <div class="kiosk-brand">
                <div class="kiosk-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="Tagoloan Community College" class="welcome-logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                </div>
                <div><h1>Tagoloan Community College - Registrar</h1></div>
            </div>
            <div class="kiosk-header-actions">
                <div class="kiosk-clock">
                    <strong id="kioskTime">--:--</strong>
                    <span id="kioskDate">Loading...</span>
                </div>
                <!-- FULLSCREEN BUTTON REMOVED -->
            </div>
        </header>

        <section class="welcome-content" style="flex-grow: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 20px;">
            <div class="welcome-logo-wrapper">
                <img src="{{ asset('images/logo.png') }}" alt="Tagoloan Community College" class="welcome-logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="welcome-icon" style="display: none;">TCC</div>
            </div>
            <span class="welcome-label">SCHOOL REGISTRAR</span>
            <h2>Welcome!</h2>
            <p class="welcome-title">Get your queue number<br>without waiting in line.</p>
            <p class="welcome-description">Enter your information, choose your transaction, and receive your queue number.</p>

            <a href="{{ route('kiosk.student-info') }}" class="kiosk-primary-button">
                <span>GET QUEUE NUMBER</span>
                <strong>→</strong>
            </a>
            <div class="welcome-note">
                <span class="note-icon">ℹ</span>
                <p>After getting your queue number, you may leave the registrar area and return when your number is announced.</p>
            </div>
        </section>

        <footer class="kiosk-footer">
            <span>Please prepare your valid Student ID.</span>
            <span>Registrar Office</span>
        </footer>
    </main>

    <script>
        function updateKioskClock() {
            const now = new Date();
            document.getElementById("kioskTime").textContent = now.toLocaleTimeString("en-US", { hour: "2-digit", minute: "2-digit" });
            document.getElementById("kioskDate").textContent = now.toLocaleDateString("en-US", { weekday: "long", month: "long", day: "numeric", year: "numeric" });
        }
        updateKioskClock();
        setInterval(updateKioskClock, 1000);
    </script>
</body>
</html>