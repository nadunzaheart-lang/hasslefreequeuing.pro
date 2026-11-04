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

        /* ===== PRINT RECEIPT (hidden on screen, shown only when printing) ===== */
        .print-receipt { display: none; }

        @media print {
            /* ===== THERMAL PAPER WIDTH =====
               80mm roll -> --paper-width: 72mm  (printable area)  and  @page size: 80mm auto
               58mm roll -> --paper-width: 48mm  (printable area)  and  @page size: 58mm auto  */
            :root { --paper-width: 72mm; }

            @page { size: 80mm auto; margin: 0; }

            html, body { height: auto !important; overflow: visible !important; background: #fff !important; margin: 0 !important; padding: 0 !important; }
            .kiosk-body { display: block !important; min-height: 0 !important; }
            .kiosk-screen { display: none !important; }

            .print-receipt, .print-receipt * {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                box-sizing: border-box;
            }
            .print-receipt {
                display: block;
                width: var(--paper-width);
                margin: 0 auto;
                padding: 2mm 0 6mm;
                color: #000;
                font-family: Arial, Helvetica, sans-serif;
            }

            /* Thermal printers are monochrome: no heavy borders/fills */
            .pr-paper { border: none; padding: 0; background: #fff; }
            .pr-inner { border: none; padding: 0; }

            /* Header: logo (left) | school details (center) | seal (right) */
            .pr-header { display: flex; flex-wrap: nowrap; align-items: center; justify-content: space-between; gap: 1.5mm; }
            .pr-header img { width: 12mm; height: 12mm; object-fit: contain; flex-shrink: 0; filter: grayscale(1) contrast(1.4); }
            .pr-header-text { flex: 1 1 auto; min-width: 0; text-align: center; line-height: 1.2; }
            .pr-header-text .pr-gov { font-size: 6.5px; font-weight: 700; margin: 0; }
            .pr-header-text .pr-college { font-size: 8.5px; font-weight: 800; color: #000; margin: .5mm 0; }
            .pr-header-text .pr-address { font-size: 6px; font-weight: 700; margin: 0 0 .5mm; }
            .pr-header-text .pr-member { font-size: 5px; margin: 0; color: #000; line-height: 1.25; }

            .pr-divider { height: 0; margin: 3mm 0 0; background: none; border-bottom: 1px dashed #000; }

            .pr-title { margin: 3mm 0 0; text-align: center; background: #000; color: #fff; padding: 1.5mm 0; font-size: 9px; font-weight: 800; letter-spacing: 1.5px; }

            .pr-number-box { margin: 3mm 0 2mm; text-align: center; border: 1.5px solid #000; background: #fff; padding: 2mm 0 2.5mm; }
            .pr-number-label { font-size: 8px; font-weight: 700; letter-spacing: 1.5px; color: #000; }
            .pr-number { font-size: 38px; font-weight: 800; line-height: 1.05; color: #000; letter-spacing: 1px; margin: 1mm 0 0; }
            .pr-number-note { font-size: 8px; color: #000; margin: 1mm 0 0; }

            .pr-rows { margin-top: 2mm; }
            .pr-row { display: flex; justify-content: space-between; align-items: baseline; gap: 3mm; padding: 1.5mm 0; border-bottom: 1px dotted #000; }
            .pr-row span { font-size: 8px; font-weight: 700; letter-spacing: .5px; color: #000; text-transform: uppercase; flex-shrink: 0; }
            .pr-row strong { font-size: 10px; color: #000; text-align: right; word-break: break-word; }

            .pr-return { display: flex; align-items: flex-start; gap: 2mm; margin-top: 3mm; padding: 2mm; background: none; border: 1px solid #000; text-align: left; }
            .pr-return-icon { font-size: 14px; line-height: 1; }
            .pr-return h3 { margin: 0 0 1mm; font-size: 9.5px; color: #000; }
            .pr-return h6 { margin: 0; font-size: 8px; font-weight: 400; line-height: 1.4; color: #000; }

            .pr-footer { margin-top: 3mm; padding-top: 2mm; border-top: 1px dashed #000; text-align: center; font-size: 8px; color: #000; }
            .pr-footer strong { color: #000; }
        }
    </style>
</head>
<body class="kiosk-body">

@php
    // Students still waiting at the same window, ahead of this ticket today (0 if none)
    $studentsAhead = (int) \App\Models\Queue::where('status', 'waiting')
        ->where('window_number', $queue->window_number)
        ->whereDate('created_at', $queue->created_at->toDateString())
        ->where('id', '<', $queue->id)
        ->count();

    // Estimated wait: 5 minutes per student ahead (0 if none)
    $estimatedWait = $studentsAhead * 5;
@endphp

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
            <div class="queue-info-row">
                <span>Date &amp; Time</span>
                <strong>{{ $queue->created_at->timezone('Asia/Manila')->format('F j, Y - h:i A') }}</strong>
            </div>
            <div class="queue-info-row">
                <span>Window Number</span>
                <strong>{{ $queue->window_number }}</strong>
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

<!-- PRINT-ONLY RECEIPT -->
<div class="print-receipt">
    <div class="pr-paper">
        <div class="pr-inner">

            <div class="pr-header">
                <img src="{{ asset('images/logo.png') }}" alt="TCC Logo">
                <div class="pr-header-text">
                    <p class="pr-gov">Republic of the Philippines</p>
                    <p class="pr-gov">Province of Misamis Oriental</p>
                    <p class="pr-gov">Municipality of TAGOLOAN</p>
                    <p class="pr-college">TAGOLOAN COMMUNITY COLLEGE</p>
                    <p class="pr-address">M.H. del Pilar St., Baluarte, Tagoloan, Misamis Oriental</p>
                    <p class="pr-member">Member: Association of Local Colleges and Universities (ALCU)</p>
                    <p class="pr-member">Member: Association of Local Colleges and Universities &ndash; Commission on Accreditation (ALCU-COA)</p>
                    <p class="pr-member">Member: Philippine Association For Teacher &amp; Educators (PAFTE) Inc.</p>
                </div>
                <img src="{{ asset('images/tagoloan-seal.png') }}" alt="Tagoloan Official Seal">
            </div>

            <div class="pr-divider"></div>
            <div class="pr-title">REGISTRAR &bull; QUEUE RECEIPT</div>

            <div class="pr-number-box">
                <div class="pr-number-label">YOUR QUEUE NUMBER</div>
                <div class="pr-number">{{ $queue->queue_number }}</div>
                <p class="pr-number-note">Your queue number has been successfully generated.</p>
            </div>

            <div class="pr-rows">
                <div class="pr-row">
                    <span>Student</span>
                    <strong>{{ $queue->student_name }}</strong>
                </div>
                <div class="pr-row">
                    <span>Student ID</span>
                    <strong>{{ $queue->student_id }}</strong>
                </div>
                <div class="pr-row">
                    <span>Transaction</span>
                    <strong>{{ $queue->transaction }}</strong>
                </div>
                <div class="pr-row">
                    <span>Date &amp; Time</span>
                    <strong>{{ $queue->created_at->timezone('Asia/Manila')->format('F j, Y - h:i A') }}</strong>
                </div>
            </div>

            <div class="pr-return">
                <div>
                    <h3>You do not need to stay in line.</h3>
                    <h6>You may continue your activities around campus. Please return to the registrar when your queue number is announced.</h6>
                </div>
            </div>

            <div class="pr-footer">
                <div>Please keep your queue receipt.</div>
            </div>

        </div>
    </div>
</div>
</body>
</html>
