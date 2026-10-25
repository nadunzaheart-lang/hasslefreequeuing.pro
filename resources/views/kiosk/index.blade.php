<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"
    >

    <title>Registrar Queuing System</title>

    <link rel="stylesheet" href="{{ asset('css/kiosk.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

</head>


<body class="kiosk-body">

    <main class="kiosk-screen kiosk-index-screen">

        <!-- TOP BRAND -->
        <header class="kiosk-header">

            <div class="kiosk-brand">

                <div class="kiosk-logo">
                    <img
                    src="{{ asset('images/logo.png') }}"
                    alt="Tagoloan Community College"
                    class="welcome-logo"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                >   
                </div>

                <div>
                    <h1>Tagoloan Community College - Registrar</h1>
                </div>

            </div>

            <div class="kiosk-header-actions">

                <div class="kiosk-clock">
                    <strong id="kioskTime">--:--</strong>
                    <span id="kioskDate">Loading...</span>
                </div>

                <button
                    type="button"
                    class="kiosk-fullscreen-toggle hide-in-fullscreen"
                    id="kioskFullscreenBtn"
                    onclick="toggleKioskFullscreen()"
                    aria-label="Toggle Fullscreen"
                    title="Full Screen"
                >
                    <svg class="fullscreen-icon-enter" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/>
                    </svg>
                    <svg class="fullscreen-icon-exit" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 3v3a2 2 0 0 1-2 2H3m18 0h-3a2 2 0 0 1-2-2V3m0 18v-3a2 2 0 0 1 2-2h3M3 16h3a2 2 0 0 1 2 2v3"/>
                    </svg>
                    <span class="fullscreen-btn-text">Full Screen</span>
                </button>

            </div>

        </header>


        <!-- WELCOME CONTENT -->
        <section class="welcome-content">

            <div class="welcome-logo-wrapper">
                <img
                    src="{{ asset('images/logo.png') }}"
                    alt="Tagoloan Community College"
                    class="welcome-logo"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                >
                <div class="welcome-icon" style="display: none;">
                    TCC
                </div>
            </div>

            <span class="welcome-label">
                SCHOOL REGISTRAR
            </span>

            <h2>
                Welcome!
            </h2>

            <p class="welcome-title">
                Get your queue number
                <br>
                without waiting in line.
            </p>

            <p class="welcome-description">
                Enter your information, choose your transaction,
                and receive your queue number.
            </p>


            <a
                href="{{ route('kiosk.student-info') }}"
                class="kiosk-primary-button"
            >
                <span>GET QUEUE NUMBER</span>
                <strong>→</strong>
            </a>


            <div class="welcome-note">

                <span class="note-icon">
                    ℹ
                </span>

                <p>
                    After getting your queue number, you may leave
                    the registrar area and return when your number
                    is announced.
                </p>

            </div>

        </section>


        <!-- FOOTER -->
        <footer class="kiosk-footer">

            <span>
                Please prepare your valid Student ID.
            </span>

            <span>
                Registrar Office
            </span>

        </footer>

    </main>


    <script>

        function updateKioskClock() {

            const now = new Date();

            document.getElementById("kioskTime").textContent =
                now.toLocaleTimeString("en-US", {
                    hour: "2-digit",
                    minute: "2-digit"
                });

            document.getElementById("kioskDate").textContent =
                now.toLocaleDateString("en-US", {
                    weekday: "long",
                    month: "long",
                    day: "numeric",
                    year: "numeric"
                });
        }

        updateKioskClock();

        setInterval(updateKioskClock, 1000);

    </script>

    <script src="{{ asset('js/kiosk.js') }}"></script>

</body>

</html>
