<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Your Queue Number</title>
    <link rel="stylesheet" href="{{ asset('css/kiosk.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <style>
        body, html { margin: 0; padding: 0; height: 100%; overflow: hidden; }
        .kiosk-screen { height: 100vh; display: flex; flex-direction: column; }
        .queue-result-screen { flex-grow: 1; overflow-y: auto; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 20px; }
    </style>
</head>
<body class="kiosk-body">

<main class="kiosk-screen">
    <header class="kiosk-header">
        <div class="kiosk-brand">
            <img src="{{ asset('images/logo.png') }}" alt="TCC Logo" class="kiosk-logo">
            <div><h1>Tagoloan Community College - Registrar</h1></div>
        </div>
        <div class="kiosk-header-actions">
            <div class="step-indicator">STEP 3 OF 3</div>
            <!-- FULLSCREEN BUTTON REMOVED -->
        </div>
    </header>

    <section class="queue-result-screen" style="text-align: center;">
        <div class="success-icon" style="margin: 0 auto;">✓</div>
        <span class="result-label">YOUR QUEUE NUMBER</span>
        <div class="queue-number-display">{{ $queue->queue_number }}</div>
        <p class="result-message">Your queue number has been successfully generated.</p>

        <div class="queue-info-card" style="text-align: left; width: 100%; max-width: 500px;">
            <div class="queue-info-row">
                <span>Student</span>
                <strong>{{ $queue->student_name }}</strong>
            </div>
            <div class="queue-info-row">
                <span>Student ID</span>
                <strong>{{ $queue->student_id }}</strong>
            </div>
            <div class="queue-info-row">
                <span>Transaction</span>
                <strong>{{ $queue->transaction }}</strong>
            </div>
            <div class="queue-info-divider"></div>
            <div class="queue-estimates">
                <div>
                    <span>STUDENTS AHEAD</span>
                    <strong>4</strong>
                </div>
                <div>
                    <span>EST. WAIT</span>
                    <strong>~20 min</strong>
                </div>
            </div>
        </div>

        <div class="return-message" style="max-width: 500px; text-align: left;">
            <div class="return-icon">🔊</div>
            <div>
                <h3>You do not need to stay in line.</h3>
                <h6>You may continue your activities around campus. Please return to the registrar when your queue number is announced.</h6>
            </div>
        </div>

        <div class="queue-actions" style="width: 100%; max-width: 500px; display: flex; justify-content: center; gap: 10px;">
            <a href="{{ route('kiosk.transaction') }}" class="kiosk-back-button">← Back</a>
            <button onclick="window.print()" class="print-button"><span>▣</span> PRINT RECEIPT</button>
            <a href="{{ route('kiosk.index') }}" class="finish-button">DONE</a>
        </div>
    </section>

    <footer class="kiosk-footer">
        <span>Please keep your queue receipt.</span>
        <span>Queue number: <strong>{{ $queue->queue_number }}</strong></span>
    </footer>
</main>
</body>
</html>