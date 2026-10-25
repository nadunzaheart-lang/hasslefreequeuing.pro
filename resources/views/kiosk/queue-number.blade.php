<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"
    >

    <title>Your Queue Number</title>

    <link rel="stylesheet" href="{{ asset('css/kiosk.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

</head>


<body class="kiosk-body">

<main class="kiosk-screen">


    <!-- HEADER -->
    <header class="kiosk-header">

        <div class="kiosk-brand">

            <img
                src="{{ asset('images/logo.png') }}"
                alt="TCC Logo"
                class="kiosk-logo"
            >

            <div>
                <h1>Tagoloan Community College - Registrar</h1>
            </div>

        </div>

        <div class="kiosk-header-actions">

            <div class="step-indicator">
                STEP 3 OF 3
            </div>

            <button
                type="button"
                class="kiosk-fullscreen-toggle"
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


    <!-- RESULT -->
    <section class="queue-result-screen">

        <div class="success-icon">
            ✓
        </div>

        <span class="result-label">
            YOUR QUEUE NUMBER
        </span>

        <div class="queue-number-display">
            {{ $queue->queue_number }}
        </div>

        <p class="result-message">
            Your queue number has been successfully generated.
        </p>


        <!-- QUEUE INFORMATION -->
        <div class="queue-info-card">

            <div class="queue-info-row">

                <span>Student</span>

                <strong>
                    {{ $queue->student_name }}
                </strong>

            </div>


            <div class="queue-info-row">

                <span>Student ID</span>

                <strong>
                    {{ $queue->student_id }}
                </strong>

            </div>


            <div class="queue-info-row">

                <span>Transaction</span>

                <strong>
                    {{ $queue->transaction }}
                </strong>

            </div>


            <div class="queue-info-divider"></div>


            <div class="queue-estimates">

                <div>

                    <span>
                        STUDENTS AHEAD
                    </span>

                    <strong>
                        4
                    </strong>

                </div>


                <div>

                    <span>
                        EST. WAIT
                    </span>

                    <strong>
                        ~20 min
                    </strong>

                </div>

            </div>

        </div>


        <!-- IMPORTANT MESSAGE -->
        <div class="return-message">

            <div class="return-icon">
                🔊
            </div>

            <div>

                <h3>
                    You do not need to stay in line.
                </h3>

                <h6>
                    You may continue your activities around campus.
                    Please return to the registrar when your queue
                    number is announced.
                </h6>

            </div>

        </div>


        <!-- ACTIONS -->
        <div class="queue-actions">

            <a
                href="{{ route('kiosk.transaction') }}"
                class="kiosk-back-button"
            >
                ← Back
            </a>

            <button
                onclick="window.print()"
                class="print-button"
            >
                <span>▣</span>
                PRINT RECEIPT
            </button>

            <a
                href="{{ route('kiosk.index') }}"
                class="finish-button"
            >
                DONE
            </a>

        </div>

    </section>


    <footer class="kiosk-footer">

        <span>
            Please keep your queue receipt.
        </span>

        <span>
            Queue number: <strong>{{ $queue->queue_number }}</strong>
        </span>

    </footer>

</main>

<script src="{{ asset('js/kiosk.js') }}"></script>

</body>
</html>